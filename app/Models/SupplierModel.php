<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table      = 'supplier';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['nama_supplier', 'alamat', 'kota', 'telepon'];

    // Validasi sesuai dengan struktur database
    protected $validationRules = [
        'nama_supplier' => 'required|min_length[3]|max_length[100]',
        'alamat'        => 'required|min_length[10]',
        'kota'          => 'required|min_length[3]|max_length[50]',
        'telepon'       => 'required|min_length[10]|max_length[15]'
    ];

    // Cek apakah supplier digunakan oleh obat
    public function isUsedByObat($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('obat');
        return $builder->where('supplier_id', $id)->countAllResults() > 0;
    }

    // Get supplier dengan jumlah obat
    public function getSupplierWithObatCount()
    {
        return $this->select('supplier.*, COUNT(obat.id) as jumlah_obat')
                   ->join('obat', 'obat.supplier_id = supplier.id', 'left')
                   ->groupBy('supplier.id')
                   ->findAll();
    }

    // Get supplier performance untuk laporan
    public function getSupplierPerformance($startDate = null, $endDate = null)
    {
        $builder = $this->db->table('supplier s');
        $builder->select('s.*, 
                         COUNT(tp.id) as total_pembelian,
                         SUM(tp.total) as total_nilai_pembelian,
                         COUNT(DISTINCT o.id) as jumlah_obat_disupply');
        $builder->join('transaksi_pembelian tp', 'tp.supplier_id = s.id', 'left');
        $builder->join('obat o', 'o.supplier_id = s.id', 'left');
        
        if ($startDate && $endDate) {
            $builder->where('tp.tanggal >=', $startDate);
            $builder->where('tp.tanggal <=', $endDate);
        }
        
        $builder->groupBy('s.id');
        $builder->orderBy('total_nilai_pembelian', 'DESC');
        
        return $builder->get()->getResultArray();
    }
}
