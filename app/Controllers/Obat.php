<?php

namespace App\Controllers;

use App\Models\ObatModel;
use App\Models\SupplierModel;

class Obat extends BaseController
{
    protected $obatModel;
    protected $supplierModel;

    public function __construct()
    {
        $this->obatModel = new ObatModel();
        $this->supplierModel = new SupplierModel();
    }

    public function index()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        $data = [
            'title' => 'Data Obat',
            'obat' => $this->obatModel->getObatWithSupplier()
        ];

        return view('obat/index', $data);
    }

    public function tambah()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        $data = [
            'title' => 'Tambah Obat',
            'supplier' => $this->supplierModel->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('obat/tambah', $data);
    }

    public function simpan()
    {
        // Validasi input
        if (!$this->validate($this->obatModel->validationRules)) {
            return redirect()->to(base_url('obat/tambah'))->withInput()->with('validation', $this->validator);
        }

        // Upload gambar
        $gambar = $this->request->getFile('gambar');
        $namaGambar = 'default.jpg';

        if ($gambar->isValid() && !$gambar->hasMoved()) {
            $namaGambar = $gambar->getRandomName();
            $gambar->move('uploads/obat', $namaGambar);
        }

        // Simpan data
        $data = [
            'kode_obat' => $this->request->getPost('kode_obat'),
            'nama_obat' => $this->request->getPost('nama_obat'),
            'harga' => $this->request->getPost('harga'),
            'produsen' => $this->request->getPost('produsen'),
            'supplier_id' => $this->request->getPost('supplier_id'),
            'stok' => $this->request->getPost('stok'),
            'gambar' => $namaGambar
        ];

        $this->obatModel->insert($data);
        session()->setFlashdata('pesan', 'Data berhasil ditambahkan');
        return redirect()->to(base_url('obat'));
    }

    public function edit($id)
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        $data = [
            'title' => 'Edit Obat',
            'obat' => $this->obatModel->find($id),
            'supplier' => $this->supplierModel->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('obat/edit', $data);
    }

    public function update($id)
    {
        // Validasi input
        if (!$this->validate($this->obatModel->validationRules)) {
            return redirect()->to(base_url('obat/edit/' . $id))->withInput()->with('validation', $this->validator);
        }

        // Data lama
        $obatLama = $this->obatModel->find($id);
        $namaGambar = $obatLama['gambar'];

        // Upload gambar baru jika ada
        $gambar = $this->request->getFile('gambar');
        if ($gambar->isValid() && !$gambar->hasMoved()) {
            // Hapus gambar lama jika bukan default
            if ($namaGambar != 'default.jpg' && file_exists('uploads/obat/' . $namaGambar)) {
                unlink('uploads/obat/' . $namaGambar);
            }
            
            $namaGambar = $gambar->getRandomName();
            $gambar->move('uploads/obat', $namaGambar);
        }

        // Update data
        $data = [
            'kode_obat' => $this->request->getPost('kode_obat'),
            'nama_obat' => $this->request->getPost('nama_obat'),
            'harga' => $this->request->getPost('harga'),
            'produsen' => $this->request->getPost('produsen'),
            'supplier_id' => $this->request->getPost('supplier_id'),
            'stok' => $this->request->getPost('stok'),
            'gambar' => $namaGambar
        ];

        $this->obatModel->update($id, $data);
        session()->setFlashdata('pesan', 'Data berhasil diupdate');
        return redirect()->to(base_url('obat'));
    }

    public function hapus($id)
    {
        // Ambil data obat
        $obat = $this->obatModel->find($id);
        
        // Hapus gambar jika bukan default
        if ($obat['gambar'] != 'default.jpg' && file_exists('uploads/obat/' . $obat['gambar'])) {
            unlink('uploads/obat/' . $obat['gambar']);
        }

        // Hapus data
        $this->obatModel->delete($id);
        session()->setFlashdata('pesan', 'Data berhasil dihapus');
        return redirect()->to(base_url('obat'));
    }
}