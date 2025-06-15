### Penjelasan Sistem Poin dan Arsitektur MVC di CodeIgniter 4

## 1. Alur Kerja Sistem Poin di Aplikasi Apotek Kita Farma

Sistem poin di aplikasi Apotek Kita Farma dirancang untuk memberikan reward kepada member yang berbelanja dan memungkinkan mereka menggunakan poin tersebut untuk mendapatkan diskon pada transaksi berikutnya. Berikut adalah alur kerja lengkapnya:

### A. Perolehan Poin

1. **Perhitungan Poin**:

1. Setiap transaksi senilai Rp 50.000 akan memberikan 1 poin kepada member
2. Perhitungan dilakukan di `Transaksi.php` dengan kode:


```php
$poin_didapat = floor($subtotal / 50000);
```


2. **Penyimpanan Poin**:

1. Poin yang didapat disimpan dalam tabel `transaksi` pada kolom `poin_didapat`
2. Poin juga diakumulasikan ke total poin member di tabel `member` pada kolom `poin`


```php
if ($member_id) {
    $member = $this->memberModel->find($member_id);
    $poin_akhir = $member['poin'] - $poin_digunakan + $poin_didapat;
    $this->memberModel->update($member_id, ['poin' => $poin_akhir]);
}
```




### B. Penggunaan Poin

1. **Konversi Nilai Poin**:

1. 1 poin bernilai Rp 1.000
2. Implementasi di JavaScript pada file `tambah.php`:


```javascript
const potonganHarga = parseInt($(this).val()) * 1000; // 1 poin = Rp 1.000
```


2. **Batasan Penggunaan**:

1. Member hanya dapat menggunakan maksimal 50% dari total belanja
2. Implementasi di JavaScript:


```javascript
const maksPotongan = Math.floor(subtotal * 0.5); // 50% dari subtotal
const maksimalPoin = Math.min(poinTersedia, Math.floor(maksPotongan / 1000));
```


3. **Proses Redeem Poin**:

1. Saat transaksi, member dapat memilih berapa poin yang ingin digunakan
2. Sistem akan menghitung potongan harga berdasarkan poin yang digunakan
3. Poin yang digunakan disimpan di kolom `poin_digunakan` dan potongan harga di kolom `potongan_harga`





### C. Alur Data Poin

1. **Tampilan Poin di Form Transaksi**:

1. Saat memilih member, sistem menampilkan poin tersedia
2. Member dapat memasukkan jumlah poin yang ingin digunakan
3. Sistem otomatis menghitung potongan harga dan total akhir



2. **Penyimpanan Data Poin dalam Transaksi**:

```php
$data_transaksi = [
    // ...
    'poin_didapat' => $poin_didapat,
    'poin_digunakan' => $poin_digunakan,
    'potongan_harga' => $potongan_harga
];
```


3. **Tampilan di Struk**:

1. Struk menampilkan poin yang didapat dari transaksi
2. Jika menggunakan poin, struk juga menampilkan potongan harga dari poin





### D. Penanganan Khusus

1. **Pembatalan Transaksi**:

1. Saat transaksi dibatalkan, poin yang sudah diberikan akan dikurangi dari total poin member


```php
if ($transaksi['member_id'] && $transaksi['poin_didapat'] > 0) {
    $member = $this->memberModel->find($transaksi['member_id']);
    $poin_baru = max(0, $member['poin'] - $transaksi['poin_didapat']);
    $this->memberModel->update($transaksi['member_id'], ['poin' => $poin_baru]);
}
```


2. **Validasi Poin**:

1. Sistem memvalidasi bahwa poin yang digunakan tidak melebihi poin yang tersedia
2. Sistem juga memvalidasi bahwa potongan harga tidak melebihi 50% dari total belanja





## 2. Arsitektur MVC di CodeIgniter 4

CodeIgniter 4 menggunakan pola arsitektur Model-View-Controller (MVC) yang memisahkan logika aplikasi menjadi tiga komponen utama. Berikut penjelasan detail dengan contoh dari kode yang Anda bagikan:

### A. Model (M)

Model bertanggung jawab untuk mengelola data dan interaksi dengan database.

**Contoh dari kode Anda:**

1. **TransaksiModel.php**:

```php
protected $table = 'transaksi';
protected $primaryKey = 'id';
protected $allowedFields = ['tanggal_transaksi', 'admin_id', 'nama_pembeli', 'member_id', 'total', 'poin_didapat', 'poin_digunakan', 'potongan_harga'];

// Method untuk mengambil data dengan join
public function getTransaksiById($id)
{
    return $this->select('transaksi.*, admin.nama_admin, member.nama as nama_member')
                ->join('admin', 'admin.id = transaksi.admin_id')
                ->join('member', 'member.id = transaksi.member_id', 'left')
                ->where('transaksi.id', $id)
                ->first();
}
```


2. **MemberModel.php**:

```php
protected $table = 'member';
protected $allowedFields = ['nama', 'no_hp', 'poin'];

// Method untuk update poin
public function updatePoin($id, $poinBaru)
{
    $member = $this->find($id);
    if ($member) {
        $totalPoin = $member['poin'] + $poinBaru;
        return $this->update($id, ['poin' => $totalPoin]);
    }
    return false;
}
```




**Karakteristik Model di CI4:**

- Extends `CodeIgniter\Model`
- Mendefinisikan tabel, primary key, dan field yang diizinkan
- Berisi method untuk operasi CRUD dan query khusus
- Dapat berisi validasi data
- Tidak berisi logika bisnis yang kompleks


### B. View (V)

View bertanggung jawab untuk menampilkan data kepada pengguna.

**Contoh dari kode Anda:**

1. **struk.php**:

```php
<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="text-center mb-4">
            <h3>APOTEK KITA FARMA</h3>
            <!-- ... -->
        </div>
        
        <!-- Tampilan data transaksi -->
        <div class="row mb-3">
            <div class="col-md-6">
                <p>
                    <strong>No. Transaksi:</strong> #<?= str_pad($transaksi['id'], 5, '0', STR_PAD_LEFT); ?><br>
                    <!-- ... -->
                </p>
            </div>
        </div>
        
        <!-- Tabel detail obat -->
        <div class="table-responsive">
            <table class="table">
                <!-- ... -->
            </table>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
```


2. **tambah.php**:

```php
<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3 bg-primary text-white">
        <h6 class="m-0 font-weight-bold">Form Tambah Transaksi</h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('transaksi/simpan'); ?>" method="post" id="formTransaksi">
            <!-- Form fields -->
        </form>
    </div>
</div>

<!-- JavaScript untuk logika client-side -->
<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // Client-side logic
    });
</script>
<?= $this->endSection(); ?>
```




**Karakteristik View di CI4:**

- Menggunakan sistem template dengan `extend`, `section`, dan `endSection`
- Fokus pada presentasi data, bukan logika bisnis
- Dapat berisi logika presentasi sederhana (loop, kondisi)
- Menerima data dari controller
- Dapat menyertakan JavaScript untuk interaksi client-side


### C. Controller (C)

Controller bertindak sebagai usertara antara Model dan View, menangani request HTTP dan menentukan respons.

**Contoh dari kode Anda:**

1. **Transaksi.php**:

```php
public function tambah()
{
    // Cek login
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('auth'));
    }

    $data = [
        'title' => 'Tambah Transaksi',
        'obat' => $this->obatModel->findAll(),
        'member' => $this->memberModel->findAll(),
        'validation' => \Config\Services::validation()
    ];

    return view('transaksi/tambah', $data);
}

public function simpan()
{
    // Validasi dan proses data
    $nama_pembeli = $this->request->getPost('nama_pembeli');
    // ...
    
    // Simpan data transaksi
    $data_transaksi = [
        'tanggal_transaksi' => date('Y-m-d H:i:s'),
        // ...
    ];
    
    $this->transaksiModel->insert($data_transaksi);
    // ...
    
    return redirect()->to(base_url('transaksi/struk/' . $transaksi_id));
}
```


2. **Member.php**:

```php
public function simpanAjax()
{
    // Validasi input
    if (!$this->validate([
        'nama' => 'required',
        'no_hp' => 'required'
    ])) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Validasi gagal'
        ]);
    }
    
    // Proses data
    $data = [
        'nama' => $this->request->getPost('nama'),
        'no_hp' => $this->request->getPost('no_hp'),
        'poin' => 0
    ];
    
    $this->memberModel->save($data);
    $id = $this->memberModel->getInsertID();
    
    // Return response
    return $this->response->setJSON([
        'status' => 'success',
        'id' => $id,
        'message' => 'Member berhasil ditambahkan'
    ]);
}
```




**Karakteristik Controller di CI4:**

- Extends `BaseController` (yang extends `CodeIgniter\Controller`)
- Menangani request HTTP (GET, POST, dll)
- Memuat model yang diperlukan
- Memproses dan memvalidasi input
- Menyiapkan data untuk view
- Menentukan view yang akan ditampilkan
- Menangani redirect dan response


### D. Routing di CodeIgniter 4

Routing menghubungkan URL dengan method controller yang sesuai.

**Contoh dari Routes.php:**

```php
// Auth Routes
$routes->group('auth', function ($routes) {
    $routes->get('', 'Auth::index');
    $routes->post('login', 'Auth::login');
    $routes->get('logout', 'Auth::logout');
    $routes->get('register', 'Auth::register');
    $routes->post('doRegister', 'Auth::doRegister');
});

// Transaksi Routes
$routes->group('transaksi', function ($routes) {
    $routes->get('', 'Transaksi::index');
    $routes->get('tambah', 'Transaksi::tambah');
    $routes->post('simpan', 'Transaksi::simpan');
    $routes->get('detail/(:num)', 'Transaksi::detail/$1');
    // ...
});
```

**Karakteristik Routing di CI4:**

- Mendefinisikan pemetaan URL ke controller dan method
- Mendukung grouping untuk organisasi yang lebih baik
- Mendukung parameter dalam URL (seperti ID)
- Mendukung pembatasan HTTP method (GET, POST, dll)
- Dapat menentukan filter (middleware) untuk grup rute


### E. Alur Request di CodeIgniter 4

Berikut alur lengkap request di aplikasi CodeIgniter 4:

1. **Request masuk** - User mengakses URL, misalnya `/transaksi/tambah`
2. **Routing** - CI4 memeriksa `Routes.php` dan menemukan bahwa URL ini dipetakan ke `Transaksi::tambah`
3. **Controller** - Method `tambah()` di `Transaksi` controller dijalankan
4. **Model** - Controller memuat data dari model, misalnya `$this->obatModel->findAll()`
5. **View** - Controller merender view dengan data, misalnya `return view('transaksi/tambah', $data)`
6. **Response** - View diproses dan dikirim sebagai response ke browser


### F. Keunggulan Arsitektur MVC di CI4

1. **Pemisahan Tanggung Jawab**:

1. Model: Logika data dan database
2. View: Presentasi dan tampilan
3. Controller: Koordinasi dan logika bisnis



2. **Maintainability**:

1. Kode lebih mudah dipelihara karena terorganisir dengan baik
2. Perubahan pada satu komponen tidak mempengaruhi komponen lain



3. **Reusability**:

1. Model dan view dapat digunakan kembali di berbagai controller
2. Komponen dapat digunakan ulang tanpa duplikasi kode



4. **Testability**:

1. Komponen dapat diuji secara terpisah
2. Memudahkan unit testing dan integration testing



5. **Kolaborasi Tim**:

1. Memungkinkan anggota tim bekerja pada komponen yang berbeda secara bersamaan
2. Frontend developer fokus pada view, backend developer fokus pada model dan controller





## Kesimpulan

Sistem poin di Apotek Kita Farma memanfaatkan arsitektur MVC CodeIgniter 4 dengan baik:

- **Model** (TransaksiModel, MemberModel) menangani operasi database dan logika data poin
- **View** (tambah.php, struk.php) menampilkan informasi poin dan form penggunaan poin
- **Controller** (Transaksi, Member) mengkoordinasikan proses perolehan dan penggunaan poin


Arsitektur ini membuat sistem poin mudah dipelihara, diperluas, dan diintegrasikan dengan fitur lain dalam aplikasi.

### Penjelasan Alur Sistem Apotek Kita Farma

Berikut adalah penjelasan detail tentang alur stok obat, alur login, dan alur tambah obat pada sistem Apotek Kita Farma berdasarkan kode yang Anda bagikan.

## 1. Alur Stok Obat (Pengurangan dan Penambahan)

Stok obat dalam sistem Apotek Kita Farma dapat berubah melalui dua mekanisme utama: pengurangan saat transaksi penjualan dan penambahan saat pembelian dari supplier.

### A. Pengurangan Stok Obat

Pengurangan stok obat terjadi saat ada transaksi penjualan kepada pembeli. Berikut alur lengkapnya:

1. **Proses Transaksi Penjualan**

1. Kasir memilih obat yang dibeli oleh pembeli pada form transaksi
2. Sistem menampilkan stok tersedia untuk setiap obat yang dipilih
3. Kasir memasukkan jumlah obat yang dibeli



2. **Validasi Stok**

1. Sistem melakukan validasi untuk memastikan jumlah yang dibeli tidak melebihi stok yang tersedia
2. Jika melebihi stok, sistem akan menampilkan pesan error dan membatasi jumlah maksimal yang bisa dibeli


```javascript
// Kode validasi stok di JavaScript (tambah.php)
if (val > max) {
    alert('Stok tidak mencukupi!');
    $(this).val(max);
    val = max;
}
```


3. **Pengurangan Stok di Database**

1. Setelah transaksi disimpan, sistem akan mengurangi stok obat di database
2. Proses ini terjadi di controller Transaksi pada method `simpan()`


```php
// Simpan detail transaksi dan update stok obat
for ($i = 0; $i < count($obat_id); $i++) {
    // Ambil data obat
    $obat = $this->obatModel->find($obat_id[$i]);

    // Simpan detail transaksi
    $data_detail = [
        'transaksi_id' => $transaksi_id,
        'obat_id' => $obat_id[$i],
        'harga_saat_ini' => $harga[$i],
        'qty' => $qty[$i]
    ];
    $this->detailTransaksiModel->insert($data_detail);

    // Update stok obat
    $stok_baru = $obat['stok'] - $qty[$i];
    $this->obatModel->update($obat_id[$i], ['stok' => $stok_baru]);
}
```


4. **Penanganan Pembatalan Transaksi**

1. Jika transaksi dibatalkan, stok obat akan dikembalikan
2. Proses ini terjadi di controller Transaksi pada method `hapus()`


```php
// Ambil detail transaksi
$detail = $this->detailTransaksiModel->where('transaksi_id', $id)->findAll();

// Kembalikan stok obat
foreach ($detail as $d) {
    $obat = $this->obatModel->find($d['obat_id']);
    $stok_baru = $obat['stok'] + $d['qty'];
    $this->obatModel->update($d['obat_id'], ['stok' => $stok_baru]);
}
```




### B. Penambahan Stok Obat

Penambahan stok obat terjadi melalui dua cara: saat menambah obat baru dan saat membeli obat dari supplier.

1. **Penambahan Obat Baru**

1. Admin/pemilik mengisi form tambah obat termasuk jumlah stok awal
2. Data disimpan ke database melalui controller Obat method `simpan()`


```php
// Simpan data
$data = [
    'bpom' => $this->request->getPost('bpom'),
    'nama_obat' => $this->request->getPost('nama_obat'),
    'harga' => $this->request->getPost('harga'),
    'produsen' => $this->request->getPost('produsen'),
    'supplier_id' => $this->request->getPost('supplier_id'),
    'stok' => $this->request->getPost('stok'),
];

$this->obatModel->insert($data);
```


2. **Pembelian dari Supplier**

1. Pemilik mengakses menu "Beli Obat dari Supplier"
2. Memilih obat dan memasukkan jumlah yang dibeli
3. Proses penambahan stok terjadi di controller Transaksi method `simpanPembelian()`


```php
$obat_id = $this->request->getPost('obat_id');
$jumlah = $this->request->getPost('jumlah');

// Ambil data obat
$obat = $this->obatModel->find($obat_id);

// Update stok obat
$stok_baru = $obat['stok'] + $jumlah;
$this->obatModel->update($obat_id, ['stok' => $stok_baru]);
```


3. **Update Stok melalui Edit Obat**

1. Admin/pemilik juga dapat langsung mengubah stok melalui form edit obat
2. Proses ini terjadi di controller Obat method `update()`


```php
// Update data
$data = [
    // ...
    'stok' => $this->request->getPost('stok'),
];

$this->obatModel->update($id, $data);
```




## 2. Alur Login (Admin & Pemilik)

Sistem Apotek Kita Farma memiliki dua role utama: admin dan pemilik. Berikut alur login untuk kedua role tersebut:

### A. Proses Login

1. **Akses Halaman Login**

1. User mengakses halaman login di `/auth`
2. Sistem menampilkan form login (username dan password)


```php
// Controller Auth method index()
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
```


2. **Pengisian Form Login**

1. User memasukkan username dan password
2. Form dikirim ke endpoint `/auth/login` dengan method POST


```html
<form action="<?= base_url('auth/login'); ?>" method="post">
    <div class="mb-3">
        <input type="text" class="form-control" name="username" placeholder="Username" required>
    </div>
    <div class="mb-4">
        <input type="password" class="form-control" name="password" placeholder="Password" required>
    </div>
    <div class="d-grid">
        <button type="submit" class="btn btn-primary btn-login">Masuk</button>
    </div>
</form>
```


3. **Verifikasi Kredensial**

1. Sistem memeriksa kredensial login melalui controller Auth method `login()`
2. Untuk lingkungan development, ada akun bypass untuk pemilik dan admin


```php
// Hanya untuk development!
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
```


4. **Verifikasi dari Database**

1. Jika bukan akun bypass, sistem memeriksa kredensial dari database


```php
// Cek login untuk production
$admin = $this->adminModel->where('username', $username)->first();

if ($admin) {
    // Verifikasi password
    if ($password === $admin['password']) {
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
}
```


5. **Penyimpanan Session**

1. Jika login berhasil, sistem menyimpan data user ke session
2. Data yang disimpan: id, nama_admin, username, role, dan status logged_in



6. **Redirect ke Dashboard**

1. Setelah login berhasil, user diarahkan ke halaman dashboard





### B. Perbedaan Role Admin dan Pemilik

1. **Hak Akses Pemilik**

1. Pemilik memiliki akses penuh ke semua fitur sistem
2. Dapat mengelola data admin (tambah, edit, hapus)
3. Dapat melakukan pembelian obat dari supplier
4. Dapat melihat laporan keuangan dan statistik



2. **Hak Akses Admin**

1. Admin dapat mengelola transaksi penjualan
2. Dapat mengelola data obat, supplier, dan member
3. Tidak dapat mengelola data admin lain
4. Tidak dapat melakukan pembelian dari supplier



3. **Implementasi Pembatasan Akses**

1. Pembatasan akses dilakukan dengan memeriksa role user di setiap controller


```php
// Contoh pembatasan akses di controller Obat
if (session()->get('role') != 'pemilik' && session()->get('role') != 'admin') {
    return redirect()->to(base_url('dashboard'));
}

// Contoh pembatasan akses khusus pemilik di controller Transaksi
if (session()->get('role') !== 'pemilik') {
    session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini');
    return redirect()->to(base_url('transaksi'));
}
```




### C. Proses Registrasi Admin

1. **Akses Halaman Register**

1. User mengakses halaman register di `/auth/register`
2. Sistem menampilkan form registrasi



2. **Pengisian Form Register**

1. User mengisi data: nama lengkap, username, password, konfirmasi password, dan kode registrasi
2. Kode registrasi diperlukan untuk mencegah registrasi tidak sah



3. **Validasi Input**

1. Sistem memvalidasi input user


```php
$rules = [
    'nama_admin' => 'required|min_length[3]|max_length[100]',
    'username' => 'required|min_length[3]|max_length[20]|is_unique[admin.username]',
    'password' => 'required|min_length[6]',
    'confirm_password' => 'required|matches[password]',
    'registration_code' => 'required|exact[APOTEK2025]'
];
```


4. **Penyimpanan Data Admin**

1. Jika validasi berhasil, data admin baru disimpan dengan role default 'admin'


```php
$data = [
    'nama_admin' => esc($this->request->getPost('nama_admin')),
    'username' => esc($this->request->getPost('username')),
    'password' => $this->request->getPost('password'),
    'role' => 'admin', // Default role admin untuk registrasi baru
    'created_at' => date('Y-m-d H:i:s')
];

$this->adminModel->insert($data);
```


5. **Redirect ke Login**

1. Setelah registrasi berhasil, user diarahkan ke halaman login





## 3. Alur Tambah Obat

Proses penambahan obat baru ke dalam sistem dilakukan oleh admin atau pemilik. Berikut alur lengkapnya:

### A. Akses Halaman Tambah Obat

1. **Navigasi ke Menu Obat**

1. Admin/pemilik mengakses menu "Data Obat"
2. Klik tombol "Tambah Obat" untuk membuka form tambah obat


```php
// Controller Obat method tambah()
public function tambah()
{
    // Cek login
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('auth'));
    }

    $data = [
        'title' => 'Tambah Obat',
        'supplier' => $this->supplierModel->findAll(),
        'validation' => \Config\Services::validation()
    ];

    return view('obat/tambah', $data);
}
```




### B. Pengisian Form Tambah Obat

1. **Input Data Obat**

1. Admin/pemilik mengisi form dengan data obat:

1. BPOM (nomor registrasi BPOM)
2. Nama Obat
3. Harga
4. Produsen
5. Supplier (dipilih dari daftar supplier yang ada)
6. Stok awal






2. **Tampilan Form**

```html
<form action="<?= base_url('obat/simpan'); ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field(); ?>
    <div class="row mb-3">
        <label for="BPOM" class="col-sm-2 col-form-label">BPOM</label>
        <div class="col-sm-10">
            <input type="text" class="form-control <?= ($validation->hasError('bpom')) ? 'is-invalid' : ''; ?>" id="bpom" name="bpom" value="<?= old('bpom'); ?>" required>
            <div class="invalid-feedback">
                <?= $validation->getError('bpom'); ?>
            </div>
        </div>
    </div>
    <!-- Input fields lainnya -->
    <div class="row mb-3">
        <div class="col-sm-10 offset-sm-2">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </div>
    </div>
</form>
```




### C. Validasi dan Penyimpanan Data

1. **Validasi Input**

1. Sistem memvalidasi input berdasarkan aturan validasi di model ObatModel


```php
// Controller Obat method simpan()
public function simpan()
{
    // Validasi input
    if (!$this->validate($this->obatModel->validationRules)) {
        return redirect()->to(base_url('obat/tambah'))->withInput()->with('validation', $this->validator);
    }

    // Proses penyimpanan data
    // ...
}
```


2. **Penyimpanan Data**

1. Jika validasi berhasil, data obat disimpan ke database


```php
// Simpan data
$data = [
    'bpom' => $this->request->getPost('bpom'),
    'nama_obat' => $this->request->getPost('nama_obat'),
    'harga' => $this->request->getPost('harga'),
    'produsen' => $this->request->getPost('produsen'),
    'supplier_id' => $this->request->getPost('supplier_id'),
    'stok' => $this->request->getPost('stok'),
];

$this->obatModel->insert($data);
session()->setFlashdata('pesan', 'Data berhasil ditambahkan');
return redirect()->to(base_url('obat'));
```


3. **Notifikasi Sukses**

1. Setelah data berhasil disimpan, sistem menampilkan notifikasi sukses
2. User diarahkan kembali ke halaman daftar obat





### D. Tampilan Daftar Obat

1. **Daftar Obat**

1. Setelah obat ditambahkan, obat akan muncul di daftar obat
2. Daftar menampilkan informasi lengkap termasuk stok yang tersedia


```php
// Controller Obat method index()
public function index()
{
    // Cek login
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('auth'));
    }

    // Cek role (if this exists)
    if (session()->get('role') != 'pemilik' && session()->get('role') != 'admin') {
        return redirect()->to(base_url('dashboard'));
    }

    $data = [
        'title' => 'Data Obat',
        'obat' => $this->obatModel->getObatWithSupplier()
    ];

    return view('obat/index', $data);
}
```


2. **Tampilan Tabel Obat**

```html
<table class="table table-bordered dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>#</th>
            <th>BPOM</th>
            <th>Nama Obat</th>
            <th>Harga</th>
            <th>Produsen</th>
            <th>Supplier</th>
            <th>Stok</th>
            <th>Opsi</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; ?>
        <?php foreach ($obat as $o) : ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= $o['bpom']; ?></td>
                <td><?= $o['nama_obat']; ?></td>
                <td>Rp <?= number_format($o['harga'], 0, ',', '.'); ?></td>
                <td><?= $o['produsen']; ?></td>
                <td><?= $o['nama_supplier']; ?></td>
                <td><?= $o['stok']; ?></td>
                <td>
                    <a href="<?= base_url('obat/edit/' . $o['id']); ?>" class="btn btn-info btn-sm">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="<?= base_url('obat/hapus/' . $o['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
```




## Kesimpulan

Sistem Apotek Kita Farma memiliki alur yang terstruktur untuk mengelola stok obat, autentikasi pengguna, dan penambahan data obat:

1. **Alur Stok Obat**:

1. Pengurangan stok terjadi saat transaksi penjualan dengan validasi stok tersedia
2. Penambahan stok terjadi saat menambah obat baru, edit obat, atau pembelian dari supplier
3. Sistem memiliki mekanisme untuk mengembalikan stok jika transaksi dibatalkan



2. **Alur Login**:

1. Sistem mendukung dua role utama: admin dan pemilik
2. Memiliki akun bypass untuk development
3. Proses registrasi admin dengan kode registrasi untuk keamanan
4. Pembatasan akses berdasarkan role di setiap controller



3. **Alur Tambah Obat**:

1. Form input lengkap dengan validasi
2. Integrasi dengan data supplier
3. Penyimpanan stok awal
4. Tampilan daftar obat yang informatif





Semua alur ini terintegrasi dalam arsitektur MVC CodeIgniter 4, dengan pemisahan yang jelas antara Model (data), View (tampilan), dan Controller (logika).

### Penjelasan Alur Supplier dan Member di Apotek Kita Farma

## 1. Alur Pengelolaan Supplier

Supplier adalah pihak yang memasok obat ke apotek. Berikut adalah alur lengkap pengelolaan supplier dalam sistem Apotek Kita Farma:

### A. Struktur Data Supplier

Berdasarkan `SupplierModel.php`, data supplier terdiri dari:

- `id` (Primary Key)
- `nama_supplier` (Nama perusahaan supplier)
- `alamat` (Alamat lengkap supplier)
- `kota` (Kota tempat supplier berada)
- `telepon` (Nomor telepon kontak supplier)


```php
protected $table = 'supplier';
protected $primaryKey = 'id';
protected $allowedFields = ['nama_supplier', 'alamat', 'kota', 'telepon'];
```

### B. Alur Melihat Daftar Supplier

1. **Akses Menu Supplier**

1. Admin/pemilik mengakses menu "Data Supplier" di sidebar
2. Sistem melakukan pengecekan login


```php
// Cek login
if (!session()->get('logged_in')) {
    return redirect()->to(base_url('auth'));
}
```


2. **Menampilkan Daftar Supplier**

1. Controller `Supplier` method `index()` mengambil semua data supplier


```php
$data = [
    'title' => 'Data Supplier',
    'supplier' => $this->supplierModel->findAll()
];
```


3. **Tampilan Daftar Supplier**

1. View `index.php` menampilkan data dalam bentuk tabel
2. Setiap baris menampilkan nama supplier, alamat, kota, telepon, dan tombol aksi (edit/hapus)


```php
<table class="table table-bordered dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Supplier</th>
            <th>Alamat</th>
            <th>Kota</th>
            <th>Telepon</th>
            <th>Opsi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($supplier as $s) : ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= $s['nama_supplier']; ?></td>
                <td><?= $s['alamat']; ?></td>
                <td><?= $s['kota']; ?></td>
                <td><?= $s['telepon']; ?></td>
                <td>
                    <a href="<?= base_url('supplier/edit/' . $s['id']); ?>" class="btn btn-info btn-sm">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="<?= base_url('supplier/hapus/' . $s['id']); ?>" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
```




### C. Alur Tambah Supplier

1. **Akses Form Tambah Supplier**

1. Admin/pemilik mengklik tombol "Tambah Supplier" di halaman daftar supplier
2. Controller `Supplier` method `tambah()` menampilkan form tambah supplier


```php
public function tambah()
{
    // Cek login
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('auth'));
    }

    $data = [
        'title' => 'Tambah Supplier',
        'validation' => \Config\Services::validation()
    ];

    return view('supplier/tambah', $data);
}
```


2. **Pengisian Form Supplier**

1. Admin/pemilik mengisi form dengan data supplier:

1. Nama Supplier
2. Alamat
3. Kota
4. Telepon





```html
<form action="<?= base_url('supplier/simpan'); ?>" method="post">
    <?= csrf_field(); ?>
    <div class="row mb-3">
        <label for="nama_supplier" class="col-sm-2 col-form-label">Nama Supplier</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="nama_supplier" name="nama_supplier" required>
        </div>
    </div>
    <!-- Input fields lainnya -->
</form>
```


3. **Validasi dan Penyimpanan Data**

1. Saat form disubmit, data dikirim ke controller `Supplier` method `simpan()`
2. Sistem melakukan validasi input berdasarkan aturan di model


```php
// Validasi input
if (!$this->validate($this->supplierModel->validationRules)) {
    return redirect()->to(base_url('supplier/tambah'))->withInput()->with('validation', $this->validator);
}
```

1. Jika validasi berhasil, data disimpan ke database


```php
// Simpan data
$data = [
    'nama_supplier' => $this->request->getPost('nama_supplier'),
    'alamat' => $this->request->getPost('alamat'),
    'kota' => $this->request->getPost('kota'),
    'telepon' => $this->request->getPost('telepon')
];

$this->supplierModel->insert($data);
```


4. **Notifikasi dan Redirect**

1. Sistem menampilkan notifikasi sukses dan mengarahkan kembali ke daftar supplier


```php
session()->setFlashdata('pesan', 'Data berhasil ditambahkan');
return redirect()->to(base_url('supplier'));
```




### D. Alur Edit Supplier

1. **Akses Form Edit Supplier**

1. Admin/pemilik mengklik tombol edit pada supplier yang ingin diubah
2. Controller `Supplier` method `edit()` menampilkan form edit dengan data supplier yang dipilih


```php
public function edit($id)
{
    // Cek login
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('auth'));
    }

    $data = [
        'title' => 'Edit Supplier',
        'supplier' => $this->supplierModel->find($id),
        'validation' => \Config\Services::validation()
    ];

    return view('supplier/edit', $data);
}
```


2. **Pengisian Form Edit**

1. Form ditampilkan dengan data supplier yang sudah ada
2. Admin/pemilik mengubah data yang diperlukan


```html
<input type="text" class="form-control" id="nama_supplier" name="nama_supplier" 
       value="<?= (old('nama_supplier')) ? old('nama_supplier') : $supplier['nama_supplier']; ?>" required>
```


3. **Validasi dan Update Data**

1. Saat form disubmit, data dikirim ke controller `Supplier` method `update()`
2. Sistem melakukan validasi dan update data


```php
// Update data
$data = [
    'nama_supplier' => $this->request->getPost('nama_supplier'),
    'alamat' => $this->request->getPost('alamat'),
    'kota' => $this->request->getPost('kota'),
    'telepon' => $this->request->getPost('telepon')
];

$this->supplierModel->update($id, $data);
```




### E. Alur Hapus Supplier

1. **Konfirmasi Hapus**

1. Admin/pemilik mengklik tombol hapus pada supplier yang ingin dihapus
2. Sistem menampilkan konfirmasi penghapusan


```html
<a href="<?= base_url('supplier/hapus/' . $s['id']); ?>" class="btn btn-danger btn-sm" 
   onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
    <i class="fas fa-trash"></i>
</a>
```


2. **Proses Penghapusan**

1. Jika dikonfirmasi, controller `Supplier` method `hapus()` menghapus data supplier


```php
public function hapus($id)
{
    // Hapus data
    $this->supplierModel->delete($id);
    session()->setFlashdata('pesan', 'Data berhasil dihapus');
    return redirect()->to(base_url('supplier'));
}
```




### F. Integrasi Supplier dengan Obat

Supplier terintegrasi dengan data obat, di mana setiap obat terhubung dengan supplier tertentu:

1. **Relasi Supplier-Obat**

1. Setiap obat memiliki `supplier_id` yang merujuk ke supplier
2. Saat menambah/edit obat, admin/pemilik memilih supplier dari daftar yang ada



2. **Pembelian Obat dari Supplier**

1. Sistem memiliki fitur untuk menambah stok obat dari supplier
2. Proses ini dilakukan melalui menu "Beli Obat dari Supplier"
3. Saat pembelian, data supplier ditampilkan bersama dengan obat yang dipilih





## 2. Alur Pengelolaan Member

Member adalah pelanggan tetap apotek yang mendapatkan keuntungan berupa poin. Berikut adalah alur lengkap pengelolaan member:

### A. Struktur Data Member

Berdasarkan `MemberModel.php`, data member terdiri dari:

- `id` (Primary Key)
- `nama` (Nama member)
- `no_hp` (Nomor telepon member)
- `poin` (Jumlah poin yang dimiliki member)


```php
protected $table = 'member';
protected $primaryKey = 'id';
protected $allowedFields = ['nama', 'no_hp', 'poin'];
```

### B. Alur Melihat Daftar Member

1. **Akses Menu Member**

1. Admin/pemilik mengakses menu "Data Member" di sidebar
2. Controller `Member` method `index()` mengambil semua data member


```php
$data = [
    'title' => 'Data Member',
    'member' => $this->memberModel->findAll()
];
```


2. **Tampilan Daftar Member**

1. View `index.php` menampilkan data dalam bentuk tabel
2. Setiap baris menampilkan nama, no. HP, poin, dan tombol aksi (riwayat/edit/hapus)


```php
<table class="table table-bordered dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>No. HP</th>
            <th>Poin</th>
            <th>Opsi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($member as $m) : ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= $m['nama']; ?></td>
                <td><?= $m['no_hp']; ?></td>
                <td><?= $m['poin']; ?></td>
                <td>
                    <a href="<?= base_url('member/riwayat/' . $m['id']); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-history"></i> Riwayat
                    </a>
                    <a href="<?= base_url('member/edit/' . $m['id']); ?>" class="btn btn-info btn-sm">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="<?= base_url('member/hapus/' . $m['id']); ?>" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
```




### C. Alur Tambah Member

1. **Akses Form Tambah Member**

1. Admin/pemilik mengklik tombol "Tambah Member" di halaman daftar member
2. Controller `Member` method `tambah()` menampilkan form tambah member



2. **Pengisian Form Member**

1. Admin/pemilik mengisi form dengan data member:

1. Nama
2. No. HP



2. Poin awal otomatis diset 0 untuk member baru



3. **Validasi dan Penyimpanan Data**

1. Saat form disubmit, data dikirim ke controller `Member` method `simpan()`
2. Sistem melakukan validasi dan menyimpan data


```php
// Validasi input
if (!$this->validate([
    'nama' => 'required',
    'no_hp' => 'required'
])) {
    return redirect()->to(base_url('member/tambah'))->withInput();
}

$this->memberModel->save([
    'nama' => $this->request->getVar('nama'),
    'no_hp' => $this->request->getVar('no_hp'),
    'poin' => 0
]);
```




### D. Alur Tambah Member via AJAX (Saat Transaksi)

Sistem juga mendukung penambahan member langsung dari form transaksi:

1. **Akses Modal Tambah Member**

1. Kasir mengklik "Tambah Member baru" pada dropdown member di form transaksi
2. Modal form tambah member ditampilkan



2. **Pengisian Form dan Pengiriman AJAX**

1. Kasir mengisi nama dan no. HP member baru
2. Data dikirim via AJAX ke controller `Member` method `simpanAjax()`


```php
public function simpanAjax()
{
    // Validasi input
    if (!$this->validate([
        'nama' => 'required',
        'no_hp' => 'required'
    ])) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Validasi gagal'
        ]);
    }

    $data = [
        'nama' => $this->request->getPost('nama'),
        'no_hp' => $this->request->getPost('no_hp'),
        'poin' => 0
    ];

    $this->memberModel->save($data);
    $id = $this->memberModel->getInsertID();

    return $this->response->setJSON([
        'status' => 'success',
        'id' => $id,
        'message' => 'Member berhasil ditambahkan'
    ]);
}
```


3. **Update Dropdown Member**

1. Setelah member berhasil ditambahkan, dropdown member di form transaksi diupdate
2. Member baru otomatis dipilih untuk transaksi yang sedang berlangsung





### E. Alur Edit Member

1. **Akses Form Edit Member**

1. Admin/pemilik mengklik tombol edit pada member yang ingin diubah
2. Controller `Member` method `edit()` menampilkan form edit dengan data member yang dipilih



2. **Pengisian Form Edit**

1. Form ditampilkan dengan data member yang sudah ada
2. Admin/pemilik dapat mengubah nama, no. HP, dan poin member


```html
<div class="row mb-3">
    <label for="poin" class="col-sm-2 col-form-label">Poin</label>
    <div class="col-sm-10">
        <input type="number" class="form-control" id="poin" name="poin" 
               value="<?= (old('poin')) ? old('poin') : $member['poin']; ?>" required>
    </div>
</div>
```


3. **Validasi dan Update Data**

1. Saat form disubmit, data dikirim ke controller `Member` method `update()`
2. Sistem melakukan validasi dan update data


```php
$this->memberModel->save([
    'id' => $id,
    'nama' => $this->request->getVar('nama'),
    'no_hp' => $this->request->getVar('no_hp'),
    'poin' => $this->request->getVar('poin')
]);
```




### F. Alur Hapus Member

1. **Konfirmasi Hapus**

1. Admin/pemilik mengklik tombol hapus pada member yang ingin dihapus
2. Sistem menampilkan konfirmasi penghapusan



2. **Validasi Penghapusan**

1. Sistem memeriksa apakah member memiliki riwayat transaksi
2. Jika ada, member tidak dapat dihapus


```php
// Cek apakah member memiliki transaksi
$transaksi = $this->transaksiModel->where('member_id', $id)->findAll();
if ($transaksi) {
    session()->setFlashdata('error', 'Member tidak dapat dihapus karena memiliki riwayat transaksi.');
    return redirect()->to(base_url('member'));
}
```


3. **Proses Penghapusan**

1. Jika tidak ada transaksi, data member dihapus


```php
$this->memberModel->delete($id);
```




### G. Alur Melihat Riwayat Transaksi Member

1. **Akses Halaman Riwayat**

1. Admin/pemilik mengklik tombol "Riwayat" pada member yang ingin dilihat riwayatnya
2. Controller `Member` method `riwayat()` menampilkan data member dan riwayat transaksinya


```php
public function riwayat($id)
{
    // Cek login
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('auth'));
    }

    $data = [
        'title' => 'Riwayat Transaksi Member',
        'member' => $this->memberModel->find($id),
        'riwayat' => $this->transaksiModel->getRiwayatByMemberId($id)
    ];

    return view('member/riwayat', $data);
}
```


2. **Tampilan Riwayat Transaksi**

1. View `riwayat.php` menampilkan detail member dan tabel riwayat transaksi
2. Tabel menampilkan tanggal, admin, total, poin didapat, dan tombol detail


```php
<table class="table table-bordered dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>#</th>
            <th>Tanggal</th>
            <th>Admin</th>
            <th>Total</th>
            <th>Poin Didapat</th>
            <th>Opsi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($riwayat as $r) : ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= date('d-m-Y H:i', strtotime($r['tanggal_transaksi'])); ?></td>
                <td><?= $r['nama_admin']; ?></td>
                <td>Rp <?= number_format($r['total'], 0, ',', '.'); ?></td>
                <td><?= $r['poin_didapat']; ?></td>
                <td>
                    <a href="<?= base_url('transaksi/detail/' . $r['id']); ?>" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i> Detail
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
```




### H. Alur Perolehan dan Penggunaan Poin Member

1. **Perolehan Poin**

1. Member mendapatkan poin saat melakukan transaksi
2. Setiap Rp 50.000 memberikan 1 poin
3. Poin dihitung di controller `Transaksi` method `simpan()`


```php
// Hitung poin yang didapat (1 poin untuk setiap Rp 50.000)
$poin_didapat = floor($subtotal / 50000);
```


2. **Penggunaan Poin**

1. Member dapat menggunakan poin untuk mendapatkan diskon
2. 1 poin bernilai Rp 1.000
3. Maksimal penggunaan poin adalah 50% dari total belanja
4. Proses ini dilakukan di form transaksi



3. **Update Poin Member**

1. Setelah transaksi, poin member diupdate
2. Poin yang digunakan dikurangi dan poin yang didapat ditambahkan


```php
// Update poin member jika transaksi menggunakan member
if ($member_id) {
    $member = $this->memberModel->find($member_id);
    
    // Kurangi poin yang digunakan
    $poin_akhir = $member['poin'] - $poin_digunakan + $poin_didapat;
    
    $this->memberModel->update($member_id, ['poin' => $poin_akhir]);
}
```




## Kesimpulan

Sistem Apotek Kita Farma memiliki alur yang terstruktur untuk mengelola supplier dan member:

### Supplier:

- Pengelolaan data supplier (tambah, lihat, edit, hapus)
- Integrasi dengan data obat
- Pembelian obat dari supplier untuk menambah stok


### Member:

- Pengelolaan data member (tambah, lihat, edit, hapus)
- Penambahan member langsung dari form transaksi
- Sistem poin untuk reward member
- Penggunaan poin untuk mendapatkan diskon
- Pelacakan riwayat transaksi member


Kedua fitur ini saling terintegrasi dalam sistem apotek, di mana supplier menyediakan obat yang kemudian dijual kepada pelanggan (termasuk member) melalui transaksi.

Saya tidak dapat langsung membuat file DOCX untuk diunduh, tetapi saya dapat membantu Anda menyusun konten yang dapat dengan mudah disalin ke Microsoft Word. Berikut adalah langkah-langkah untuk membuat dokumen Word dari penjelasan alur sistem Apotek Kita Farma:

## Langkah-langkah Membuat Dokumen Word:

1. Buka Microsoft Word di komputer Anda
2. Salin konten di bawah ini
3. Tempel ke dokumen Word Anda
4. Simpan dokumen sebagai file .docx


---

# DOKUMENTASI ALUR SISTEM APOTEK KITA FARMA

## Daftar Isi

1. Alur Login dan Autentikasi
2. Alur Pengelolaan Admin
3. Alur Pengelolaan Obat dan Stok
4. Alur Pengelolaan Supplier
5. Alur Pengelolaan Member
6. Alur Sistem Poin Member
7. Alur Transaksi


---

## 1. Alur Login dan Autentikasi

### A. Proses Login

1. **Akses Halaman Login**

1. Pengguna mengakses halaman login di URL `/auth`
2. Sistem menampilkan form login dengan field username dan password



2. **Pengisian Form Login**

1. Pengguna memasukkan username dan password
2. Pengguna mengklik tombol "Login"



3. **Validasi Kredensial**

1. Sistem memeriksa kredensial dengan data di database
2. Untuk development, sistem menyediakan akses khusus:

1. Username: pemilik, Password: pemilik123 (role pemilik)
2. Username: admin, Password: admin123 (role admin)






4. **Pemberian Akses**

1. Jika kredensial valid, sistem membuat session dengan data:

1. id
2. nama_admin
3. username
4. role (admin/pemilik)
5. logged_in = TRUE



2. Pengguna diarahkan ke halaman dashboard





### B. Proses Logout

1. **Akses Logout**

1. Pengguna mengklik menu "Logout" di dropdown profil
2. Sistem menghapus session dengan `session()->destroy()`
3. Pengguna diarahkan kembali ke halaman login





### C. Registrasi Admin Baru

1. **Akses Halaman Registrasi**

1. Pengguna mengklik link "Daftar" di halaman login
2. Sistem menampilkan form registrasi



2. **Pengisian Form Registrasi**

1. Pengguna mengisi nama lengkap, username, password, dan konfirmasi password
2. Pengguna mengklik tombol "Daftar"



3. **Validasi dan Penyimpanan**

1. Sistem memvalidasi input (username unik, password minimal 6 karakter, dll)
2. Jika valid, sistem menyimpan data admin baru dengan role default "admin"
3. Pengguna diarahkan ke halaman login dengan pesan sukses





---

## 2. Alur Pengelolaan Admin

### A. Struktur Data Admin

- `id` (Primary Key)
- `nama_admin` (Nama lengkap admin)
- `username` (Username untuk login)
- `password` (Password untuk login)
- `role` (Role: admin/pemilik)


### B. Alur Melihat Daftar Admin

1. **Akses Menu Admin**

1. Pemilik mengakses menu "Data Admin" di sidebar
2. Sistem memeriksa role pengguna (hanya pemilik yang diizinkan)
3. Sistem menampilkan daftar admin dalam bentuk tabel





### C. Alur Tambah Admin

1. **Akses Form Tambah Admin**

1. Pemilik mengklik tombol "Tambah Admin" di halaman daftar admin
2. Sistem menampilkan form tambah admin



2. **Pengisian Form Admin**

1. Pemilik mengisi form dengan data admin baru:

1. Nama Admin
2. Username
3. Password
4. Role (admin/pemilik)






3. **Validasi dan Penyimpanan**

1. Sistem memvalidasi input (username unik, dll)
2. Jika valid, sistem menyimpan data admin baru
3. Sistem menampilkan notifikasi sukses dan kembali ke daftar admin





### D. Alur Edit Admin

1. **Akses Form Edit Admin**

1. Pemilik mengklik tombol edit pada admin yang ingin diubah
2. Sistem menampilkan form edit dengan data admin yang dipilih



2. **Pengisian Form Edit**

1. Pemilik mengubah data yang diperlukan
2. Jika password dikosongkan, password tidak diubah



3. **Validasi dan Update**

1. Sistem memvalidasi input
2. Jika valid, sistem mengupdate data admin
3. Sistem menampilkan notifikasi sukses dan kembali ke daftar admin





### E. Alur Hapus Admin

1. **Konfirmasi Hapus**

1. Pemilik mengklik tombol hapus pada admin yang ingin dihapus
2. Sistem menampilkan konfirmasi penghapusan



2. **Proses Penghapusan**

1. Jika dikonfirmasi, sistem menghapus data admin
2. Sistem menampilkan notifikasi sukses dan kembali ke daftar admin





---

## 3. Alur Pengelolaan Obat dan Stok

### A. Struktur Data Obat

- `id` (Primary Key)
- `nama_obat` (Nama obat)
- `bpom` (Nomor BPOM)
- `harga` (Harga jual obat)
- `stok` (Jumlah stok tersedia)
- `supplier_id` (ID supplier yang memasok obat)


### B. Alur Melihat Daftar Obat

1. **Akses Menu Obat**

1. Admin/pemilik mengakses menu "Data Obat" di sidebar
2. Sistem menampilkan daftar obat dalam bentuk tabel
3. Tabel menampilkan nama obat, BPOM, harga, stok, dan supplier





### C. Alur Tambah Obat

1. **Akses Form Tambah Obat**

1. Admin/pemilik mengklik tombol "Tambah Obat" di halaman daftar obat
2. Sistem menampilkan form tambah obat



2. **Pengisian Form Obat**

1. Admin/pemilik mengisi form dengan data obat baru:

1. Nama Obat
2. Nomor BPOM
3. Harga
4. Stok Awal
5. Supplier (dipilih dari dropdown)






3. **Validasi dan Penyimpanan**

1. Sistem memvalidasi input
2. Jika valid, sistem menyimpan data obat baru
3. Sistem menampilkan notifikasi sukses dan kembali ke daftar obat





### D. Alur Edit Obat

1. **Akses Form Edit Obat**

1. Admin/pemilik mengklik tombol edit pada obat yang ingin diubah
2. Sistem menampilkan form edit dengan data obat yang dipilih



2. **Pengisian Form Edit**

1. Admin/pemilik mengubah data yang diperlukan
2. Perubahan stok dilakukan melalui form ini atau melalui transaksi



3. **Validasi dan Update**

1. Sistem memvalidasi input
2. Jika valid, sistem mengupdate data obat
3. Sistem menampilkan notifikasi sukses dan kembali ke daftar obat





### E. Alur Hapus Obat

1. **Konfirmasi Hapus**

1. Admin/pemilik mengklik tombol hapus pada obat yang ingin dihapus
2. Sistem menampilkan konfirmasi penghapusan



2. **Validasi Penghapusan**

1. Sistem memeriksa apakah obat memiliki riwayat transaksi
2. Jika ada, obat tidak dapat dihapus



3. **Proses Penghapusan**

1. Jika tidak ada transaksi, sistem menghapus data obat
2. Sistem menampilkan notifikasi sukses dan kembali ke daftar obat





### F. Alur Pengurangan Stok Obat

1. **Pengurangan Otomatis Saat Transaksi**

1. Saat transaksi penjualan, stok obat dikurangi sesuai jumlah yang dibeli
2. Proses ini dilakukan di controller `Transaksi` method `simpan()`


```php
// Update stok obat
$stok_baru = $obat['stok'] - $qty[$i];
$this->obatModel->update($obat_id[$i], ['stok' => $stok_baru]);
```


2. **Validasi Stok Saat Transaksi**

1. Sistem memvalidasi bahwa stok mencukupi sebelum transaksi
2. Jika stok tidak mencukupi, sistem menampilkan peringatan





### G. Alur Penambahan Stok Obat

1. **Penambahan Manual**

1. Admin/pemilik dapat menambah stok melalui form edit obat
2. Stok lama ditambah dengan jumlah yang diinput



2. **Penambahan via Pembelian dari Supplier**

1. Admin/pemilik mencatat pembelian obat dari supplier
2. Stok obat ditambah sesuai jumlah yang dibeli
3. Data pembelian disimpan untuk keperluan laporan





---

## 4. Alur Pengelolaan Supplier

### A. Struktur Data Supplier

- `id` (Primary Key)
- `nama_supplier` (Nama perusahaan supplier)
- `alamat` (Alamat lengkap supplier)
- `kota` (Kota tempat supplier berada)
- `telepon` (Nomor telepon kontak supplier)


### B. Alur Melihat Daftar Supplier

1. **Akses Menu Supplier**

1. Admin/pemilik mengakses menu "Data Supplier" di sidebar
2. Sistem menampilkan daftar supplier dalam bentuk tabel
3. Tabel menampilkan nama supplier, alamat, kota, telepon, dan tombol aksi





### C. Alur Tambah Supplier

1. **Akses Form Tambah Supplier**

1. Admin/pemilik mengklik tombol "Tambah Supplier" di halaman daftar supplier
2. Sistem menampilkan form tambah supplier



2. **Pengisian Form Supplier**

1. Admin/pemilik mengisi form dengan data supplier baru:

1. Nama Supplier
2. Alamat
3. Kota
4. Telepon






3. **Validasi dan Penyimpanan**

1. Sistem memvalidasi input berdasarkan aturan di model
2. Jika valid, sistem menyimpan data supplier baru
3. Sistem menampilkan notifikasi sukses dan kembali ke daftar supplier





### D. Alur Edit Supplier

1. **Akses Form Edit Supplier**

1. Admin/pemilik mengklik tombol edit pada supplier yang ingin diubah
2. Sistem menampilkan form edit dengan data supplier yang dipilih



2. **Pengisian Form Edit**

1. Admin/pemilik mengubah data yang diperlukan
2. Sistem memvalidasi dan mengupdate data supplier
3. Sistem menampilkan notifikasi sukses dan kembali ke daftar supplier





### E. Alur Hapus Supplier

1. **Konfirmasi Hapus**

1. Admin/pemilik mengklik tombol hapus pada supplier yang ingin dihapus
2. Sistem menampilkan konfirmasi penghapusan
3. Jika dikonfirmasi, sistem menghapus data supplier
4. Sistem menampilkan notifikasi sukses dan kembali ke daftar supplier





### F. Integrasi Supplier dengan Obat

1. **Relasi Supplier-Obat**

1. Setiap obat memiliki `supplier_id` yang merujuk ke supplier
2. Saat menambah/edit obat, admin/pemilik memilih supplier dari daftar yang ada



2. **Pembelian Obat dari Supplier**

1. Sistem memiliki fitur untuk menambah stok obat dari supplier
2. Proses ini dilakukan melalui menu "Beli Obat dari Supplier"
3. Saat pembelian, data supplier ditampilkan bersama dengan obat yang dipilih





---

## 5. Alur Pengelolaan Member

### A. Struktur Data Member

- `id` (Primary Key)
- `nama` (Nama member)
- `no_hp` (Nomor telepon member)
- `poin` (Jumlah poin yang dimiliki member)


### B. Alur Melihat Daftar Member

1. **Akses Menu Member**

1. Admin/pemilik mengakses menu "Data Member" di sidebar
2. Sistem menampilkan daftar member dalam bentuk tabel
3. Tabel menampilkan nama, no. HP, poin, dan tombol aksi (riwayat/edit/hapus)





### C. Alur Tambah Member

1. **Akses Form Tambah Member**

1. Admin/pemilik mengklik tombol "Tambah Member" di halaman daftar member
2. Sistem menampilkan form tambah member



2. **Pengisian Form Member**

1. Admin/pemilik mengisi form dengan data member baru:

1. Nama
2. No. HP



2. Poin awal otomatis diset 0 untuk member baru



3. **Validasi dan Penyimpanan**

1. Sistem memvalidasi input
2. Jika valid, sistem menyimpan data member baru
3. Sistem menampilkan notifikasi sukses dan kembali ke daftar member





### D. Alur Tambah Member via AJAX (Saat Transaksi)

1. **Akses Modal Tambah Member**

1. Kasir mengklik "Tambah Member baru" pada dropdown member di form transaksi
2. Modal form tambah member ditampilkan



2. **Pengisian Form dan Pengiriman AJAX**

1. Kasir mengisi nama dan no. HP member baru
2. Data dikirim via AJAX ke controller `Member` method `simpanAjax()`
3. Sistem menyimpan data member baru dan mengembalikan response JSON
4. Dropdown member di form transaksi diupdate dengan member baru





### E. Alur Edit Member

1. **Akses Form Edit Member**

1. Admin/pemilik mengklik tombol edit pada member yang ingin diubah
2. Sistem menampilkan form edit dengan data member yang dipilih



2. **Pengisian Form Edit**

1. Admin/pemilik dapat mengubah nama, no. HP, dan poin member
2. Sistem memvalidasi dan mengupdate data member
3. Sistem menampilkan notifikasi sukses dan kembali ke daftar member





### F. Alur Hapus Member

1. **Konfirmasi Hapus**

1. Admin/pemilik mengklik tombol hapus pada member yang ingin dihapus
2. Sistem menampilkan konfirmasi penghapusan



2. **Validasi Penghapusan**

1. Sistem memeriksa apakah member memiliki riwayat transaksi
2. Jika ada, member tidak dapat dihapus



3. **Proses Penghapusan**

1. Jika tidak ada transaksi, sistem menghapus data member
2. Sistem menampilkan notifikasi sukses dan kembali ke daftar member





### G. Alur Melihat Riwayat Transaksi Member

1. **Akses Halaman Riwayat**

1. Admin/pemilik mengklik tombol "Riwayat" pada member yang ingin dilihat riwayatnya
2. Sistem menampilkan data member dan riwayat transaksinya



2. **Tampilan Riwayat Transaksi**

1. Halaman menampilkan detail member (nama, no. HP, poin)
2. Tabel menampilkan riwayat transaksi dengan kolom:

1. Tanggal
2. Admin
3. Total
4. Poin Didapat
5. Tombol Detail (untuk melihat detail transaksi)








---

## 6. Alur Sistem Poin Member

### A. Perolehan Poin

1. **Perhitungan Poin**

1. Member mendapatkan poin saat melakukan transaksi
2. Setiap Rp 50.000 memberikan 1 poin
3. Poin dihitung di controller `Transaksi` method `simpan()`


```php
// Hitung poin yang didapat (1 poin untuk setiap Rp 50.000)
$poin_didapat = floor($subtotal / 50000);
```


2. **Penyimpanan Poin**

1. Poin yang didapat disimpan dalam tabel `transaksi` (field `poin_didapat`)
2. Total poin member diupdate di tabel `member` (field `poin`)





### B. Penggunaan Poin

1. **Pemilihan Member**

1. Saat transaksi, kasir memilih member dari dropdown
2. Sistem menampilkan poin yang tersedia untuk member tersebut



2. **Input Poin yang Digunakan**

1. Kasir memasukkan jumlah poin yang ingin digunakan
2. Sistem menghitung potongan harga (1 poin = Rp 1.000)
3. Maksimal penggunaan poin adalah 50% dari total belanja



3. **Validasi Penggunaan Poin**

1. Sistem memvalidasi bahwa poin yang digunakan tidak melebihi poin tersedia
2. Sistem memvalidasi bahwa potongan tidak melebihi 50% dari total belanja



4. **Penyimpanan Penggunaan Poin**

1. Poin yang digunakan disimpan dalam tabel `transaksi` (field `poin_digunakan`)
2. Potongan harga disimpan dalam tabel `transaksi` (field `potongan_harga`)





### C. Update Poin Member

1. **Perhitungan Poin Akhir**

1. Setelah transaksi, poin member diupdate
2. Poin akhir = poin awal - poin digunakan + poin didapat


```php
$poin_akhir = $member['poin'] - $poin_digunakan + $poin_didapat;
```


2. **Update Database**

1. Poin akhir diupdate di tabel `member`


```php
$this->memberModel->update($member_id, ['poin' => $poin_akhir]);
```




### D. Tampilan Poin di Struk

1. **Informasi Poin pada Struk**

1. Struk transaksi menampilkan informasi poin:

1. Poin yang didapat dari transaksi
2. Poin yang digunakan (jika ada)
3. Potongan harga dari penggunaan poin (jika ada)








---

## 7. Alur Transaksi

### A. Struktur Data Transaksi

1. **Tabel Transaksi**

1. `id` (Primary Key)
2. `tanggal_transaksi` (Waktu transaksi)
3. `admin_id` (ID admin yang melakukan transaksi)
4. `nama_pembeli` (Nama pembeli)
5. `member_id` (ID member, nullable)
6. `total` (Total pembayaran)
7. `poin_didapat` (Poin yang didapat)
8. `poin_digunakan` (Poin yang digunakan)
9. `potongan_harga` (Potongan harga dari poin)



2. **Tabel Detail Transaksi**

1. `id` (Primary Key)
2. `transaksi_id` (ID transaksi)
3. `obat_id` (ID obat)
4. `harga_saat_ini` (Harga obat saat transaksi)
5. `qty` (Jumlah obat yang dibeli)





### B. Alur Melihat Daftar Transaksi

1. **Akses Menu Transaksi**

1. Admin/pemilik mengakses menu "Transaksi" di sidebar
2. Sistem menampilkan form filter tanggal (default: bulan ini)
3. Sistem menampilkan daftar transaksi sesuai filter dalam bentuk tabel



2. **Filter Transaksi**

1. Admin/pemilik dapat mengubah rentang tanggal filter
2. Sistem menampilkan transaksi yang sesuai dengan filter





### C. Alur Tambah Transaksi

1. **Akses Form Tambah Transaksi**

1. Admin/pemilik mengklik tombol "Tambah Transaksi" di halaman daftar transaksi
2. Sistem menampilkan form tambah transaksi



2. **Pengisian Data Pembeli**

1. Admin/pemilik mengisi nama pembeli
2. Admin/pemilik dapat memilih member dari dropdown (opsional)
3. Jika member dipilih, nama pembeli otomatis diisi dengan nama member



3. **Pengisian Detail Obat**

1. Admin/pemilik memilih obat dari dropdown
2. Sistem menampilkan harga obat dan stok tersedia
3. Admin/pemilik mengisi jumlah obat yang dibeli
4. Sistem menghitung subtotal (harga × jumlah)
5. Admin/pemilik dapat menambah baris obat dengan tombol "Tambah Obat"



4. **Penggunaan Poin Member (jika ada)**

1. Jika member dipilih dan memiliki poin, sistem menampilkan bagian poin
2. Admin/pemilik dapat mengisi jumlah poin yang digunakan
3. Sistem menghitung potongan harga dan memvalidasi penggunaan poin



5. **Perhitungan Total**

1. Sistem menghitung subtotal dari semua obat
2. Sistem menghitung potongan dari penggunaan poin (jika ada)
3. Sistem menghitung total akhir (subtotal - potongan)



6. **Simpan Transaksi**

1. Admin/pemilik mengklik tombol "Simpan Transaksi"
2. Sistem memvalidasi input (stok mencukupi, dll)
3. Sistem menyimpan data transaksi dan detail transaksi
4. Sistem mengupdate stok obat
5. Sistem mengupdate poin member (jika ada)
6. Sistem mengarahkan ke halaman struk transaksi





### D. Alur Cetak Struk

1. **Tampilan Struk**

1. Sistem menampilkan struk transaksi dengan informasi:

1. Nomor transaksi
2. Tanggal dan waktu
3. Nama kasir (admin)
4. Nama pembeli
5. Detail member dan poin (jika ada)
6. Daftar obat yang dibeli (nama, harga, jumlah, subtotal)
7. Subtotal keseluruhan
8. Potongan dari poin (jika ada)
9. Total akhir






2. **Cetak Struk**

1. Admin/pemilik dapat mencetak struk dengan mengklik tombol "Cetak"
2. Sistem menggunakan CSS print media untuk format cetak





### E. Alur Detail Transaksi

1. **Akses Detail Transaksi**

1. Admin/pemilik mengklik tombol "Detail" pada transaksi di daftar transaksi
2. Sistem menampilkan detail transaksi termasuk:

1. Informasi transaksi (tanggal, admin, pembeli, member)
2. Daftar obat yang dibeli
3. Informasi pembayaran dan poin








### F. Alur Hapus Transaksi

1. **Konfirmasi Hapus**

1. Admin/pemilik mengklik tombol hapus pada transaksi yang ingin dihapus
2. Sistem menampilkan konfirmasi penghapusan



2. **Proses Penghapusan**

1. Jika dikonfirmasi, sistem:

1. Mengembalikan stok obat
2. Mengembalikan poin member (jika ada)
3. Menghapus detail transaksi
4. Menghapus data transaksi



2. Sistem menampilkan notifikasi sukses dan kembali ke daftar transaksi





---

## Catatan Penting:

- Dokumen ini berisi alur proses utama dalam sistem Apotek Kita Farma
- Implementasi teknis dapat dilihat pada kode sumber aplikasi
- Alur dapat dimodifikasi sesuai kebutuhan pengembangan lebih lanjut


---

*Dokumen ini dibuat untuk keperluan dokumentasi Sistem Informasi Apotek Kita Farma*

*Terakhir diperbarui: `<?= date('d-m-Y'); ?>`*

---

Setelah menyalin konten di atas ke Microsoft Word, Anda dapat:

1. Menambahkan header dan footer
2. Menyesuaikan format heading dan subheading
3. Menambahkan nomor halaman
4. Membuat daftar isi otomatis
5. Menambahkan gambar screenshot jika diperlukan
6. Menyesuaikan font dan ukuran teks


Dengan begitu, Anda akan memiliki dokumentasi lengkap tentang alur sistem Apotek Kita Farma dalam format DOCX yang profesional.