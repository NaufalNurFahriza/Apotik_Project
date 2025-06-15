<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Jika sudah login, redirect ke dashboard
        if (session()->get('logged_in')) {
            return redirect()->to(base_url('dashboard'));
        }

        $data = [
            'title' => 'Login - Apotek Kita Farma'
        ];

        return view('auth/login', $data);
    }

    public function login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Validasi input
        if (empty($username) || empty($password)) {
            session()->setFlashdata('error', 'Username dan password harus diisi');
            return redirect()->to(base_url('auth'));
        }

        // Cek login
        $user = $this->userModel->cekLogin($username, $password);
        
        if ($user) {
            // Set session
            $sessionData = [
                'id' => $user['id'],
                'nama' => $user['nama'],
                'username' => $user['username'],
                'role' => $user['role'],
                'logged_in' => true
            ];
            
            session()->set($sessionData);
            
            // Redirect berdasarkan role
            if ($user['role'] == 'pemilik') {
                return redirect()->to(base_url('dashboard'));
            } else {
                return redirect()->to(base_url('dashboard'));
            }
        } else {
            session()->setFlashdata('error', 'Username atau password salah');
            return redirect()->to(base_url('auth'));
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('auth'));
    }

    public function register()
    {
        // Hanya pemilik yang bisa mengakses halaman register
        if (!session()->get('logged_in') || session()->get('role') != 'pemilik') {
            return redirect()->to(base_url('auth'));
        }

        $data = [
            'title' => 'Registrasi TTK Baru',
            'validation' => \Config\Services::validation()
        ];

        return view('auth/register', $data);
    }

    public function doRegister()
    {
        // Hanya pemilik yang bisa registrasi user baru
        if (!session()->get('logged_in') || session()->get('role') != 'pemilik') {
            return redirect()->to(base_url('auth'));
        }

        // Validasi input
        if (!$this->validate($this->userModel->validationRules)) {
            return redirect()->to(base_url('auth/register'))->withInput()->with('validation', $this->validator);
        }

        // Simpan data
        $data = [
            'nama' => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role')
        ];

        $this->userModel->insert($data);
        session()->setFlashdata('pesan', 'TTK baru berhasil didaftarkan');
        return redirect()->to(base_url('user'));
    }
}