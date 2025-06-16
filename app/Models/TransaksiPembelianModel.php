<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiPembelianModel extends Model
{
    protected $table      = 'transaksi_pembelian';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['nomor_faktur', 'nomor_faktur_supplier', 'tanggal_transaksi', 'user_id', 'supplier_id', 'total', 'keterangan', 'status'];

    // Validasi
    protected $validationRules = [
        'user_id' => 'required|numeric',
        'supplier_id' => 'required|numeric',
        'tanggal_transaksi' => 'required',
        'total' => 'required|numeric'
    ];

    // Get semua transaksi dengan detail supplier dan user
    public function getAllTransaksi()
    {
        return $this->select('transaksi_pembelian.*, supplier.nama_supplier, supplier.alamat as alamat_supplier, supplier.kota as kota_supplier, supplier.telepon as telepon_supplier, user.nama as nama_user')
                   ->join('supplier', 'supplier.id = transaksi_pembelian.supplier_id')
                   ->join('user', 'user.id = transaksi_pembelian.user_id')
                   ->orderBy('transaksi_pembelian.tanggal_transaksi', 'DESC')
                   ->findAll();
    }

    // Get transaksi berdasarkan ID dengan detail
    public function getTransaksiById($id)
    {
        return $this->select('transaksi_pembelian.*, supplier.nama_supplier, supplier.alamat as alamat_supplier, supplier.kota as kota_supplier, supplier.telepon as telepon_supplier, user.nama as nama_user')
                   ->join('supplier', 'supplier.id = transaksi_pembelian.supplier_id')
                   ->join('user', 'user.id = transaksi_pembelian.user_id')
                   ->where('transaksi_pembelian.id', $id)
                   ->first();
    }

    // Get detail obat dalam transaksi
    public function getDetailObat($transaksiId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('detail_pembelian');
        $builder->select('detail_pembelian.*, obat.nama_obat, obat.bpom, obat.satuan');
        $builder->join('obat', 'obat.id = detail_pembelian.obat_id');
        $builder->where('detail_pembelian.transaksi_pembelian_id', $transaksiId);
        return $builder->get()->getResultArray();
    }

    // Get transaksi berdasarkan rentang tanggal
    public function getTransaksiByDateRange($startDate, $endDate)
    {
        return $this->select('transaksi_pembelian.*, supplier.nama_supplier, supplier.alamat as alamat_supplier, supplier.kota as kota_supplier, supplier.telepon as telepon_supplier, user.nama as nama_user')
                   ->join('supplier', 'supplier.id = transaksi_pembelian.supplier_id')
                   ->join('user', 'user.id = transaksi_pembelian.user_id')
                   ->where('DATE(transaksi_pembelian.tanggal_transaksi) >=', $startDate)
                   ->where('DATE(transaksi_pembelian.tanggal_transaksi) <=', $endDate)
                   ->orderBy('transaksi_pembelian.tanggal_transaksi', 'DESC')
                   ->findAll();
    }

    // Get laporan pembelian
    public function getLaporanPembelian($startDate, $endDate, $periode = 'harian')
    {
        $builder = $this->db->table('transaksi_pembelian tp');
        $builder->select('DATE(tp.tanggal_transaksi) as tanggal, COUNT(*) as jumlah_transaksi, SUM(tp.total) as total_pembelian');
        $builder->where('DATE(tp.tanggal_transaksi) >=', $startDate);
        $builder->where('DATE(tp.tanggal_transaksi) <=', $endDate);
        
        if ($periode == 'bulanan') {
            $builder->select('YEAR(tp.tanggal_transaksi) as tahun, MONTH(tp.tanggal_transaksi) as bulan, COUNT(*) as jumlah_transaksi, SUM(tp.total) as total_pembelian');
            $builder->groupBy('YEAR(tp.tanggal_transaksi), MONTH(tp.tanggal_transaksi)');
        } else {
            $builder->groupBy('DATE(tp.tanggal_transaksi)');
        }
        
        $builder->orderBy('tp.tanggal_transaksi', 'DESC');
        return $builder->get()->getResultArray();
    }

    // Get summary pembelian
    public function getSummaryPembelian($startDate, $endDate)
    {
        $builder = $this->db->table('transaksi_pembelian tp');
        $builder->select('COUNT(*) as total_transaksi, SUM(tp.total) as total_pembelian, AVG(tp.total) as rata_rata');
        $builder->where('DATE(tp.tanggal_transaksi) >=', $startDate);
        $builder->where('DATE(tp.tanggal_transaksi) <=', $endDate);
        return $builder->get()->getRowArray();
    }
}
