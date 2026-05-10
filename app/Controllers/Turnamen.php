<?php

namespace App\Controllers;

use App\Models\TournamentModel;
use App\Models\TeamModel;

class Turnamen extends BaseController
{

    public function detail($id)
    {
        $tournamentModel = new \App\Models\TournamentModel();
        $teamModel = new \App\Models\TeamModel();
        $matchModel = new \App\Models\MatchModel();

        // Cari data turnamen
        $data['turnamen'] = $tournamentModel->find($id);
        
        if (!$data['turnamen']) {
            return redirect()->to('/')->with('error', 'Turnamen tidak ditemukan.');
        }

        // Hitung slot terisi (hanya yang disetujui)
        $data['approved_teams_count'] = $teamModel->where('tournament_id', $id)
                                                  ->where('status', 'approved')
                                                  ->countAllResults();

        // Ambil daftar tim yang sudah disetujui untuk ditampilkan di Tab Peserta
        $db = \Config\Database::connect();
        $builder = $db->table('teams');
        $builder->select('teams.team_name, users.name as manager_name');
        $builder->join('users', 'users.id = teams.user_id');
        $builder->where('teams.tournament_id', $id);
        $builder->where('teams.status', 'approved');
        $data['peserta'] = $builder->get()->getResultArray();

        // --- AMBIL DATA BAGAN PERTANDINGAN ---
        $data['matches'] = $matchModel->select('matches.*, t1.team_name as team1_name, t2.team_name as team2_name')
                                      ->join('teams as t1', 't1.id = matches.team1_id', 'left')
                                      ->join('teams as t2', 't2.id = matches.team2_id', 'left')
                                      ->where('matches.tournament_id', $id)
                                      ->orderBy('matches.round', 'ASC') 
                                      ->orderBy('matches.match_number', 'ASC')
                                      ->findAll();

        // =========================================================
        // PERBAIKAN PENCARIAN JUARA & HITUNG KLASEMEN
        // =========================================================
        $data['champion'] = null;
        $data['standings'] = [];
        
        if ($data['turnamen']['format'] == 'league') {
            // Jika Liga, hitung klasemen dulu untuk ditampilkan di view
            $data['standings'] = $this->_calculateStandings($id);
            
            // Jika liga sudah selesai, juara diambil dari pemuncak klasemen (Peringkat 1)
            if ($data['turnamen']['status'] == 'completed' && !empty($data['standings'])) {
                $topTeam = $data['standings'][0]; // [0] berarti array indeks pertama (peringkat teratas)
                $data['champion'] = $teamModel->select('teams.team_name, users.name as manager_name')
                                              ->join('users', 'users.id = teams.user_id')
                                              ->find($topTeam['team_id']);
            }
        } else {
            // Jika Gugur (Bracket), juara diambil dari pertandingan terakhir
            if ($data['turnamen']['status'] == 'completed') {
                $lastMatch = $matchModel->where('tournament_id', $id)
                                        ->orderBy('round', 'DESC')
                                        ->orderBy('match_number', 'DESC')
                                        ->first();
                
                if ($lastMatch && $lastMatch['winner_id']) {
                    $data['champion'] = $teamModel->select('teams.team_name, users.name as manager_name')
                                                  ->join('users', 'users.id = teams.user_id')
                                                  ->find($lastMatch['winner_id']);
                }
            }
        }

        return view('turnamen/detail', $data);
    }

    // =========================================================
    // FUNGSI RAHASIA PENGHITUNG KLASEMEN (WAJIB DITAMBAHKAN)
    // =========================================================
    private function _calculateStandings($tournament_id)
    {
        $teamModel = new \App\Models\TeamModel();
        $matchModel = new \App\Models\MatchModel();
        
        $teams = $teamModel->where('tournament_id', $tournament_id)->where('status', 'approved')->findAll();
        $matches = $matchModel->where('tournament_id', $tournament_id)->where('status', 'completed')->findAll();
        
        $standings = [];
        
        // Siapkan papan klasemen kosong untuk semua tim
        foreach ($teams as $t) {
            $standings[$t['id']] = [
                'team_id' => $t['id'],
                'name' => $t['team_name'],
                'played' => 0, 'win' => 0, 'draw' => 0, 'loss' => 0,
                'gf' => 0, 'ga' => 0, 'gd' => 0, 'points' => 0
            ];
        }

        // Hitung skor dari setiap pertandingan yang sudah selesai
        foreach ($matches as $m) {
            if (!$m['team1_id'] || !$m['team2_id']) continue; // Lewati jika tim dapat BYE

            $s1 = $m['score_team1'];
            $s2 = $m['score_team2'];

            $standings[$m['team1_id']]['played']++;
            $standings[$m['team2_id']]['played']++;
            
            $standings[$m['team1_id']]['gf'] += $s1; // Gol Memasukkan (Goal For)
            $standings[$m['team1_id']]['ga'] += $s2; // Gol Kemasukan (Goal Against)
            
            $standings[$m['team2_id']]['gf'] += $s2;
            $standings[$m['team2_id']]['ga'] += $s1;

            if ($s1 > $s2) {
                // Tim 1 Menang
                $standings[$m['team1_id']]['win']++;
                $standings[$m['team1_id']]['points'] += 3;
                $standings[$m['team2_id']]['loss']++;
            } elseif ($s2 > $s1) {
                // Tim 2 Menang
                $standings[$m['team2_id']]['win']++;
                $standings[$m['team2_id']]['points'] += 3;
                $standings[$m['team1_id']]['loss']++;
            } else {
                // Seri / Draw
                $standings[$m['team1_id']]['draw']++;
                $standings[$m['team2_id']]['draw']++;
                $standings[$m['team1_id']]['points'] += 1;
                $standings[$m['team2_id']]['points'] += 1;
            }
        }

        // Hitung Selisih Gol (Goal Difference)
        foreach ($standings as &$s) { 
            $s['gd'] = $s['gf'] - $s['ga']; 
        }

        // Urutkan Klasemen: Poin Tertinggi -> Selisih Gol Terbaik -> Cetak Gol Terbanyak
        usort($standings, function($a, $b) {
            if ($a['points'] != $b['points']) return $b['points'] - $a['points'];
            if ($a['gd'] != $b['gd']) return $b['gd'] - $a['gd'];
            return $b['gf'] - $a['gf'];
        });

        // Reset indeks array kembali ke 0, 1, 2, dst setelah diurutkan
        return array_values($standings);
    }

    public function daftar($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu untuk mendaftar.');
        }

        $tournamentModel = new TournamentModel();
        $data['turnamen'] = $tournamentModel->find($id);

        return view('turnamen/daftar', $data);
    }

    public function simpan()
    {
        $teamModel = new \App\Models\TeamModel();
        $tournamentModel = new \App\Models\TournamentModel();
        
        // Panggil penengah Database
        $db = \Config\Database::connect();

        $userId = session()->get('user_id');
        $tournamentId = $this->request->getPost('tournament_id');

        // =======================================================
        // MULAI GEMBOK DATABASE (TRANSACTIONAL)
        // =======================================================
        $db->transStart(); 

        $tournament = $tournamentModel->find($tournamentId);

        // SATPAM 1: CEK TOTAL KUOTA TURNAMEN
        $currentTeams = $teamModel->where('tournament_id', $tournamentId)->countAllResults();
        
        if ($currentTeams >= $tournament['quota']) {
            $db->transRollback(); // Batalkan semua proses!
            return redirect()->back()->with('error', 'Mohon maaf, kuota pendaftaran turnamen ini sudah penuh (' . $tournament['quota'] . '/' . $tournament['quota'] . ' Tim).');
        }

        // SATPAM 2: CEK BATAS SLOT PER AKUN
        $myTeamsCount = $teamModel->where('tournament_id', $tournamentId)
                                  ->where('user_id', $userId)
                                  ->countAllResults();

        if ($myTeamsCount >= $tournament['max_slots']) {
            $db->transRollback(); // Batalkan semua proses!
            return redirect()->back()->with('error', 'Kamu sudah mencapai batas maksimal pendaftaran tim di turnamen ini!');
        }

        // Jika Lolos Pengecekan, Simpan Data
        $data = [
            'user_id'       => $userId,
            'tournament_id' => $tournamentId,
            'team_name'     => $this->request->getPost('team_name'),
            'in_game_name'  => $this->request->getPost('in_game_name'),
            'in_game_id'    => $this->request->getPost('in_game_id'),
            'status'        => 'pending'
        ];

        $teamModel->save($data);
        
        $newTeamId = $teamModel->insertID();

        // =======================================================
        // SELESAI! BUKA GEMBOK DATABASE
        // =======================================================
        $db->transComplete(); 

        // Cek apakah ada masalah saat menyimpan (misal server down)
        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat mendaftar. Silakan coba lagi.');
        }

        // Arahkan ke halaman pembayaran dengan membawa ID Tim
        return redirect()->to('/turnamen/pembayaran/' . $newTeamId);
    }

    public function timSaya()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        
        // Mengambil data tim dan nama turnamennya menggunakan Query Builder
        $db      = \Config\Database::connect();
        $builder = $db->table('teams');
        
        // PERBAIKAN DI SINI: Menambahkan tournaments.status as turnamen_status
        $builder->select('teams.*, tournaments.name as turnamen_name, tournaments.status as turnamen_status');
        $builder->join('tournaments', 'tournaments.id = teams.tournament_id');
        $builder->where('teams.user_id', $userId);
        $query   = $builder->get();

        $data['my_teams'] = $query->getResultArray();

        return view('turnamen/tim_saya', $data);
    }

    public function bagan($id)
    {
        $tournamentModel = new TournamentModel();
        $teamModel       = new TeamModel();

        $data['turnamen'] = $tournamentModel->find($id);
        
        // Ambil HANYA tim yang statusnya 'approved' (disetujui admin)
        $data['approved_teams'] = $teamModel->where('tournament_id', $id)
                                            ->where('status', 'approved')
                                            ->findAll();

        return view('turnamen/bagan', $data);
    }

    public function batal($team_id)
    {
        $teamModel = new \App\Models\TeamModel();
        $tournamentModel = new \App\Models\TournamentModel();

        // 1. Cari data tim yang mau dihapus
        $team = $teamModel->find($team_id);
        if (!$team) {
            return redirect()->back()->with('error', 'Tim tidak ditemukan.');
        }

        // 2. Cari data turnamennya
        $tournament = $tournamentModel->find($team['tournament_id']);

        // 3. PENGAMANAN UTAMA: Cek status turnamen
        if ($tournament['status'] != 'open') {
            return redirect()->back()->with('error', 'Gagal: Turnamen sudah dimulai atau selesai. Kamu tidak bisa membatalkan pendaftaran!');
        }

        // 4. Jika masih 'open', baru boleh dihapus
        $teamModel->delete($team_id);

        return redirect()->back()->with('success', 'Pendaftaran berhasil dibatalkan.');
    }

    public function pembayaran($team_id)
    {
        $teamModel = new \App\Models\TeamModel();
        $tournamentModel = new \App\Models\TournamentModel();

        $data['tim'] = $teamModel->find($team_id);
        $data['turnamen'] = $tournamentModel->find($data['tim']['tournament_id']);

        return view('turnamen/pembayaran', $data);
    }

}