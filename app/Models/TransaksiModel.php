<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table      = 'transaksi';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['tanggal_transaksi', 'admin_id', 'nama_pembeli', 'member_id', 'total', 'poin_didapat', 'poin_digunakan', 'potongan_harga'];

    // Mendapatkan semua transaksi dengan nama admin
    public function getAllTransaksi()
    {
        return $this->select('transaksi.*, admin.nama_admin, member.nama as nama_member')
                    ->join('admin', 'admin.id = transaksi.admin_id')
                    ->join('member', 'member.id = transaksi.member_id', 'left')
                    ->orderBy('transaksi.tanggal_transaksi', 'DESC')
                    ->findAll();
    }

    // Mendapatkan detail transaksi berdasarkan ID
    public function getTransaksiById($id)
    {
        return $this->select('transaksi.*, admin.nama_admin, member.nama as nama_member')
                    ->join('admin', 'admin.id = transaksi.admin_id')
                    ->join('member', 'member.id = transaksi.member_id', 'left')
                    ->where('transaksi.id', $id)
                    ->first();
    }

    // Mendapatkan detail obat yang dibeli dalam transaksi
    public function getDetailObat($transaksiId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('detail_transaksi');
        $builder->select('detail_transaksi.*, obat.nama_obat, obat.bpom');
        $builder->join('obat', 'obat.id = detail_transaksi.obat_id');
        $builder->where('detail_transaksi.transaksi_id', $transaksiId);
        return $builder->get()->getResultArray();
    }

    // Mendapatkan riwayat transaksi berdasarkan member_id
    public function getRiwayatByMemberId($memberId)
    {
        return $this->select('transaksi.*, admin.nama_admin')
                    ->join('admin', 'admin.id = transaksi.admin_id')
                    ->where('transaksi.member_id', $memberId)
                    ->orderBy('transaksi.tanggal_transaksi', 'DESC')
                    ->findAll();
    }

    // Mendapatkan transaksi berdasarkan rentang tanggal
    public function getTransaksiByDateRange($startDate, $endDate)
    {
        return $this->select('transaksi.*, admin.nama_admin, member.nama as nama_member')
                    ->join('admin', 'admin.id = transaksi.admin_id')
                    ->join('member', 'member.id = transaksi.member_id', 'left')
                    ->where('DATE(transaksi.tanggal_transaksi) >=', $startDate)
                    ->where('DATE(transaksi.tanggal_transaksi) <=', $endDate)
                    ->orderBy('transaksi.tanggal_transaksi', 'DESC')
                    ->findAll();
    }
}
