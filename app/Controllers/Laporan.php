<?php

namespace App\Controllers;

use App\Models\TransaksiPenjualanModel;
use App\Models\TransaksiPembelianModel;
use App\Models\ObatModel;
use App\Models\MemberModel;
use App\Models\SupplierModel;
use App\Models\DetailPenjualanModel;
use App\Models\DetailPembelianModel;

class Laporan extends BaseController
{
    protected $transaksiPenjualanModel;
    protected $transaksiPembelianModel;
    protected $obatModel;
    protected $memberModel;
    protected $supplierModel;
    protected $detailPenjualanModel;
    protected $detailPembelianModel;
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->transaksiPenjualanModel = new TransaksiPenjualanModel();
        $this->transaksiPembelianModel = new TransaksiPembelianModel();
        $this->obatModel = new ObatModel();
        $this->memberModel = new MemberModel();
        $this->supplierModel = new SupplierModel();
        $this->detailPenjualanModel = new DetailPenjualanModel();
        $this->detailPembelianModel = new DetailPembelianModel();
    }

    public function penjualan()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Ambil parameter filter
        $start_date = $this->request->getGet('start_date') ?? date('Y-m-01');
        $end_date = $this->request->getGet('end_date') ?? date('Y-m-d');
        $periode = $this->request->getGet('periode') ?? 'harian';

        // Query data transaksi penjualan dengan detail
        $builder = $this->db->table('transaksi_penjualan tp');
        $data = $builder
            ->select('tp.*, u.nama as nama_user, m.nama as nama_member')
            ->join('user u', 'tp.user_id = u.id', 'left')
            ->join('member m', 'tp.member_id = m.id', 'left')
            ->where('DATE(tp.tanggal_transaksi) >=', $start_date)
            ->where('DATE(tp.tanggal_transaksi) <=', $end_date)
            ->orderBy('tp.tanggal_transaksi', 'DESC')
            ->get()
            ->getResultArray();

        // Hitung summary
        $totalTransaksi = count($data);
        $totalPenjualan = array_sum(array_column($data, 'total'));
        $rataRata = $totalTransaksi > 0 ? $totalPenjualan / $totalTransaksi : 0;
        
        // Hitung total item
        $builderItem = $this->db->table('detail_penjualan dp');
        $totalItem = $builderItem
            ->selectSum('qty')
            ->join('transaksi_penjualan tp', 'dp.transaksi_id = tp.id')
            ->where('DATE(tp.tanggal_transaksi) >=', $start_date)
            ->where('DATE(tp.tanggal_transaksi) <=', $end_date)
            ->get()
            ->getRow()
            ->qty ?? 0;

        $summary = [
            'total_transaksi' => $totalTransaksi,
            'total_penjualan' => $totalPenjualan,
            'rata_rata' => $rataRata,
            'total_item' => $totalItem
        ];

        $viewData = [
            'title' => 'Laporan Penjualan',
            'data' => $data,
            'summary' => $summary,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'periode' => $periode
        ];

        return view('laporan/penjualan', $viewData);
    }

    public function pembelian()
    {
        // Cek login dan role
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        if (session()->get('role') !== 'pemilik') {
            session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini');
            return redirect()->to(base_url('dashboard'));
        }

        // Ambil parameter filter
        $start_date = $this->request->getGet('start_date') ?? date('Y-m-01');
        $end_date = $this->request->getGet('end_date') ?? date('Y-m-d');
        $supplier_id = $this->request->getGet('supplier_id') ?? '';

        // Query data transaksi pembelian
        $builder = $this->db->table('transaksi_pembelian tp');
        $query = $builder
            ->select('tp.*, u.nama as nama_user, s.nama_supplier')
            ->join('user u', 'tp.user_id = u.id', 'left')
            ->join('supplier s', 'tp.supplier_id = s.id')
            ->where('DATE(tp.tanggal) >=', $start_date)
            ->where('DATE(tp.tanggal) <=', $end_date);

        if (!empty($supplier_id)) {
            $query->where('tp.supplier_id', $supplier_id);
        }

        $data = $query->orderBy('tp.tanggal', 'DESC')->get()->getResultArray();

        // Hitung summary
        $totalTransaksi = count($data);
        $totalPembelian = array_sum(array_column($data, 'total'));
        $rataRata = $totalTransaksi > 0 ? $totalPembelian / $totalTransaksi : 0;

        // Hitung total item
        $builderItem = $this->db->table('detail_pembelian dp');
        $itemQuery = $builderItem
            ->selectSum('qty')
            ->join('transaksi_pembelian tp', 'dp.transaksi_pembelian_id = tp.id')
            ->where('DATE(tp.tanggal) >=', $start_date)
            ->where('DATE(tp.tanggal) <=', $end_date);

        if (!empty($supplier_id)) {
            $itemQuery->where('tp.supplier_id', $supplier_id);
        }

        $totalItem = $itemQuery->get()->getRow()->qty ?? 0;

        $summary = [
            'total_transaksi' => $totalTransaksi,
            'total_pembelian' => $totalPembelian,
            'rata_rata' => $rataRata,
            'total_item' => $totalItem
        ];

        // Ambil data supplier untuk filter
        $supplier = $this->supplierModel->findAll();

        $viewData = [
            'title' => 'Laporan Pembelian',
            'data' => $data,
            'summary' => $summary,
            'supplier' => $supplier,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'supplier_id' => $supplier_id
        ];

        return view('laporan/pembelian', $viewData);
    }

    public function obatTerlaris()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Ambil parameter filter
        $start_date = $this->request->getGet('start_date') ?? date('Y-m-01');
        $end_date = $this->request->getGet('end_date') ?? date('Y-m-d');
        $limit = $this->request->getGet('limit') ?? 20;

        // Query obat terlaris
        $builder = $this->db->table('detail_penjualan dp');
        $obatTerlaris = $builder
            ->select('o.nama_obat, o.kategori, s.nama_supplier, SUM(dp.qty) as total_terjual, SUM(dp.subtotal) as total_pendapatan')
            ->join('obat o', 'dp.obat_id = o.id')
            ->join('supplier s', 'o.supplier_id = s.id')
            ->join('transaksi_penjualan tp', 'dp.transaksi_id = tp.id')
            ->where('DATE(tp.tanggal_transaksi) >=', $start_date)
            ->where('DATE(tp.tanggal_transaksi) <=', $end_date)
            ->groupBy(['o.id', 'o.nama_obat', 'o.kategori', 's.nama_supplier'])
            ->orderBy('total_terjual', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Laporan Obat Terlaris',
            'obat_terlaris' => $obatTerlaris,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'limit' => $limit
        ];

        return view('laporan/obat_terlaris', $data);
    }

    public function member()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Ambil parameter filter
        $start_date = $this->request->getGet('start_date') ?? date('Y-m-01');
        $end_date = $this->request->getGet('end_date') ?? date('Y-m-d');

        // Query aktivitas member
        $builder = $this->db->table('transaksi_penjualan tp');
        $aktivitasMember = $builder
            ->select('m.nama as nama_member, m.no_hp, COUNT(*) as jumlah_transaksi, SUM(tp.total) as total_belanja, m.poin')
            ->join('member m', 'tp.member_id = m.id')
            ->where('DATE(tp.tanggal_transaksi) >=', $start_date)
            ->where('DATE(tp.tanggal_transaksi) <=', $end_date)
            ->where('tp.member_id IS NOT NULL')
            ->groupBy(['m.id', 'm.nama', 'm.no_hp', 'm.poin'])
            ->orderBy('total_belanja', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Laporan Aktivitas Member',
            'aktivitas_member' => $aktivitasMember,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];

        return view('laporan/member', $data);
    }

    public function supplier()
    {
        // Cek login dan role
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        if (session()->get('role') !== 'pemilik') {
            session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini');
            return redirect()->to(base_url('dashboard'));
        }

        // Ambil parameter filter
        $start_date = $this->request->getGet('start_date') ?? date('Y-m-01');
        $end_date = $this->request->getGet('end_date') ?? date('Y-m-d');

        // Query performance supplier
        $builder = $this->db->table('transaksi_pembelian tp');
        $performanceSupplier = $builder
            ->select('s.nama_supplier, s.no_hp, s.alamat, COUNT(*) as jumlah_transaksi, SUM(tp.total) as total_pembelian')
            ->join('supplier s', 'tp.supplier_id = s.id')
            ->where('DATE(tp.tanggal) >=', $start_date)
            ->where('DATE(tp.tanggal) <=', $end_date)
            ->groupBy(['s.id', 's.nama_supplier', 's.no_hp', 's.alamat'])
            ->orderBy('total_pembelian', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Performance Supplier',
            'performance_supplier' => $performanceSupplier,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];

        return view('laporan/supplier', $data);
    }

    public function keuangan()
    {
        // Cek login dan role
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        if (session()->get('role') !== 'pemilik') {
            session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini');
            return redirect()->to(base_url('dashboard'));
        }

        // Ambil parameter filter
        $start_date = $this->request->getGet('start_date') ?? date('Y-m-01');
        $end_date = $this->request->getGet('end_date') ?? date('Y-m-d');

        // Query laporan keuangan
        $builderPenjualan = $this->db->table('transaksi_penjualan');
        $totalPenjualan = $builderPenjualan
            ->selectSum('total')
            ->where('DATE(tanggal_transaksi) >=', $start_date)
            ->where('DATE(tanggal_transaksi) <=', $end_date)
            ->get()
            ->getRow()
            ->total ?? 0;

        $builderPembelian = $this->db->table('transaksi_pembelian');
        $totalPembelian = $builderPembelian
            ->selectSum('total')
            ->where('DATE(tanggal) >=', $start_date)
            ->where('DATE(tanggal) <=', $end_date)
            ->get()
            ->getRow()
            ->total ?? 0;

        // Hitung profit kotor
        $profitKotor = $totalPenjualan - $totalPembelian;

        $data = [
            'title' => 'Laporan Keuangan',
            'total_penjualan' => $totalPenjualan,
            'total_pembelian' => $totalPembelian,
            'profit_kotor' => $profitKotor,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];

        return view('laporan/keuangan', $data);
    }

    public function stok()
    {
        // Cek login dan role
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        if (session()->get('role') !== 'pemilik') {
            session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini');
            return redirect()->to(base_url('dashboard'));
        }

        // Query laporan stok
        $builder = $this->db->table('obat o');
        $laporanStok = $builder
            ->select('o.nama_obat, o.kategori, o.satuan, o.stok, o.harga_beli, o.harga_jual, s.nama_supplier')
            ->join('supplier s', 'o.supplier_id = s.id')
            ->orderBy('o.stok', 'ASC')
            ->get()
            ->getResultArray();

        // Kategorikan stok
        $stokHabis = array_filter($laporanStok, function($item) { return $item['stok'] == 0; });
        $stokMinim = array_filter($laporanStok, function($item) { return $item['stok'] > 0 && $item['stok'] < 10; });
        $stokAman = array_filter($laporanStok, function($item) { return $item['stok'] >= 10; });

        $data = [
            'title' => 'Laporan Stok',
            'laporan_stok' => $laporanStok,
            'stok_habis' => $stokHabis,
            'stok_minim' => $stokMinim,
            'stok_aman' => $stokAman
        ];

        return view('laporan/stok', $data);
    }
}
