<?php

namespace App\Controllers;

use App\Models\ObatModel;
use App\Models\SupplierModel;
use App\Models\MemberModel;
use App\Models\TransaksiPenjualanModel;
use App\Models\TransaksiPembelianModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    protected $obatModel;
    protected $supplierModel;
    protected $memberModel;
    protected $transaksiPenjualanModel;
    protected $transaksiPembelianModel;
    protected $userModel;
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->obatModel = new ObatModel();
        $this->supplierModel = new SupplierModel();
        $this->memberModel = new MemberModel();
        $this->transaksiPenjualanModel = new TransaksiPenjualanModel();
        $this->transaksiPembelianModel = new TransaksiPembelianModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Hitung statistik dasar
        $totalObat = $this->obatModel->countAll();
        $totalMember = $this->memberModel->countAll();
        $totalSupplier = $this->supplierModel->countAll();
        $totalTTK = $this->userModel->where('role', 'ttk')->countAllResults();
        
        // Transaksi hari ini
        $today = date('Y-m-d');
        $transaksiHariIni = $this->transaksiPenjualanModel
            ->where('DATE(tanggal_transaksi)', $today)
            ->countAllResults();
        
        // Total penjualan hari ini
        $penjualanHariIni = $this->transaksiPenjualanModel
            ->selectSum('total')
            ->where('DATE(tanggal_transaksi)', $today)
            ->get()
            ->getRow()
            ->total ?? 0;

        // Obat dengan stok menipis (< 10)
        $obatStokMinim = $this->obatModel
            ->where('stok <', 10)
            ->countAllResults();

        // Obat terlaris bulan ini
        $bulanIni = date('Y-m');
        $builder = $this->db->table('detail_penjualan dp');
        $obatTerlaris = $builder
            ->select('o.nama_obat, SUM(dp.qty) as total_terjual')
            ->join('obat o', 'dp.obat_id = o.id')
            ->join('transaksi_penjualan tp', 'dp.transaksi_id = tp.id')
            ->where("DATE_FORMAT(tp.tanggal_transaksi, '%Y-%m')", $bulanIni)
            ->groupBy(['dp.obat_id', 'o.nama_obat'])
            ->orderBy('total_terjual', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        // Transaksi pembelian bulan ini
        $pembelianBulanIni = $this->transaksiPembelianModel
            ->selectSum('total')
            ->where("DATE_FORMAT(tanggal, '%Y-%m')", $bulanIni)
            ->get()
            ->getRow()
            ->total ?? 0;

        // Penjualan bulan ini
        $penjualanBulanIni = $this->transaksiPenjualanModel
            ->selectSum('total')
            ->where("DATE_FORMAT(tanggal_transaksi, '%Y-%m')", $bulanIni)
            ->get()
            ->getRow()
            ->total ?? 0;

        $data = [
            'title' => 'Dashboard - Apotek Kita Farma',
            'totalObat' => $totalObat,
            'totalMember' => $totalMember,
            'totalSupplier' => $totalSupplier,
            'totalTTK' => $totalTTK,
            'transaksiHariIni' => $transaksiHariIni,
            'penjualanHariIni' => $penjualanHariIni,
            'pembelianBulanIni' => $pembelianBulanIni,
            'penjualanBulanIni' => $penjualanBulanIni,
            'obatStokMinim' => $obatStokMinim,
            'obatTerlaris' => $obatTerlaris
        ];

        return view('dashboard/index', $data);
    }

    public function getChartData()
    {
        // Data untuk chart penjualan 7 hari terakhir
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $total = $this->transaksiPenjualanModel
                ->selectSum('total')
                ->where('DATE(tanggal_transaksi)', $date)
                ->get()
                ->getRow()
                ->total ?? 0;
            
            $chartData[] = [
                'date' => date('d/m', strtotime($date)),
                'total' => (float)$total
            ];
        }

        return $this->response->setJSON($chartData);
    }
}
