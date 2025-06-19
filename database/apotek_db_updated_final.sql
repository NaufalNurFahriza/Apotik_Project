-- =====================================================
-- DATABASE APOTEK KITA FARMA v1.3 - UPDATED
-- Data Obat Terbaru dengan Produk Farmasi Indonesia
-- Data Transaksi: 1 Januari 2025 - 15 Juni 2025
-- =====================================================

-- Gunakan database
CREATE DATABASE IF NOT EXISTS apotek_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE apotek_db;

-- =====================================================
-- STRUKTUR TABEL
-- =====================================================

-- 1. Tabel user (TTK & Pemilik)
CREATE TABLE IF NOT EXISTS user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('pemilik', 'ttk') NOT NULL DEFAULT 'ttk'
);

-- 2. Tabel supplier
CREATE TABLE IF NOT EXISTS supplier (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_supplier VARCHAR(100) NOT NULL,
  alamat TEXT NOT NULL,
  kota VARCHAR(50) NOT NULL,
  telepon VARCHAR(15) NOT NULL
);

-- 3. Tabel obat
CREATE TABLE IF NOT EXISTS obat (
  id INT AUTO_INCREMENT PRIMARY KEY,
  bpom VARCHAR(20) NOT NULL UNIQUE,
  nama_obat VARCHAR(100) NOT NULL,
  produsen VARCHAR(100) NOT NULL,
  supplier_id INT NOT NULL,
  kategori ENUM('resep', 'non-resep') NOT NULL DEFAULT 'non-resep',
  satuan VARCHAR(20) NOT NULL,
  harga_beli INT NOT NULL,
  harga_jual INT NOT NULL,
  stok INT NOT NULL DEFAULT 0,
  FOREIGN KEY (supplier_id) REFERENCES supplier(id) ON DELETE CASCADE
);

-- 4. Tabel member
CREATE TABLE IF NOT EXISTS member (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  no_hp VARCHAR(15) NOT NULL,
  poin INT DEFAULT 0
);

-- 5. Tabel transaksi_penjualan
CREATE TABLE IF NOT EXISTS transaksi_penjualan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tanggal_transaksi DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  user_id INT NOT NULL,
  nama_pembeli VARCHAR(100) NOT NULL,
  member_id INT DEFAULT NULL,
  total INT NOT NULL,
  poin_didapat INT DEFAULT 0,
  poin_digunakan INT DEFAULT 0,
  potongan_harga INT DEFAULT 0,
  FOREIGN KEY (user_id) REFERENCES user(id),
  FOREIGN KEY (member_id) REFERENCES member(id)
);

-- 6. Tabel detail_penjualan
CREATE TABLE IF NOT EXISTS detail_penjualan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  transaksi_id INT NOT NULL,
  obat_id INT NOT NULL,
  qty INT NOT NULL,
  harga_saat_ini INT NOT NULL,
  FOREIGN KEY (transaksi_id) REFERENCES transaksi_penjualan(id),
  FOREIGN KEY (obat_id) REFERENCES obat(id)
);

-- 7. Tabel transaksi_pembelian
CREATE TABLE IF NOT EXISTS transaksi_pembelian (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  supplier_id INT NOT NULL,
  tanggal DATE NOT NULL,
  nomor_faktur VARCHAR(50) NOT NULL UNIQUE,
  total INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES user(id),
  FOREIGN KEY (supplier_id) REFERENCES supplier(id)
);

-- 8. Tabel detail_pembelian
CREATE TABLE IF NOT EXISTS detail_pembelian (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pembelian_id INT NOT NULL,
  obat_id INT NOT NULL,
  qty INT NOT NULL,
  harga_beli INT NOT NULL,
  nomor_batch VARCHAR(50) NOT NULL,
  expired_date DATE NOT NULL,
  satuan VARCHAR(20) NOT NULL,
  FOREIGN KEY (pembelian_id) REFERENCES transaksi_pembelian(id),
  FOREIGN KEY (obat_id) REFERENCES obat(id)
);

-- =====================================================
-- DATA MASTER
-- =====================================================

-- Insert data user (TTK & Pemilik)
INSERT INTO user (nama, username, password, role) VALUES
('Kevin Bimo', 'kevin_bimo', 'kevin123', 'pemilik'),
('Sari Dewi', 'sari_ttk', 'sari123', 'ttk'),
('Ahmad Fauzi', 'ahmad_ttk', 'ahmad123', 'ttk'),
('Rina Sari', 'rina_ttk', 'rina123', 'ttk');

-- Insert data supplier
INSERT INTO supplier (nama_supplier, alamat, kota, telepon) VALUES
('PT Kimia Farma', 'Jl. Veteran No. 9', 'Jakarta', '021-3441991'),
('PT Kalbe Farma', 'Jl. Let. Jend. Suprapto Kav. 4', 'Jakarta', '021-42873888'),
('PT Dexa Medica', 'Jl. Bambang Utoyo No. 138', 'Palembang', '0711-710961'),
('PT Pharos Indonesia', 'Jl. Kyai Maja No. 1', 'Jakarta', '021-7394001'),
('PT Novartis Indonesia', 'Jl. TB Simatupang Kav. 88', 'Jakarta', '021-7884-0888'),
('PT Pfizer Indonesia', 'Jl. Jend. Sudirman Kav. 28', 'Jakarta', '021-5212-1000'),
('PT Tempo Scan Pacific', 'Jl. H.R. Rasuna Said Blok X-2 Kav. 6', 'Jakarta', '021-5794-7788'),
('PT Guardian Pharmatama', 'Jl. Raya Bogor KM 27', 'Jakarta', '021-8710-8888');

-- Insert data obat terbaru (100 produk farmasi Indonesia)
INSERT INTO obat (id, bpom, nama_obat, produsen, supplier_id, kategori, satuan, harga_beli, harga_jual, stok) VALUES
(1, 'DTL1831410010A1', 'Lozentrin', 'Lloyd Pharma Indonesia', 1, 'resep', 'Tablet', 1700, 2500, 250),
(2, 'GKL0805043117A1', 'Spironolactone', 'Dexa Medica', 3, 'resep', 'Tablet', 3800, 5000, 180),
(3, 'DBL1928910504B1', 'Ramagesic Forte', 'Rama Emerald Multi Sukses', 2, 'resep', 'Kaplet', 3400, 5200, 200),
(4, 'GPL2531808910A1', 'Clonazepam', 'Sunthi Sepuri', 4, 'resep', 'Tablet', 2900, 3900, 150),
(5, 'GKL9807103901B1', 'Piroxicam', 'First Medipharma', 1, 'resep', 'Kapsul', 2600, 3900, 160),
(6, 'DKL1943500617C1', 'Q-Pin', 'Amarox Pharma Global', 2, 'resep', 'Tablet', 2200, 3400, 140),
(7, 'GKL1943500517A1', 'Quetiapine Fumarate', 'Amarox Pharma Global', 3, 'resep', 'Tablet', 2400, 4000, 120),
(8, 'DKL9613306710A1', 'Diadium', 'Lapi Laboratories', 4, 'resep', 'Tablet', 1900, 2500, 300),
(9, 'DKL2019934401A1', 'Noleptic', 'Phapros Tbk', 1, 'resep', 'Kapsul', 3000, 4000, 180),
(10, 'DBL0133203310A1', 'Procet', 'Promedrahardjo Farmasi Industri', 2, 'resep', 'Tablet', 1900, 2900, 220),
(11, 'DKI0361300110A1', 'Postinor-2', 'Tunggal Idaman Abdi', 3, 'resep', 'Tablet', 3100, 4100, 100),
(12, 'DKL7821630806A1', 'Euphyllin Retard', 'Pharos Indonesia', 4, 'resep', 'Kaplet', 3500, 4700, 140),
(13, 'GKL8116900201A2', 'Chloramphenicol', 'Mutiara Mukti Farma', 1, 'resep', 'Kapsul', 3400, 5200, 160),
(14, 'DPL9931806610B1', 'Calmlet', 'Sunthi Sepuri', 2, 'resep', 'Tablet', 2300, 3300, 200),
(15, 'DKL1413319201A1', 'Obeslim', 'Lapi Laboratories', 3, 'non-resep', 'Kapsul', 1900, 2700, 180),
(16, 'GKL1905061714B1', 'Divalproex Sodium', 'Dexa Medica', 3, 'resep', 'Tablet', 4900, 7000, 120),
(17, 'GKL2540102004A1', 'Allopurinol', 'Harbat Farma', 4, 'non-resep', 'Kaplet', 3100, 4200, 250),
(18, 'DKL2513031717A1', 'Noterol 10', 'Konimex', 1, 'non-resep', 'Tablet', 3200, 4600, 200),
(19, 'GKL9005007310A1', 'Diltiazem Hcl', 'Dexa Medica', 3, 'resep', 'Tablet', 4400, 6700, 150),
(20, 'DKL0111618501A1', 'Kalxetin 20', 'Kalbe Farma', 2, 'non-resep', 'Kapsul', 4400, 5700, 140),
(21, 'GKL1421644601A1', 'Cefadroxil Monohydrate', 'Pharos Indonesia', 4, 'resep', 'Kapsul', 3000, 4300, 180),
(22, 'GKL1316702504B1', 'Amlodipine Besilate', 'Mulia Farma Suci', 1, 'resep', 'Kaplet', 2300, 3100, 300),
(23, 'DKL1616703317A2', 'Genflam 50', 'Mulia Farma Suci', 2, 'non-resep', 'Tablet', 3100, 5100, 220),
(24, 'DBL2330902704B1', 'Alphamol 500', 'Molex Ayus', 3, 'non-resep', 'Kaplet', 2900, 4200, 350),
(25, 'GKL2140407809A1', 'Mefenamic Acid', 'Prima Medika Laboratories', 4, 'non-resep', 'Kaplet', 2900, 4700, 280),
(26, 'DKI2561100317B1', 'Etinib', 'Ferron Par Pharmaceuticals', 1, 'non-resep', 'Tablet', 2300, 3900, 160),
(27, 'DKL0826111717A1', 'Voldiamer B6', 'Tunggal Idaman Abdi', 2, 'non-resep', 'Tablet', 1600, 2000, 400),
(28, 'DKL1316702604B1', 'Genvask 10', 'Mulia Farma Suci', 3, 'non-resep', 'Kaplet', 2900, 4200, 200),
(29, 'DKI2523900316A1', 'Cataflam', 'Novartis Indonesia', 5, 'non-resep', 'Tablet', 2100, 3100, 320),
(30, 'DKL2031547410A1', 'Mierin', 'Pratapa Nirmala', 4, 'non-resep', 'Tablet', 3100, 4800, 180),
(31, 'GKL9805024110A1', 'Ketoconazole', 'Dexa Medica', 3, 'resep', 'Tablet', 3200, 5300, 160),
(32, 'DTL0204419110A1', 'Librofed', 'Dankos Farma', 1, 'non-resep', 'Tablet', 3100, 4300, 200),
(33, 'DKL0511636817A1', 'Cholestat', 'Kalbe Farma', 2, 'non-resep', 'Tablet', 2400, 3300, 250),
(34, 'DKI2190702117B1', 'Eliquis', 'Pfizer Indonesia', 6, 'non-resep', 'Tablet', 3100, 4000, 120),
(35, 'DKL0908015116A1', 'Vomil B6', 'Guardian Pharmatama', 8, 'non-resep', 'Tablet', 2800, 4300, 180),
(36, 'GKL0506504504A1', 'Dexamethasone', 'Errita Pharma', 1, 'resep', 'Kaplet', 2500, 3200, 220),
(37, 'GKL0205031510A1', 'Glimepiride', 'Dexa Medica', 3, 'resep', 'Tablet', 4500, 6100, 140),
(38, 'DKL9604413909A1', 'Zithrax', 'Dankos Farma', 2, 'resep', 'Kaplet', 2300, 3300, 180),
(39, 'GPL1733314710A1', 'Phenobarbital', 'Mersifarma Tirmaku Mercusana', 4, 'resep', 'Tablet', 1600, 2300, 200),
(40, 'DKL1421643906A1', 'Adecco Xr', 'Pharos Indonesia', 4, 'non-resep', 'Kaplet', 3000, 4700, 160),
(41, 'DKL0505513574A1', 'Nitral', 'Actavis Indonesia', 1, 'non-resep', 'Tablet', 1900, 2500, 300),
(42, 'DKL1105045910B1', 'Canderin 16', 'Dexa Medica', 3, 'non-resep', 'Tablet', 2500, 3800, 200),
(43, 'DKL0604425710A1', 'Alodan', 'Dankos Farma', 2, 'non-resep', 'Tablet', 3000, 5000, 180),
(44, 'DKL1916710001A1', 'Gencobal', 'Mulia Farma Suci', 1, 'non-resep', 'Kapsul', 2500, 3400, 220),
(45, 'GKL2007924204A1', 'Methylprednisolone', 'Harsen Laboratories', 4, 'resep', 'Kaplet', 2700, 4400, 160),
(46, 'DKL9608005717A1', 'Exaflam-25', 'Guardian Pharmatama', 8, 'non-resep', 'Tablet', 1700, 2800, 280),
(47, 'DKL8317201010A2', 'Dexanel', 'Nellco Indopharma', 2, 'non-resep', 'Tablet', 3400, 5100, 150),
(48, 'GKL2012431417B1', 'Entecavir Monohydrate', 'Kimia Farma Tbk', 1, 'resep', 'Tablet', 2500, 3500, 140),
(49, 'DKL0621632501B1', 'Drovax', 'Pharos Indonesia', 4, 'non-resep', 'Kapsul', 3300, 5600, 120),
(50, 'DKL1209320910A1', 'Kurtigo', 'Ikapharmindo Putramas Tbk', 3, 'non-resep', 'Tablet', 2100, 3100, 200),
(51, 'GKL1916709901A1', 'Mecobalamin', 'Mulia Farma Suci', 1, 'non-resep', 'Kapsul', 2100, 2800, 250),
(52, 'GKL1105046010A1', 'Candesartan Cilexetil', 'Dexa Medica', 3, 'non-resep', 'Tablet', 3900, 5400, 160),
(53, 'DKL1328206410A1', 'Maxprinol', 'Simex Pharmaceutical Indonesia', 2, 'non-resep', 'Tablet', 2300, 3500, 180),
(54, 'DTL1928916604A1', 'Pro-Ceta', 'Rama Emerald Multi Sukses', 4, 'non-resep', 'Kaplet', 3300, 4900, 140),
(55, 'DKI2258501717B1', 'Lorlak', 'Pfizer Indonesia', 6, 'non-resep', 'Tablet', 2700, 3500, 160),
(56, 'DKL0305033910A1', 'Gluvas 1', 'Dexa Medica', 3, 'non-resep', 'Tablet', 4300, 6200, 120),
(57, 'GKL2306514409A1', 'Ciprofloxacin Hydrochloride', 'Errita Pharma', 1, 'resep', 'Kaplet', 5000, 8100, 100),
(58, 'DKL2415636210A1', 'Prazox 0,5', 'Meprofarm', 2, 'non-resep', 'Tablet', 2900, 4600, 180),
(59, 'GKL1415716517A1', 'Irbesartan', 'Etercon Pharma', 3, 'non-resep', 'Tablet', 1900, 3100, 220),
(60, 'DKL9110901801B2', 'Wiros', 'Itrasal', 4, 'non-resep', 'Kapsul', 3400, 5100, 140),
(61, 'DKI2043400817B1', 'Farcor 7,5', 'Pratapa Nirmala', 1, 'non-resep', 'Tablet', 2600, 4100, 160),
(62, 'DKI2043400817A1', 'Farcor 5', 'Pratapa Nirmala', 2, 'non-resep', 'Tablet', 2200, 2900, 200),
(63, 'DKL1131530914A1', 'Miozidine Mr', 'An Lambat', 3, 'non-resep', 'Tablet', 3100, 4300, 150),
(64, 'GKL2533219817A1', 'Rosuvastatin Calcium', 'Promedrahardjo Farmasi Industri', 4, 'non-resep', 'Tablet', 2500, 3600, 180),
(65, 'DKL2415636210B1', 'Prazox 1', 'Meprofarm', 1, 'non-resep', 'Tablet', 2600, 3400, 170),
(66, 'DKL0133502517B1', 'Irvell', 'Novell Pharmaceutical Laboratories', 2, 'non-resep', 'Tablet', 2600, 3400, 160),
(67, 'DKL9409309601B1', 'Nomika 100', 'Ikapharmindo Putramas Tbk', 3, 'non-resep', 'Kapsul', 1800, 2600, 250),
(68, 'DTL9930702510A1', 'Oskadon Sp', 'Supra Ferbindo Farma', 4, 'non-resep', 'Tablet', 2200, 3200, 300),
(69, 'DKL1321205417B1', 'Recansa', 'Kalventis Sinergi Farma', 1, 'non-resep', 'Tablet', 2400, 3800, 180),
(70, 'DKL1221018701A1', 'Gabasant 300', 'Pyridam Farma Tbk', 2, 'resep', 'Kapsul', 3000, 4400, 140),
(71, 'DKL0033400704A1', 'Soldextam', 'Solas Langgeng Sejahtera', 3, 'non-resep', 'Kaplet', 3300, 4900, 160),
(72, 'DKL8324201004A2', 'Primadol', 'Soho Industri Pharmasi', 4, 'non-resep', 'Kaplet', 3100, 4500, 180),
(73, 'GKL2505069217A1', 'Linagliptin', 'Dexa Medica', 3, 'non-resep', 'Tablet', 3300, 5500, 120),
(74, 'GKL9405013010A1', 'Propylthiouracil', 'Dexa Medica', 3, 'non-resep', 'Tablet', 2200, 3200, 200),
(75, 'DBL9933400304A1', 'Cafmosol', 'Solas Langgeng Sejahtera', 1, 'non-resep', 'Kaplet', 1700, 2700, 280),
(76, 'GKL0305032417A1', 'Bisoprolol Fumarate', 'Dexa Medica', 3, 'non-resep', 'Tablet', 3200, 4300, 160),
(77, 'DTL0233401404B1', 'Lodecon Forte', 'Solas Langgeng Sejahtera', 2, 'non-resep', 'Kaplet', 2600, 4300, 180),
(78, 'GKL2544301617A1', 'Gefitinib', 'Global Onkolab Farma', 4, 'non-resep', 'Tablet', 2700, 4500, 100),
(79, 'DKL2505069117A1', 'Tralin', 'Dexa Medica', 3, 'non-resep', 'Tablet', 2800, 4300, 150),
(80, 'DKL0605041617A1', 'Biscor', 'Dexa Medica', 3, 'non-resep', 'Tablet', 5000, 8200, 80),
(81, 'DKL2043800301A1', 'Lafsefik 100', 'Lembaga Farmasi Angkatan Udara Roostyan Effe', 1, 'non-resep', 'Kapsul', 2400, 3600, 160),
(82, 'DTL1122702410B1', 'Neo Rheumacyl', 'Tempo Scan Pacific Tbk', 7, 'non-resep', 'Tablet', 1600, 2400, 350),
(83, 'DKL7219807303A1', 'Dilantin', 'Pfizer Indonesia', 6, 'non-resep', 'Kapsul', 1900, 3100, 180),
(84, 'DKL9409309015B1', 'Ikalep', 'Ikapharmindo Putramas Tbk', 2, 'non-resep', 'Tablet', 2200, 2900, 220),
(85, 'DKL2318834909A1', 'Tropicet', 'Otto Pharmaceutical Industries', 4, 'non-resep', 'Kaplet', 3000, 4700, 160),
(86, 'DTL1121205217B1', 'Telfast', 'Kalventis Sinergi Farma', 1, 'non-resep', 'Tablet', 2800, 4500, 140),
(87, 'DKL2506317510B1', 'Ervask', 'Erlangga Edi Laboratories', 3, 'non-resep', 'Tablet', 1900, 2500, 250),
(88, 'GKL2543011801A1', 'Erdosteine', 'Nulab Pharmaceutical Indonesia', 2, 'non-resep', 'Kapsul', 2300, 3500, 180),
(89, 'DKI1951400167A1', 'Salmeflo', 'Lloyd Pharma Indonesia', 4, 'non-resep', 'Kapsul', 1900, 2900, 200),
(90, 'DTL2407813204A1', 'Hufagesic Dp', 'Gratia Husada Farma', 1, 'non-resep', 'Kaplet', 1800, 2500, 280),
(91, 'DKL2406422110A1', 'Ersoprinosine', 'Erlimpex', 2, 'non-resep', 'Tablet', 3200, 5200, 140),
(92, 'GKL7215206504A1', 'Prednisone', 'Mega Esa Farma', 3, 'resep', 'Kaplet', 1900, 3000, 200),
(93, 'DKL2533219717A1', 'Medvast', 'Promedrahardjo Farmasi Industri', 4, 'non-resep', 'Tablet', 2400, 3400, 180),
(94, 'DKL2043006017A1', 'Nudone', 'Nulab Pharmaceutical Indonesia', 1, 'non-resep', 'Tablet', 2500, 3400, 160),
(95, 'DKL9608006210A1', 'Forasma', 'Guardian Pharmatama', 8, 'non-resep', 'Tablet', 2400, 3200, 180),
(96, 'DKL9409309015B2', 'Ikalep Plus', 'Ikapharmindo Putramas Tbk', 2, 'non-resep', 'Tablet', 2200, 2900, 220),
(97, 'DKL2318834909B1', 'Tropicet Forte', 'Otto Pharmaceutical Industries', 4, 'non-resep', 'Kaplet', 3000, 4700, 160),
(98, 'DTL1121205217C1', 'Telfast HD', 'Kalventis Sinergi Farma', 1, 'non-resep', 'Tablet', 2800, 4500, 140),
(99, 'DKL2506317510C1', 'Ervask Plus', 'Erlangga Edi Laboratories', 3, 'non-resep', 'Tablet', 1900, 2500, 250),
(100, 'GKL2543011801B1', 'Erdosteine Plus', 'Nulab Pharmaceutical Indonesia', 2, 'non-resep', 'Kapsul', 2300, 3500, 180);

-- Insert data member
INSERT INTO member (nama, no_hp, poin) VALUES
('Budi Santoso', '081234567890', 150),
('Siti Nurhaliza', '081234567891', 200),
('Ahmad Rahman', '081234567892', 75),
('Dewi Sartika', '081234567893', 300),
('Rudi Hermawan', '081234567894', 50),
('Maya Sari', '081234567895', 180),
('Andi Wijaya', '081234567896', 120),
('Lina Marlina', '081234567897', 250),
('Doni Pratama', '081234567898', 90),
('Rina Wati', '081234567899', 160),
('Bambang Sutrisno', '081234567800', 220),
('Indira Sari', '081234567801', 80),
('Fajar Nugroho', '081234567802', 340),
('Ratna Dewi', '081234567803', 110),
('Hendra Wijaya', '081234567804', 190),
('Sari Indah', '081234567805', 270),
('Agus Setiawan', '081234567806', 130),
('Wulan Dari', '081234567807', 210),
('Eko Prasetyo', '081234567808', 95),
('Fitri Handayani', '081234567809', 175);

-- =====================================================
-- DATA TRANSAKSI PENJUALAN (1 Jan - 15 Jun 2025)
-- =====================================================

-- Insert transaksi penjualan dengan data yang disesuaikan dengan obat baru
INSERT INTO transaksi_penjualan (tanggal_transaksi, user_id, nama_pembeli, member_id, total, poin_didapat, poin_digunakan, potongan_harga) VALUES
-- Januari 2025
('2025-01-02 09:15:00', 2, 'Budi Santoso', 1, 25000, 25, 0, 0),
('2025-01-02 10:30:00', 3, 'Walk-in Customer', NULL, 15000, 0, 0, 0),
('2025-01-03 08:45:00', 2, 'Siti Nurhaliza', 2, 42000, 42, 50, 5000),
('2025-01-03 14:20:00', 4, 'Ahmad Rahman', 3, 18000, 18, 0, 0),
('2025-01-05 11:10:00', 2, 'Dewi Sartika', 4, 65000, 65, 100, 10000),
('2025-01-07 16:30:00', 3, 'Walk-in Customer', NULL, 12000, 0, 0, 0),
('2025-01-08 09:00:00', 2, 'Maya Sari', 6, 38000, 38, 0, 0),
('2025-01-10 13:45:00', 4, 'Andi Wijaya', 7, 28000, 28, 50, 5000),
('2025-01-12 10:15:00', 2, 'Lina Marlina', 8, 55000, 55, 100, 10000),
('2025-01-15 15:20:00', 3, 'Doni Pratama', 9, 22000, 22, 0, 0),
('2025-01-18 08:30:00', 2, 'Rina Wati', 10, 35000, 35, 0, 0),
('2025-01-20 12:00:00', 4, 'Bambang Sutrisno', 11, 48000, 48, 80, 8000),
('2025-01-22 14:45:00', 2, 'Indira Sari', 12, 19000, 19, 0, 0),
('2025-01-25 11:30:00', 3, 'Fajar Nugroho', 13, 72000, 72, 150, 15000),
('2025-01-28 16:15:00', 2, 'Ratna Dewi', 14, 31000, 31, 0, 0),

-- Februari 2025
('2025-02-01 09:20:00', 4, 'Hendra Wijaya', 15, 44000, 44, 0, 0),
('2025-02-03 10:45:00', 2, 'Sari Indah', 16, 26000, 26, 0, 0),
('2025-02-05 13:30:00', 3, 'Agus Setiawan', 17, 39000, 39, 50, 5000),
('2025-02-08 08:15:00', 2, 'Wulan Dari', 18, 33000, 33, 0, 0),
('2025-02-10 15:00:00', 4, 'Eko Prasetyo', 19, 21000, 21, 0, 0),
('2025-02-12 11:45:00', 2, 'Fitri Handayani', 20, 47000, 47, 80, 8000),
('2025-02-15 14:20:00', 3, 'Walk-in Customer', NULL, 16000, 0, 0, 0),
('2025-02-18 09:30:00', 2, 'Budi Santoso', 1, 29000, 29, 0, 0),
('2025-02-20 12:15:00', 4, 'Siti Nurhaliza', 2, 52000, 52, 100, 10000),
('2025-02-22 16:45:00', 2, 'Ahmad Rahman', 3, 24000, 24, 0, 0),
('2025-02-25 10:00:00', 3, 'Dewi Sartika', 4, 61000, 61, 120, 12000),
('2025-02-27 13:15:00', 2, 'Maya Sari', 6, 36000, 36, 0, 0),

-- Maret 2025
('2025-03-02 08:45:00', 4, 'Andi Wijaya', 7, 43000, 43, 60, 6000),
('2025-03-05 11:20:00', 2, 'Lina Marlina', 8, 28000, 28, 0, 0),
('2025-03-08 14:30:00', 3, 'Doni Pratama', 9, 35000, 35, 0, 0),
('2025-03-10 09:15:00', 2, 'Rina Wati', 10, 49000, 49, 80, 8000),
('2025-03-12 15:45:00', 4, 'Walk-in Customer', NULL, 18000, 0, 0, 0),
('2025-03-15 10:30:00', 2, 'Bambang Sutrisno', 11, 56000, 56, 100, 10000),
('2025-03-18 13:00:00', 3, 'Indira Sari', 12, 22000, 22, 0, 0),
('2025-03-20 16:20:00', 2, 'Fajar Nugroho', 13, 67000, 67, 140, 14000),
('2025-03-22 08:50:00', 4, 'Ratna Dewi', 14, 31000, 31, 0, 0),
('2025-03-25 12:40:00', 2, 'Hendra Wijaya', 15, 45000, 45, 0, 0),
('2025-03-28 15:10:00', 3, 'Sari Indah', 16, 38000, 38, 50, 5000),
('2025-03-30 11:25:00', 2, 'Agus Setiawan', 17, 27000, 27, 0, 0),

-- April 2025
('2025-04-02 09:40:00', 4, 'Wulan Dari', 18, 41000, 41, 0, 0),
('2025-04-05 14:15:00', 2, 'Eko Prasetyo', 19, 23000, 23, 0, 0),
('2025-04-08 10:50:00', 3, 'Fitri Handayani', 20, 54000, 54, 90, 9000),
('2025-04-10 13:25:00', 2, 'Walk-in Customer', NULL, 19000, 0, 0, 0),
('2025-04-12 16:00:00', 4, 'Budi Santoso', 1, 37000, 37, 0, 0),
('2025-04-15 08:35:00', 2, 'Siti Nurhaliza', 2, 46000, 46, 70, 7000),
('2025-04-18 11:45:00', 3, 'Ahmad Rahman', 3, 32000, 32, 0, 0),
('2025-04-20 15:30:00', 2, 'Dewi Sartika', 4, 58000, 58, 110, 11000),
('2025-04-22 09:20:00', 4, 'Maya Sari', 6, 25000, 25, 0, 0),
('2025-04-25 12:55:00', 2, 'Andi Wijaya', 7, 42000, 42, 60, 6000),
('2025-04-28 14:40:00', 3, 'Lina Marlina', 8, 39000, 39, 0, 0),
('2025-04-30 10:10:00', 2, 'Doni Pratama', 9, 29000, 29, 0, 0),

-- Mei 2025
('2025-05-02 13:50:00', 4, 'Rina Wati', 10, 51000, 51, 80, 8000),
('2025-05-05 08:25:00', 2, 'Bambang Sutrisno', 11, 34000, 34, 0, 0),
('2025-05-08 11:15:00', 3, 'Indira Sari', 12, 26000, 26, 0, 0),
('2025-05-10 15:40:00', 2, 'Fajar Nugroho', 13, 63000, 63, 130, 13000),
('2025-05-12 09:05:00', 4, 'Ratna Dewi', 14, 28000, 28, 0, 0),
('2025-05-15 12:30:00', 2, 'Hendra Wijaya', 15, 47000, 47, 0, 0),
('2025-05-18 16:25:00', 3, 'Sari Indah', 16, 35000, 35, 0, 0),
('2025-05-20 10:45:00', 2, 'Agus Setiawan', 17, 40000, 40, 50, 5000),
('2025-05-22 14:10:00', 4, 'Wulan Dari', 18, 33000, 33, 0, 0),
('2025-05-25 08:55:00', 2, 'Eko Prasetyo', 19, 24000, 24, 0, 0),
('2025-05-28 13:20:00', 3, 'Fitri Handayani', 20, 49000, 49, 80, 8000),
('2025-05-30 11:35:00', 2, 'Walk-in Customer', NULL, 21000, 0, 0, 0),

-- Juni 2025 (sampai tanggal 15)
('2025-06-01 09:30:00', 4, 'Budi Santoso', 1, 44000, 44, 0, 0),
('2025-06-03 12:45:00', 2, 'Siti Nurhaliza', 2, 38000, 38, 60, 6000),
('2025-06-05 15:15:00', 3, 'Ahmad Rahman', 3, 27000, 27, 0, 0),
('2025-06-08 08:40:00', 2, 'Dewi Sartika', 4, 55000, 55, 100, 10000),
('2025-06-10 11:20:00', 4, 'Maya Sari', 6, 31000, 31, 0, 0),
('2025-06-12 14:50:00', 2, 'Andi Wijaya', 7, 46000, 46, 70, 7000),
('2025-06-14 10:05:00', 3, 'Lina Marlina', 8, 33000, 33, 0, 0),
('2025-06-15 13:30:00', 2, 'Walk-in Customer', NULL, 22000, 0, 0, 0);

-- Insert detail penjualan dengan obat ID yang valid (1-100)
INSERT INTO detail_penjualan (transaksi_id, obat_id, qty, harga_saat_ini) VALUES
-- Detail untuk transaksi 1-10 (Januari)
(1, 1, 10, 2500), (1, 27, 15, 2000), (1, 41, 8, 2500),
(2, 68, 10, 3200), (2, 82, 8, 2400),
(3, 21, 8, 4300), (3, 31, 6, 5300), (3, 15, 10, 2700),
(4, 24, 8, 4200), (4, 50, 5, 3100),
(5, 56, 6, 6200), (5, 37, 8, 6100), (5, 19, 5, 6700),
(6, 27, 20, 2000), (6, 75, 8, 2700),
(7, 40, 8, 4700), (7, 20, 6, 5700), (7, 33, 10, 3300),
(8, 24, 10, 4200), (8, 46, 8, 2800),
(9, 49, 6, 5600), (9, 57, 4, 8100), (9, 35, 8, 4300),
(10, 68, 15, 3200), (10, 67, 8, 2600),

-- Detail untuk transaksi 11-20 (Januari-Februari)
(11, 33, 15, 3300), (11, 42, 8, 3800), (11, 59, 10, 3100),
(12, 25, 6, 4700), (12, 23, 8, 5100), (12, 73, 4, 5500),
(13, 82, 20, 2400), (13, 90, 8, 2500),
(14, 58, 8, 4600), (14, 43, 6, 5000), (14, 52, 4, 5400),
(15, 21, 12, 4300), (15, 46, 10, 2800), (15, 41, 8, 2500),
(16, 37, 6, 6100), (16, 31, 8, 5300), (16, 20, 5, 5700),
(17, 68, 18, 3200), (17, 51, 8, 2800),
(18, 40, 10, 4700), (18, 20, 6, 5700), (18, 33, 6, 3300),
(19, 24, 15, 4200), (19, 46, 8, 2800),
(20, 27, 30, 2000), (20, 75, 8, 2700),

-- Detail untuk transaksi 21-30 (Februari)
(21, 49, 8, 5600), (21, 57, 3, 8100), (21, 35, 6, 4300),
(22, 33, 10, 3300), (22, 42, 8, 3800),
(23, 25, 8, 4700), (23, 23, 6, 5100),
(24, 58, 6, 4600), (24, 43, 8, 5000),
(25, 21, 15, 4300), (25, 46, 12, 2800),
(26, 56, 8, 6200), (26, 37, 6, 6100), (26, 19, 4, 6700),
(27, 82, 12, 2400), (27, 90, 4, 2500),

-- Detail untuk transaksi 31-40 (Maret)
(28, 68, 20, 3200), (28, 67, 10, 2600), (28, 51, 8, 2800),
(29, 49, 6, 5600), (29, 57, 4, 8100), (29, 35, 8, 4300),
(30, 68, 15, 3200), (30, 51, 8, 2800),
(31, 40, 12, 4700), (31, 20, 8, 5700), (31, 33, 6, 3300),
(32, 24, 20, 4200), (32, 46, 10, 2800),
(33, 27, 25, 2000), (33, 75, 10, 2700),
(34, 33, 18, 3300), (34, 42, 12, 3800), (34, 59, 8, 3100),
(35, 25, 8, 4700), (35, 23, 10, 5100),
(36, 58, 10, 4600), (36, 43, 8, 5000), (36, 52, 6, 5400),
(37, 21, 10, 4300), (37, 46, 8, 2800),

-- Detail untuk transaksi 41-50 (Maret-April)
(38, 56, 12, 6200), (38, 37, 6, 6100), (38, 19, 5, 6700),
(39, 82, 15, 2400), (39, 90, 6, 2500),
(40, 49, 8, 5600), (40, 57, 3, 8100), (40, 35, 6, 4300),
(41, 68, 12, 3200), (41, 51, 10, 2800),
(42, 68, 25, 3200), (42, 67, 12, 2600), (42, 51, 10, 2800),
(43, 40, 15, 4700), (43, 20, 8, 5700), (43, 33, 8, 3300),
(44, 24, 18, 4200), (44, 46, 12, 2800),
(45, 33, 20, 3300), (45, 42, 10, 3800), (45, 59, 8, 3100),
(46, 25, 6, 4700), (46, 23, 12, 5100),
(47, 27, 20, 2000), (47, 75, 12, 2700),

-- Detail untuk transaksi 51-60 (April-Mei)
(48, 58, 12, 4600), (48, 43, 8, 5000), (48, 52, 6, 5400),
(49, 21, 15, 4300), (49, 46, 10, 2800),
(50, 56, 8, 6200), (50, 37, 8, 6100), (50, 19, 4, 6700),
(51, 82, 20, 2400), (51, 90, 8, 2500),
(52, 49, 10, 5600), (52, 57, 4, 8100), (52, 35, 8, 4300),
(53, 68, 15, 3200), (53, 51, 6, 2800),
(54, 68, 25, 3200), (54, 67, 15, 2600), (54, 51, 12, 2800),
(55, 40, 18, 4700), (55, 20, 10, 5700), (55, 33, 8, 3300),
(56, 24, 15, 4200), (56, 46, 15, 2800),
(57, 33, 15, 3300), (57, 42, 15, 3800),

-- Detail untuk transaksi 61-68 (Mei-Juni)
(58, 25, 8, 4700), (58, 23, 10, 5100), (58, 73, 4, 5500),
(59, 58, 8, 4600), (59, 43, 8, 5000),
(60, 21, 18, 4300), (60, 46, 12, 2800), (60, 41, 10, 2500),
(61, 56, 10, 6200), (61, 37, 6, 6100),
(62, 82, 25, 2400), (62, 90, 10, 2500),
(63, 49, 8, 5600), (63, 57, 3, 8100), (63, 35, 6, 4300),
(64, 68, 12, 3200), (64, 51, 8, 2800),
(65, 68, 30, 3200), (65, 67, 10, 2600), (65, 51, 8, 2800),
(66, 40, 12, 4700), (66, 20, 8, 5700), (66, 33, 6, 3300),
(67, 24, 18, 4200), (67, 46, 10, 2800),
(68, 27, 25, 2000), (68, 75, 8, 2700);

-- =====================================================
-- DATA TRANSAKSI PEMBELIAN (1 Jan - 15 Jun 2025)
-- =====================================================

-- Insert transaksi pembelian
INSERT INTO transaksi_pembelian (user_id, supplier_id, tanggal, nomor_faktur, total) VALUES
-- Januari 2025
(1, 1, '2025-01-05', 'KF-2025-001', 8500000),
(1, 2, '2025-01-08', 'KB-2025-001', 12000000),
(2, 3, '2025-01-12', 'DX-2025-001', 6800000),
(1, 4, '2025-01-15', 'PH-2025-001', 9200000),
(3, 5, '2025-01-20', 'NV-2025-001', 5400000),
(1, 6, '2025-01-25', 'PF-2025-001', 7600000),
(2, 7, '2025-01-28', 'TS-2025-001', 8900000),

-- Februari 2025
(1, 8, '2025-02-02', 'GP-2025-001', 6200000),
(3, 1, '2025-02-08', 'KF-2025-002', 7800000),
(1, 2, '2025-02-12', 'KB-2025-002', 11500000),
(2, 3, '2025-02-18', 'DX-2025-002', 5900000),
(1, 4, '2025-02-22', 'PH-2025-002', 8700000),
(3, 5, '2025-02-25', 'NV-2025-002', 4800000),

-- Maret 2025
(1, 6, '2025-03-05', 'PF-2025-002', 9100000),
(2, 7, '2025-03-10', 'TS-2025-002', 7300000),
(1, 8, '2025-03-15', 'GP-2025-002', 6600000),
(3, 1, '2025-03-20', 'KF-2025-003', 8200000),
(1, 2, '2025-03-25', 'KB-2025-003', 10800000),
(2, 3, '2025-03-28', 'DX-2025-003', 5700000),

-- April 2025
(1, 4, '2025-04-02', 'PH-2025-003', 9500000),
(3, 5, '2025-04-08', 'NV-2025-003', 5200000),
(1, 6, '2025-04-12', 'PF-2025-003', 7900000),
(2, 7, '2025-04-18', 'TS-2025-003', 8400000),
(1, 8, '2025-04-22', 'GP-2025-003', 6100000),
(3, 1, '2025-04-28', 'KF-2025-004', 7700000),

-- Mei 2025
(1, 2, '2025-05-05', 'KB-2025-004', 11200000),
(2, 3, '2025-05-10', 'DX-2025-004', 6300000),
(1, 4, '2025-05-15', 'PH-2025-004', 8800000),
(3, 5, '2025-05-20', 'NV-2025-004', 4900000),
(1, 6, '2025-05-25', 'PF-2025-004', 8600000),
(2, 7, '2025-05-28', 'TS-2025-004', 7800000),

-- Juni 2025 (sampai tanggal 15)
(1, 8, '2025-06-02', 'GP-2025-004', 5800000),
(3, 1, '2025-06-08', 'KF-2025-005', 8100000),
(1, 2, '2025-06-12', 'KB-2025-005', 10900000),
(2, 3, '2025-06-15', 'DX-2025-005', 6500000);

-- Insert detail pembelian dengan obat ID yang valid (1-100)
INSERT INTO detail_pembelian (pembelian_id, obat_id, qty, harga_beli, nomor_batch, expired_date, satuan) VALUES
-- Detail pembelian 1-5 (Januari)
(1, 1, 500, 1700, 'KF250105001', '2026-07-05', 'Tablet'),
(1, 8, 300, 1900, 'KF250105002', '2026-06-05', 'Tablet'),
(1, 41, 400, 1900, 'KF250105003', '2026-05-05', 'Tablet'),
(1, 48, 200, 2500, 'KF250105004', '2026-04-05', 'Tablet'),

(2, 20, 250, 4400, 'KB250108001', '2026-08-08', 'Kapsul'),
(2, 33, 300, 2400, 'KB250108002', '2026-07-08', 'Tablet'),
(2, 51, 400, 2100, 'KB250108003', '2026-09-08', 'Kapsul'),
(2, 88, 200, 2300, 'KB250108004', '2026-06-08', 'Kapsul'),
(2, 96, 250, 2200, 'KB250108005', '2026-05-08', 'Tablet'),

(3, 2, 200, 3800, 'DX250112001', '2026-06-12', 'Tablet'),
(3, 16, 100, 4900, 'DX250112002', '2026-05-12', 'Tablet'),
(3, 19, 150, 4400, 'DX250112003', '2026-08-12', 'Tablet'),
(3, 37, 80, 4500, 'DX250112004', '2026-07-12', 'Tablet'),

(4, 12, 120, 3500, 'PH250115001', '2026-07-15', 'Kaplet'),
(4, 21, 180, 3000, 'PH250115002', '2026-06-15', 'Kapsul'),
(4, 40, 150, 3000, 'PH250115003', '2026-08-15', 'Kaplet'),
(4, 49, 100, 3300, 'PH250115004', '2026-05-15', 'Kapsul'),
(4, 78, 80, 2700, 'PH250115005', '2026-09-15', 'Tablet'),

(5, 29, 300, 2100, 'NV250120001', '2026-06-20', 'Tablet'),
(5, 34, 150, 3100, 'NV250120002', '2026-05-20', 'Tablet'),
(5, 55, 200, 2700, 'NV250120003', '2026-08-20', 'Tablet'),
(5, 83, 180, 1900, 'NV250120004', '2026-04-20', 'Kapsul'),

-- Detail pembelian 6-10 (Januari-Februari)
(6, 34, 200, 3100, 'PF250125001', '2026-07-25', 'Tablet'),
(6, 55, 150, 2700, 'PF250125002', '2026-06-25', 'Tablet'),
(6, 83, 250, 1900, 'PF250125003', '2026-08-25', 'Kapsul'),

(7, 82, 400, 1600, 'TS250128001', '2026-08-28', 'Tablet'),
(7, 86, 200, 2800, 'TS250128002', '2026-
