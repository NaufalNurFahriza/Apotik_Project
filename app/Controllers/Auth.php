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
    
    // Method untuk menampilkan halaman register
    public function register()
    {
        // Jika sudah login, redirect ke dashboard
        if (session()->get('logged_in')) {
            return redirect()->to(base_url('dashboard'));
        }
        
        $data = [
            'validation_errors' => session()->getFlashdata('validation_errors')
        ];
        
        return view('auth/register', $data);
    }
    
    // Method untuk memproses registrasi
    public function doRegister()
    {
        // Validasi input
        $rules = [
            'nama_admin' => 'required',
            'username' => 'required|is_unique[admin.username]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'registration_code' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            // Langsung kembalikan dengan validation object
            return redirect()->to(base_url('auth/register'))
                ->withInput()
                ->with('error', 'Validasi gagal. Silakan periksa form Anda.')
                ->with('validation_errors', $this->validator->getErrors());
        }
        
        // Verifikasi kode registrasi (ganti 'APOTEK2025' dengan kode yang Anda inginkan)
        $registrationCode = $this->request->getPost('registration_code');
        if ($registrationCode !== 'APOTEK2025') {
            return redirect()->to(base_url('auth/register'))
                ->withInput()
                ->with('error', 'Kode registrasi tidak valid');
        }
        
        // Simpan data admin baru
        $data = [
            'nama_admin' => $this->request->getPost('nama_admin'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
        ];
        
        $this->adminModel->insert($data);
        
        session()->setFlashdata('success', 'Registrasi berhasil. Silakan login dengan akun baru Anda.');
        return redirect()->to(base_url('auth'));
    }
}