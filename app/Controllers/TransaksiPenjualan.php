<?php

namespace App\Controllers;

use App\Models\TransaksiPenjualanModel;
use App\Models\DetailPenjualanModel;
use App\Models\ObatModel;
use App\Models\MemberModel;
use App\Models\UserModel;

class TransaksiPenjualan extends BaseController
{
    protected $transaksiPenjualanModel;
    protected $detailPenjualanModel;
    protected $obatModel;
    protected $memberModel;
    protected $userModel;

    public function __construct()
    {
        $this->transaksiPenjualanModel = new TransaksiPenjualanModel();
        $this->detailPenjualanModel = new DetailPenjualanModel();
        $this->obatModel = new ObatModel();
        $this->memberModel = new MemberModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Ambil parameter filter tanggal
        $start_date = $this->request->getGet('start_date') ?? date('Y-m-01');
        $end_date = $this->request->getGet('end_date') ?? date('Y-m-d');

        // Ambil data transaksi penjualan berdasarkan filter tanggal
        $transaksi = $this->transaksiPenjualanModel->getTransaksiByDateRange($start_date, $end_date);

        $data = [
            'title' => 'Data Transaksi Penjualan',
            'transaksi' => $transaksi,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];

        return view('transaksi_penjualan/index', $data);
    }

    public function tambah()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        $data = [
            'title' => 'Tambah Transaksi Penjualan',
            'obat' => $this->obatModel->where('stok >', 0)->findAll(),
            'member' => $this->memberModel->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('transaksi_penjualan/tambah', $data);
    }

    public function getObatById()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $id = $this->request->getPost('id');
        $obat = $this->obatModel->find($id);
        
        if (!$obat) {
            return $this->response->setJSON(['error' => 'Obat tidak ditemukan']);
        }

        return $this->response->setJSON($obat);
    }

    public function simpan()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama_pembeli' => 'required|min_length[3]',
            'obat_id.*' => 'required|numeric',
            'qty.*' => 'required|numeric|greater_than[0]',
            'total' => 'required|numeric|greater_than[0]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->to(base_url('transaksi-penjualan/tambah'))
                           ->withInput()
                           ->with('validation', $validation);
        }

        // Ambil data dari form
        $nama_pembeli = $this->request->getPost('nama_pembeli');
        $member_id = $this->request->getPost('member_id');
        $obat_id = $this->request->getPost('obat_id');
        $harga = $this->request->getPost('harga');
        $qty = $this->request->getPost('qty');
        $poin_digunakan = $this->request->getPost('poin_digunakan') ?? 0;
        $potongan_harga = $this->request->getPost('potongan_harga') ?? 0;
        $total = $this->request->getPost('total');

        // Validasi stok
        for ($i = 0; $i < count($obat_id); $i++) {
            $obat = $this->obatModel->find($obat_id[$i]);
            if ($obat['stok'] < $qty[$i]) {
                session()->setFlashdata('error', "Stok obat {$obat['nama_obat']} tidak mencukupi. Stok tersedia: {$obat['stok']}");
                return redirect()->to(base_url('transaksi-penjualan/tambah'))->withInput();
            }
        }

        // Pastikan user_id (TTK) valid
        $user_id = session()->get('id');
        if (!$user_id) {
            $firstUser = $this->userModel->first();
            if ($firstUser) {
                $user_id = $firstUser['id'];
            } else {
                session()->setFlashdata('error', 'Tidak ada TTK yang tersedia di database.');
                return redirect()->to(base_url('transaksi-penjualan/tambah'));
            }
        }

        // Hitung poin yang didapat (1 poin untuk setiap Rp 50.000)
        $poin_didapat = floor($total / 50000);

        // Simpan data transaksi penjualan
        $data_transaksi = [
            'tanggal_transaksi' => date('Y-m-d H:i:s'),
            'user_id' => $user_id,
            'nama_pembeli' => $nama_pembeli,
            'member_id' => $member_id ? $member_id : null,
            'total' => $total,
            'poin_didapat' => $poin_didapat,
            'poin_digunakan' => $poin_digunakan,
            'potongan_harga' => $potongan_harga
        ];

        $this->transaksiPenjualanModel->insert($data_transaksi);
        $transaksi_id = $this->transaksiPenjualanModel->getInsertID();

        // Simpan detail transaksi dan update stok obat
        for ($i = 0; $i < count($obat_id); $i++) {
            // Ambil data obat
            $obat = $this->obatModel->find($obat_id[$i]);

            // Simpan detail transaksi
            $data_detail = [
                'transaksi_id' => $transaksi_id,
                'obat_id' => $obat_id[$i],
                'qty' => $qty[$i],
                'harga_saat_ini' => $harga[$i]
            ];
            $this->detailPenjualanModel->insert($data_detail);

            // Update stok obat
            $stok_baru = $obat['stok'] - $qty[$i];
            $this->obatModel->update($obat_id[$i], ['stok' => $stok_baru]);
        }

        // Update poin member jika transaksi menggunakan member
        if ($member_id) {
            $member = $this->memberModel->find($member_id);
            $poin_akhir = $member['poin'] - $poin_digunakan + $poin_didapat;
            $this->memberModel->update($member_id, ['poin' => $poin_akhir]);
        }

        session()->setFlashdata('pesan', 'Transaksi penjualan berhasil ditambahkan.');
        return redirect()->to(base_url('transaksi-penjualan/struk/' . $transaksi_id));
    }

    public function detail($id)
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        $data = [
            'title' => 'Detail Transaksi Penjualan',
            'transaksi' => $this->transaksiPenjualanModel->getTransaksiById($id),
            'detail' => $this->transaksiPenjualanModel->getDetailObat($id)
        ];

        return view('transaksi_penjualan/detail', $data);
    }

    public function struk($id)
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        $data = [
            'title' => 'Struk Transaksi Penjualan',
            'transaksi' => $this->transaksiPenjualanModel->getTransaksiById($id),
            'detail' => $this->transaksiPenjualanModel->getDetailObat($id)
        ];

        return view('transaksi_penjualan/struk', $data);
    }

    public function faktur($id)
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        $data = [
            'title' => 'Faktur Penjualan',
            'transaksi' => $this->transaksiPenjualanModel->getTransaksiById($id),
            'detail' => $this->transaksiPenjualanModel->getDetailObat($id)
        ];

        return view('transaksi_penjualan/faktur', $data);
    }

    public function hapus($id)
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Cek role - hanya pemilik yang bisa hapus transaksi
        if (session()->get('role') !== 'pemilik') {
            session()->setFlashdata('error', 'Anda tidak memiliki akses untuk menghapus transaksi');
            return redirect()->to(base_url('transaksi-penjualan'));
        }

        // Ambil detail transaksi
        $detail = $this->detailPenjualanModel->where('transaksi_id', $id)->findAll();

        // Kembalikan stok obat
        foreach ($detail as $d) {
            $obat = $this->obatModel->find($d['obat_id']);
            $stok_baru = $obat['stok'] + $d['qty'];
            $this->obatModel->update($d['obat_id'], ['stok' => $stok_baru]);
        }

        // Ambil data transaksi untuk mengurangi poin member jika ada
        $transaksi = $this->transaksiPenjualanModel->find($id);
        if ($transaksi['member_id'] && $transaksi['poin_didapat'] > 0) {
            $member = $this->memberModel->find($transaksi['member_id']);
            $poin_baru = max(0, $member['poin'] - $transaksi['poin_didapat'] + $transaksi['poin_digunakan']);
            $this->memberModel->update($transaksi['member_id'], ['poin' => $poin_baru]);
        }

        // Hapus detail transaksi
        $this->detailPenjualanModel->where('transaksi_id', $id)->delete();

        // Hapus transaksi
        $this->transaksiPenjualanModel->delete($id);

        session()->setFlashdata('pesan', 'Transaksi penjualan berhasil dihapus.');
        return redirect()->to(base_url('transaksi-penjualan'));
    }
}
