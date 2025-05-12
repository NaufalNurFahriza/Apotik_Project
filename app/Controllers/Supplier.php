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

        $data = [
            'title' => 'Data Supplier',
            'supplier' => $this->supplierModel->findAll()
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
        // Validasi input
        if (!$this->validate($this->supplierModel->validationRules)) {
            return redirect()->to(base_url('supplier/tambah'))->withInput()->with('validation', $this->validator);
        }

        // Simpan data
        $data = [
            'nama_supplier' => $this->request->getPost('nama_supplier'),
            'alamat' => $this->request->getPost('alamat'),
            'kota' => $this->request->getPost('kota'),
            'telepon' => $this->request->getPost('telepon')
        ];

        $this->supplierModel->insert($data);
        session()->setFlashdata('pesan', 'Data berhasil ditambahkan');
        return redirect()->to(base_url('supplier'));
    }

    public function edit($id)
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        $data = [
            'title' => 'Edit Supplier',
            'supplier' => $this->supplierModel->find($id),
            'validation' => \Config\Services::validation()
        ];

        return view('supplier/edit', $data);
    }

    public function update($id)
    {
        // Validasi input
        if (!$this->validate($this->supplierModel->validationRules)) {
            return redirect()->to(base_url('supplier/edit/' . $id))->withInput()->with('validation', $this->validator);
        }

        // Update data
        $data = [
            'nama_supplier' => $this->request->getPost('nama_supplier'),
            'alamat' => $this->request->getPost('alamat'),
            'kota' => $this->request->getPost('kota'),
            'telepon' => $this->request->getPost('telepon')
        ];

        $this->supplierModel->update($id, $data);
        session()->setFlashdata('pesan', 'Data berhasil diupdate');
        return redirect()->to(base_url('supplier'));
    }

    public function hapus($id)
    {
        // Hapus data
        $this->supplierModel->delete($id);
        session()->setFlashdata('pesan', 'Data berhasil dihapus');
        return redirect()->to(base_url('supplier'));
    }
}
