-- =====================================================
-- QUERY LAPORAN SIAP PAKAI
-- =====================================================

-- Query untuk 10 obat terlaris
-- SELECT o.nama_obat, SUM(dp.qty) as total_terjual, SUM(dp.qty * dp.harga_saat_ini) as total_pendapatan
-- FROM obat o 
-- JOIN detail_penjualan dp ON o.id = dp.obat_id
-- GROUP BY o.id, o.nama_obat
-- ORDER BY total_terjual DESC
-- LIMIT 10;

-- Query untuk laporan penjualan harian
-- SELECT DATE(tp.tanggal_transaksi) as tanggal,
--        COUNT(tp.id) as jumlah_transaksi,
--        SUM(tp.total) as total_penjualan,
--        SUM(tp.poin_didapat) as total_poin_diberikan
-- FROM transaksi_penjualan tp
-- GROUP BY DATE(tp.tanggal_transaksi)
-- ORDER BY tanggal DESC;

-- Query untuk laporan penjualan bulanan
-- SELECT YEAR(tp.tanggal_transaksi) as tahun,
--        MONTH(tp.tanggal_transaksi) as bulan,
--        COUNT(tp.id) as jumlah_transaksi,
--        SUM(tp.total) as total_penjualan
-- FROM transaksi_penjualan tp
-- GROUP BY YEAR(tp.tanggal_transaksi), MONTH(tp.tanggal_transaksi)
-- ORDER BY tahun DESC, bulan DESC;

-- Query untuk supplier terbanyak supply
-- SELECT s.nama_supplier, 
--        COUNT(tpb.id) as jumlah_transaksi_pembelian,
--        SUM(tpb.total) as total_pembelian
-- FROM supplier s
-- JOIN transaksi_pembelian tpb ON s.id = tpb.supplier_id
-- GROUP BY s.id, s.nama_supplier
-- ORDER BY jumlah_transaksi_pembelian DESC;

-- Query untuk margin keuntungan obat
-- SELECT o.nama_obat, o.harga_beli, o.harga_jual,
--        (o.harga_jual - o.harga_beli) as margin,
--        ROUND(((o.harga_jual - o.harga_beli) / o.harga_beli * 100), 2) as margin_persen
-- FROM obat o
-- ORDER BY margin_persen DESC;

-- Query untuk stok menipis (stok < 100)
-- SELECT nama_obat, stok, kategori, satuan
-- FROM obat
-- WHERE stok < 100
-- ORDER BY stok ASC;

-- =====================================================
-- END OF SCRIPT
-- =====================================================
