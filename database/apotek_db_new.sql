-- 1. Buat database
CREATE DATABASE IF NOT EXISTS `apotek_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `apotek_db`;

-- 2. Tabel Admin
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_admin` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` ENUM('pemilik', 'admin') NOT NULL DEFAULT 'admin',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Contoh data admin (password: admin123)
INSERT INTO `admin` (`nama_admin`, `username`, `password`, `role`) VALUES
('Kevin Bimo', 'kevin_bimo', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'pemilik'),
('Admin Satu', 'admin1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Admin Dua', 'admin2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- 3. Tabel Supplier
CREATE TABLE IF NOT EXISTS `supplier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_supplier` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `kota` varchar(50) NOT NULL,
  `telepon` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Contoh data supplier
INSERT INTO `supplier` (`nama_supplier`, `alamat`, `kota`, `telepon`) VALUES
('Kimia Farma', 'Jl. Sudirman No.1', 'Jakarta', '021123456'),
('Dexa Medica', 'Jl. Gatot Subroto No.2', 'Bandung', '022654321'),
('Phapros', 'Jl. Imam Bonjol No.3', 'Semarang', '024789654');

-- 4. Tabel Obat
CREATE TABLE IF NOT EXISTS `obat` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `bpom` VARCHAR(20) NOT NULL,
  `nama_obat` VARCHAR(100) NOT NULL,
  `harga` INT(11) NOT NULL,
  `produsen` VARCHAR(100) NOT NULL,
  `supplier_id` INT(11) NOT NULL,
  `stok` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bpom` (`bpom`),
  KEY `supplier_id` (`supplier_id`),
  CONSTRAINT `obat_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `obat` (`bpom`, `nama_obat`, `harga`, `produsen`, `supplier_id`, `stok`) VALUES
('PCT500', 'Paracetamol 500mg', 15000, 'PT. Bintang Farmasi', 1, 100),
('VITC', 'Vitamin C 500mg', 30000, 'PT. Sehat Abadi', 2, 50),
('AMOX', 'Amoxicillin 500mg', 25000, 'PT. Phapros', 3, 75);

-- 5. Tabel Member
CREATE TABLE IF NOT EXISTS `member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `poin` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Contoh data member
INSERT INTO `member` (`nama`, `no_hp`, `poin`) VALUES
('Budi Santoso', '08123456789', 0),
('Ani Wijaya', '08234567890', 5),
('Joko Prabowo', '08334567891', 10);

-- 6. Tabel Transaksi
CREATE TABLE IF NOT EXISTS `transaksi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal_transaksi` datetime NOT NULL DEFAULT current_timestamp(),
  `admin_id` int(11) NOT NULL,
  `nama_pembeli` varchar(100) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `total` int(11) NOT NULL,
  `poin_didapat` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`),
  KEY `member_id` (`member_id`),
  CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`),
  CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tambahkan kolom poin_digunakan dan potongan_harga
ALTER TABLE `transaksi` 
ADD COLUMN `poin_digunakan` int(11) DEFAULT 0 AFTER `poin_didapat`,
ADD COLUMN `potongan_harga` int(11) DEFAULT 0 AFTER `poin_digunakan`;

-- 7. Tabel Detail Transaksi
CREATE TABLE IF NOT EXISTS `detail_transaksi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaksi_id` int(11) NOT NULL,
  `obat_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `harga_saat_ini` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `transaksi_id` (`transaksi_id`),
  KEY `obat_id` (`obat_id`),
  CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`),
  CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`obat_id`) REFERENCES `obat` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Contoh data transaksi (opsional)
INSERT INTO `transaksi` (
  `tanggal_transaksi`, `admin_id`, `nama_pembeli`, `member_id`,
  `total`, `poin_didapat`, `poin_digunakan`, `potongan_harga`
) VALUES
('2024-04-15 10:30:00', 1, 'Budi Santoso', 1, 105000, 2, 2, 2000);

-- Detail transaksi
INSERT INTO `detail_transaksi` (`transaksi_id`, `obat_id`, `qty`, `harga_saat_ini`) VALUES
(1, 1, 2, 15000),
(1, 2, 3, 30000);