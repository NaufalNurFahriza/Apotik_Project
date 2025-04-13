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
    protected $allowedFields = ['kode_obat', 'nama_obat', 'harga', 'produsen', 'supplier_id', 'stok', 'gambar'];

    // Validasi
    protected $validationRules = [
        'kode_obat' => 'required|is_unique[obat.kode_obat,id,{id}]',
        'nama_obat' => 'required',
        'harga'     => 'required|numeric',
        'produsen'  => 'required',
        'supplier_id' => 'required|numeric',
        'stok'      => 'required|numeric',
    ];

    // Mendapatkan data obat dengan nama supplier
    public function getObatWithSupplier()
    {
        return $this->select('obat.*, supplier.nama_supplier')
                    ->join('supplier', 'supplier.id = obat.supplier_id')
                    ->findAll();
    }

    // Mendapatkan satu obat dengan nama supplier
    public function getObatById($id)
    {
        return $this->select('obat.*, supplier.nama_supplier')
                    ->join('supplier', 'supplier.id = obat.supplier_id')
                    ->where('obat.id', $id)
                    ->first();
    }

    // Update stok obat
    public function updateStok($id, $jumlah)
    {
        $obat = $this->find($id);
        if ($obat) {
            $stokBaru = $obat['stok'] + $jumlah;
            return $this->update($id, ['stok' => $stokBaru]);
        }
        return false;
    }
}