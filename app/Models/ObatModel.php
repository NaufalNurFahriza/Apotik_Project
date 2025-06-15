<?php

namespace App\Models;

use CodeIgniter\Model;

class ObatModel extends Model
{
    protected $table      = 'obat';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['bpom', 'nama_obat', 'produsen', 'supplier_id', 'kategori', 'satuan', 'harga_beli', 'harga_jual', 'stok'];

    // Validasi
    protected $validationRules = [
        'bpom' => 'required|is_unique[obat.bpom,id,{id}]',
        'nama_obat' => 'required',
        'produsen' => 'required',
        'supplier_id' => 'required|numeric',
        'kategori' => 'required|in_list[resep,non-resep]',
        'satuan' => 'required',
        'harga_beli' => 'required|numeric',
        'harga_jual' => 'required|numeric',
        'stok' => 'required|numeric'
    ];

    // Get obat dengan supplier
    public function getObatWithSupplier()
    {
        return $this->select('obat.*, supplier.nama_supplier')
                   ->join('supplier', 'supplier.id = obat.supplier_id')
                   ->findAll();
    }

    // Kurangi stok
    public function kurangiStok($id, $qty)
    {
        $obat = $this->find($id);
        if ($obat && $obat['stok'] >= $qty) {
            return $this->update($id, ['stok' => $obat['stok'] - $qty]);
        }
        return false;
    }

    // Tambah stok
    public function tambahStok($id, $qty)
    {
        $obat = $this->find($id);
        if ($obat) {
            return $this->update($id, ['stok' => $obat['stok'] + $qty]);
        }
        return false;
    }
}