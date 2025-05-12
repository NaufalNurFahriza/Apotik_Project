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
        $builder = $db->table('transaksi');
        $builder->select('transaksi.*, admin.nama_admin');
        $builder->join('admin', 'admin.id = transaksi.admin_id');
        $builder->where('transaksi.member_id', $memberId);
        $builder->orderBy('transaksi.tanggal_transaksi', 'DESC');
        return $builder->get()->getResultArray();
    }
}
