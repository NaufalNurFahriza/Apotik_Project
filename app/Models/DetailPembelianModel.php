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

    // Validasi - HAPUS subtotal yang tidak ada
    protected $validationRules = [
        'pembelian_id' => 'required|numeric',
        'obat_id' => 'required|numeric',
        'qty' => 'required|numeric',
        'harga_beli' => 'required|numeric'
        // HAPUS: 'subtotal' => 'required|numeric'
    ];

    protected $validationMessages = [
        'pembelian_id' => [
            'required' => 'ID Pembelian harus diisi',
            'numeric' => 'ID Pembelian harus berupa angka'
        ],
        'obat_id' => [
            'required' => 'ID Obat harus diisi',
            'numeric' => 'ID Obat harus berupa angka'
        ],
        'qty' => [
            'required' => 'Quantity harus diisi',
            'numeric' => 'Quantity harus berupa angka'
        ],
        'harga_beli' => [
            'required' => 'Harga beli harus diisi',
            'numeric' => 'Harga beli harus berupa angka'
        ]
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
