<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPembelianModel extends Model
{
    protected $table      = 'detail_pembelian';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['pembelian_id', 'obat_id', 'qty', 'harga_beli', 'nomor_batch', 'expired_date', 'satuan'];

    // Validasi
    protected $validationRules = [
        'pembelian_id' => 'required|numeric',
        'obat_id' => 'required|numeric',
        'qty' => 'required|numeric',
        'harga_beli' => 'required|numeric',
        'subtotal' => 'required|numeric'
    ];

    // Get detail dengan obat
    public function getDetailWithObat($pembelian_id)
    {
        return $this->select('detail_pembelian.*, obat.nama_obat, obat.bpom, obat.satuan')
                   ->join('obat', 'obat.id = detail_pembelian.obat_id')
                   ->where('pembelian_id', $pembelian_id)
                   ->findAll();
    }
}
