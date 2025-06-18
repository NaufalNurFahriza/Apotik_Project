<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['nama', 'username', 'password', 'role'];
    
    // DISABLE timestamps karena kolom sudah di-drop
    protected $useTimestamps = false;
    
    protected $validationRules = [
        'nama' => 'required|min_length[3]|max_length[100]',
        'username' => 'required|min_length[3]|max_length[20]|is_unique[user.username,id,{id}]',
        'password' => 'required|min_length[6]',
        'role' => 'required|in_list[pemilik,ttk]'
    ];
    protected $validationMessages = [
        'username' => [
            'is_unique' => 'Username sudah terdaftar'
        ]
    ];
    protected $skipValidation = false;

    // Cek login - support both plain text dan hashed password
    public function cekLogin($username, $password)
    {
        $user = $this->where('username', $username)->first();
        if ($user) {
            // Cek apakah password di database sudah di-hash
            if (password_verify($password, $user['password'])) {
                // Password ter-hash, verifikasi dengan password_verify
                return $user;
            } elseif ($password === $user['password']) {
                // Password plain text (untuk backward compatibility)
                return $user;
            }
        }
        return false;
    }

    // Get users by role
    public function getUsersByRole($role)
    {
        return $this->where('role', $role)->findAll();
    }

    // Check if user is pemilik
    public function isPemilik($user_id)
    {
        $user = $this->find($user_id);
        return $user && $user['role'] === 'pemilik';
    }

    // Get total users count
    public function getTotalUsers()
    {
        return $this->countAllResults();
    }

    // Get users with statistics
    public function getUsersWithStats()
    {
        return $this->select('user.*, 
                             (SELECT COUNT(*) FROM transaksi_penjualan WHERE user_id = user.id) as total_penjualan,
                             (SELECT COUNT(*) FROM transaksi_pembelian WHERE user_id = user.id) as total_pembelian')
                    ->findAll();
    }
}
