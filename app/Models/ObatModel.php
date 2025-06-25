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
    // TAMBAHKAN 'stok' ke allowedFields
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
        'harga_jual' => 'required|numeric'
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

    // TAMBAHKAN method updateStok yang hilang
    public function updateStok($id, $stok_baru)
    {
        $obat = $this->find($id);
        if ($obat) {
            return $this->update($id, ['stok' => max(0, $stok_baru)]);
        }
        return false;
    }

    // Method alternatif untuk update stok dengan validasi lebih ketat
    public function setStok($id, $stok_baru)
    {
        // Validasi input
        if (!is_numeric($stok_baru) || $stok_baru < 0) {
            return false;
        }

        $obat = $this->find($id);
        if (!$obat) {
            return false;
        }

        try {
            return $this->update($id, ['stok' => (int)$stok_baru]);
        } catch (\Exception $e) {
            log_message('error', 'Error updating stock for obat ID ' . $id . ': ' . $e->getMessage());
            return false;
        }
    }

    // Method untuk mendapatkan stok obat
    public function getStok($id)
    {
        $obat = $this->find($id);
        return $obat ? $obat['stok'] : 0;
    }

    // Method untuk cek apakah stok mencukupi
    public function cekStokCukup($id, $qty_dibutuhkan)
    {
        $obat = $this->find($id);
        return $obat && $obat['stok'] >= $qty_dibutuhkan;
    }
}
