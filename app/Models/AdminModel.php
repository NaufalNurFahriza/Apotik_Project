<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table      = 'admin';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['nama_admin', 'username', 'password'];

    // Validasi
    protected $validationRules = [
        'nama_admin' => 'required',
        'username'   => 'required|is_unique[admin.username,id,{id}]',
        'password'   => 'required|min_length[6]',
    ];

    // Cek login
    public function cekLogin($username, $password)
    {
        $admin = $this->where('username', $username)->first();
        if ($admin) {
            if (password_verify($password, $admin['password'])) {
                return $admin;
            }
        }
        return false;
    }
}