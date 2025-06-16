<?php

namespace App\Controllers;

use App\Models\MemberModel;
use App\Models\TransaksiPenjualanModel;

class Member extends BaseController
{
    protected $memberModel;
    protected $transaksiPenjualanModel;

    public function __construct()
    {
        $this->memberModel = new MemberModel();
        $this->transaksiPenjualanModel = new TransaksiPenjualanModel();
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
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Validasi input
        if (!$this->validate([
            'nama' => 'required',
            'no_hp' => 'required'
        ])) {
            return redirect()->to(base_url('member/tambah'))->withInput();
        }

        $this->memberModel->save([
            'nama' => $this->request->getVar('nama'),
            'no_hp' => $this->request->getVar('no_hp'),
            'poin' => 0
        ]);

        session()->setFlashdata('pesan', 'Data member berhasil ditambahkan.');
        return redirect()->to(base_url('member'));
    }

    // Method baru untuk simpan member via AJAX
    public function simpanAjax()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Tidak memiliki akses'
            ]);
        }

        // Validasi input
        if (!$this->validate([
            'nama' => 'required',
            'no_hp' => 'required'
        ])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Validasi gagal'
            ]);
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'no_hp' => $this->request->getPost('no_hp'),
            'poin' => 0
        ];

        $this->memberModel->save($data);
        $id = $this->memberModel->getInsertID();

        return $this->response->setJSON([
            'status' => 'success',
            'id' => $id,
            'message' => 'Member berhasil ditambahkan'
        ]);
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
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Validasi input
        if (!$this->validate([
            'nama' => 'required',
            'no_hp' => 'required',
            'poin' => 'required|numeric'
        ])) {
            return redirect()->to(base_url('member/edit/' . $id))->withInput();
        }

        $this->memberModel->save([
            'id' => $id,
            'nama' => $this->request->getVar('nama'),
            'no_hp' => $this->request->getVar('no_hp'),
            'poin' => $this->request->getVar('poin')
        ]);

        session()->setFlashdata('pesan', 'Data member berhasil diupdate.');
        return redirect()->to(base_url('member'));
    }

    public function hapus($id)
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Cek apakah member memiliki transaksi
        $transaksi = $this->transaksiPenjualanModel->where('member_id', $id)->findAll();
        if ($transaksi) {
            session()->setFlashdata('error', 'Member tidak dapat dihapus karena memiliki riwayat transaksi.');
            return redirect()->to(base_url('member'));
        }

        $this->memberModel->delete($id);

        session()->setFlashdata('pesan', 'Data member berhasil dihapus.');
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
