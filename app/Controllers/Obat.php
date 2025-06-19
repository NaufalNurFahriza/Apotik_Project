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
    
        // Cek role (if this exists)
        if (!in_array(session()->get('role'), ['pemilik', 'ttk'])) {
            return redirect()->to(base_url('dashboard'));
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

        // Cek role (if this exists)
        if (!in_array(session()->get('role'), ['pemilik', 'ttk'])) {
            return redirect()->to(base_url('dashboard'));
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

        // Simpan data
        $data = [
            'bpom' => $this->request->getPost('bpom'),
            'nama_obat' => $this->request->getPost('nama_obat'),
            'produsen' => $this->request->getPost('produsen'),
            'supplier_id' => $this->request->getPost('supplier_id'),
            'kategori' => $this->request->getPost('kategori'),
            'satuan' => $this->request->getPost('satuan'),
            'harga_beli' => $this->request->getPost('harga_beli'),
            'harga_jual' => $this->request->getPost('harga_jual'),
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

        // Cek role (if this exists)
        if (!in_array(session()->get('role'), ['pemilik', 'ttk'])) {
            return redirect()->to(base_url('dashboard'));
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

        // Update data
        $data = [
            'bpom' => $this->request->getPost('bpom'),
            'nama_obat' => $this->request->getPost('nama_obat'),
            'produsen' => $this->request->getPost('produsen'),
            'supplier_id' => $this->request->getPost('supplier_id'),
            'kategori' => $this->request->getPost('kategori'),
            'satuan' => $this->request->getPost('satuan'),
            'harga_beli' => $this->request->getPost('harga_beli'),
            'harga_jual' => $this->request->getPost('harga_jual'),
        ];

        $this->obatModel->update($id, $data);
        session()->setFlashdata('pesan', 'Data berhasil diupdate');
        return redirect()->to(base_url('obat'));
    }

    public function hapus($id)
    {
        // Ambil data obat
        $obat = $this->obatModel->find($id);

        // Hapus data
        $this->obatModel->delete($id);
        session()->setFlashdata('pesan', 'Data berhasil dihapus');
        return redirect()->to(base_url('obat'));
    }
}
