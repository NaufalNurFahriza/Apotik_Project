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

    // Validasi
    protected $validationRules = [
        'nama_supplier' => 'required',
        'alamat'        => 'required',
        'kota'          => 'required',
        'telepon'       => 'required',
    ];
}
