<?php

namespace App\Controllers;

use App\Models\TransaksiModel;
use App\Models\DetailTransaksiModel;
use App\Models\ObatModel;
use App\Models\MemberModel;

class Transaksi extends BaseController
{
    protected $transaksiModel;
    protected $detailTransaksiModel;
    protected $obatModel;
    protected $memberModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiModel();
        $this->detailTransaksiModel = new DetailTransaksiModel();
        $this->obatModel = new ObatModel();
        $this->memberModel = new MemberModel();
    }

    public function index()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        $data = [
            'title' => 'Data Transaksi',
            'transaksi' => $this->transaksiModel->getAllTransaksi()
        ];

        return view('transaksi/index', $data);
    }

    public function tambah()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        $data = [
            'title' => 'Tambah Transaksi',
            'obat' => $this->obatModel->findAll(),
            'member' => $this->memberModel->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('transaksi/tambah', $data);
    }

    public function getObatById()
    {
        $id = $this->request->getPost('id');
        $obat = $this->obatModel->find($id);
        return $this->response->setJSON($obat);
    }

    public function simpan()
    {
        // Data transaksi
        $namaPembeli = $this->request->getPost('nama_pembeli');
        $memberId = $this->request->getPost('member_id');
        $total = $this->request->getPost('total');
        $obatId = $this->request->getPost('obat_id');
        $qty = $this->request->getPost('qty');
        $harga = $this->request->getPost('harga');

        // Hitung poin yang didapat (1 poin per 50.000)
        $poinDidapat = floor($total / 50000);

        // Simpan transaksi
        $dataTransaksi = [
            'tanggal_transaksi' => date('Y-m-d H:i:s'),
            'admin_id' => session()->get('id'),
            'nama_pembeli' => $namaPembeli,
            'member_id' => $memberId ? $memberId : null,
            'total' => $total,
            'poin_didapat' => $poinDidapat
        ];

        $this->transaksiModel->insert($dataTransaksi);
        $transaksiId = $this->transaksiModel->getInsertID();

        // Simpan detail transaksi
        for ($i = 0; $i < count($obatId); $i++) {
            if ($obatId[$i] != '' && $qty[$i] > 0) {
                $dataDetail = [
                    'transaksi_id' => $transaksiId,
                    'obat_id' => $obatId[$i],
                    'qty' => $qty[$i],
                    'harga_saat_ini' => $harga[$i]
                ];
                $this->detailTransaksiModel->tambahDetail($dataDetail);

                // Update stok obat
                $this->obatModel->updateStok($obatId[$i], -$qty[$i]);
            }
        }

        // Update poin member jika ada
        if ($memberId) {
            $this->memberModel->updatePoin($memberId, $poinDidapat);
        }

        session()->setFlashdata('pesan', 'Transaksi berhasil disimpan');
        return redirect()->to(base_url('transaksi/struk/' . $transaksiId));
    }

    public function detail($id)
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        $data = [
            'title' => 'Detail Transaksi',
            'transaksi' => $this->transaksiModel->getTransaksiById($id),
            'detail' => $this->transaksiModel->getDetailObat($id)
        ];

        return view('transaksi/detail', $data);
    }

    public function struk($id)
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        $data = [
            'title' => 'Struk Transaksi',
            'transaksi' => $this->transaksiModel->getTransaksiById($id),
            'detail' => $this->transaksiModel->getDetailObat($id)
        ];

        return view('transaksi/struk', $data);
    }

    public function hapus($id)
    {
        // Hapus data
        $this->transaksiModel->delete($id);
        session()->setFlashdata('pesan', 'Data berhasil dihapus');
        return redirect()->to(base_url('transaksi'));
    }

    public function beliDariSupplier()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        $data = [
            'title' => 'Beli Obat dari Supplier',
            'obat' => $this->obatModel->getObatWithSupplier(),
            'validation' => \Config\Services::validation()
        ];

        return view('transaksi/beli_supplier', $data);
    }

    public function simpanPembelian()
    {
        $obatId = $this->request->getPost('obat_id');
        $jumlah = $this->request->getPost('jumlah');

        // Update stok obat
        $this->obatModel->updateStok($obatId, $jumlah);

        session()->setFlashdata('pesan', 'Pembelian obat dari supplier berhasil');
        return redirect()->to(base_url('obat'));
    }
}