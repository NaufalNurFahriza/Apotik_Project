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
    protected $allowedFields = ['tanggal_transaksi', 'admin_id', 'nama_pembeli', 'member_id', 'total', 'poin_didapat'];

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

    // Mendapatkan detail obat yang dibeli dalam transaksi (versi terbaik)
    public function getDetailObat($transaksiId)
    {
        return $this->db->table('detail_transaksi')
            ->select('detail_transaksi.*, obat.nama_obat, obat.bpom, obat.harga')
            ->join('obat', 'obat.id = detail_transaksi.obat_id')
            ->where('detail_transaksi.transaksi_id', $transaksiId)
            ->get()
            ->getResultArray();
    }

    //Mendapatkan riwayat transaksi berdasarkan ID member
    public function getRiwayatByMemberId($memberId)
    {
        return $this->select('transaksi.*, admin.nama_admin as nama_admin')
            ->join('admin', 'admin.id = transaksi.admin_id')
            ->where('member_id', $memberId)
            ->orderBy('tanggal_transaksi', 'DESC')
            ->findAll();
    }
}
