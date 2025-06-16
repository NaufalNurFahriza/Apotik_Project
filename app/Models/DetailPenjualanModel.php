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

    // Validasi
    protected $validationRules = [
        'transaksi_id' => 'required|numeric',
        'obat_id' => 'required|numeric',
        'qty' => 'required|numeric|greater_than[0]',
        'harga_saat_ini' => 'required|numeric|greater_than[0]'
    ];

    protected $validationMessages = [
        'transaksi_id' => [
            'required' => 'ID Transaksi harus diisi',
            'numeric' => 'ID Transaksi harus berupa angka'
        ],
        'obat_id' => [
            'required' => 'ID Obat harus diisi',
            'numeric' => 'ID Obat harus berupa angka'
        ],
        'qty' => [
            'required' => 'Jumlah harus diisi',
            'numeric' => 'Jumlah harus berupa angka',
            'greater_than' => 'Jumlah harus lebih dari 0'
        ],
        'harga_saat_ini' => [
            'required' => 'Harga harus diisi',
            'numeric' => 'Harga harus berupa angka',
            'greater_than' => 'Harga harus lebih dari 0'
        ]
    ];

    // Menambahkan detail transaksi
    public function tambahDetail($data)
    {
        return $this->insert($data);
    }

    // Get detail dengan obat
    public function getDetailWithObat($transaksi_id)
    {
        return $this->select('detail_penjualan.*, obat.nama_obat, obat.satuan, obat.bpom, obat.kode_obat')
                   ->join('obat', 'obat.id = detail_penjualan.obat_id')
                   ->where('detail_penjualan.transaksi_id', $transaksi_id)
                   ->findAll();
    }

    // Get detail berdasarkan ID transaksi dengan informasi lengkap
    public function getDetailByTransaksiId($transaksi_id)
    {
        return $this->select('
                detail_penjualan.*,
                obat.nama_obat,
                obat.satuan,
                obat.bpom,
                obat.kode_obat,
                obat.kategori,
                (detail_penjualan.qty * detail_penjualan.harga_saat_ini) as subtotal
            ')
            ->join('obat', 'obat.id = detail_penjualan.obat_id')
            ->where('detail_penjualan.transaksi_id', $transaksi_id)
            ->orderBy('detail_penjualan.id', 'ASC')
            ->findAll();
    }

    // Get total item dalam transaksi
    public function getTotalItemByTransaksi($transaksi_id)
    {
        return $this->selectSum('qty')
                   ->where('transaksi_id', $transaksi_id)
                   ->get()
                   ->getRow()
                   ->qty ?? 0;
    }

    // Get total nilai detail transaksi
    public function getTotalNilaiByTransaksi($transaksi_id)
    {
        return $this->select('SUM(qty * harga_saat_ini) as total')
                   ->where('transaksi_id', $transaksi_id)
                   ->get()
                   ->getRow()
                   ->total ?? 0;
    }

    // Hapus detail berdasarkan transaksi ID
    public function deleteByTransaksiId($transaksi_id)
    {
        return $this->where('transaksi_id', $transaksi_id)->delete();
    }

    // Get obat terlaris berdasarkan detail penjualan
    public function getObatTerlaris($limit = 10, $start_date = null, $end_date = null)
    {
        $builder = $this->select('
                obat.id,
                obat.nama_obat,
                obat.satuan,
                SUM(detail_penjualan.qty) as total_terjual,
                SUM(detail_penjualan.qty * detail_penjualan.harga_saat_ini) as total_nilai
            ')
            ->join('obat', 'obat.id = detail_penjualan.obat_id')
            ->join('transaksi_penjualan', 'transaksi_penjualan.id = detail_penjualan.transaksi_id')
            ->groupBy('obat.id, obat.nama_obat, obat.satuan')
            ->orderBy('total_terjual', 'DESC');

        if ($start_date && $end_date) {
            $builder->where('DATE(transaksi_penjualan.tanggal_transaksi) >=', $start_date)
                   ->where('DATE(transaksi_penjualan.tanggal_transaksi) <=', $end_date);
        }

        return $builder->limit($limit)->findAll();
    }

    // Validasi stok sebelum insert/update
    public function validateStok($obat_id, $qty, $exclude_detail_id = null)
    {
        $obatModel = new \App\Models\ObatModel();
        $obat = $obatModel->find($obat_id);
        
        if (!$obat) {
            return ['status' => false, 'message' => 'Obat tidak ditemukan'];
        }

        // Hitung qty yang sudah digunakan (exclude current detail jika edit)
        $builder = $this->where('obat_id', $obat_id);
        if ($exclude_detail_id) {
            $builder->where('id !=', $exclude_detail_id);
        }
        
        $used_qty = $builder->selectSum('qty')->get()->getRow()->qty ?? 0;
        $available_stock = $obat['stok'] - $used_qty;

        if ($qty > $available_stock) {
            return [
                'status' => false, 
                'message' => "Stok tidak mencukupi. Tersedia: {$available_stock}, Diminta: {$qty}"
            ];
        }

        return ['status' => true, 'message' => 'Stok mencukupi'];
    }
}
