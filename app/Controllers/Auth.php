<?php

namespace App\Controllers;

use App\Models\AdminModel;

class Auth extends BaseController
{
    protected $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
    }

    public function index()
    {
        // Jika sudah login, redirect ke dashboard
        if (session()->get('logged_in')) {
            return redirect()->to(base_url('dashboard'));
        }
        
        return view('auth/login');
    }

    public function login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $admin = $this->adminModel->cekLogin($username, $password);

        if ($admin) {
            // Set session
            $data = [
                'id' => $admin['id'],
                'nama_admin' => $admin['nama_admin'],
                'username' => $admin['username'],
                'logged_in' => TRUE
            ];
            session()->set($data);
            return redirect()->to(base_url('dashboard'));
        } else {
            session()->setFlashdata('error', 'Username atau Password salah');
            return redirect()->to(base_url('auth'));
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('auth'));
    }
}