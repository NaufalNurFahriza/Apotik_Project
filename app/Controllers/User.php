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
        // Cek role - hanya pemilik yang bisa akses
        if (session()->get('role') !== 'pemilik') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak');
        }

        $data = [
            'title' => 'Data TTK',
            'users' => $this->userModel->findAll()
        ];

        return view('user/index', $data);
    }

    public function tambah()
    {
        // Cek role - hanya pemilik yang bisa akses
        if (session()->get('role') !== 'pemilik') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak');
        }

        $data = [
            'title' => 'Tambah TTK',
            'validation' => \Config\Services::validation()
        ];

        return view('user/tambah', $data);
    }

    public function simpan()
    {
        // Cek login dan role
        if (!session()->get('logged_in') || session()->get('role') != 'pemilik') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak.');
        }

        // STEP 1: Cek apakah data POST ada
        $postData = $this->request->getPost();
        if (empty($postData)) {
            session()->setFlashdata('error', 'Tidak ada data yang dikirim');
            return redirect()->to(base_url('user/tambah'));
        }

        // STEP 2: Validasi input
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'username' => 'required|min_length[3]|max_length[20]|is_unique[user.username]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'role' => 'required|in_list[ttk,pemilik]'
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Validasi gagal: ' . implode(', ', $this->validator->getErrors()));
            return redirect()->to(base_url('user/tambah'))->withInput()->with('validation', $this->validator);
        }

        // STEP 3: Siapkan data
        $data = [
            'nama' => esc($this->request->getPost('nama')),
            'username' => esc($this->request->getPost('username')),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role')
        ];

        // STEP 4: Coba insert dengan error handling
        try {
            $result = $this->userModel->insert($data);
            
            if ($result) {
                session()->setFlashdata('pesan', 'Data TTK berhasil ditambahkan');
                return redirect()->to(base_url('user'));
            } else {
                // Ambil error dari model
                $errors = $this->userModel->errors();
                session()->setFlashdata('error', 'Gagal menyimpan: ' . implode(', ', $errors));
                return redirect()->to(base_url('user/tambah'))->withInput();
            }
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Error: ' . $e->getMessage());
            return redirect()->to(base_url('user/tambah'))->withInput();
        }
    }

    public function edit($id)
    {
        // Cek role - hanya pemilik yang bisa akses
        if (session()->get('role') !== 'pemilik') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to(base_url('user'))->with('error', 'Data tidak ditemukan');
        }

        $data = [
            'title' => 'Edit TTK',
            'user' => $user,
            'validation' => \Config\Services::validation()
        ];

        return view('user/edit', $data);
    }

    public function update($id)
    {
        // Cek role - hanya pemilik yang bisa akses
        if (session()->get('role') !== 'pemilik') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak');
        }

        // Validasi
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'username' => "required|min_length[3]|max_length[20]|is_unique[user.username,id,$id]",
            'role' => 'required|in_list[ttk,pemilik]'
        ];

        if (!empty($this->request->getPost('password'))) {
            $rules['password'] = 'min_length[6]';
            $rules['confirm_password'] = 'matches[password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->to(base_url('user/edit/' . $id))->withInput()->with('validation', $this->validator);
        }

        // Ambil data
        $data = [
            'nama' => esc($this->request->getPost('nama')),
            'username' => esc($this->request->getPost('username')),
            'role' => $this->request->getPost('role')
        ];

        if (!empty($this->request->getPost('password'))) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        // Update
        try {
            $result = $this->userModel->update($id, $data);
            if ($result) {
                session()->setFlashdata('pesan', 'Data TTK berhasil diubah');
            } else {
                session()->setFlashdata('error', 'Gagal mengubah data');
            }
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Error: ' . $e->getMessage());
        }
        
        return redirect()->to(base_url('user'));
    }

    public function hapus($id)
    {
        // Cek role - hanya pemilik yang bisa akses
        if (session()->get('role') !== 'pemilik') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak');
        }

        // Tidak bisa hapus diri sendiri
        if ($id == session()->get('id')) {
            return redirect()->to(base_url('user'))->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        try {
            $result = $this->userModel->delete($id);
            
            if ($result) {
                session()->setFlashdata('pesan', 'TTK berhasil dihapus');
            } else {
                session()->setFlashdata('error', 'Gagal menghapus TTK');
            }
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Error: ' . $e->getMessage());
        }
        
        return redirect()->to(base_url('user'));
    }
}
