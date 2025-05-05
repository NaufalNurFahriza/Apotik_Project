<?php

namespace App\Controllers;

use App\Models\TransaksiModel;
use App\Models\DetailTransaksiModel;
use App\Models\ObatModel;
use App\Models\MemberModel;
use App\Models\AdminModel;

class Transaksi extends BaseController
{
    protected $transaksiModel;
    protected $detailTransaksiModel;
    protected $obatModel;
    protected $memberModel;
    protected $adminModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiModel();
        $this->detailTransaksiModel = new DetailTransaksiModel();
        $this->obatModel = new ObatModel();
        $this->memberModel = new MemberModel();
        $this->adminModel = new AdminModel();
    }

    public function index()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Ambil parameter filter tanggal
        $start_date = $this->request->getGet('start_date') ?? date('Y-m-01'); // Default: awal bulan ini
        $end_date = $this->request->getGet('end_date') ?? date('Y-m-d'); // Default: hari ini

        // Ambil data transaksi berdasarkan filter tanggal
        $transaksi = $this->transaksiModel->getTransaksiByDateRange($start_date, $end_date);

        $data = [
            'title' => 'Data Transaksi',
            'transaksi' => $transaksi,
            'start_date' => $start_date,
            'end_date' => $end_date
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
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        $id = $this->request->getPost('id');
        $obat = $this->obatModel->find($id);
        return $this->response->setJSON($obat);
    }

    public function simpan()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Ambil data dari form
        $nama_pembeli = $this->request->getPost('nama_pembeli');
        $member_id = $this->request->getPost('member_id');
        $obat_id = $this->request->getPost('obat_id');
        $harga = $this->request->getPost('harga');
        $qty = $this->request->getPost('qty');
        $subtotal = $this->request->getPost('subtotal');
        $poin_digunakan = $this->request->getPost('poin_digunakan') ? $this->request->getPost('poin_digunakan') : 0;
        $potongan_harga = $this->request->getPost('potongan_harga') ? $this->request->getPost('potongan_harga') : 0;
        $total = $this->request->getPost('total');

        // Hitung poin yang didapat (1 poin untuk setiap Rp 50.000)
        $poin_didapat = floor($total / 50000);

        // Simpan data transaksi
        $data_transaksi = [
            'tanggal_transaksi' => date('Y-m-d H:i:s'),
            'admin_id' => session()->get('id'),
            'nama_pembeli' => $nama_pembeli,
            'member_id' => $member_id ? $member_id : null,
            'total' => $total,
            'poin_didapat' => $poin_didapat,
            'poin_digunakan' => $poin_digunakan,
            'potongan_harga' => $potongan_harga
        ];

        $this->transaksiModel->insert($data_transaksi);
        $transaksi_id = $this->transaksiModel->getInsertID();

        // Simpan detail transaksi dan update stok obat
        for ($i = 0; $i < count($obat_id); $i++) {
            // Ambil data obat
            $obat = $this->obatModel->find($obat_id[$i]);

            // Simpan detail transaksi
            $data_detail = [
                'transaksi_id' => $transaksi_id,
                'obat_id' => $obat_id[$i],
                'harga_saat_ini' => $harga[$i],
                'qty' => $qty[$i]
            ];
            $this->detailTransaksiModel->insert($data_detail);

            // Update stok obat
            $stok_baru = $obat['stok'] - $qty[$i];
            $this->obatModel->update($obat_id[$i], ['stok' => $stok_baru]);
        }

        // Update poin member jika transaksi menggunakan member
        if ($member_id) {
            $member = $this->memberModel->find($member_id);

            // Kurangi poin yang digunakan dan tambahkan poin baru
            $poin_baru = $member['poin'] - $poin_digunakan + $poin_didapat;
            $this->memberModel->update($member_id, ['poin' => $poin_baru]);
        }

        session()->setFlashdata('pesan', 'Transaksi berhasil ditambahkan.');
        return redirect()->to(base_url('transaksi/struk/' . $transaksi_id));
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

        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Ambil detail transaksi
        $detail = $this->detailTransaksiModel->where('transaksi_id', $id)->findAll();

        // Kembalikan stok obat
        foreach ($detail as $d) {
            $obat = $this->obatModel->find($d['obat_id']);
            $stok_baru = $obat['stok'] + $d['qty'];
            $this->obatModel->update($d['obat_id'], ['stok' => $stok_baru]);
        }

        // Ambil data transaksi untuk mengurangi poin member jika ada
        $transaksi = $this->transaksiModel->find($id);
        if ($transaksi['member_id'] && $transaksi['poin_didapat'] > 0) {
            $member = $this->memberModel->find($transaksi['member_id']);
            $poin_baru = max(0, $member['poin'] - $transaksi['poin_didapat']); // Pastikan poin tidak negatif
            $this->memberModel->update($transaksi['member_id'], ['poin' => $poin_baru]);
        }

        // Hapus detail transaksi
        $this->detailTransaksiModel->where('transaksi_id', $id)->delete();

        // Hapus transaksi
        $this->transaksiModel->delete($id);

        session()->setFlashdata('pesan', 'Transaksi berhasil dihapus.');
        return redirect()->to(base_url('transaksi'));
    }

    public function beliDariSupplier()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Cek role - hanya pemilik yang bisa akses
        if (session()->get('role') !== 'pemilik') {
            session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini');
            return redirect()->to(base_url('transaksi'));
        }

        $data = [
            'title' => 'Beli Obat dari Supplier',
            'obat' => $this->obatModel->getObatWithSupplier()
        ];

        return view('transaksi/beli_supplier', $data);
    }

    public function simpanPembelian()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Cek role - hanya pemilik yang bisa akses
        if (session()->get('role') !== 'pemilik') {
            session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini');
            return redirect()->to(base_url('transaksi'));
        }

        $obat_id = $this->request->getPost('obat_id');
        $jumlah = $this->request->getPost('jumlah');

        // Ambil data obat
        $obat = $this->obatModel->find($obat_id);

        // Update stok obat
        $stok_baru = $obat['stok'] + $jumlah;
        $this->obatModel->update($obat_id, ['stok' => $stok_baru]);

        session()->setFlashdata('pesan', 'Pembelian obat dari supplier berhasil.');
        return redirect()->to(base_url('obat'));
    }
}
