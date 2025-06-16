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
            'title' => 'Login - Apotek Kita Farma',
            'error' => session()->getFlashdata('error')
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

        // Development login - untuk testing
        if (ENVIRONMENT === 'development') {
            if ($username === 'pemilik' && $password === 'pemilik123') {
                session()->set([
                    'id' => 0,
                    'nama' => 'Pemilik Dev',
                    'username' => 'pemilik',
                    'role' => 'pemilik',
                    'logged_in' => true
                ]);
                return redirect()->to(base_url('dashboard'));
            }
            
            if ($username === 'ttk' && $password === 'ttk123') {
                session()->set([
                    'id' => 1,
                    'nama' => 'TTK Dev',
                    'username' => 'ttk',
                    'role' => 'ttk',
                    'logged_in' => true
                ]);
                return redirect()->to(base_url('dashboard'));
            }
        }

        // Cek login dari database
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
            return redirect()->to(base_url('dashboard'));
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
            'success' => session()->getFlashdata('success'),
            'old' => session()->getFlashdata('old')
        ];

        return view('auth/register', $data);
    }

    public function doRegister()
    {
        // Validasi input
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'username' => 'required|min_length[3]|max_length[20]|is_unique[user.username]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'registration_code' => 'required|exact[APOTEK2025]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to(base_url('auth/register'))
                ->withInput()
                ->with('validation_errors', $this->validator->getErrors());
        }

        // Simpan data user baru dengan role default 'ttk'
        $data = [
            'nama' => esc($this->request->getPost('nama')),
            'username' => esc($this->request->getPost('username')),
            'password' => $this->request->getPost('password'),
            'role' => 'ttk' // Default role ttk untuk registrasi baru
        ];

        $this->userModel->insert($data);

        session()->setFlashdata('success', 'Registrasi berhasil. Silakan login dengan akun baru Anda.');
        return redirect()->to(base_url('auth'));
    }
}
