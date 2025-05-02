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

        return view('auth/login', [
            'validation_errors' => session()->getFlashdata('validation_errors'),
            'error' => session()->getFlashdata('error')
        ]);
    }

    public function login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Hanya untuk development! - Letakkan di awal method login
        if (ENVIRONMENT === 'development') {
            if ($username === 'pemilik' && $password === 'pemilik123') {
                session()->set([
                    'id' => 0,
                    'nama_admin' => 'Pemilik Dev',
                    'username' => 'pemilik',
                    'role' => 'pemilik',
                    'logged_in' => true
                ]);
                return redirect()->to(base_url('dashboard'));
            }
            
            if ($username === 'admin' && $password === 'admin123') {
                session()->set([
                    'id' => 1,
                    'nama_admin' => 'Admin Dev',
                    'username' => 'admin',
                    'role' => 'admin',
                    'logged_in' => true
                ]);
                return redirect()->to(base_url('dashboard'));
            }
        }

        // Cek login untuk production
        $admin = $this->adminModel->where('username', $username)->first();

        if ($admin) {
            // Verifikasi password
            if (password_verify($password, $admin['password'])) {
                // Set session
                $data = [
                    'id' => $admin['id'],
                    'nama_admin' => $admin['nama_admin'],
                    'username' => $admin['username'],
                    'role' => $admin['role'],
                    'logged_in' => TRUE
                ];
                session()->set($data);
                
                return redirect()->to(base_url('dashboard'));
            } else {
                session()->setFlashdata('error', 'Username atau password salah');
                return redirect()->to(base_url('auth'));
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
        // Jika sudah login, redirect ke dashboard
        if (session()->get('logged_in')) {
            return redirect()->to(base_url('dashboard'));
        }

        $data = [
            'validation_errors' => session()->getFlashdata('validation_errors'),
            'error' => session()->getFlashdata('error'),
            'old' => session()->getFlashdata('old')
        ];

        return view('auth/register', $data);
    }

    public function doRegister()
    {
        // Validasi input
        $rules = [
            'nama_admin' => 'required|min_length[3]|max_length[100]',
            'username' => 'required|min_length[3]|max_length[20]|is_unique[admin.username]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'registration_code' => 'required|exact[APOTEK2025]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to(base_url('auth/register'))
                ->withInput()
                ->with('validation_errors', $this->validator->getErrors());
        }

        // Simpan data admin baru dengan role default 'admin'
        $data = [
            'nama_admin' => esc($this->request->getPost('nama_admin')),
            'username' => esc($this->request->getPost('username')),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => 'admin', // Default role admin untuk registrasi baru
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->adminModel->insert($data);

        session()->setFlashdata('success', 'Registrasi berhasil. Silakan login dengan akun baru Anda.');
        return redirect()->to(base_url('auth'));
    }
}