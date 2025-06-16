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

       // Hitung total item - PERBAIKAN ERROR DI SINI
       $builderItem = $this->db->table('detail_pembelian dp');
       $itemQuery = $builderItem
           ->selectSum('qty')
           ->join('transaksi_pembelian tp', 'dp.pembelian_id = tp.id') // DIPERBAIKI: pembelian_id bukan transaksi_pembelian_id
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
}
