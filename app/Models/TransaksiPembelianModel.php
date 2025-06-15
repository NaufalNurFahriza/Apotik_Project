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
    protected $allowedFields = ['user_id', 'supplier_id', 'tanggal', 'nomor_faktur', 'total'];

    // Validasi
    protected $validationRules = [
        'user_id' => 'required|numeric',
        'supplier_id' => 'required|numeric',
        'tanggal' => 'required|valid_date',
        'nomor_faktur' => 'required|is_unique[transaksi_pembelian.nomor_faktur,id,{id}]',
        'total' => 'required|numeric'
    ];

    // Get pembelian dengan supplier dan user
    public function getPembelianWithDetails()
    {
        return $this->select('transaksi_pembelian.*, supplier.nama_supplier, user.nama as nama_user')
                   ->join('supplier', 'supplier.id = transaksi_pembelian.supplier_id')
                   ->join('user', 'user.id = transaksi_pembelian.user_id')
                   ->orderBy('tanggal', 'DESC')
                   ->findAll();
    }
}