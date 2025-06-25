<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiPenjualanModel extends Model
{
    protected $table      = 'transaksi_penjualan';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['no_faktur_jual', 'tanggal_transaksi', 'user_id', 'nama_pembeli', 'member_id', 'total', 'poin_didapat', 'poin_digunakan', 'potongan_harga'];

    // Mendapatkan semua transaksi dengan nama user
    public function getAllTransaksi()
    {
        return $this->select('transaksi_penjualan.*, user.nama as nama_user, member.nama as nama_member')
                    ->join('user', 'user.id = transaksi_penjualan.user_id')
                    ->join('member', 'member.id = transaksi_penjualan.member_id', 'left')
                    ->orderBy('transaksi_penjualan.tanggal_transaksi', 'DESC')
                    ->findAll();
    }

    // Mendapatkan detail transaksi berdasarkan ID
    public function getTransaksiById($id)
    {
        return $this->select('transaksi_penjualan.*, user.nama as nama_user, member.nama as nama_member')
                    ->join('user', 'user.id = transaksi_penjualan.user_id')
                    ->join('member', 'member.id = transaksi_penjualan.member_id', 'left')
                    ->where('transaksi_penjualan.id', $id)
                    ->first();
    }

    // Mendapatkan detail obat yang dibeli dalam transaksi
    public function getDetailObat($transaksiId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('detail_penjualan');
        $builder->select('detail_penjualan.*, obat.nama_obat, obat.bpom, obat.satuan');
        $builder->join('obat', 'obat.id = detail_penjualan.obat_id');
        $builder->where('detail_penjualan.transaksi_id', $transaksiId);
        return $builder->get()->getResultArray();
    }

    // Mendapatkan riwayat transaksi berdasarkan member_id
    public function getRiwayatByMemberId($memberId)
    {
        return $this->select('transaksi_penjualan.*, user.nama as nama_user')
                    ->join('user', 'user.id = transaksi_penjualan.user_id')
                    ->where('transaksi_penjualan.member_id', $memberId)
                    ->orderBy('transaksi_penjualan.tanggal_transaksi', 'DESC')
                    ->findAll();
    }

    // Mendapatkan transaksi berdasarkan rentang tanggal
    public function getTransaksiByDateRange($startDate, $endDate)
    {
        return $this->select('transaksi_penjualan.*, user.nama as nama_user, member.nama as nama_member')
                    ->join('user', 'user.id = transaksi_penjualan.user_id')
                    ->join('member', 'member.id = transaksi_penjualan.member_id', 'left')
                    ->where('DATE(transaksi_penjualan.tanggal_transaksi) >=', $startDate)
                    ->where('DATE(transaksi_penjualan.tanggal_transaksi) <=', $endDate)
                    ->orderBy('transaksi_penjualan.tanggal_transaksi', 'DESC')
                    ->findAll();
    }
}
