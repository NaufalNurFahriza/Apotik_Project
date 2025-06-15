<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPenjualanModel extends Model
{
    protected $table      = 'detail_penjualan';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['transaksi_id', 'obat_id', 'qty', 'harga_saat_ini'];

    // Menambahkan detail transaksi
    public function tambahDetail($data)
    {
        return $this->insert($data);
    }

    // Get detail dengan obat
    public function getDetailWithObat($transaksi_id)
    {
        return $this->select('detail_penjualan.*, obat.nama_obat, obat.satuan')
                   ->join('obat', 'obat.id = detail_penjualan.obat_id')
                   ->where('transaksi_id', $transaksi_id)
                   ->findAll();
    }
}
