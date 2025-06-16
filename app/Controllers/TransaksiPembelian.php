<?php

namespace App\Controllers;

use App\Models\TransaksiPembelianModel;
use App\Models\DetailPembelianModel;
use App\Models\ObatModel;
use App\Models\SupplierModel;
use App\Models\UserModel;

class TransaksiPembelian extends BaseController
{
    protected $transaksiPembelianModel;
    protected $detailPembelianModel;
    protected $obatModel;
    protected $supplierModel;
    protected $userModel;

    public function __construct()
    {
        $this->transaksiPembelianModel = new TransaksiPembelianModel();
        $this->detailPembelianModel = new DetailPembelianModel();
        $this->obatModel = new ObatModel();
        $this->supplierModel = new SupplierModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Cek role - pemilik dan TTK yang bisa akses
        if (!in_array(session()->get('role'), ['pemilik', 'ttk'])) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini');
            return redirect()->to(base_url('dashboard'));
        }

        // Ambil parameter filter tanggal
        $start_date = $this->request->getGet('start_date') ?? date('Y-m-01');
        $end_date = $this->request->getGet('end_date') ?? date('Y-m-d');

        // Ambil data transaksi pembelian berdasarkan filter tanggal
        $transaksi = $this->transaksiPembelianModel->getTransaksiByDateRange($start_date, $end_date);

        $data = [
            'title' => 'Data Pembelian',
            'transaksi' => $transaksi,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];

        return view('transaksi_pembelian/index', $data);
    }

    public function tambah()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Cek role - pemilik dan TTK yang bisa akses
        if (!in_array(session()->get('role'), ['pemilik', 'ttk'])) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini');
            return redirect()->to(base_url('dashboard'));
        }

        $data = [
            'title' => 'Beli dari Supplier',
            'obat' => $this->obatModel->findAll(),
            'supplier' => $this->supplierModel->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('transaksi_pembelian/tambah', $data);
    }

    public function getObatBySupplier()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $supplier_id = $this->request->getPost('supplier_id');
        
        if (!$supplier_id) {
            return $this->response->setJSON(['error' => 'Supplier ID required']);
        }

        $obat = $this->obatModel->where('supplier_id', $supplier_id)->findAll();
        
        return $this->response->setJSON($obat);
    }

    public function getObatById()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $id = $this->request->getPost('id');
        $obat = $this->obatModel->find($id);
        
        if (!$obat) {
            return $this->response->setJSON(['error' => 'Obat tidak ditemukan']);
        }

        return $this->response->setJSON($obat);
    }

    public function cariFaktur()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $supplier_id = $this->request->getPost('supplier_id');
        $nomor_faktur = $this->request->getPost('nomor_faktur');
        
        if (!$supplier_id || !$nomor_faktur) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak lengkap']);
        }

        // Simulasi data faktur (dalam implementasi nyata, ini bisa dari API supplier atau database faktur)
        // Untuk demo, kita buat data dummy berdasarkan supplier
        $supplier = $this->supplierModel->find($supplier_id);
        if (!$supplier) {
            return $this->response->setJSON(['success' => false, 'message' => 'Supplier tidak ditemukan']);
        }

        // Get obat dari supplier untuk simulasi
        $obatSupplier = $this->obatModel->where('supplier_id', $supplier_id)->findAll();
        
        if (empty($obatSupplier)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak ada obat dari supplier ini']);
        }

        // Simulasi detail faktur (dalam implementasi nyata, data ini dari sistem supplier)
        $detailFaktur = [];
        foreach ($obatSupplier as $obat) {
            // Simulasi qty random untuk demo
            $qty = rand(10, 50);
            $detailFaktur[] = [
                'obat_id' => $obat['id'],
                'nama_obat' => $obat['nama_obat'],
                'harga_beli' => $obat['harga_beli'],
                'qty' => $qty,
                'satuan' => $obat['satuan'],
                'nomor_batch' => 'BATCH-' . date('Ymd') . '-' . $obat['id'],
                'expired_date' => date('Y-m-d', strtotime('+2 years'))
            ];
        }

        $response = [
            'success' => true,
            'data' => [
                'supplier' => $supplier,
                'faktur' => [
                    'nomor_faktur' => $nomor_faktur,
                    'tanggal' => date('Y-m-d')
                ],
                'detail' => $detailFaktur
            ]
        ];

        return $this->response->setJSON($response);
    }

    public function simpan()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Cek role - pemilik dan TTK yang bisa akses
        if (!in_array(session()->get('role'), ['pemilik', 'ttk'])) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini');
            return redirect()->to(base_url('dashboard'));
        }

        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'supplier_id' => 'required|numeric',
            'obat_id.*' => 'required|numeric',
            'qty.*' => 'required|numeric|greater_than[0]',
            'harga_beli.*' => 'required|numeric|greater_than[0]',
            'total' => 'required|numeric|greater_than[0]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->to(base_url('transaksi-pembelian/tambah'))
                           ->withInput()
                           ->with('validation', $validation);
        }

        // Ambil data dari form
        $supplier_id = $this->request->getPost('supplier_id');
        $obat_id = $this->request->getPost('obat_id');
        $harga_beli = $this->request->getPost('harga_beli');
        $qty = $this->request->getPost('qty');
        $total = $this->request->getPost('total');

        // Pastikan user_id (TTK) valid
        $user_id = session()->get('id');
        if ($user_id == 0) {
            $firstUser = $this->userModel->first();
            if ($firstUser) {
                $user_id = $firstUser['id'];
            } else {
                session()->setFlashdata('error', 'Tidak ada TTK yang tersedia di database.');
                return redirect()->to(base_url('transaksi-pembelian/tambah'));
            }
        }

        // Generate nomor faktur internal
        $nomor_faktur = 'PB-' . date('Ymd') . '-' . str_pad($this->transaksiPembelianModel->countAllResults() + 1, 4, '0', STR_PAD_LEFT);

        // Simpan data transaksi pembelian
        $data_transaksi = [
            'nomor_faktur' => $nomor_faktur,
            'tanggal' => date('Y-m-d'),
            'user_id' => $user_id,
            'supplier_id' => $supplier_id,
            'total' => $total
        ];

        $this->transaksiPembelianModel->insert($data_transaksi);
        $transaksi_id = $this->transaksiPembelianModel->getInsertID();

        // Simpan detail transaksi dan update stok obat
        for ($i = 0; $i < count($obat_id); $i++) {
            // Ambil data obat
            $obat = $this->obatModel->find($obat_id[$i]);

            // Simpan detail transaksi
            $data_detail = [
                'pembelian_id' => $transaksi_id,
                'obat_id' => $obat_id[$i],
                'harga_beli' => $harga_beli[$i],
                'qty' => $qty[$i],
                'nomor_batch' => 'BATCH-' . date('Ymd') . '-' . $obat_id[$i],
                'expired_date' => date('Y-m-d', strtotime('+2 years')),
                'satuan' => $obat['satuan']
            ];
            $this->detailPembelianModel->insert($data_detail);

            // Update stok dan harga beli obat
            $stok_baru = $obat['stok'] + $qty[$i];
            $this->obatModel->update($obat_id[$i], [
                'stok' => $stok_baru,
                'harga_beli' => $harga_beli[$i]
            ]);
        }

        session()->setFlashdata('pesan', 'Transaksi pembelian berhasil ditambahkan.');
        return redirect()->to(base_url('transaksi-pembelian/faktur/' . $transaksi_id));
    }

    public function detail($id)
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Cek role - pemilik dan TTK yang bisa akses
        if (!in_array(session()->get('role'), ['pemilik', 'ttk'])) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini');
            return redirect()->to(base_url('dashboard'));
        }

        $data = [
            'title' => 'Detail Transaksi Pembelian',
            'transaksi' => $this->transaksiPembelianModel->getTransaksiById($id),
            'detail' => $this->transaksiPembelianModel->getDetailObat($id)
        ];

        return view('transaksi_pembelian/detail', $data);
    }

    public function faktur($id)
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Cek role - pemilik dan TTK yang bisa akses
        if (!in_array(session()->get('role'), ['pemilik', 'ttk'])) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini');
            return redirect()->to(base_url('dashboard'));
        }

        $data = [
            'title' => 'Faktur Pembelian',
            'transaksi' => $this->transaksiPembelianModel->getTransaksiById($id),
            'detail' => $this->transaksiPembelianModel->getDetailObat($id)
        ];

        return view('transaksi_pembelian/faktur', $data);
    }

    public function hapus($id)
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Cek role - pemilik dan TTK yang bisa hapus transaksi
        if (!in_array(session()->get('role'), ['pemilik', 'ttk'])) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses untuk menghapus transaksi');
            return redirect()->to(base_url('transaksi-pembelian'));
        }

        // Ambil detail transaksi
        $detail = $this->detailPembelianModel->where('pembelian_id', $id)->findAll();

        // Kembalikan stok obat (kurangi stok)
        foreach ($detail as $d) {
            $obat = $this->obatModel->find($d['obat_id']);
            $stok_baru = max(0, $obat['stok'] - $d['qty']); // Pastikan stok tidak negatif
            $this->obatModel->update($d['obat_id'], ['stok' => $stok_baru]);
        }

        // Hapus detail transaksi
        $this->detailPembelianModel->where('pembelian_id', $id)->delete();

        // Hapus transaksi
        $this->transaksiPembelianModel->delete($id);

        session()->setFlashdata('pesan', 'Transaksi pembelian berhasil dihapus.');
        return redirect()->to(base_url('transaksi-pembelian'));
    }
}
