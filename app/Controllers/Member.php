<?php

namespace App\Controllers;

use App\Models\MemberModel;

class Member extends BaseController
{
    protected $memberModel;

    public function __construct()
    {
        $this->memberModel = new MemberModel();
    }

    public function index()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        $data = [
            'title' => 'Data Member',
            'member' => $this->memberModel->findAll()
        ];

        return view('member/index', $data);
    }

    public function tambah()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        $data = [
            'title' => 'Tambah Member',
            'validation' => \Config\Services::validation()
        ];

        return view('member/tambah', $data);
    }

    public function simpan()
    {
        // Validasi input
        if (!$this->validate($this->memberModel->validationRules)) {
            return redirect()->to(base_url('member/tambah'))->withInput()->with('validation', $this->validator);
        }

        // Simpan data
        $data = [
            'nama' => $this->request->getPost('nama'),
            'no_hp' => $this->request->getPost('no_hp'),
            'poin' => 0
        ];

        $this->memberModel->insert($data);
        session()->setFlashdata('pesan', 'Data berhasil ditambahkan');
        return redirect()->to(base_url('member'));
    }

    public function edit($id)
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        $data = [
            'title' => 'Edit Member',
            'member' => $this->memberModel->find($id),
            'validation' => \Config\Services::validation()
        ];

        return view('member/edit', $data);
    }

    public function update($id)
    {
        // Validasi input
        if (!$this->validate($this->memberModel->validationRules)) {
            return redirect()->to(base_url('member/edit/' . $id))->withInput()->with('validation', $this->validator);
        }

        // Update data
        $data = [
            'nama' => $this->request->getPost('nama'),
            'no_hp' => $this->request->getPost('no_hp'),
            'poin' => $this->request->getPost('poin')
        ];

        $this->memberModel->update($id, $data);
        session()->setFlashdata('pesan', 'Data berhasil diupdate');
        return redirect()->to(base_url('member'));
    }

    public function hapus($id)
    {
        // Hapus data
        $this->memberModel->delete($id);
        session()->setFlashdata('pesan', 'Data berhasil dihapus');
        return redirect()->to(base_url('member'));
    }

    public function riwayat($id)
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        $data = [
            'title' => 'Riwayat Transaksi Member',
            'member' => $this->memberModel->find($id),
            'riwayat' => $this->memberModel->getRiwayatTransaksi($id)
        ];

        return view('member/riwayat', $data);
    }
}