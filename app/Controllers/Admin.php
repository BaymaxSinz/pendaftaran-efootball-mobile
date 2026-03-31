<?php

namespace App\Controllers;

use App\Models\TournamentModel;
use App\Models\TeamModel;
use App\Models\MatchModel;

class Admin extends BaseController
{
    public function index()
    {
        return redirect()->to('/');
    }

    public function create()
    {
        if (session()->get('user_role') !== 'admin') return redirect()->to('/');
        return view('admin/create_tournament');
    }

    public function store()
    {
        $quota = $this->request->getPost('quota');
        $max_slots = $this->request->getPost('max_slots');

        if ($max_slots > $quota) {
            return redirect()->back()->with('error', 'Gagal: Batas Slot/Akun tidak boleh melebihi Total Kuota Turnamen!');
        }

        // --- PROSES UPLOAD POSTER ---
        $posterFile = $this->request->getFile('poster');
        $posterName = null;

        if ($posterFile && $posterFile->isValid() && !$posterFile->hasMoved()) {
            $posterName = $posterFile->getRandomName();
            $posterFile->move('uploads/posters', $posterName);
        }

        $tournamentModel = new TournamentModel();
        $data = [
            'name'        => $this->request->getPost('name'),
            'poster'      => $posterName, // Menyimpan nama file gambar
            'description' => $this->request->getPost('description'),
            'prize'       => $this->request->getPost('prize'),
            'rules'       => $this->request->getPost('rules'),
            'quota'       => $quota,
            'max_slots'   => $max_slots,
            'status'      => $this->request->getPost('status')
        ];
        
        $tournamentModel->save($data);
        return redirect()->to('/')->with('success', 'Turnamen berhasil dibuat!');
    }

    public function edit($id)
    {
        if (session()->get('user_role') !== 'admin') return redirect()->to('/');
        
        $tournamentModel = new TournamentModel();
        $data['turnamen'] = $tournamentModel->find($id);
        
        return view('admin/edit_tournament', $data);
    }

    public function update($id)
    {
        $quota = $this->request->getPost('quota');
        $max_slots = $this->request->getPost('max_slots');

        if ($max_slots > $quota) {
            return redirect()->back()->with('error', 'Gagal: Batas Slot/Akun tidak boleh melebihi Total Kuota Turnamen!');
        }

        // --- PROSES UPLOAD POSTER (EDIT) ---
        $posterFile = $this->request->getFile('poster');
        $posterName = $this->request->getPost('old_poster'); // Gunakan poster lama sebagai default

        if ($posterFile && $posterFile->isValid() && !$posterFile->hasMoved()) {
            // Jika ada file baru diupload, timpa namanya
            $posterName = $posterFile->getRandomName();
            $posterFile->move('uploads/posters', $posterName);
        }

        $tournamentModel = new TournamentModel();
        $data = [
            'name'        => $this->request->getPost('name'),
            'poster'      => $posterName,
            'description' => $this->request->getPost('description'),
            'prize'       => $this->request->getPost('prize'),
            'rules'       => $this->request->getPost('rules'),
            'quota'       => $quota,
            'max_slots'   => $max_slots,
            'status'      => $this->request->getPost('status')
        ];
        
        $tournamentModel->update($id, $data);
        return redirect()->to('/')->with('success', 'Turnamen berhasil diperbarui!');
    }

    public function delete($id)
    {
        if (session()->get('user_role') !== 'admin') return redirect()->to('/');
        
        $tournamentModel = new TournamentModel();
        $tournamentModel->delete($id);
        
        return redirect()->to('/')->with('success', 'Turnamen berhasil dihapus!');
    }

    public function teams($id)
    {
        if (session()->get('user_role') !== 'admin') return redirect()->to('/');

        $tournamentModel = new TournamentModel();
        $data['turnamen'] = $tournamentModel->find($id);

        $db = \Config\Database::connect();
        $builder = $db->table('teams');
        $builder->select('teams.*, users.name as player_name');
        $builder->join('users', 'users.id = teams.user_id');
        $builder->where('teams.tournament_id', $id);
        $data['teams'] = $builder->get()->getResultArray();

        return view('admin/teams', $data);
    }

    public function updateTeamStatus($id, $status)
    {
        if (session()->get('user_role') !== 'admin') return redirect()->to('/');

        $teamModel = new TeamModel();
        if (in_array($status, ['approved', 'rejected', 'pending'])) {
            $teamModel->update($id, ['status' => $status]);
            return redirect()->back()->with('success', 'Status pendaftar berhasil diperbarui!');
        }

        return redirect()->back()->with('error', 'Status tidak valid!');
    }

    // ==========================================================
    // FITUR BAGAN & SKOR
    // ==========================================================
    
    // 1. Generate Bagan Babak Pertama (DIPERBARUI)
    public function generateBracket($tournament_id)
    {
        if (session()->get('user_role') !== 'admin') return redirect()->to('/');

        $teamModel = new TeamModel();
        $matchModel = new MatchModel();

        $existingMatches = $matchModel->where('tournament_id', $tournament_id)->first();
        if ($existingMatches) return redirect()->back()->with('error', 'Bagan sudah pernah dibuat!');

        $teams = $teamModel->where('tournament_id', $tournament_id)->where('status', 'approved')->findAll();
        $totalTeams = count($teams);

        if ($totalTeams < 2) return redirect()->back()->with('error', 'Minimal harus ada 2 tim!');

        shuffle($teams);
        $matchNumber = 1;

        for ($i = 0; $i < $totalTeams; $i += 2) {
            $team1 = $teams[$i]['id'];
            $team2 = isset($teams[$i+1]) ? $teams[$i+1]['id'] : null; 
            
            // LOGIKA BARU: Jika tidak ada lawan (Ganjil), otomatis Menang (BYE)
            $status = 'pending';
            $winner_id = null;
            if ($team2 === null) {
                $status = 'completed';
                $winner_id = $team1;
            }

            $matchModel->save([
                'tournament_id' => $tournament_id,
                'round'         => 1,
                'match_number'  => $matchNumber,
                'team1_id'      => $team1,
                'team2_id'      => $team2,
                'winner_id'     => $winner_id,
                'status'        => $status
            ]);
            $matchNumber++;
        }

        $tournamentModel = new TournamentModel();
        $tournamentModel->update($tournament_id, ['status' => 'ongoing']);

        return redirect()->back()->with('success', 'Drawing Berhasil! Tim yang ganjil otomatis Lolos (BYE).');
    }

    // 2. Tampilkan Halaman Input Skor Admin
    public function matches($tournament_id)
    {
        if (session()->get('user_role') !== 'admin') return redirect()->to('/');

        $tournamentModel = new TournamentModel();
        $matchModel = new MatchModel();

        $data['turnamen'] = $tournamentModel->find($tournament_id);
        
        $data['matches'] = $matchModel->select('matches.*, t1.team_name as team1_name, t2.team_name as team2_name')
                                      ->join('teams as t1', 't1.id = matches.team1_id', 'left')
                                      ->join('teams as t2', 't2.id = matches.team2_id', 'left')
                                      ->where('matches.tournament_id', $tournament_id)
                                      ->orderBy('matches.round', 'ASC')
                                      ->orderBy('matches.match_number', 'ASC')
                                      ->findAll();

        $db = \Config\Database::connect();
        $query = $db->query("SELECT MAX(round) as max_round FROM matches WHERE tournament_id = $tournament_id");
        $data['current_round'] = $query->getRow()->max_round ?? 1;

        $data['all_completed'] = true;
        foreach ($data['matches'] as $m) {
            if ($m['round'] == $data['current_round'] && $m['status'] == 'pending') {
                $data['all_completed'] = false;
                break;
            }
        }

        // --- TAMBAHAN BARU: CARI SANG JUARA JIKA TURNAMEN SELESAI ---
        $data['champion'] = null;
        if ($data['turnamen']['status'] == 'completed') {
            $lastMatch = $matchModel->where('tournament_id', $tournament_id)
                                    ->orderBy('round', 'DESC')
                                    ->orderBy('match_number', 'DESC')
                                    ->first();
            
            if ($lastMatch && $lastMatch['winner_id']) {
                $teamModel = new TeamModel();
                $data['champion'] = $teamModel->select('teams.team_name, users.name as manager_name')
                                              ->join('users', 'users.id = teams.user_id')
                                              ->find($lastMatch['winner_id']);
            }
        }

        return view('admin/matches', $data);
    }

    // 3. Simpan Skor & Tentukan Pemenang
    public function updateScore($match_id)
    {
        if (session()->get('user_role') !== 'admin') return redirect()->to('/');

        $matchModel = new MatchModel();
        $match = $matchModel->find($match_id);

        $score1 = $this->request->getPost('score_team1');
        $score2 = $this->request->getPost('score_team2');

        // Penentuan Pemenang Otomatis (Tidak boleh seri di eFootball, harus ada penalti/golden goal)
        if ($score1 == $score2) return redirect()->back()->with('error', 'Skor tidak boleh seri! Masukkan hasil akhir setelah Penalti jika perlu.');
        
        $winner_id = ($score1 > $score2) ? $match['team1_id'] : $match['team2_id'];

        $matchModel->update($match_id, [
            'score_team1' => $score1,
            'score_team2' => $score2,
            'winner_id'   => $winner_id,
            'status'      => 'completed'
        ]);

        return redirect()->back()->with('success', 'Skor berhasil disimpan!');
    }

    // 4. Generate Babak Berikutnya dari Para Pemenang
    public function generateNextRound($tournament_id, $current_round)
    {
        if (session()->get('user_role') !== 'admin') return redirect()->to('/');

        $matchModel = new MatchModel();
        $currentMatches = $matchModel->where('tournament_id', $tournament_id)->where('round', $current_round)->findAll();

        // Kumpulkan semua pemenang
        $winners = [];
        foreach ($currentMatches as $m) {
            if ($m['winner_id']) $winners[] = $m['winner_id'];
        }

        // JIKA PEMENANG SISA 1 = TURNAMEN SELESAI!
        if (count($winners) == 1) {
            $tournamentModel = new TournamentModel();
            $tournamentModel->update($tournament_id, ['status' => 'completed']);
            return redirect()->back()->with('success', '🏆 TURNAMEN SELESAI! Selamat kepada Sang Juara!');
        }

        // JIKA MASIH BANYAK, BUAT BABAK BARU
        $nextRound = $current_round + 1;
        $matchNumber = 1;
        
        for ($i = 0; $i < count($winners); $i += 2) {
            $team1 = $winners[$i];
            $team2 = isset($winners[$i+1]) ? $winners[$i+1] : null; 

            // Logika Lolos Otomatis (BYE) jika ganjil
            $status = 'pending';
            $winner_id = null;
            if ($team2 === null) {
                $status = 'completed';
                $winner_id = $team1;
            }

            $matchModel->save([
                'tournament_id' => $tournament_id,
                'round'         => $nextRound,
                'match_number'  => $matchNumber,
                'team1_id'      => $team1,
                'team2_id'      => $team2,
                'winner_id'     => $winner_id,
                'status'        => $status
            ]);
            $matchNumber++;
        }

        return redirect()->back()->with('success', 'Babak ' . $nextRound . ' berhasil dibuat!');
    }
}