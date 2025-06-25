<?php

namespace App\Controllers;

use App\Models\TransaksiPembelianModel;
use App\Models\DetailPembelianModel;
use App\Models\ObatModel;
use App\Models\SupplierModel;
use App\Models\UserModel;

class TransaksiPembelian extends BaseController
{
    protected $transaksiPembelianModel;
    protected $detailPembelianModel;
    protected $obatModel;
    protected $supplierModel;
    protected $userModel;

    public function __construct()
    {
        $this->transaksiPembelianModel = new TransaksiPembelianModel();
        $this->detailPembelianModel = new DetailPembelianModel();
        $this->obatModel = new ObatModel();
        $this->supplierModel = new SupplierModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Cek role - pemilik dan TTK yang bisa akses
        if (!in_array(session()->get('role'), ['pemilik', 'ttk'])) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini');
            return redirect()->to(base_url('dashboard'));
        }

        // Ambil parameter filter tanggal
        $start_date = $this->request->getGet('start_date') ?? date('Y-m-01');
        $end_date = $this->request->getGet('end_date') ?? date('Y-m-d');

        // Ambil data transaksi pembelian berdasarkan filter tanggal
        $transaksi = $this->transaksiPembelianModel->getTransaksiByDateRange($start_date, $end_date);

        $data = [
            'title' => 'Data Pembelian',
            'transaksi' => $transaksi,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];

        return view('transaksi_pembelian/index', $data);
    }

    public function tambah()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Cek role - pemilik dan TTK yang bisa akses
        if (!in_array(session()->get('role'), ['pemilik', 'ttk'])) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini');
            return redirect()->to(base_url('dashboard'));
        }

        $data = [
            'title' => 'Beli dari Supplier',
            'obat' => $this->obatModel->findAll(),
            'supplier' => $this->supplierModel->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('transaksi_pembelian/tambah', $data);
    }

    public function getObatBySupplier()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $supplier_id = $this->request->getPost('supplier_id');
        
        if (!$supplier_id) {
            return $this->response->setJSON(['error' => 'Supplier ID required']);
        }

        $obat = $this->obatModel->where('supplier_id', $supplier_id)->findAll();
        
        return $this->response->setJSON($obat);
    }

    public function simpan()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Cek role - pemilik dan TTK yang bisa akses
        if (!in_array(session()->get('role'), ['pemilik', 'ttk'])) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini');
            return redirect()->to(base_url('dashboard'));
        }

        // Debug: Log semua data yang diterima
        log_message('info', 'POST data received: ' . json_encode($this->request->getPost()));

        // Ambil data dari form
        $supplier_id = $this->request->getPost('supplier_id');
        $obat_id = $this->request->getPost('obat_id');
        $harga_beli = $this->request->getPost('harga_beli');
        $qty = $this->request->getPost('qty');
        $nomor_batch = $this->request->getPost('nomor_batch');
        $expired_date = $this->request->getPost('expired_date');
        $total = $this->request->getPost('total');
        $no_faktur_beli = $this->request->getPost('no_faktur_beli');

        // Validasi dasar
        if (empty($supplier_id)) {
            session()->setFlashdata('error', 'Supplier harus dipilih.');
            return redirect()->to(base_url('transaksi-pembelian/tambah'))->withInput();
        }

        if (empty($obat_id) || !is_array($obat_id)) {
            session()->setFlashdata('error', 'Minimal harus ada 1 obat yang dipilih.');
            return redirect()->to(base_url('transaksi-pembelian/tambah'))->withInput();
        }

        if (empty($no_faktur_beli)) {
            session()->setFlashdata('error', 'Nomor faktur harus diisi.');
            return redirect()->to(base_url('transaksi-pembelian/tambah'))->withInput();
        }

        $existingFaktur = $this->transaksiPembelianModel->where('no_faktur_beli', $no_faktur_beli)->first();
        if ($existingFaktur) {
            session()->setFlashdata('error', 'Nomor faktur sudah ada, gunakan nomor yang berbeda.');
            return redirect()->to(base_url('transaksi-pembelian/tambah'))->withInput();
        }

        // Validasi item yang valid
        $valid_items = [];
        for ($i = 0; $i < count($obat_id); $i++) {
            if (!empty($obat_id[$i]) && 
                !empty($qty[$i]) && $qty[$i] > 0 && 
                !empty($harga_beli[$i]) && $harga_beli[$i] > 0 &&
                !empty($nomor_batch[$i]) && trim($nomor_batch[$i]) != '' &&
                !empty($expired_date[$i])) {
                
                $valid_items[] = $i;
            }
        }

        if (empty($valid_items)) {
            session()->setFlashdata('error', 'Tidak ada item obat yang valid untuk disimpan.');
            return redirect()->to(base_url('transaksi-pembelian/tambah'))->withInput();
        }

        // Pastikan user_id (TTK) valid
        $user_id = session()->get('id');
        if (empty($user_id) || $user_id == 0) {
            $firstUser = $this->userModel->first();
            if ($firstUser) {
                $user_id = $firstUser['id'];
            } else {
                session()->setFlashdata('error', 'Tidak ada TTK yang tersedia di database.');
                return redirect()->to(base_url('transaksi-pembelian/tambah'));
            }
        }

        // Simpan data transaksi pembelian
        $data_transaksi = [
            'no_faktur_beli' => $no_faktur_beli, // Nomor faktur manual input
            'tanggal' => date('Y-m-d H:i:s'),
            'user_id' => $user_id,
            'supplier_id' => $supplier_id,
            'total' => $total
        ];

        try {
            // Start transaction
            $this->transaksiPembelianModel->transStart();

            $this->transaksiPembelianModel->insert($data_transaksi);
            $transaksi_id = $this->transaksiPembelianModel->getInsertID();

            if (!$transaksi_id) {
                throw new \Exception('Gagal menyimpan transaksi pembelian');
            }

            // Simpan detail transaksi dan update stok obat
            foreach ($valid_items as $i) {
                // Ambil data obat
                $obat = $this->obatModel->find($obat_id[$i]);
                if (!$obat) {
                    continue;
                }

                // Simpan detail transaksi
                $data_detail = [
                    'pembelian_id' => $transaksi_id,
                    'obat_id' => $obat_id[$i],
                    'harga_beli' => $harga_beli[$i],
                    'qty' => $qty[$i],
                    'nomor_batch' => $nomor_batch[$i],
                    'expired_date' => $expired_date[$i]
                ];
            
                $result = $this->detailPembelianModel->insert($data_detail);
            
                if (!$result) {
                    throw new \Exception('Gagal menyimpan detail pembelian');
                }

                // Update stok dan harga beli obat
                $stok_baru = $obat['stok'] + $qty[$i];
                $this->obatModel->update($obat_id[$i], [
                    'stok' => $stok_baru,
                    'harga_beli' => $harga_beli[$i]
                ]);
            }

            // Complete transaction
            $this->transaksiPembelianModel->transComplete();

            if ($this->transaksiPembelianModel->transStatus() === FALSE) {
                throw new \Exception('Transaction failed');
            }

            session()->setFlashdata('pesan', 'Transaksi pembelian berhasil ditambahkan dengan nomor faktur: ' . $no_faktur_beli);
            return redirect()->to(base_url('transaksi-pembelian/faktur/' . $transaksi_id));

        } catch (\Exception $e) {
            $this->transaksiPembelianModel->transRollback();
            log_message('error', 'Error saving transaction: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
            return redirect()->to(base_url('transaksi-pembelian/tambah'))->withInput();
        }
    }

    public function detail($id)
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Cek role - pemilik dan TTK yang bisa akses
        if (!in_array(session()->get('role'), ['pemilik', 'ttk'])) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini');
            return redirect()->to(base_url('dashboard'));
        }

        $data = [
            'title' => 'Detail Transaksi Pembelian',
            'transaksi' => $this->transaksiPembelianModel->getTransaksiById($id),
            'detail' => $this->transaksiPembelianModel->getDetailObat($id)
        ];

        return view('transaksi_pembelian/detail', $data);
    }

    public function faktur($id)
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }

        // Cek role - pemilik dan TTK yang bisa akses
        if (!in_array(session()->get('role'), ['pemilik', 'ttk'])) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini');
            return redirect()->to(base_url('dashboard'));
        }

        $data = [
            'title' => 'Faktur Pembelian',
            'transaksi' => $this->transaksiPembelianModel->getTransaksiById($id),
            'detail' => $this->transaksiPembelianModel->getDetailObat($id)
        ];

        return view('transaksi_pembelian/faktur', $data);
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
            return redirect()->to(base_url('transaksi-pembelian'));
        }

        try {
            // Start transaction untuk memastikan konsistensi data
            $this->transaksiPembelianModel->transStart();

            // Cek apakah transaksi ada
            $transaksi = $this->transaksiPembelianModel->find($id);
            if (!$transaksi) {
                session()->setFlashdata('error', 'Transaksi tidak ditemukan');
                return redirect()->to(base_url('transaksi-pembelian'));
            }

            // Ambil detail transaksi
            $detail = $this->detailPembelianModel->where('pembelian_id', $id)->findAll();

            if (empty($detail)) {
                session()->setFlashdata('error', 'Detail transaksi tidak ditemukan');
                return redirect()->to(base_url('transaksi-pembelian'));
            }

            // Kembalikan stok obat (kurangi stok karena pembatalan pembelian)
            foreach ($detail as $d) {
                $obat = $this->obatModel->find($d['obat_id']);
                
                if (!$obat) {
                    log_message('warning', 'Obat dengan ID ' . $d['obat_id'] . ' tidak ditemukan saat menghapus transaksi ' . $id);
                    continue; // Skip jika obat tidak ditemukan
                }

                // Kurangi stok karena pembatalan pembelian
                $stok_baru = max(0, $obat['stok'] - $d['qty']); // Pastikan stok tidak negatif
                
                // PERBAIKI: Gunakan method yang benar untuk update stok
                $updateResult = $this->obatModel->updateStok($d['obat_id'], $stok_baru);
                
                if (!$updateResult) {
                    throw new \Exception('Gagal mengupdate stok obat: ' . $obat['nama_obat']);
                }
            }

            // Hapus detail transaksi
            $deleteDetailResult = $this->detailPembelianModel->where('pembelian_id', $id)->delete();
            if (!$deleteDetailResult) {
                throw new \Exception('Gagal menghapus detail transaksi');
            }

            // Hapus transaksi
            $deleteTransaksiResult = $this->transaksiPembelianModel->delete($id);
            if (!$deleteTransaksiResult) {
                throw new \Exception('Gagal menghapus transaksi');
            }

            // Complete transaction
            $this->transaksiPembelianModel->transComplete();

            if ($this->transaksiPembelianModel->transStatus() === FALSE) {
                throw new \Exception('Transaction failed');
            }

            session()->setFlashdata('pesan', 'Transaksi pembelian berhasil dihapus dan stok obat telah dikembalikan.');
            return redirect()->to(base_url('transaksi-pembelian'));

        } catch (\Exception $e) {
            // Rollback transaction jika ada error
            $this->transaksiPembelianModel->transRollback();
            
            log_message('error', 'Error deleting transaction: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
            return redirect()->to(base_url('transaksi-pembelian'));
        }
    }
}
