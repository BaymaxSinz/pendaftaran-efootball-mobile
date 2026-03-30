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

        return view('turnamen/detail', $data);
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
        $teamModel = new TeamModel();
        $tournamentModel = new TournamentModel();

        $userId = session()->get('user_id');
        $tournamentId = $this->request->getPost('tournament_id');

        $tournament = $tournamentModel->find($tournamentId);

        // ---------------------------------------------------------
        // SATPAM 1: CEK TOTAL KUOTA TURNAMEN (Misal: Max 32 Tim)
        // ---------------------------------------------------------
        $currentTeams = $teamModel->where('tournament_id', $tournamentId)->countAllResults();
        
        if ($currentTeams >= $tournament['quota']) {
            return redirect()->back()->with('error', 'Mohon maaf, kuota pendaftaran turnamen ini sudah penuh (' . $tournament['quota'] . '/' . $tournament['quota'] . ' Tim).');
        }

        // ---------------------------------------------------------
        // SATPAM 2: CEK BATAS SLOT PER AKUN
        // ---------------------------------------------------------
        $myTeamsCount = $teamModel->where('tournament_id', $tournamentId)
                                  ->where('user_id', $userId)
                                  ->countAllResults();

        if ($myTeamsCount >= $tournament['max_slots']) {
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

        return redirect()->to('/tim-saya')->with('success', 'Pendaftaran berhasil dikirim! Silakan tunggu persetujuan Admin.');
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
        $builder->select('teams.*, tournaments.name as turnamen_name');
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

    public function batalDaftar($id)
    {
        $teamModel = new TeamModel();
        $teamModel->delete($id);
        
        return redirect()->to('/tim-saya')->with('success', 'Pendaftaran tim berhasil dibatalkan.');
    }

}