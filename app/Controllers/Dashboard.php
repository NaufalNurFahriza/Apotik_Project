<?php

namespace App\Controllers;

use App\Models\ObatModel;
use App\Models\SupplierModel;
use App\Models\AdminModel;
use App\Models\MemberModel;
use App\Models\TransaksiModel;

class Dashboard extends BaseController
{
    protected $obatModel;
    protected $supplierModel;
    protected $adminModel;
    protected $memberModel;
    protected $transaksiModel;

    public function __construct()
    {
        $this->obatModel = new ObatModel();
        $this->supplierModel = new SupplierModel();
        $this->adminModel = new AdminModel();
        $this->memberModel = new MemberModel();
        $this->transaksiModel = new TransaksiModel();
    }

    public function index()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        $data = [
            'title' => 'Dashboard',
            'jumlah_obat' => count($this->obatModel->findAll()),
            'jumlah_supplier' => count($this->supplierModel->findAll()),
            'jumlah_admin' => count($this->adminModel->findAll()),
            'jumlah_member' => count($this->memberModel->findAll()),
            'jumlah_transaksi' => count($this->transaksiModel->findAll()),
        ];

        return view('dashboard/index', $data);
    }
}