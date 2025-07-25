-- =====================================================
-- DATABASE APOTEK KITA FARMA v1.2.2 - UPDATED WITH INVOICE NUMBERS
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
  no_faktur_jual VARCHAR(20) NOT NULL UNIQUE,
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
  no_faktur_beli VARCHAR(20) NOT NULL UNIQUE,
  user_id INT NOT NULL,
  supplier_id INT NOT NULL,
  tanggal DATE NOT NULL,
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

-- Insert transaksi penjualan dengan nomor faktur
INSERT INTO transaksi_penjualan (no_faktur_jual, tanggal_transaksi, user_id, nama_pembeli, member_id, total, poin_didapat, poin_digunakan, potongan_harga) VALUES
-- Januari 2025
('PJ-20250102-0001', '2025-01-02 09:15:00', 2, 'Budi Santoso', 1, 25000, 25, 0, 0),
('PJ-20250102-0002', '2025-01-02 10:30:00', 3, 'Walk-in Customer', NULL, 15000, 0, 0, 0),
('PJ-20250103-0001', '2025-01-03 08:45:00', 2, 'Siti Nurhaliza', 2, 42000, 42, 50, 5000),
('PJ-20250103-0002', '2025-01-03 14:20:00', 4, 'Ahmad Rahman', 3, 18000, 18, 0, 0),
('PJ-20250105-0001', '2025-01-05 11:10:00', 2, 'Dewi Sartika', 4, 65000, 65, 100, 10000),
('PJ-20250107-0001', '2025-01-07 16:30:00', 3, 'Walk-in Customer', NULL, 12000, 0, 0, 0),
('PJ-20250108-0001', '2025-01-08 09:00:00', 2, 'Maya Sari', 6, 38000, 38, 0, 0),
('PJ-20250110-0001', '2025-01-10 13:45:00', 4, 'Andi Wijaya', 7, 28000, 28, 50, 5000),
('PJ-20250112-0001', '2025-01-12 10:15:00', 2, 'Lina Marlina', 8, 55000, 55, 100, 10000),
('PJ-20250115-0001', '2025-01-15 15:20:00', 3, 'Doni Pratama', 9, 22000, 22, 0, 0),
('PJ-20250118-0001', '2025-01-18 08:30:00', 2, 'Rina Wati', 10, 35000, 35, 0, 0),
('PJ-20250120-0001', '2025-01-20 12:00:00', 4, 'Bambang Sutrisno', 11, 48000, 48, 80, 8000),
('PJ-20250122-0001', '2025-01-22 14:45:00', 2, 'Indira Sari', 12, 19000, 19, 0, 0),
('PJ-20250125-0001', '2025-01-25 11:30:00', 3, 'Fajar Nugroho', 13, 72000, 72, 150, 15000),
('PJ-20250128-0001', '2025-01-28 16:15:00', 2, 'Ratna Dewi', 14, 31000, 31, 0, 0),

-- Februari 2025
('PJ-20250201-0001', '2025-02-01 09:20:00', 4, 'Hendra Wijaya', 15, 44000, 44, 0, 0),
('PJ-20250203-0001', '2025-02-03 10:45:00', 2, 'Sari Indah', 16, 26000, 26, 0, 0),
('PJ-20250205-0001', '2025-02-05 13:30:00', 3, 'Agus Setiawan', 17, 39000, 39, 50, 5000),
('PJ-20250208-0001', '2025-02-08 08:15:00', 2, 'Wulan Dari', 18, 33000, 33, 0, 0),
('PJ-20250210-0001', '2025-02-10 15:00:00', 4, 'Eko Prasetyo', 19, 21000, 21, 0, 0),
('PJ-20250212-0001', '2025-02-12 11:45:00', 2, 'Fitri Handayani', 20, 47000, 47, 80, 8000),
('PJ-20250215-0001', '2025-02-15 14:20:00', 3, 'Walk-in Customer', NULL, 16000, 0, 0, 0),
('PJ-20250218-0001', '2025-02-18 09:30:00', 2, 'Budi Santoso', 1, 29000, 29, 0, 0),
('PJ-20250220-0001', '2025-02-20 12:15:00', 4, 'Siti Nurhaliza', 2, 52000, 52, 100, 10000),
('PJ-20250222-0001', '2025-02-22 16:45:00', 2, 'Ahmad Rahman', 3, 24000, 24, 0, 0),
('PJ-20250225-0001', '2025-02-25 10:00:00', 3, 'Dewi Sartika', 4, 61000, 61, 120, 12000),
('PJ-20250227-0001', '2025-02-27 13:15:00', 2, 'Maya Sari', 6, 36000, 36, 0, 0),

-- Maret 2025
('PJ-20250302-0001', '2025-03-02 08:45:00', 4, 'Andi Wijaya', 7, 43000, 43, 60, 6000),
('PJ-20250305-0001', '2025-03-05 11:20:00', 2, 'Lina Marlina', 8, 28000, 28, 0, 0),
('PJ-20250308-0001', '2025-03-08 14:30:00', 3, 'Doni Pratama', 9, 35000, 35, 0, 0),
('PJ-20250310-0001', '2025-03-10 09:15:00', 2, 'Rina Wati', 10, 49000, 49, 80, 8000),
('PJ-20250312-0001', '2025-03-12 15:45:00', 4, 'Walk-in Customer', NULL, 18000, 0, 0, 0),
('PJ-20250315-0001', '2025-03-15 10:30:00', 2, 'Bambang Sutrisno', 11, 56000, 56, 100, 10000),
('PJ-20250318-0001', '2025-03-18 13:00:00', 3, 'Indira Sari', 12, 22000, 22, 0, 0),
('PJ-20250320-0001', '2025-03-20 16:20:00', 2, 'Fajar Nugroho', 13, 67000, 67, 140, 14000),
('PJ-20250322-0001', '2025-03-22 08:50:00', 4, 'Ratna Dewi', 14, 31000, 31, 0, 0),
('PJ-20250325-0001', '2025-03-25 12:40:00', 2, 'Hendra Wijaya', 15, 45000, 45, 0, 0),
('PJ-20250328-0001', '2025-03-28 15:10:00', 3, 'Sari Indah', 16, 38000, 38, 50, 5000),
('PJ-20250330-0001', '2025-03-30 11:25:00', 2, 'Agus Setiawan', 17, 27000, 27, 0, 0),

-- April 2025
('PJ-20250402-0001', '2025-04-02 09:40:00', 4, 'Wulan Dari', 18, 41000, 41, 0, 0),
('PJ-20250405-0001', '2025-04-05 14:15:00', 2, 'Eko Prasetyo', 19, 23000, 23, 0, 0),
('PJ-20250408-0001', '2025-04-08 10:50:00', 3, 'Fitri Handayani', 20, 54000, 54, 90, 9000),
('PJ-20250410-0001', '2025-04-10 13:25:00', 2, 'Walk-in Customer', NULL, 19000, 0, 0, 0),
('PJ-20250412-0001', '2025-04-12 16:00:00', 4, 'Budi Santoso', 1, 37000, 37, 0, 0),
('PJ-20250415-0001', '2025-04-15 08:35:00', 2, 'Siti Nurhaliza', 2, 46000, 46, 70, 7000),
('PJ-20250418-0001', '2025-04-18 11:45:00', 3, 'Ahmad Rahman', 3, 32000, 32, 0, 0),
('PJ-20250420-0001', '2025-04-20 15:30:00', 2, 'Dewi Sartika', 4, 58000, 58, 110, 11000),
('PJ-20250422-0001', '2025-04-22 09:20:00', 4, 'Maya Sari', 6, 25000, 25, 0, 0),
('PJ-20250425-0001', '2025-04-25 12:55:00', 2, 'Andi Wijaya', 7, 42000, 42, 60, 6000),
('PJ-20250428-0001', '2025-04-28 14:40:00', 3, 'Lina Marlina', 8, 39000, 39, 0, 0),
('PJ-20250430-0001', '2025-04-30 10:10:00', 2, 'Doni Pratama', 9, 29000, 29, 0, 0),

-- Mei 2025
('PJ-20250502-0001', '2025-05-02 13:50:00', 4, 'Rina Wati', 10, 51000, 51, 80, 8000),
('PJ-20250505-0001', '2025-05-05 08:25:00', 2, 'Bambang Sutrisno', 11, 34000, 34, 0, 0),
('PJ-20250508-0001', '2025-05-08 11:15:00', 3, 'Indira Sari', 12, 26000, 26, 0, 0),
('PJ-20250510-0001', '2025-05-10 15:40:00', 2, 'Fajar Nugroho', 13, 63000, 63, 130, 13000),
('PJ-20250512-0001', '2025-05-12 09:05:00', 4, 'Ratna Dewi', 14, 28000, 28, 0, 0),
('PJ-20250515-0001', '2025-05-15 12:30:00', 2, 'Hendra Wijaya', 15, 47000, 47, 0, 0),
('PJ-20250518-0001', '2025-05-18 16:25:00', 3, 'Sari Indah', 16, 35000, 35, 0, 0),
('PJ-20250520-0001', '2025-05-20 10:45:00', 2, 'Agus Setiawan', 17, 40000, 40, 50, 5000),
('PJ-20250522-0001', '2025-05-22 14:10:00', 4, 'Wulan Dari', 18, 33000, 33, 0, 0),
('PJ-20250525-0001', '2025-05-25 08:55:00', 2, 'Eko Prasetyo', 19, 24000, 24, 0, 0),
('PJ-20250528-0001', '2025-05-28 13:20:00', 3, 'Fitri Handayani', 20, 49000, 49, 80, 8000),
('PJ-20250530-0001', '2025-05-30 11:35:00', 2, 'Walk-in Customer', NULL, 21000, 0, 0, 0),

-- Juni 2025 (sampai tanggal 15)
('PJ-20250601-0001', '2025-06-01 09:30:00', 4, 'Budi Santoso', 1, 44000, 44, 0, 0),
('PJ-20250603-0001', '2025-06-03 12:45:00', 2, 'Siti Nurhaliza', 2, 38000, 38, 60, 6000),
('PJ-20250605-0001', '2025-06-05 15:15:00', 3, 'Ahmad Rahman', 3, 27000, 27, 0, 0),
('PJ-20250608-0001', '2025-06-08 08:40:00', 2, 'Dewi Sartika', 4, 55000, 55, 100, 10000),
('PJ-20250610-0001', '2025-06-10 11:20:00', 4, 'Maya Sari', 6, 31000, 31, 0, 0),
('PJ-20250612-0001', '2025-06-12 14:50:00', 2, 'Andi Wijaya', 7, 46000, 46, 70, 7000),
('PJ-20250614-0001', '2025-06-14 10:05:00', 3, 'Lina Marlina', 8, 33000, 33, 0, 0),
('PJ-20250615-0001', '2025-06-15 13:30:00', 2, 'Walk-in Customer', NULL, 22000, 0, 0, 0);

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

-- Insert transaksi pembelian dengan nomor faktur beli
INSERT INTO transaksi_pembelian (no_faktur_beli, user_id, supplier_id, tanggal, total) VALUES
-- Januari 2025
('KF-20250105-0001', 1, 1, '2025-01-05', 8500000),  -- Kimia Farma
('KB-20250108-0001', 1, 2, '2025-01-08', 12000000), -- Kalbe Farma
('DX-20250112-0001', 2, 3, '2025-01-12', 6800000),  -- Dexa Medica
('PH-20250115-0001', 1, 4, '2025-01-15', 9200000),  -- Pharos Indonesia
('NV-20250120-0001', 3, 5, '2025-01-20', 5400000),  -- Novartis Indonesia
('PF-20250125-0001', 1, 6, '2025-01-25', 7600000),  -- Pfizer Indonesia
('TS-20250128-0001', 2, 7, '2025-01-28', 8900000),  -- Tempo Scan Pacific

-- Februari 2025
('GP-20250202-0001', 1, 8, '2025-02-02', 6200000),  -- Guardian Pharmatama
('KF-20250208-0001', 3, 1, '2025-02-08', 7800000),  -- Kimia Farma
('KB-20250212-0001', 1, 2, '2025-02-12', 11500000), -- Kalbe Farma
('DX-20250218-0001', 2, 3, '2025-02-18', 5900000),  -- Dexa Medica
('PH-20250222-0001', 1, 4, '2025-02-22', 8700000),  -- Pharos Indonesia
('NV-20250225-0001', 3, 5, '2025-02-25', 4800000),  -- Novartis Indonesia

-- Maret 2025
('PF-20250305-0001', 1, 6, '2025-03-05', 9100000),  -- Pfizer Indonesia
('TS-20250310-0001', 2, 7, '2025-03-10', 7300000),  -- Tempo Scan Pacific
('GP-20250315-0001', 1, 8, '2025-03-15', 6600000),  -- Guardian Pharmatama
('KF-20250320-0001', 3, 1, '2025-03-20', 8200000),  -- Kimia Farma
('KB-20250325-0001', 1, 2, '2025-03-25', 10800000), -- Kalbe Farma
('DX-20250328-0001', 2, 3, '2025-03-28', 5700000),  -- Dexa Medica

-- April 2025
('PH-20250402-0001', 1, 4, '2025-04-02', 9500000),  -- Pharos Indonesia
('NV-20250408-0001', 3, 5, '2025-04-08', 5200000),  -- Novartis Indonesia
('PF-20250412-0001', 1, 6, '2025-04-12', 7900000),  -- Pfizer Indonesia
('TS-20250418-0001', 2, 7, '2025-04-18', 8400000),  -- Tempo Scan Pacific
('GP-20250422-0001', 1, 8, '2025-04-22', 6100000),  -- Guardian Pharmatama
('KF-20250428-0001', 3, 1, '2025-04-28', 7700000),  -- Kimia Farma

-- Mei 2025
('KB-20250505-0001', 1, 2, '2025-05-05', 11200000), -- Kalbe Farma
('DX-20250510-0001', 2, 3, '2025-05-10', 6300000),  -- Dexa Medica
('PH-20250515-0001', 1, 4, '2025-05-15', 8800000),  -- Pharos Indonesia
('NV-20250520-0001', 3, 5, '2025-05-20', 4900000),  -- Novartis Indonesia
('PF-20250525-0001', 1, 6, '2025-05-25', 8600000),  -- Pfizer Indonesia
('TS-20250528-0001', 2, 7, '2025-05-28', 7800000),  -- Tempo Scan Pacific

-- Juni 2025 (sampai tanggal 15)
('GP-20250602-0001', 1, 8, '2025-06-02', 5800000),  -- Guardian Pharmatama
('KF-20250608-0001', 3, 1, '2025-06-08', 8100000),  -- Kimia Farma
('KB-20250612-0001', 1, 2, '2025-06-12', 10900000), -- Kalbe Farma
('DX-20250615-0001', 2, 3, '2025-06-15', 6500000);  -- Dexa Medica

-- Insert detail pembelian dengan obat ID yang valid (1-100) - TANPA kolom satuan
INSERT INTO detail_pembelian (pembelian_id, obat_id, qty, harga_beli, nomor_batch, expired_date) VALUES
-- Detail pembelian 1-5 (Januari)
(1, 1, 500, 1700, 'KF250105001', '2026-07-05'),
(1, 8, 300, 1900, 'KF250105002', '2026-06-05'),
(1, 41, 400, 1900, 'KF250105003', '2026-05-05'),
(1, 48, 200, 2500, 'KF250105004', '2026-04-05'),

(2, 20, 250, 4400, 'KB250108001', '2026-08-08'),
(2, 33, 300, 2400, 'KB250108002', '2026-07-08'),
(2, 51, 400, 2100, 'KB250108003', '2026-09-08'),
(2, 88, 200, 2300, 'KB250108004', '2026-06-08'),
(2, 96, 250, 2200, 'KB250108005', '2026-05-08'),

(3, 2, 200, 3800, 'DX250112001', '2026-06-12'),
(3, 16, 100, 4900, 'DX250112002', '2026-05-12'),
(3, 19, 150, 4400, 'DX250112003', '2026-08-12'),
(3, 37, 80, 4500, 'DX250112004', '2026-07-12'),

(4, 12, 120, 3500, 'PH250115001', '2026-07-15'),
(4, 21, 180, 3000, 'PH250115002', '2026-06-15'),
(4, 40, 150, 3000, 'PH250115003', '2026-08-15'),
(4, 49, 100, 3300, 'PH250115004', '2026-05-15'),
(4, 78, 80, 2700, 'PH250115005', '2026-09-15'),

(5, 29, 300, 2100, 'NV250120001', '2026-06-20'),
(5, 34, 150, 3100, 'NV250120002', '2026-05-20'),
(5, 55, 200, 2700, 'NV250120003', '2026-08-20'),
(5, 83, 180, 1900, 'NV250120004', '2026-04-20'),

-- Detail pembelian 6-10 (Januari-Februari)
(6, 34, 200, 3100, 'PF250125001', '2026-07-25'),
(6, 55, 150, 2700, 'PF250125002', '2026-06-25'),
(6, 83, 250, 1900, 'PF250125003', '2026-08-25'),

(7, 82, 400, 1600, 'TS250128001', '2026-08-28'),
(7, 86, 200, 2800, 'TS250128002', '2026-07-28'),
(7, 98, 150, 2800, 'TS250128003', '2026-06-28'),

(8, 35, 250, 2800, 'GP250202001', '2026-08-02'),
(8, 46, 300, 1700, 'GP250202002', '2026-07-02'),
(8, 95, 200, 2400, 'GP250202003', '2026-06-02'),

(9, 1, 400, 1700, 'KF250208001', '2026-08-08'),
(9, 44, 200, 2500, 'KF250208002', '2026-07-08'),
(9, 75, 300, 1700, 'KF250208003', '2026-06-08'),

(10, 20, 180, 4400, 'KB250212001', '2026-09-12'),
(10, 33, 250, 2400, 'KB250212002', '2026-08-12'),
(10, 88, 150, 2300, 'KB250212003', '2026-07-12'),

-- Detail pembelian 11-15 (Februari)
(11, 2, 150, 3800, 'DX250218001', '2026-08-18'),
(11, 16, 80, 4900, 'DX250218002', '2026-07-18'),
(11, 19, 120, 4400, 'DX250218003', '2026-09-18'),

(12, 12, 100, 3500, 'PH250222001', '2026-08-22'),
(12, 21, 150, 3000, 'PH250222002', '2026-07-22'),
(12, 40, 120, 3000, 'PH250222003', '2026-09-22'),

(13, 29, 200, 2100, 'NV250225001', '2026-07-25'),
(13, 34, 120, 3100, 'NV250225002', '2026-06-25'),
(13, 55, 150, 2700, 'NV250225003', '2026-08-25'),

-- Detail pembelian 16-20 (Maret)
(14, 34, 180, 3100, 'PF250305001', '2026-09-05'),
(14, 55, 120, 2700, 'PF250305002', '2026-08-05'),
(14, 83, 200, 1900, 'PF250305003', '2026-10-05'),

(15, 82, 300, 1600, 'TS250310001', '2026-09-10'),
(15, 86, 150, 2800, 'TS250310002', '2026-08-10'),
(15, 98, 120, 2800, 'TS250310003', '2026-07-10'),

(16, 35, 200, 2800, 'GP250315001', '2026-09-15'),
(16, 46, 250, 1700, 'GP250315002', '2026-08-15'),
(16, 95, 180, 2400, 'GP250315003', '2026-07-15'),

(17, 1, 350, 1700, 'KF250320001', '2026-09-20'),
(17, 44, 180, 2500, 'KF250320002', '2026-08-20'),
(17, 75, 250, 1700, 'KF250320003', '2026-07-20'),

(18, 20, 150, 4400, 'KB250325001', '2026-10-25'),
(18, 33, 200, 2400, 'KB250325002', '2026-09-25'),
(18, 88, 120, 2300, 'KB250325003', '2026-08-25'),

(19, 2, 120, 3800, 'DX250328001', '2026-09-28'),
(19, 16, 60, 4900, 'DX250328002', '2026-08-28'),

-- Detail pembelian 21-25 (April)
(20, 12, 80, 3500, 'PH250402001', '2026-10-02'),
(20, 21, 120, 3000, 'PH250402002', '2026-09-02'),
(20, 40, 100, 3000, 'PH250402003', '2026-11-02'),

(21, 29, 150, 2100, 'NV250408001', '2026-09-08'),
(21, 34, 100, 3100, 'NV250408002', '2026-08-08'),
(21, 55, 120, 2700, 'NV250408003', '2026-10-08'),

(22, 34, 150, 3100, 'PF250412001', '2026-10-12'),
(22, 55, 100, 2700, 'PF250412002', '2026-09-12'),
(22, 83, 180, 1900, 'PF250412003', '2026-11-12'),

(23, 82, 250, 1600, 'TS250418001', '2026-10-18'),
(23, 86, 120, 2800, 'TS250418002', '2026-09-18'),
(23, 98, 100, 2800, 'TS250418003', '2026-08-18'),

(24, 35, 180, 2800, 'GP250422001', '2026-10-22'),
(24, 46, 200, 1700, 'GP250422002', '2026-09-22'),
(24, 95, 150, 2400, 'GP250422003', '2026-08-22'),

(25, 1, 300, 1700, 'KF250428001', '2026-10-28'),
(25, 44, 150, 2500, 'KF250428002', '2026-09-28'),

-- Detail pembelian 26-30 (Mei)
(26, 20, 120, 4400, 'KB250505001', '2026-11-05'),
(26, 33, 180, 2400, 'KB250505002', '2026-10-05'),
(26, 88, 100, 2300, 'KB250505003', '2026-09-05'),

(27, 2, 100, 3800, 'DX250510001', '2026-10-10'),
(27, 16, 50, 4900, 'DX250510002', '2026-09-10'),
(27, 19, 80, 4400, 'DX250510003', '2026-11-10'),

(28, 12, 70, 3500, 'PH250515001', '2026-11-15'),
(28, 21, 100, 3000, 'PH250515002', '2026-10-15'),
(28, 40, 80, 3000, 'PH250515003', '2026-12-15'),

(29, 29, 120, 2100, 'NV250520001', '2026-10-20'),
(29, 34, 80, 3100, 'NV250520002', '2026-09-20'),

(30, 34, 120, 3100, 'PF250525001', '2026-11-25'),
(30, 55, 80, 2700, 'PF250525002', '2026-10-25'),
(30, 83, 150, 1900, 'PF250525003', '2026-12-25'),

(31, 82, 200, 1600, 'TS250528001', '2026-11-28'),
(31, 86, 100, 2800, 'TS250528002', '2026-10-28'),

-- Detail pembelian 32-35 (Juni)
(32, 35, 150, 2800, 'GP250602001', '2026-12-02'),
(32, 46, 180, 1700, 'GP250602002', '2026-11-02'),
(32, 95, 120, 2400, 'GP250602003', '2026-10-02'),

(33, 1, 250, 1700, 'KF250608001', '2026-12-08'),
(33, 44, 120, 2500, 'KF250608002', '2026-11-08'),
(33, 75, 200, 1700, 'KF250608003', '2026-10-08'),

(34, 20, 100, 4400, 'KB250612001', '2026-12-12'),
(34, 33, 150, 2400, 'KB250612002', '2026-11-12'),
(34, 88, 80, 2300, 'KB250612003', '2026-10-12'),

(35, 2, 80, 3800, 'DX250615001', '2026-11-15'),
(35, 16, 40, 4900, 'DX250615002', '2026-10-15'),
(35, 19, 60, 4400, 'DX250615003', '2026-12-15');

-- =====================================================
-- VERIFIKASI DATA
-- =====================================================

-- Tampilkan ringkasan data
SELECT 'Total Users' as Info, COUNT(*) as Jumlah FROM user
UNION ALL
SELECT 'Total Suppliers', COUNT(*) FROM supplier
UNION ALL
SELECT 'Total Obat', COUNT(*) FROM obat
UNION ALL
SELECT 'Total Members', COUNT(*) FROM member
UNION ALL
SELECT 'Total Transaksi Penjualan', COUNT(*) FROM transaksi_penjualan
UNION ALL
SELECT 'Total Detail Penjualan', COUNT(*) FROM detail_penjualan
UNION ALL
SELECT 'Total Transaksi Pembelian', COUNT(*) FROM transaksi_pembelian
UNION ALL
SELECT 'Total Detail Pembelian', COUNT(*) FROM detail_pembelian;

-- Tampilkan contoh data obat
SELECT 'Contoh Data Obat:' as Info;
SELECT id, bpom, nama_obat, produsen, kategori, satuan, harga_beli, harga_jual, stok 
FROM obat 
LIMIT 10;

-- Tampilkan contoh nomor faktur
SELECT 'Contoh Nomor Faktur Penjualan:' as Info;
SELECT no_faktur_jual, tanggal_transaksi, nama_pembeli, total 
FROM transaksi_penjualan 
LIMIT 5;

SELECT 'Contoh Nomor Faktur Pembelian:' as Info;
SELECT no_faktur_beli, tanggal, total 
FROM transaksi_pembelian 
LIMIT 5;
