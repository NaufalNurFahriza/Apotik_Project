<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailTransaksiModel extends Model
{
    protected $table      = 'detail_transaksi';
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
}