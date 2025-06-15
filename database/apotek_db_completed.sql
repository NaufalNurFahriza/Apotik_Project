-- =====================================================
-- DATABASE APOTEK KITA FARMA v1.2
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
INSERT INTO user (nama, username, password, user) VALUES
('Kevin Bimo', 'kevin_bimo', 'kevin123', 'pemilik'),
('Sari Dewi', 'sari_ttk', 'sari123', 'ttk'),
('Ahmad Fauzi', 'ahmad_ttk', 'ahmad123', 'ttk'),
('Rina Sari', 'rina_ttk', 'rina123', 'ttk');

-- Insert data supplier
INSERT INTO supplier (nama_supplier, alamat, kota, telepon) VALUES
('PT Kimia Farma', 'Jl. Veteran No. 9', 'Jakarta', '021-3441991'),
('PT Kalbe Farma', 'Jl. Let. Jend. Suprapto Kav. 4', 'Jakarta', '021-42873888'),
('PT Sanbe Farma', 'Jl. Raya Lembang No. 115', 'Bandung', '022-2786001'),
('PT Dexa Medica', 'Jl. Bambang Utoyo No. 138', 'Palembang', '0711-710961'),
('PT Pharos Indonesia', 'Jl. Kyai Maja No. 1', 'Jakarta', '021-7394001'),
('PT Indofarma', 'Jl. Letjen S. Parman Kav. 81', 'Jakarta', '021-5694-0055'),
('PT Tempo Scan Pacific', 'Jl. H.R. Rasuna Said Blok X-2 Kav. 6', 'Jakarta', '021-5794-7788'),
('PT Combiphar', 'Jl. Raya Padalarang No. 237', 'Bandung', '022-6866-001');

-- Insert 100 data obat
INSERT INTO obat (bpom, nama_obat, produsen, supplier_id, kategori, satuan, harga_beli, harga_jual, stok) VALUES
-- Obat Umum (Non-Resep)
('DTL0332708637', 'Paracetamol 500mg', 'Kimia Farma', 1, 'non-resep', 'tablet', 300, 500, 500),
('DTL0332708638', 'Ibuprofen 400mg', 'Sanbe Farma', 3, 'non-resep', 'tablet', 500, 800, 400),
('DTL0332708639', 'Cetirizine 10mg', 'Dexa Medica', 4, 'non-resep', 'tablet', 400, 700, 350),
('DTL0332708641', 'Aspirin 100mg', 'Kimia Farma', 1, 'non-resep', 'tablet', 200, 400, 450),
('DTL0332708642', 'Vitamin C 500mg', 'Kalbe Farma', 2, 'non-resep', 'tablet', 300, 600, 600),
('DTL0332708643', 'Vitamin B Complex', 'Sanbe Farma', 3, 'non-resep', 'tablet', 400, 700, 400),
('DTL0332708644', 'Calcium Lactate', 'Dexa Medica', 4, 'non-resep', 'tablet', 500, 800, 300),
('DTL0332708645', 'Iron Sulfate', 'Pharos', 5, 'non-resep', 'tablet', 350, 600, 350),
('DTL0332708646', 'Antasida DOEN', 'Kimia Farma', 1, 'non-resep', 'tablet', 250, 450, 500),
('DTL0332708647', 'Loperamide 2mg', 'Kalbe Farma', 2, 'non-resep', 'kapsul', 600, 1000, 250),
('DTL0332708648', 'Domperidone 10mg', 'Sanbe Farma', 3, 'non-resep', 'tablet', 500, 800, 300),
('DTL0332708649', 'Ranitidine 150mg', 'Dexa Medica', 4, 'non-resep', 'tablet', 400, 700, 350),
('DTL0332708650', 'Diclofenac 50mg', 'Pharos', 5, 'non-resep', 'tablet', 600, 1000, 280),
('DTL0332708651', 'Vitamin D3 1000IU', 'Indofarma', 6, 'non-resep', 'tablet', 800, 1200, 200),
('DTL0332708652', 'Multivitamin', 'Tempo Scan', 7, 'non-resep', 'tablet', 600, 1000, 320),
('DTL0332708653', 'Zinc Sulfate 20mg', 'Combiphar', 8, 'non-resep', 'tablet', 400, 700, 280),
('DTL0332708654', 'Magnesium 400mg', 'Kimia Farma', 1, 'non-resep', 'tablet', 500, 800, 250),
('DTL0332708655', 'Omega 3', 'Kalbe Farma', 2, 'non-resep', 'kapsul', 1200, 1800, 180),
('DTL0332708656', 'Probiotik', 'Sanbe Farma', 3, 'non-resep', 'kapsul', 800, 1300, 200),
('DTL0332708657', 'Glucosamine 500mg', 'Dexa Medica', 4, 'non-resep', 'tablet', 1000, 1500, 150),

-- Obat Resep - Antibiotik
('DTL7226300446', 'Amoxicillin 500mg', 'Kalbe Farma', 2, 'resep', 'kapsul', 800, 1200, 300),
('DTL0332708658', 'Chloramphenicol 250mg', 'Sanbe Farma', 3, 'resep', 'kapsul', 700, 1100, 200),
('DTL0332708659', 'Erythromycin 500mg', 'Dexa Medica', 4, 'resep', 'tablet', 900, 1400, 180),
('DTL0332708660', 'Ciprofloxacin 500mg', 'Pharos', 5, 'resep', 'tablet', 1200, 1800, 160),
('DTL0332708661', 'Azithromycin 500mg', 'Indofarma', 6, 'resep', 'tablet', 1500, 2200, 140),
('DTL0332708662', 'Cefixime 200mg', 'Tempo Scan', 7, 'resep', 'kapsul', 1800, 2500, 120),
('DTL0332708663', 'Levofloxacin 500mg', 'Combiphar', 8, 'resep', 'tablet', 2000, 2800, 100),
('DTL0332708664', 'Clindamycin 300mg', 'Kimia Farma', 1, 'resep', 'kapsul', 1600, 2300, 130),
('DTL0332708665', 'Doxycycline 100mg', 'Kalbe Farma', 2, 'resep', 'kapsul', 1200, 1800, 170),
('DTL0332708666', 'Tetracycline 500mg', 'Sanbe Farma', 3, 'resep', 'kapsul', 800, 1300, 190),

-- Obat Resep - Diabetes
('DTL0332708667', 'Metformin 500mg', 'Kimia Farma', 1, 'resep', 'tablet', 600, 1000, 400),
('DTL0332708668', 'Glibenclamide 5mg', 'Kalbe Farma', 2, 'resep', 'tablet', 300, 600, 350),
('DTL0332708669', 'Gliclazide 80mg', 'Sanbe Farma', 3, 'resep', 'tablet', 500, 900, 280),
('DTL0332708670', 'Insulin Glargine', 'Dexa Medica', 4, 'resep', 'vial', 15000, 22000, 50),
('DTL0332708671', 'Insulin Aspart', 'Pharos', 5, 'resep', 'vial', 14000, 21000, 45),
('DTL0332708672', 'Acarbose 50mg', 'Indofarma', 6, 'resep', 'tablet', 800, 1300, 220),
('DTL0332708673', 'Pioglitazone 15mg', 'Tempo Scan', 7, 'resep', 'tablet', 1200, 1800, 180),
('DTL0332708674', 'Sitagliptin 100mg', 'Combiphar', 8, 'resep', 'tablet', 2500, 3500, 120),

-- Obat Resep - Hipertensi
('DTL0332708675', 'Amlodipine 5mg', 'Kimia Farma', 1, 'resep', 'tablet', 700, 1100, 300),
('DTL0332708676', 'Losartan 50mg', 'Kalbe Farma', 2, 'resep', 'tablet', 1000, 1500, 250),
('DTL0332708677', 'Captopril 25mg', 'Sanbe Farma', 3, 'resep', 'tablet', 400, 700, 380),
('DTL0332708678', 'Valsartan 80mg', 'Dexa Medica', 4, 'resep', 'tablet', 1200, 1800, 220),
('DTL0332708679', 'Telmisartan 40mg', 'Pharos', 5, 'resep', 'tablet', 1400, 2000, 180),
('DTL0332708680', 'Irbesartan 150mg', 'Indofarma', 6, 'resep', 'tablet', 1300, 1900, 200),
('DTL0332708681', 'Candesartan 8mg', 'Tempo Scan', 7, 'resep', 'tablet', 1100, 1700, 240),
('DTL0332708682', 'Bisoprolol 5mg', 'Combiphar', 8, 'resep', 'tablet', 800, 1300, 260),
('DTL0332708683', 'Carvedilol 25mg', 'Kimia Farma', 1, 'resep', 'tablet', 1000, 1600, 200),
('DTL0332708684', 'Atenolol 50mg', 'Kalbe Farma', 2, 'resep', 'tablet', 600, 1000, 300),

-- Obat Resep - Kolesterol
('DTL0332708685', 'Simvastatin 20mg', 'Sanbe Farma', 3, 'resep', 'tablet', 900, 1400, 260),
('DTL0332708686', 'Atorvastatin 20mg', 'Dexa Medica', 4, 'resep', 'tablet', 1200, 1800, 220),
('DTL0332708687', 'Rosuvastatin 10mg', 'Pharos', 5, 'resep', 'tablet', 1500, 2200, 180),
('DTL0332708688', 'Lovastatin 20mg', 'Indofarma', 6, 'resep', 'tablet', 1000, 1600, 240),
('DTL0332708689', 'Pravastatin 40mg', 'Tempo Scan', 7, 'resep', 'tablet', 1300, 1900, 200),

-- Obat Resep - Jantung
('DTL0332708690', 'Clopidogrel 75mg', 'Combiphar', 8, 'resep', 'tablet', 2000, 2800, 140),
('DTL0332708691', 'Warfarin 5mg', 'Kimia Farma', 1, 'resep', 'tablet', 800, 1300, 240),
('DTL0332708692', 'Digoxin 0.25mg', 'Kalbe Farma', 2, 'resep', 'tablet', 400, 700, 300),
('DTL0332708693', 'Furosemide 40mg', 'Sanbe Farma', 3, 'resep', 'tablet', 300, 600, 340),
('DTL0332708694', 'Spironolactone 25mg', 'Dexa Medica', 4, 'resep', 'tablet', 500, 900, 280),
('DTL0332708695', 'Isosorbide Dinitrate 5mg', 'Pharos', 5, 'resep', 'tablet', 600, 1000, 260),

-- Obat Resep - Lambung
('DTL0332708696', 'Omeprazole 20mg', 'Indofarma', 6, 'resep', 'kapsul', 1200, 1800, 200),
('DTL0332708697', 'Lansoprazole 30mg', 'Tempo Scan', 7, 'resep', 'kapsul', 1500, 2200, 160),
('DTL0332708698', 'Pantoprazole 40mg', 'Combiphar', 8, 'resep', 'tablet', 1800, 2500, 140),
('DTL0332708699', 'Esomeprazole 20mg', 'Kimia Farma', 1, 'resep', 'tablet', 2000, 2800, 120),

-- Obat Resep - Antijamur
('DTL0332708700', 'Ketoconazole 200mg', 'Kalbe Farma', 2, 'resep', 'tablet', 800, 1300, 220),
('DTL0332708701', 'Fluconazole 150mg', 'Sanbe Farma', 3, 'resep', 'kapsul', 1500, 2200, 140),
('DTL0332708702', 'Itraconazole 100mg', 'Dexa Medica', 4, 'resep', 'kapsul', 2000, 2800, 100),
('DTL0332708703', 'Terbinafine 250mg', 'Pharos', 5, 'resep', 'tablet', 1800, 2500, 120),

-- Obat Resep - Antiviral
('DTL0332708704', 'Acyclovir 400mg', 'Indofarma', 6, 'resep', 'tablet', 1000, 1600, 200),
('DTL0332708705', 'Valacyclovir 500mg', 'Tempo Scan', 7, 'resep', 'tablet', 2500, 3500, 80),
('DTL0332708706', 'Oseltamivir 75mg', 'Combiphar', 8, 'resep', 'kapsul', 3000, 4200, 60),

-- Obat Resep - Pereda Nyeri
('DTL0332708707', 'Tramadol 50mg', 'Kimia Farma', 1, 'resep', 'kapsul', 1200, 1800, 160),
('DTL0332708708', 'Codeine 30mg', 'Kalbe Farma', 2, 'resep', 'tablet', 1500, 2200, 120),
('DTL0332708709', 'Morphine 10mg', 'Sanbe Farma', 3, 'resep', 'tablet', 2000, 3000, 100),
('DTL0332708710', 'Meloxicam 15mg', 'Dexa Medica', 4, 'resep', 'tablet', 800, 1300, 240),
('DTL0332708711', 'Celecoxib 200mg', 'Pharos', 5, 'resep', 'kapsul', 1500, 2200, 140),

-- Obat Resep - Steroid
('DTL0332708712', 'Dexamethasone 0.5mg', 'Indofarma', 6, 'resep', 'tablet', 300, 600, 300),
('DTL0332708713', 'Prednisone 5mg', 'Tempo Scan', 7, 'resep', 'tablet', 400, 700, 260),
('DTL0332708714', 'Methylprednisolone 4mg', 'Combiphar', 8, 'resep', 'tablet', 500, 800, 280),
('DTL0332708715', 'Hydrocortisone 10mg', 'Kimia Farma', 1, 'resep', 'tablet', 600, 1000, 240),

-- Obat Resep - Asma
('DTL0332708716', 'Salbutamol 2mg', 'Kalbe Farma', 2, 'resep', 'tablet', 800, 1200, 240),
('DTL0332708717', 'Theophylline 200mg', 'Sanbe Farma', 3, 'resep', 'tablet', 600, 1000, 280),
('DTL0332708718', 'Montelukast 10mg', 'Dexa Medica', 4, 'resep', 'tablet', 1800, 2500, 160),

-- Obat Resep - Neurologi
('DTL0332708719', 'Phenytoin 100mg', 'Pharos', 5, 'resep', 'kapsul', 800, 1300, 220),
('DTL0332708720', 'Carbamazepine 200mg', 'Indofarma', 6, 'resep', 'tablet', 1000, 1600, 200),
('DTL0332708721', 'Gabapentin 300mg', 'Tempo Scan', 7, 'resep', 'kapsul', 1500, 2200, 140),
('DTL0332708722', 'Pregabalin 75mg', 'Combiphar', 8, 'resep', 'kapsul', 2000, 2800, 120),

-- Obat Resep - Psikiatri
('DTL0332708723', 'Haloperidol 5mg', 'Kimia Farma', 1, 'resep', 'tablet', 600, 1000, 260),
('DTL0332708724', 'Risperidone 2mg', 'Kalbe Farma', 2, 'resep', 'tablet', 1200, 1800, 180),
('DTL0332708725', 'Olanzapine 10mg', 'Sanbe Farma', 3, 'resep', 'tablet', 2500, 3500, 100),
('DTL0332708726', 'Sertraline 50mg', 'Dexa Medica', 4, 'resep', 'tablet', 1800, 2500, 140),
('DTL0332708727', 'Fluoxetine 20mg', 'Pharos', 5, 'resep', 'kapsul', 1500, 2200, 160),

-- Obat Resep - Hormon
('DTL0332708728', 'Levothyroxine 100mcg', 'Indofarma', 6, 'resep', 'tablet', 800, 1300, 240),
('DTL0332708729', 'Methimazole 5mg', 'Tempo Scan', 7, 'resep', 'tablet', 1000, 1600, 200),
('DTL0332708730', 'Estradiol 2mg', 'Combiphar', 8, 'resep', 'tablet', 1500, 2200, 160),

-- Obat Resep - Mata
('DTL0332708731', 'Timolol 0.5% Tetes Mata', 'Kimia Farma', 1, 'resep', 'botol', 2000, 2800, 120),
('DTL0332708732', 'Latanoprost 0.005% Tetes Mata', 'Kalbe Farma', 2, 'resep', 'botol', 3500, 4900, 80),
('DTL0332708733', 'Prednisolone 1% Tetes Mata', 'Sanbe Farma', 3, 'resep', 'botol', 1800, 2500, 140),

-- Obat Resep - Kulit
('DTL0332708734', 'Tretinoin 0.025% Krim', 'Dexa Medica', 4, 'resep', 'tube', 2500, 3500, 100),
('DTL0332708735', 'Hydroquinone 4% Krim', 'Pharos', 5, 'resep', 'tube', 3000, 4200, 80),
('DTL0332708736', 'Clindamycin 1% Gel', 'Indofarma', 6, 'resep', 'tube', 2200, 3100, 120);

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

-- Insert transaksi penjualan dengan tanggal random antara 1 Jan - 15 Jun 2025
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

-- Insert detail penjualan untuk semua transaksi di atas
INSERT INTO detail_penjualan (transaksi_id, obat_id, qty, harga_saat_ini) VALUES
-- Detail untuk transaksi 1-10 (Januari)
(1, 1, 20, 500), (1, 5, 15, 600), (1, 9, 10, 450),
(2, 3, 10, 700), (2, 11, 8, 800),
(3, 21, 8, 1200), (3, 31, 20, 1000), (3, 5, 10, 600),
(4, 2, 15, 800), (4, 10, 5, 1000),
(5, 56, 12, 1800), (5, 41, 15, 1100), (5, 25, 8, 2200),
(6, 1, 20, 500), (6, 4, 5, 400),
(7, 39, 12, 1500), (7, 14, 10, 1200), (7, 6, 8, 700),
(8, 2, 20, 800), (8, 16, 10, 1000),
(9, 45, 15, 1800), (9, 50, 8, 2800), (9, 7, 10, 800),
(10, 1, 25, 500), (10, 12, 8, 700), (10, 17, 6, 700),

-- Detail untuk transaksi 11-20 (Januari-Februari)
(11, 33, 20, 1000), (11, 18, 10, 800), (11, 8, 8, 600),
(12, 25, 6, 2200), (12, 19, 12, 1800), (12, 13, 10, 700),
(13, 7, 15, 800), (13, 20, 5, 1500),
(14, 58, 10, 2200), (14, 43, 12, 1400), (14, 35, 8, 1300),
(15, 21, 15, 1200), (15, 11, 10, 800), (15, 9, 8, 450),
(16, 41, 20, 1100), (16, 31, 15, 1000), (16, 5, 12, 600),
(17, 3, 18, 700), (17, 15, 8, 1200),
(18, 39, 15, 1500), (18, 14, 10, 1200), (18, 6, 6, 700),
(19, 2, 25, 800), (19, 16, 8, 1000),
(20, 1, 30, 500), (20, 4, 8, 400),

-- Detail untuk transaksi 21-30 (Februari)
(21, 45, 12, 1800), (21, 50, 6, 2800), (21, 7, 10, 800),
(22, 33, 10, 1000), (22, 18, 15, 800),
(23, 25, 8, 2200), (23, 19, 10, 1800),
(24, 58, 8, 2200), (24, 43, 10, 1400),
(25, 21, 20, 1200), (25, 11, 12, 800),
(26, 56, 15, 1800), (26, 41, 12, 1100), (26, 25, 6, 2200),
(27, 7, 12, 800), (27, 20, 4, 1500),

-- Detail untuk transaksi 31-40 (Maret)
(28, 1, 35, 500), (28, 12, 10, 700), (28, 17, 8, 700),
(29, 45, 10, 1800), (29, 50, 8, 2800), (29, 7, 12, 800),
(30, 3, 20, 700), (30, 15, 8, 1200),
(31, 39, 18, 1500), (31, 14, 12, 1200), (31, 6, 8, 700),
(32, 2, 30, 800), (32, 16, 10, 1000),
(33, 1, 25, 500), (33, 4, 10, 400),
(34, 33, 25, 1000), (34, 18, 12, 800), (34, 8, 10, 600),
(35, 25, 10, 2200), (35, 19, 8, 1800),
(36, 58, 12, 2200), (36, 43, 15, 1400), (36, 35, 10, 1300),
(37, 21, 12, 1200), (37, 11, 8, 800),

-- Detail untuk transaksi 41-50 (Maret-April)
(38, 56, 18, 1800), (38, 41, 10, 1100), (38, 25, 8, 2200),
(39, 7, 20, 800), (39, 20, 6, 1500),
(40, 45, 15, 1800), (40, 50, 6, 2800), (40, 7, 8, 800),
(41, 3, 15, 700), (41, 15, 10, 1200),
(42, 1, 40, 500), (42, 12, 12, 700), (42, 17, 10, 700),
(43, 39, 20, 1500), (43, 14, 8, 1200), (43, 6, 10, 700),
(44, 2, 25, 800), (44, 16, 12, 1000),
(45, 33, 30, 1000), (45, 18, 10, 800), (45, 8, 8, 600),
(46, 25, 8, 2200), (46, 19, 12, 1800),
(47, 1, 30, 500), (47, 4, 12, 400),

-- Detail untuk transaksi 51-60 (April-Mei)
(48, 58, 15, 2200), (48, 43, 12, 1400), (48, 35, 8, 1300),
(49, 21, 18, 1200), (49, 11, 10, 800),
(50, 56, 12, 1800), (50, 41, 15, 1100), (50, 25, 6, 2200),
(51, 7, 25, 800), (51, 20, 8, 1500),
(52, 45, 20, 1800), (52, 50, 8, 2800), (52, 7, 10, 800),
(53, 3, 22, 700), (53, 15, 6, 1200),
(54, 1, 35, 500), (54, 12, 15, 700), (54, 17, 12, 700),
(55, 39, 25, 1500), (55, 14, 10, 1200), (55, 6, 8, 700),
(56, 2, 20, 800), (56, 16, 15, 1000),
(57, 33, 20, 1000), (57, 18, 15, 800),

-- Detail untuk transaksi 61-68 (Mei-Juni)
(58, 25, 12, 2200), (58, 19, 10, 1800), (58, 13, 8, 700),
(59, 58, 10, 2200), (59, 43, 8, 1400),
(60, 21, 25, 1200), (60, 11, 12, 800), (60, 9, 10, 450),
(61, 56, 15, 1800), (61, 41, 8, 1100),
(62, 7, 30, 800), (62, 20, 10, 1500),
(63, 45, 12, 1800), (63, 50, 6, 2800), (63, 7, 8, 800),
(64, 3, 18, 700), (64, 15, 8, 1200),
(65, 1, 40, 500), (65, 12, 10, 700), (65, 17, 8, 700),
(66, 39, 18, 1500), (66, 14, 12, 1200), (66, 6, 6, 700),
(67, 2, 25, 800), (67, 16, 10, 1000),
(68, 1, 30, 500), (68, 4, 8, 400);

-- =====================================================
-- DATA TRANSAKSI PEMBELIAN (1 Jan - 15 Jun 2025)
-- =====================================================

-- Insert transaksi pembelian dengan tanggal random antara 1 Jan - 15 Jun 2025
INSERT INTO transaksi_pembelian (user_id, supplier_id, tanggal, nomor_faktur, total) VALUES
-- Januari 2025
(1, 1, '2025-01-05', 'KF-2025-001', 8500000),
(1, 2, '2025-01-08', 'KB-2025-001', 12000000),
(2, 3, '2025-01-12', 'SB-2025-001', 6800000),
(1, 4, '2025-01-15', 'DX-2025-001', 9200000),
(3, 5, '2025-01-20', 'PH-2025-001', 5400000),
(1, 6, '2025-01-25', 'IF-2025-001', 7600000),
(2, 7, '2025-01-28', 'TS-2025-001', 8900000),

-- Februari 2025
(1, 8, '2025-02-02', 'CB-2025-001', 6200000),
(3, 1, '2025-02-08', 'KF-2025-002', 7800000),
(1, 2, '2025-02-12', 'KB-2025-002', 11500000),
(2, 3, '2025-02-18', 'SB-2025-002', 5900000),
(1, 4, '2025-02-22', 'DX-2025-002', 8700000),
(3, 5, '2025-02-25', 'PH-2025-002', 4800000),

-- Maret 2025
(1, 6, '2025-03-05', 'IF-2025-002', 9100000),
(2, 7, '2025-03-10', 'TS-2025-002', 7300000),
(1, 8, '2025-03-15', 'CB-2025-002', 6600000),
(3, 1, '2025-03-20', 'KF-2025-003', 8200000),
(1, 2, '2025-03-25', 'KB-2025-003', 10800000),
(2, 3, '2025-03-28', 'SB-2025-003', 5700000),

-- April 2025
(1, 4, '2025-04-02', 'DX-2025-003', 9500000),
(3, 5, '2025-04-08', 'PH-2025-003', 5200000),
(1, 6, '2025-04-12', 'IF-2025-003', 7900000),
(2, 7, '2025-04-18', 'TS-2025-003', 8400000),
(1, 8, '2025-04-22', 'CB-2025-003', 6100000),
(3, 1, '2025-04-28', 'KF-2025-004', 7700000),

-- Mei 2025
(1, 2, '2025-05-05', 'KB-2025-004', 11200000),
(2, 3, '2025-05-10', 'SB-2025-004', 6300000),
(1, 4, '2025-05-15', 'DX-2025-004', 8800000),
(3, 5, '2025-05-20', 'PH-2025-004', 4900000),
(1, 6, '2025-05-25', 'IF-2025-004', 8600000),
(2, 7, '2025-05-28', 'TS-2025-004', 7800000),

-- Juni 2025 (sampai tanggal 15)
(1, 8, '2025-06-02', 'CB-2025-004', 5800000),
(3, 1, '2025-06-08', 'KF-2025-005', 8100000),
(1, 2, '2025-06-12', 'KB-2025-005', 10900000),
(2, 3, '2025-06-15', 'SB-2025-005', 6500000);

-- Insert detail pembelian untuk semua transaksi pembelian
INSERT INTO detail_pembelian (pembelian_id, obat_id, qty, harga_beli, nomor_batch, expired_date, satuan) VALUES
-- Detail pembelian 1-5 (Januari)
(1, 1, 1000, 300, 'KF250105001', '2026-07-05', 'tablet'),
(1, 31, 500, 600, 'KF250105002', '2026-06-05', 'tablet'),
(1, 9, 800, 250, 'KF250105003', '2026-05-05', 'tablet'),
(1, 41, 300, 700, 'KF250105004', '2026-04-05', 'tablet'),

(2, 21, 400, 800, 'KB250108001', '2026-08-08', 'kapsul'),
(2, 39, 300, 1000, 'KB250108002', '2026-07-08', 'tablet'),
(2, 5, 600, 300, 'KB250108003', '2026-09-08', 'tablet'),
(2, 10, 200, 600, 'KB250108004', '2026-06-08', 'kapsul'),
(2, 33, 250, 300, 'KB250108005', '2026-05-08', 'tablet'),

(3, 2, 500, 500, 'SB250112001', '2026-06-12', 'tablet'),
(3, 43, 200, 900, 'SB250112002', '2026-05-12', 'tablet'),
(3, 6, 400, 400, 'SB250112003', '2026-08-12', 'tablet'),
(3, 11, 300, 500, 'SB250112004', '2026-07-12', 'tablet'),

(4, 3, 300, 400, 'DX250115001', '2026-07-15', 'tablet'),
(4, 42, 150, 1200, 'DX250115002', '2026-06-15', 'tablet'),
(4, 7, 350, 500, 'DX250115003', '2026-08-15', 'tablet'),
(4, 45, 100, 1200, 'DX250115004', '2026-05-15', 'tablet'),
(4, 14, 400, 500, 'DX250115005', '2026-09-15', 'tablet'),

(5, 56, 120, 1200, 'PH250120001', '2026-06-20', 'kapsul'),
(5, 40, 250, 400, 'PH250120002', '2026-05-20', 'tablet'),
(5, 8, 300, 350, 'PH250120003', '2026-08-20', 'tablet'),
(5, 50, 80, 2000, 'PH250120004', '2026-04-20', 'tablet'),

-- Detail pembelian 6-10 (Januari-Februari)
(6, 15, 300, 800, 'IF250125001', '2026-07-25', 'tablet'),
(6, 25, 100, 1500, 'IF250125002', '2026-06-25', 'tablet'),
(6, 35, 200, 800, 'IF250125003', '2026-08-25', 'tablet'),
(6, 64, 150, 1000, 'IF250125004', '2026-05-25', 'tablet'),

(7, 16, 400, 600, 'TS250128001', '2026-08-28', 'tablet'),
(7, 26, 80, 1800, 'TS250128002', '2026-07-28', 'kapsul'),
(7, 37, 120, 1300, 'TS250128003', '2026-06-28', 'tablet'),
(7, 73, 180, 400, 'TS250128004', '2026-05-28', 'tablet'),

(8, 17, 250, 400, 'CB250202001', '2026-08-02', 'tablet'),
(8, 27, 60, 2500, 'CB250202002', '2026-07-02', 'tablet'),
(8, 38, 120, 800, 'CB250202003', '2026-06-02', 'tablet'),
(8, 74, 150, 500, 'CB250202004', '2026-05-02', 'tablet'),

(9, 1, 800, 300, 'KF250208001', '2026-08-08', 'tablet'),
(9, 68, 250, 1200, 'KF250208002', '2026-07-08', 'kapsul'),
(9, 75, 200, 600, 'KF250208003', '2026-06-08', 'tablet'),

(10, 21, 300, 800, 'KB250212001', '2026-09-12', 'kapsul'),
(10, 32, 180, 300, 'KB250212002', '2026-08-12', 'tablet'),
(10, 76, 150, 1200, 'KB250212003', '2026-07-12', 'tablet'),
(10, 5, 500, 300, 'KB250212004', '2026-10-12', 'tablet'),

-- Detail pembelian 11-15 (Februari)
(11, 2, 400, 500, 'SB250218001', '2026-08-18', 'tablet'),
(11, 43, 180, 900, 'SB250218002', '2026-07-18', 'tablet'),
(11, 6, 300, 400, 'SB250218003', '2026-09-18', 'tablet'),

(12, 3, 350, 400, 'DX250222001', '2026-08-22', 'tablet'),
(12, 42, 120, 1200, 'DX250222002', '2026-07-22', 'tablet'),
(12, 7, 400, 500, 'DX250222003', '2026-09-22', 'tablet'),
(12, 45, 80, 1200, 'DX250222004', '2026-06-22', 'tablet'),

(13, 56, 100, 1200, 'PH250225001', '2026-07-25', 'kapsul'),
(13, 40, 200, 400, 'PH250225002', '2026-06-25', 'tablet'),
(13, 8, 250, 350, 'PH250225003', '2026-08-25', 'tablet'),

-- Detail pembelian 16-20 (Maret)
(14, 15, 350, 800, 'IF250305001', '2026-09-05', 'tablet'),
(14, 25, 120, 1500, 'IF250305002', '2026-08-05', 'tablet'),
(14, 35, 180, 800, 'IF250305003', '2026-10-05', 'tablet'),

(15, 16, 300, 600, 'TS250310001', '2026-09-10', 'tablet'),
(15, 26, 70, 1800, 'TS250310002', '2026-08-10', 'kapsul'),
(15, 37, 100, 1300, 'TS250310003', '2026-07-10', 'tablet'),

(16, 17, 200, 400, 'CB250315001', '2026-09-15', 'tablet'),
(16, 27, 50, 2500, 'CB250315002', '2026-08-15', 'tablet'),
(16, 38, 100, 800, 'CB250315003', '2026-07-15', 'tablet'),

(17, 1, 700, 300, 'KF250320001', '2026-09-20', 'tablet'),
(17, 68, 200, 1200, 'KF250320002', '2026-08-20', 'kapsul'),
(17, 75, 180, 600, 'KF250320003', '2026-07-20', 'tablet'),

(18, 21, 280, 800, 'KB250325001', '2026-10-25', 'kapsul'),
(18, 32, 200, 300, 'KB250325002', '2026-09-25', 'tablet'),
(18, 76, 120, 1200, 'KB250325003', '2026-08-25', 'tablet'),

(19, 2, 350, 500, 'SB250328001', '2026-09-28', 'tablet'),
(19, 43, 150, 900, 'SB250328002', '2026-08-28', 'tablet'),

-- Detail pembelian 21-25 (April)
(20, 3, 400, 400, 'DX250402001', '2026-10-02', 'tablet'),
(20, 42, 100, 1200, 'DX250402002', '2026-09-02', 'tablet'),
(20, 7, 450, 500, 'DX250402003', '2026-11-02', 'tablet'),

(21, 56, 90, 1200, 'PH250408001', '2026-09-08', 'kapsul'),
(21, 40, 180, 400, 'PH250408002', '2026-08-08', 'tablet'),
(21, 8, 220, 350, 'PH250408003', '2026-10-08', 'tablet'),

(22, 15, 300, 800, 'IF250412001', '2026-10-12', 'tablet'),
(22, 25, 100, 1500, 'IF250412002', '2026-09-12', 'tablet'),
(22, 35, 160, 800, 'IF250412003', '2026-11-12', 'tablet'),

(23, 16, 280, 600, 'TS250418001', '2026-10-18', 'tablet'),
(23, 26, 60, 1800, 'TS250418002', '2026-09-18', 'kapsul'),
(23, 37, 90, 1300, 'TS250418003', '2026-08-18', 'tablet'),

(24, 17, 180, 400, 'CB250422001', '2026-10-22', 'tablet'),
(24, 27, 40, 2500, 'CB250422002', '2026-09-22', 'tablet'),
(24, 38, 80, 800, 'CB250422003', '2026-08-22', 'tablet'),

(25, 1, 600, 300, 'KF250428001', '2026-10-28', 'tablet'),
(25, 68, 180, 1200, 'KF250428002', '2026-09-28', 'kapsul'),

-- Detail pembelian 26-30 (Mei)
(26, 21, 250, 800, 'KB250505001', '2026-11-05', 'kapsul'),
(26, 32, 180, 300, 'KB250505002', '2026-10-05', 'tablet'),
(26, 76, 100, 1200, 'KB250505003', '2026-09-05', 'tablet'),

(27, 2, 300, 500, 'SB250510001', '2026-10-10', 'tablet'),
(27, 43, 120, 900, 'SB250510002', '2026-09-10', 'tablet'),
(27, 6, 250, 400, 'SB250510003', '2026-11-10', 'tablet'),

(28, 3, 350, 400, 'DX250515001', '2026-11-15', 'tablet'),
(28, 42, 90, 1200, 'DX250515002', '2026-10-15', 'tablet'),
(28, 7, 400, 500, 'DX250515003', '2026-12-15', 'tablet'),

(29, 56, 80, 1200, 'PH250520001', '2026-10-20', 'kapsul'),
(29, 40, 160, 400, 'PH250520002', '2026-09-20', 'tablet'),

(30, 15, 280, 800, 'IF250525001', '2026-11-25', 'tablet'),
(30, 25, 90, 1500, 'IF250525002', '2026-10-25', 'tablet'),
(30, 35, 140, 800, 'IF250525003', '2026-12-25', 'tablet'),

(31, 16, 250, 600, 'TS250528001', '2026-11-28', 'tablet'),
(31, 26, 50, 1800, 'TS250528002', '2026-10-28', 'kapsul'),

-- Detail pembelian 32-35 (Juni)
(32, 17, 160, 400, 'CB250602001', '2026-12-02', 'tablet'),
(32, 27, 35, 2500, 'CB250602002', '2026-11-02', 'tablet'),
(32, 38, 70, 800, 'CB250602003', '2026-10-02', 'tablet'),

(33, 1, 550, 300, 'KF250608001', '2026-12-08', 'tablet'),
(33, 68, 160, 1200, 'KF250608002', '2026-11-08', 'kapsul'),
(33, 75, 150, 600, 'KF250608003', '2026-10-08', 'tablet'),

(34, 21, 220, 800, 'KB250612001', '2026-12-12', 'kapsul'),
(34, 32, 160, 300, 'KB250612002', '2026-11-12', 'tablet'),
(34, 76, 90, 1200, 'KB250612003', '2026-10-12', 'tablet'),

(35, 2, 280, 500, 'SB250615001', '2026-11-15', 'tablet'),
(35, 43, 100, 900, 'SB250615002', '2026-10-15', 'tablet'),
(35, 6, 220, 400, 'SB250615003', '2026-12-15', 'tablet');