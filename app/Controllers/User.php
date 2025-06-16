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
        // Cek login dan role
        if (!session()->get('logged_in') || session()->get('role') != 'pemilik') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak. Hanya pemilik yang dapat menambah user.');
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

        // Validasi input dengan confirm password
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'username' => 'required|min_length[3]|max_length[20]|is_unique[user.username]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'role' => 'required|in_list[ttk,pemilik]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to(base_url('user/tambah'))->withInput()->with('validation', $this->validator);
        }

        // Simpan data
        $data = [
            'nama' => esc($this->request->getPost('nama')),
            'username' => esc($this->request->getPost('username')),
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

        // Cek role - hanya pemilik atau edit data sendiri
        if (session()->get('role') != 'pemilik' && session()->get('id') != $id) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak.');
        }

        $data = [
            'title' => 'Edit TTK',
            'user' => $this->userModel->find($id),
            'validation' => \Config\Services::validation()
        ];

        if (!$data['user']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User tidak ditemukan');
        }

        return view('user/edit', $data);
    }

    public function update($id)
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Cek role - hanya pemilik atau edit data sendiri
        if (session()->get('role') != 'pemilik' && session()->get('id') != $id) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak.');
        }

        // Validasi input
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'username' => 'required|min_length[3]|max_length[20]|is_unique[user.username,id,' . $id . ']',
            'role' => 'required|in_list[ttk,pemilik]'
        ];

        // Tambah validasi password jika diisi
        if ($this->request->getPost('password') != '') {
            $rules['password'] = 'required|min_length[6]';
            $rules['confirm_password'] = 'required|matches[password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->to(base_url('user/edit/' . $id))->withInput()->with('validation', $this->validator);
        }

        // Update data
        $data = [
            'nama' => esc($this->request->getPost('nama')),
            'username' => esc($this->request->getPost('username')),
            'role' => esc($this->request->getPost('role'))
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
        // Cek login dan role
        if (!session()->get('logged_in') || session()->get('role') != 'pemilik') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak.');
        }

        // Cek apakah user yang akan dihapus bukan user yang sedang login
        if ($id == session()->get('id')) {
            session()->setFlashdata('error', 'Tidak dapat menghapus akun sendiri');
            return redirect()->to(base_url('user'));
        }

        // Cek apakah user masih memiliki transaksi
        $db = \Config\Database::connect();
        $transaksiPenjualan = $db->table('transaksi_penjualan')->where('user_id', $id)->countAllResults();
        $transaksiPembelian = $db->table('transaksi_pembelian')->where('user_id', $id)->countAllResults();

        if ($transaksiPenjualan > 0 || $transaksiPembelian > 0) {
            session()->setFlashdata('error', 'Tidak dapat menghapus TTK yang masih memiliki riwayat transaksi');
            return redirect()->to(base_url('user'));
        }

        // Hapus data
        $this->userModel->delete($id);
        session()->setFlashdata('pesan', 'Data TTK berhasil dihapus');
        return redirect()->to(base_url('user'));
    }
}
