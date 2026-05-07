<?php

namespace App\Controllers;

use App\Models\TournamentModel;
use App\Models\TeamModel;

class Home extends BaseController
{
    public function index()
    {
        $tournamentModel = new TournamentModel();
        $teamModel = new TeamModel();

        // Ambil semua data turnamen
        $tournaments = $tournamentModel->findAll();

        // Hitung jumlah tim untuk masing-masing turnamen
        foreach ($tournaments as &$t) {
            // Menghitung total pendaftar (semua status)
            $t['registered_teams'] = $teamModel->where('tournament_id', $t['id'])->countAllResults();
            
            // Menghitung HANYA tim yang sudah DISETUJUI (Approved)
            $t['approved_teams'] = $teamModel->where('tournament_id', $t['id'])
                                             ->where('status', 'approved')
                                             ->countAllResults();
        }

        $data = [
            'turnamen' => $tournaments
        ];

        return view('Home', $data);
    }
}