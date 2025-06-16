<?php

namespace App\Models;

use CodeIgniter\Model;

class MemberModel extends Model
{
    protected $table      = 'member';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['nama', 'no_hp', 'poin'];

    // Validasi
    protected $validationRules = [
        'nama'  => 'required',
        'no_hp' => 'required',
    ];

    // Update poin member
    public function updatePoin($id, $poinBaru)
    {
        $member = $this->find($id);
        if ($member) {
            $totalPoin = $member['poin'] + $poinBaru;
            return $this->update($id, ['poin' => $totalPoin]);
        }
        return false;
    }

    // Mendapatkan riwayat transaksi member
    public function getRiwayatTransaksi($memberId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('transaksi_penjualan');
        $builder->select('transaksi_penjualan.*, user.nama');
        $builder->join('user', 'user.id = transaksi_penjualan.user_id');
        $builder->where('transaksi_penjualan.member_id', $memberId);
        $builder->orderBy('transaksi_penjualan.tanggal_transaksi', 'DESC');
        return $builder->get()->getResultArray();
    }
}
