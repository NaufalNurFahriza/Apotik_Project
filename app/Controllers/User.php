<?php

namespace App\Controllers;

use App\Models\UserModel;

class User extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        $data = [
            'title' => 'Data TTK (Tenaga Teknis Kefarmasian)',
            'users' => $this->userModel->findAll()
        ];

        return view('user/index', $data);
    }

    public function tambah()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        $data = [
            'title' => 'Tambah TTK',
            'validation' => \Config\Services::validation()
        ];

        return view('user/tambah', $data);
    }

    public function simpan()
    {
        // Validasi input
        if (!$this->validate($this->userModel->validationRules)) {
            return redirect()->to(base_url('user/tambah'))->withInput()->with('validation', $this->validator);
        }

        // Simpan data
        $data = [
            'nama' => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role')
        ];

        $this->userModel->insert($data);
        session()->setFlashdata('pesan', 'Data TTK berhasil ditambahkan');
        return redirect()->to(base_url('user'));
    }

    public function edit($id)
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        $data = [
            'title' => 'Edit TTK',
            'user' => $this->userModel->find($id),
            'validation' => \Config\Services::validation()
        ];

        return view('user/edit', $data);
    }

    public function update($id)
    {
        // Validasi input
        $rules = $this->userModel->validationRules;
        $rules['username'] = 'required|is_unique[user.username,id,' . $id . ']';
        
        if (!$this->validate($rules)) {
            return redirect()->to(base_url('user/edit/' . $id))->withInput()->with('validation', $this->validator);
        }

        // Update data
        $data = [
            'nama' => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'role' => $this->request->getPost('role')
        ];

        // Update password jika diisi
        if ($this->request->getPost('password') != '') {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $data);
        session()->setFlashdata('pesan', 'Data TTK berhasil diupdate');
        return redirect()->to(base_url('user'));
    }

    public function hapus($id)
    {
        // Cek apakah user yang akan dihapus bukan user yang sedang login
        if ($id == session()->get('id')) {
            session()->setFlashdata('error', 'Tidak dapat menghapus akun sendiri');
            return redirect()->to(base_url('user'));
        }

        // Hapus data
        $this->userModel->delete($id);
        session()->setFlashdata('pesan', 'Data TTK berhasil dihapus');
        return redirect()->to(base_url('user'));
    }
}
