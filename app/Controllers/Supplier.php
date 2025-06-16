<?php

namespace App\Controllers;

use App\Models\SupplierModel;

class Supplier extends BaseController
{
    protected $supplierModel;

    public function __construct()
    {
        $this->supplierModel = new SupplierModel();
    }

    public function index()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Cek role - hanya pemilik dan ttk yang bisa akses
        if (!in_array(session()->get('role'), ['pemilik', 'ttk'])) {
            return redirect()->to(base_url('dashboard'));
        }

        $data = [
            'title' => 'Data Supplier',
            'supplier' => $this->supplierModel->getSupplierWithObatCount()
        ];

        return view('supplier/index', $data);
    }

    public function tambah()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        $data = [
            'title' => 'Tambah Supplier',
            'validation' => \Config\Services::validation()
        ];

        return view('supplier/tambah', $data);
    }

    public function simpan()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Validasi input
        if (!$this->validate($this->supplierModel->validationRules)) {
            return redirect()->to(base_url('supplier/tambah'))->withInput()->with('validation', $this->validator);
        }

        // Simpan data
        $data = [
            'nama_supplier' => esc($this->request->getPost('nama_supplier')),
            'alamat' => esc($this->request->getPost('alamat')),
            'kota' => esc($this->request->getPost('kota')),
            'telepon' => esc($this->request->getPost('telepon'))
        ];

        $this->supplierModel->insert($data);
        session()->setFlashdata('pesan', 'Data supplier berhasil ditambahkan');
        return redirect()->to(base_url('supplier'));
    }

    public function edit($id)
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        $supplier = $this->supplierModel->find($id);
        if (!$supplier) {
            session()->setFlashdata('error', 'Data supplier tidak ditemukan');
            return redirect()->to(base_url('supplier'));
        }

        $data = [
            'title' => 'Edit Supplier',
            'supplier' => $supplier,
            'validation' => \Config\Services::validation()
        ];

        return view('supplier/edit', $data);
    }

    public function update($id)
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Validasi input
        if (!$this->validate($this->supplierModel->validationRules)) {
            return redirect()->to(base_url('supplier/edit/' . $id))->withInput()->with('validation', $this->validator);
        }

        // Update data
        $data = [
            'nama_supplier' => esc($this->request->getPost('nama_supplier')),
            'alamat' => esc($this->request->getPost('alamat')),
            'kota' => esc($this->request->getPost('kota')),
            'telepon' => esc($this->request->getPost('telepon'))
        ];

        $this->supplierModel->update($id, $data);
        session()->setFlashdata('pesan', 'Data supplier berhasil diupdate');
        return redirect()->to(base_url('supplier'));
    }

    public function hapus($id)
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Cek apakah supplier digunakan oleh obat
        if ($this->supplierModel->isUsedByObat($id)) {
            session()->setFlashdata('error', 'Supplier tidak dapat dihapus karena masih digunakan oleh obat');
            return redirect()->to(base_url('supplier'));
        }

        // Hapus data
        $this->supplierModel->delete($id);
        session()->setFlashdata('pesan', 'Data supplier berhasil dihapus');
        return redirect()->to(base_url('supplier'));
    }
}
