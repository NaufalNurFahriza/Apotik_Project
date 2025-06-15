<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'user';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['nama', 'username', 'password', 'role'];

    // Validasi
    protected $validationRules = [
        'nama' => 'required',
        'username'   => 'required|is_unique[user.username,id,{id}]',
        'password'   => 'required|min_length[6]',
        'role' => 'required|in_list[pemilik,ttk]'
    ];

    // Cek login
    public function cekLogin($username, $password)
    {
        $user = $this->where('username', $username)->first();
        if ($user) {
            if ($password === $user['password']) {
                return $user;
            }
        }
        return false;
    }
}
