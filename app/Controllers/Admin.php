<?php

namespace App\Controllers;

use App\Models\AdminModel;

class Admin extends BaseController
{
    protected $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
    }

    public function index()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        $data = [
            'title' => 'Data Admin',
            'admin' => $this->adminModel->findAll()
        ];

        return view('admin/index', $data);
    }

    public function tambah()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        $data = [
            'title' => 'Tambah Admin',
            'validation' => \Config\Services::validation()
        ];

        return view('admin/tambah', $data);
    }

    public function simpan()
    {
        // Validasi input
        if (!$this->validate($this->adminModel->validationRules)) {
            return redirect()->to(base_url('admin/tambah'))->withInput()->with('validation', $this->validator);
        }

        // Simpan data
        $data = [
            'nama_admin' => $this->request->getPost('nama_admin'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
        ];

        $this->adminModel->insert($data);
        session()->setFlashdata('pesan', 'Data berhasil ditambahkan');
        return redirect()->to(base_url('admin'));
    }

    public function edit($id)
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        $data = [
            'title' => 'Edit Admin',
            'admin' => $this->adminModel->find($id),
            'validation' => \Config\Services::validation()
        ];

        return view('admin/edit', $data);
    }

    public function update($id)
    {
        // Validasi input
        if (!$this->validate($this->adminModel->validationRules)) {
            return redirect()->to(base_url('admin/edit/' . $id))->withInput()->with('validation', $this->validator);
        }

        // Update data
        $data = [
            'nama_admin' => $this->request->getPost('nama_admin'),
            'username' => $this->request->getPost('username'),
        ];

        // Update password jika diisi
        if ($this->request->getPost('password') != '') {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $this->adminModel->update($id, $data);
        session()->setFlashdata('pesan', 'Data berhasil diupdate');
        return redirect()->to(base_url('admin'));
    }

    public function hapus($id)
    {
        // Hapus data
        $this->adminModel->delete($id);
        session()->setFlashdata('pesan', 'Data berhasil dihapus');
        return redirect()->to(base_url('admin'));
    }
}
