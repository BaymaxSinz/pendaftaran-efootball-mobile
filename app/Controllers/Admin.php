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
            'status'      => 'open', // Otomatis open saat dibuat
            'format'      => $this->request->getPost('format')
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
            'format'      => $this->request->getPost('format')
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
    
    // 1. Generate Bagan Babak Pertama (GUGUR)
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

        // PENGACAKAN PINTAR (ANTI TABRAKAN 1 OWNER)
        shuffle($teams);
        $leftBlock = [];
        $rightBlock = [];
        $userTracker = []; 

        foreach ($teams as $t) {
            $uid = $t['user_id'];
            if (!isset($userTracker[$uid])) {
                $leftBlock[] = $t;
                $userTracker[$uid] = true; 
            } else {
                $rightBlock[] = $t;
                unset($userTracker[$uid]); 
            }
        }

        $smartTeams = [];
        $maxCount = max(count($leftBlock), count($rightBlock));
        for ($i = 0; $i < $maxCount; $i++) {
            if (isset($leftBlock[$i])) $smartTeams[] = $leftBlock[$i];
            if (isset($rightBlock[$i])) $smartTeams[] = $rightBlock[$i];
        }
        $teams = $smartTeams;

        $matchNumber = 1;

        for ($i = 0; $i < $totalTeams; $i += 2) {
            $team1 = $teams[$i]['id'];
            $team2 = isset($teams[$i+1]) ? $teams[$i+1]['id'] : null; 
            
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

        return redirect()->back()->with('success', 'Drawing Berhasil! Tim dari manajer yang sama telah dipisahkan agar tidak bertemu di Babak 1.');
    }

    // 2. Tampilkan Halaman Input Skor Admin
    public function matches($tournament_id)
    {
        if (session()->get('user_role') !== 'admin') return redirect()->to('/');

        $tournamentModel = new TournamentModel();
        $matchModel = new MatchModel();
        $teamModel = new TeamModel();

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

        // ========================================================
        // LOGIKA KLASEMEN ADMIN YANG SEMPAT HILANG ADA DI SINI
        // ========================================================
        $data['champion'] = null;
        $data['standings'] = []; // Variabel untuk tabel klasemen

        if ($data['turnamen']['status'] == 'completed') {
            if ($data['turnamen']['format'] == 'bracket') {
                $lastMatch = $matchModel->where('tournament_id', $tournament_id)
                                        ->orderBy('round', 'DESC')
                                        ->orderBy('match_number', 'DESC')
                                        ->first();
                if ($lastMatch && $lastMatch['winner_id']) {
                    $data['champion'] = $teamModel->select('teams.team_name, users.name as manager_name')
                                                  ->join('users', 'users.id = teams.user_id')
                                                  ->find($lastMatch['winner_id']);
                }
            } else {
                $data['standings'] = $this->_calculateStandings($tournament_id);
                if (!empty($data['standings'])) {
                    $topTeam = $data['standings'][0]; 
                    $data['champion'] = $teamModel->select('teams.team_name, users.name as manager_name')
                                                  ->join('users', 'users.id = teams.user_id')
                                                  ->find($topTeam['team_id']);
                }
            }
        } elseif (isset($data['turnamen']['format']) && $data['turnamen']['format'] == 'league') {
            // Hitung klasemen sementara jika liga sedang berlangsung
            $data['standings'] = $this->_calculateStandings($tournament_id);
        }

        return view('admin/matches', $data);
    }

    // FUNGSI RAHASIA: MENGHITUNG KLASEMEN OTOMATIS
    private function _calculateStandings($tournament_id)
    {
        $teamModel = new TeamModel();
        $matchModel = new MatchModel();
        
        $teams = $teamModel->where('tournament_id', $tournament_id)->where('status', 'approved')->findAll();
        $matches = $matchModel->where('tournament_id', $tournament_id)->where('status', 'completed')->findAll();
        
        $standings = [];
        foreach ($teams as $t) {
            $standings[$t['id']] = [
                'team_id' => $t['id'],
                'name' => $t['team_name'],
                'played' => 0, 'win' => 0, 'draw' => 0, 'loss' => 0,
                'gf' => 0, 'ga' => 0, 'gd' => 0, 'points' => 0
            ];
        }

        foreach ($matches as $m) {
            if (!$m['team1_id'] || !$m['team2_id']) continue; 

            $s1 = $m['score_team1'];
            $s2 = $m['score_team2'];

            $standings[$m['team1_id']]['played']++;
            $standings[$m['team2_id']]['played']++;
            $standings[$m['team1_id']]['gf'] += $s1;
            $standings[$m['team1_id']]['ga'] += $s2;
            $standings[$m['team2_id']]['gf'] += $s2;
            $standings[$m['team2_id']]['ga'] += $s1;

            if ($s1 > $s2) {
                $standings[$m['team1_id']]['win']++;
                $standings[$m['team1_id']]['points'] += 3;
                $standings[$m['team2_id']]['loss']++;
            } elseif ($s2 > $s1) {
                $standings[$m['team2_id']]['win']++;
                $standings[$m['team2_id']]['points'] += 3;
                $standings[$m['team1_id']]['loss']++;
            } else {
                $standings[$m['team1_id']]['draw']++;
                $standings[$m['team2_id']]['draw']++;
                $standings[$m['team1_id']]['points'] += 1;
                $standings[$m['team2_id']]['points'] += 1;
            }
        }

        foreach ($standings as &$s) { $s['gd'] = $s['gf'] - $s['ga']; }

        usort($standings, function($a, $b) {
            if ($a['points'] != $b['points']) return $b['points'] - $a['points'];
            if ($a['gd'] != $b['gd']) return $b['gd'] - $a['gd'];
            return $b['gf'] - $a['gf'];
        });

        return array_values($standings);
    }

    // 3. Simpan Skor & Tentukan Pemenang
    public function updateScore($match_id)
    {
        if (session()->get('user_role') !== 'admin') return redirect()->to('/');

        $matchModel = new MatchModel();
        $tournamentModel = new TournamentModel();
        
        $match = $matchModel->find($match_id);
        $turnamen = $tournamentModel->find($match['tournament_id']);

        $score1 = $this->request->getPost('score_team1');
        $score2 = $this->request->getPost('score_team2');

        $winner_id = null;

        if ($score1 > $score2) {
            $winner_id = $match['team1_id'];
        } elseif ($score2 > $score1) {
            $winner_id = $match['team2_id'];
        } else {
            if ($turnamen['format'] == 'bracket') {
                return redirect()->back()->with('error', 'Sistem Gugur tidak boleh seri! Masukkan hasil akhir setelah Penalti/Waktu Tambahan.');
            }
        }

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

        $winners = [];
        foreach ($currentMatches as $m) {
            if ($m['winner_id']) $winners[] = $m['winner_id'];
        }

        if (count($winners) == 1) {
            $tournamentModel = new TournamentModel();
            $tournamentModel->update($tournament_id, ['status' => 'completed']);
            return redirect()->back()->with('success', '🏆 TURNAMEN SELESAI! Selamat kepada Sang Juara!');
        }

        $nextRound = $current_round + 1;
        $matchNumber = 1;
        
        for ($i = 0; $i < count($winners); $i += 2) {
            $team1 = $winners[$i];
            $team2 = isset($winners[$i+1]) ? $winners[$i+1] : null; 

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

    // ==========================================================
    // 5. GENERATOR JADWAL SISTEM LIGA (ROUND-ROBIN)
    // ==========================================================
    public function generateLeague($tournament_id)
    {
        if (session()->get('user_role') !== 'admin') return redirect()->to('/');

        $teamModel = new TeamModel();
        $matchModel = new MatchModel();

        $existingMatches = $matchModel->where('tournament_id', $tournament_id)->first();
        if ($existingMatches) return redirect()->back()->with('error', 'Jadwal Liga sudah pernah dibuat!');

        $teams = $teamModel->where('tournament_id', $tournament_id)->where('status', 'approved')->findAll();
        
        if (count($teams) < 3) return redirect()->back()->with('error', 'Sistem Liga butuh minimal 3 tim!');

        if (count($teams) % 2 != 0) {
            array_push($teams, ['id' => null, 'team_name' => 'BYE']);
        }

        $totalTeams = count($teams);
        $totalRounds = $totalTeams - 1; 
        $matchesPerRound = $totalTeams / 2;
        $teamIndexes = array_keys($teams);
        $matchNumber = 1;

        for ($round = 1; $round <= $totalRounds; $round++) {
            for ($i = 0; $i < $matchesPerRound; $i++) {
                $t1_index = $teamIndexes[$i];
                $t2_index = $teamIndexes[$totalTeams - 1 - $i];
                
                $team1 = $teams[$t1_index]['id'];
                $team2 = $teams[$t2_index]['id'];

                $status = 'pending';
                $winner_id = null;
                
                if ($team1 === null || $team2 === null) {
                    $status = 'completed'; 
                    $winner_id = ($team1 === null) ? $team2 : $team1; 
                }

                $matchModel->save([
                    'tournament_id' => $tournament_id,
                    'round'         => $round, 
                    'match_number'  => $matchNumber,
                    'team1_id'      => $team1,
                    'team2_id'      => $team2,
                    'winner_id'     => $winner_id,
                    'status'        => $status
                ]);
                $matchNumber++;
            }
            $lastIndex = array_pop($teamIndexes);
            array_splice($teamIndexes, 1, 0, [$lastIndex]);
        }

        $tournamentModel = new TournamentModel();
        $tournamentModel->update($tournament_id, ['status' => 'ongoing']);

        return redirect()->back()->with('success', 'Jadwal Liga berhasil di-generate! Semua tim akan saling berhadapan.');
    }

    // 6. TUTUP TURNAMEN LIGA
    public function completeLeague($tournament_id)
    {
        if (session()->get('user_role') !== 'admin') return redirect()->to('/');
        
        $tournamentModel = new TournamentModel();
        $tournamentModel->update($tournament_id, ['status' => 'completed']);
        
        return redirect()->back()->with('success', '🏆 Turnamen Liga Resmi Selesai! Klasemen Akhir telah dikunci.');
    }
}