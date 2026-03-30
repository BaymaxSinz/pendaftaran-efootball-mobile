<?php

namespace App\Controllers;

use App\Models\TournamentModel;
use App\Models\TeamModel;

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
}