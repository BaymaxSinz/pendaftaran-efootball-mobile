<?php

namespace App\Controllers;

use App\Models\TournamentModel;
use App\Models\TeamModel;

class Admin extends BaseController
{
    // Karena dashboard admin sudah menyatu dengan Home, kita alihkan ke Home (/)
    public function index()
    {
        return redirect()->to('/');
    }

    // Menampilkan Form Tambah Turnamen
    public function create()
    {
        if (session()->get('user_role') !== 'admin') return redirect()->to('/');
        return view('admin/create_tournament');
    }

    // Memproses Simpan Turnamen Baru
    public function store()
    {
        $quota = $this->request->getPost('quota');
        $max_slots = $this->request->getPost('max_slots');

        // LOGIKA PENCEGAH ERROR: Slot/Akun tidak boleh lebih besar dari Kuota Total
        if ($max_slots > $quota) {
            return redirect()->back()->with('error', 'Gagal: Batas Slot/Akun tidak boleh melebihi Total Kuota Turnamen!');
        }

        $tournamentModel = new TournamentModel();
        $data = [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'rules'       => $this->request->getPost('rules'),
            'quota'       => $quota,
            'max_slots'   => $max_slots,
            'status'      => $this->request->getPost('status')
        ];
        
        $tournamentModel->save($data);
        
        // Redirect kembali ke Home setelah berhasil save
        return redirect()->to('/')->with('success', 'Turnamen berhasil dibuat!');
    }

    // Menampilkan Form Edit Turnamen
    public function edit($id)
    {
        if (session()->get('user_role') !== 'admin') return redirect()->to('/');
        
        $tournamentModel = new TournamentModel();
        $data['turnamen'] = $tournamentModel->find($id);
        
        return view('admin/edit_tournament', $data);
    }

    // Memproses Update Turnamen
    public function update($id)
    {
        $quota = $this->request->getPost('quota');
        $max_slots = $this->request->getPost('max_slots');

        // LOGIKA PENCEGAH ERROR
        if ($max_slots > $quota) {
            return redirect()->back()->with('error', 'Gagal: Batas Slot/Akun tidak boleh melebihi Total Kuota Turnamen!');
        }

        $tournamentModel = new TournamentModel();
        $data = [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'rules'       => $this->request->getPost('rules'),
            'quota'       => $quota,
            'max_slots'   => $max_slots,
            'status'      => $this->request->getPost('status')
        ];
        
        $tournamentModel->update($id, $data);
        
        // Redirect kembali ke Home setelah berhasil update
        return redirect()->to('/')->with('success', 'Turnamen berhasil diperbarui!');
    }

    // Menghapus Turnamen
    public function delete($id)
    {
        if (session()->get('user_role') !== 'admin') return redirect()->to('/');
        
        $tournamentModel = new TournamentModel();
        $tournamentModel->delete($id);
        
        return redirect()->to('/')->with('success', 'Turnamen berhasil dihapus!');
    }

    // ==========================================================
    // FITUR KELOLA PENDAFTAR (TIM)
    // ==========================================================

    // Menampilkan daftar tim yang mendaftar di suatu turnamen
    public function teams($id)
    {
        if (session()->get('user_role') !== 'admin') return redirect()->to('/');

        $tournamentModel = new TournamentModel();
        $data['turnamen'] = $tournamentModel->find($id);

        // Mengambil data tim beserta nama user yang mendaftarkan
        $db = \Config\Database::connect();
        $builder = $db->table('teams');
        $builder->select('teams.*, users.name as player_name');
        $builder->join('users', 'users.id = teams.user_id');
        $builder->where('teams.tournament_id', $id);
        $data['teams'] = $builder->get()->getResultArray();

        return view('admin/teams', $data);
    }

    // Mengubah status tim (Approve / Reject)
    public function updateTeamStatus($id, $status)
    {
        if (session()->get('user_role') !== 'admin') return redirect()->to('/');

        $teamModel = new TeamModel();
        
        // Hanya izinkan status 'approved', 'rejected', atau 'pending'
        if (in_array($status, ['approved', 'rejected', 'pending'])) {
            $teamModel->update($id, ['status' => $status]);
            return redirect()->back()->with('success', 'Status pendaftar berhasil diperbarui!');
        }

        return redirect()->back()->with('error', 'Status tidak valid!');
    }
}