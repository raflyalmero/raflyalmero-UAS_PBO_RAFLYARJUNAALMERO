<?php
/**
 * File: index.php
 * Halaman utama untuk menampilkan daftar registrasi pembayaran kuliah
 * dengan tampilan terpisah per katego
 */

require_once 'database.php';

$db = getDB();

// Inisialisasi objek
$mandiri = new MahasiswaMandiri();
$bidikmisi = new MahasiswaBidikmisi();
$prestasi = new MahasiswaPrestasi();

// Ambil data dari database
$dataMandiri = $mandiri->getDataMahasiswaMandiri($db);
$dataBidikmisi = $bidikmisi->getDataMahasiswaBidikmisi($db);
$dataPrestasi = $prestasi->getDataMahasiswaPrestasi($db);

// Hitung statistik
$totalMandiri = count($dataMandiri);
$totalBidikmisi = count($dataBidikmisi);
$totalPrestasi = count($dataPrestasi);
$totalSemua = $totalMandiri + $totalBidikmisi + $totalPrestasi;

// Hitung total tagihan per kategori
$totalTagihanMandiri = 0;
foreach ($dataMandiri as $row) {
    $obj = new MahasiswaMandiri($row);
    $totalTagihanMandiri += $obj->hitungTagihanSemester();
}

$totalTagihanBidikmisi = 0;
foreach ($dataBidikmisi as $row) {
    $obj = new MahasiswaBidikmisi($row);
    $totalTagihanBidikmisi += $obj->hitungTagihanSemester();
}

$totalTagihanPrestasi = 0;
foreach ($dataPrestasi as $row) {
    $obj = new MahasiswaPrestasi($row);
    $totalTagihanPrestasi += $obj->hitungTagihanSemester();
}

$totalTagihanSemua = $totalTagihanMandiri + $totalTagihanBidikmisi + $totalTagihanPrestasi;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pembayaran Kuliah</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* ===== RESET & BASE ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f4f8;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        /* ===== HEADER ===== */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 4px solid #1a237e;
            padding-bottom: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .header h1 {
            color: #1a237e;
            font-size: 28px;
        }
        .header h1 i {
            margin-right: 10px;
        }
        .header .subtitle {
            color: #666;
            font-size: 14px;
            font-weight: normal;
        }
        .header .date {
            background: #e8eaf6;
            padding: 8px 16px;
            border-radius: 20px;
            color: #1a237e;
            font-size: 14px;
        }

        /* ===== STATISTIK ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            border: 1px solid #e0e0e0;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .stat-card .number {
            font-size: 32px;
            font-weight: bold;
        }
        .stat-card .label {
            font-size: 13px;
            color: #666;
            margin-top: 5px;
        }
        .stat-card .icon {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .stat-card.total { border-top: 4px solid #1a237e; }
        .stat-card.total .number { color: #1a237e; }
        .stat-card.mandiri { border-top: 4px solid #1976d2; }
        .stat-card.mandiri .number { color: #1976d2; }
        .stat-card.bidikmisi { border-top: 4px solid #388e3c; }
        .stat-card.bidikmisi .number { color: #388e3c; }
        .stat-card.prestasi { border-top: 4px solid #f57c00; }
        .stat-card.prestasi .number { color: #f57c00; }
        .stat-card.tagihan { border-top: 4px solid #c62828; }
        .stat-card.tagihan .number { color: #c62828; }

        /* ===== LEGENDA ===== */
        .legend {
            background: #e3f2fd;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 4px solid #1976d2;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            align-items: center;
        }
        .legend strong {
            color: #1a237e;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
        .legend-item .color-box {
            width: 16px;
            height: 16px;
            border-radius: 4px;
        }
        .legend-item .color-box.mandiri { background: #1976d2; }
        .legend-item .color-box.bidikmisi { background: #388e3c; }
        .legend-item .color-box.prestasi { background: #f57c00; }

        /* ===== SECTION KATEGORI ===== */
        .category-section {
            margin-bottom: 40px;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #e0e0e0;
        }
        .category-header {
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        .category-header .title {
            font-size: 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .category-header .title i {
            font-size: 22px;
        }
        .category-header .badge-count {
            background: rgba(255,255,255,0.3);
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 14px;
        }
        .category-header .total-tagihan {
            font-size: 14px;
            font-weight: 500;
        }
        .category-header.mandiri {
            background: linear-gradient(135deg, #1976d2, #0d47a1);
            color: white;
        }
        .category-header.bidikmisi {
            background: linear-gradient(135deg, #388e3c, #1b5e20);
            color: white;
        }
        .category-header.prestasi {
            background: linear-gradient(135deg, #f57c00, #e65100);
            color: white;
        }

        .category-body {
            padding: 20px;
            background: #fafafa;
            overflow-x: auto;
        }

        /* ===== TABEL ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        table thead {
            background: #263238;
            color: white;
        }
        table th {
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        table td {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }
        table tbody tr:hover {
            background: #f5f5f5;
        }
        table tbody tr:last-child td {
            border-bottom: none;
        }

        /* ===== BADGE ===== */
        .badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            color: white;
        }
        .badge-ukt-a { background: #c62828; }
        .badge-ukt-b { background: #ef6c00; }
        .badge-ukt-c { background: #2e7d32; }
        .badge-ukt-d { background: #00695c; }
        .badge-status { background: #1a237e; }
        .badge-gratis { background: #388e3c; }
        .badge-diskon { background: #f57c00; }

        /* ===== RINCIAN TAGIHAN ===== */
        .rincian-tagihan {
            font-size: 12px;
            line-height: 1.6;
        }
        .rincian-tagihan .row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }
        .rincian-tagihan .label {
            color: #666;
        }
        .rincian-tagihan .value {
            font-weight: 600;
        }
        .rincian-tagihan .value.positive { color: #c62828; }
        .rincian-tagihan .value.negative { color: #2e7d32; }
        .rincian-tagihan .value.neutral { color: #f57c00; }

        /* ===== TAGIHAN TOTAL ===== */
        .tagihan-total {
            font-weight: 700;
            font-size: 16px;
        }
        .tagihan-total.mandiri { color: #c62828; }
        .tagihan-total.bidikmisi { color: #388e3c; }
        .tagihan-total.prestasi { color: #f57c00; }
        .tagihan-total .gratis {
            color: #388e3c;
            font-weight: 700;
        }

        /* ===== FOOTER ===== */
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .footer .total-rekap {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }
        .footer .total-rekap span {
            font-weight: 600;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .container { padding: 15px; }
            .header h1 { font-size: 20px; }
            table { font-size: 12px; }
            table th, table td { padding: 8px 10px; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .category-header { flex-direction: column; align-items: flex-start; }
            .legend { flex-direction: column; align-items: flex-start; }
            .rincian-tagihan .row { flex-direction: column; gap: 2px; }
        }
        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
        }

        /* ===== PRINT ===== */
        @media print {
            body { background: white; padding: 10px; }
            .container { box-shadow: none; padding: 15px; }
            .stat-card:hover { transform: none; box-shadow: none; }
            .category-header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .badge { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>
<div class="container">

    <!-- ===== HEADER ===== -->
    <div class="header">
        <div>
            <h1><i class="fas fa-graduation-cap"></i> Registrasi Pembayaran Kuliah</h1>
            <span class="subtitle">Sistem Informasi Mahasiswa - OOP PHP</span>
        </div>
        <div class="date">
            <i class="far fa-calendar-alt"></i> <?= date('d F Y H:i') ?> WIB
        </div>
    </div>

    <!-- ===== STATISTIK ===== -->
    <div class="stats-grid">
        <div class="stat-card total">
            <div class="icon"><i class="fas fa-users"></i></div>
            <div class="number"><?= $totalSemua ?></div>
            <div class="label">Total Mahasiswa</div>
        </div>
        <div class="stat-card mandiri">
            <div class="icon"><i class="fas fa-user-tie"></i></div>
            <div class="number"><?= $totalMandiri ?></div>
            <div class="label">Mahasiswa Mandiri</div>
        </div>
        <div class="stat-card bidikmisi">
            <div class="icon"><i class="fas fa-hand-holding-heart"></i></div>
            <div class="number"><?= $totalBidikmisi ?></div>
            <div class="label">Mahasiswa Bidikmisi</div>
        </div>
        <div class="stat-card prestasi">
            <div class="icon"><i class="fas fa-trophy"></i></div>
            <div class="number"><?= $totalPrestasi ?></div>
            <div class="label">Mahasiswa Prestasi</div>
        </div>
        <div class="stat-card tagihan">
            <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
            <div class="number">Rp <?= number_format($totalTagihanSemua, 0, ',', '.') ?></div>
            <div class="label">Total Tagihan Keseluruhan</div>
        </div>
    </div>

    <!-- ===== LEGENDA ===== -->
    <div class="legend">
        <strong><i class="fas fa-info-circle"></i> Logika Perhitungan:</strong>
        <div class="legend-item">
            <span class="color-box mandiri"></span>
            <span><strong>Mandiri:</strong> UKT + Rp 100.000</span>
        </div>
        <div class="legend-item">
            <span class="color-box bidikmisi"></span>
            <span><strong>Bidikmisi:</strong> Rp 0 (Gratis)</span>
        </div>
        <div class="legend-item">
            <span class="color-box prestasi"></span>
            <span><strong>Prestasi:</strong> UKT × 25% (Diskon 75%)</span>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- ===== SECTION 1: MAHASISWA MANDIRI ===== -->
    <!-- ============================================================ -->
    <div class="category-section">
        <div class="category-header mandiri">
            <div class="title">
                <i class="fas fa-user-tie"></i>
                Mahasiswa Mandiri
                <span class="badge-count"><?= $totalMandiri ?> Mahasiswa</span>
            </div>
            <div class="total-tagihan">
                <i class="fas fa-coins"></i>
                Total Tagihan: Rp <?= number_format($totalTagihanMandiri, 0, ',', '.') ?>
            </div>
        </div>
        <div class="category-body">
            <?php if (!empty($dataMandiri)): ?>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Mahasiswa</th>
                        <th>NIM</th>
                        <th>Semester</th>
                        <th>Golongan UKT</th>
                        <th>Nama Wali</th>
                        <th>Rincian Tagihan</th>
                        <th>Total Tagihan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach ($dataMandiri as $row): 
                        $obj = new MahasiswaMandiri($row);
                        $rincian = $obj->getRincianTagihan();
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><strong><?= htmlspecialchars($row['nama_mahasiswa']) ?></strong></td>
                        <td><?= htmlspecialchars($row['nim']) ?></td>
                        <td>Semester <?= $row['semester'] ?></td>
                        <td><span class="badge badge-ukt-<?= strtolower($row['golongan_ukt'] ?? 'b') ?>"><?= $row['golongan_ukt'] ?? '-' ?></span></td>
                        <td><?= htmlspecialchars($row['nama_wali'] ?? '-') ?></td>
                        <td>
                            <div class="rincian-tagihan">
                                <div class="row">
                                    <span class="label">UKT:</span>
                                    <span class="value">Rp <?= number_format($rincian['ukt'], 0, ',', '.') ?></span>
                                </div>
                                <div class="row">
                                    <span class="label">Biaya Operasional:</span>
                                    <span class="value positive">+ Rp <?= number_format($rincian['biaya_operasional'], 0, ',', '.') ?></span>
                                </div>
                            </div>
                        </td>
                        <td class="tagihan-total mandiri">
                            Rp <?= number_format($rincian['total'], 0, ',', '.') ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr style="background: #f5f5f5; font-weight: 600;">
                        <td colspan="7" style="text-align: right;">Total Tagihan Mandiri:</td>
                        <td style="color: #c62828;">Rp <?= number_format($totalTagihanMandiri, 0, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
            <?php else: ?>
            <p style="text-align: center; color: #999; padding: 30px;">
                <i class="fas fa-info-circle"></i> Belum ada data mahasiswa mandiri.
            </p>
            <?php endif; ?>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- ===== SECTION 2: MAHASISWA BIDIKMISI ===== -->
    <!-- ============================================================ -->
    <div class="category-section">
        <div class="category-header bidikmisi">
            <div class="title">
                <i class="fas fa-hand-holding-heart"></i>
                Mahasiswa Bidikmisi
                <span class="badge-count"><?= $totalBidikmisi ?> Mahasiswa</span>
            </div>
            <div class="total-tagihan">
                <i class="fas fa-coins"></i>
                Total Tagihan: Rp <?= number_format($totalTagihanBidikmisi, 0, ',', '.') ?>
                <span style="font-size:12px; opacity:0.8;"> (Gratis)</span>
            </div>
        </div>
        <div class="category-body">
            <?php if (!empty($dataBidikmisi)): ?>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Mahasiswa</th>
                        <th>NIM</th>
                        <th>Semester</th>
                        <th>No. KIP Kuliah</th>
                        <th>Dana Saku</th>
                        <th>Rincian Tagihan</th>
                        <th>Total Tagihan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach ($dataBidikmisi as $row): 
                        $obj = new MahasiswaBidikmisi($row);
                        $rincian = $obj->getRincianTagihan();
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><strong><?= htmlspecialchars($row['nama_mahasiswa']) ?></strong></td>
                        <td><?= htmlspecialchars($row['nim']) ?></td>
                        <td>Semester <?= $row['semester'] ?></td>
                        <td><?= htmlspecialchars($row['nomor_kip_kuliah'] ?? '-') ?></td>
                        <td>Rp <?= number_format($row['dana_saku_subsidi'] ?? 0, 0, ',', '.') ?></td>
                        <td>
                            <div class="rincian-tagihan">
                                <div class="row">
                                    <span class="label">UKT:</span>
                                    <span class="value">Rp <?= number_format($rincian['ukt'], 0, ',', '.') ?></span>
                                </div>
                                <div class="row">
                                    <span class="label">Subsidi Negara:</span>
                                    <span class="value negative">- Rp <?= number_format($rincian['subsidi_negara'], 0, ',', '.') ?></span>
                                </div>
                                <div class="row" style="border-top: 1px dashed #ccc; padding-top: 4px;">
                                    <span class="label">Dana Saku:</span>
                                    <span class="value neutral">Rp <?= number_format($rincian['dana_saku'], 0, ',', '.') ?></span>
                                </div>
                            </div>
                        </td>
                        <td class="tagihan-total bidikmisi">
                            <span class="gratis"><i class="fas fa-check-circle"></i> GRATIS</span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr style="background: #f5f5f5; font-weight: 600;">
                        <td colspan="7" style="text-align: right;">Total Tagihan Bidikmisi (Gratis):</td>
                        <td style="color: #388e3c;">Rp 0</td>
                    </tr>
                </tfoot>
            </table>
            <?php else: ?>
            <p style="text-align: center; color: #999; padding: 30px;">
                <i class="fas fa-info-circle"></i> Belum ada data mahasiswa bidikmisi.
            </p>
            <?php endif; ?>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- ===== SECTION 3: MAHASISWA PRESTASI ===== -->
    <!-- ============================================================ -->
    <div class="category-section">
        <div class="category-header prestasi">
            <div class="title">
                <i class="fas fa-trophy"></i>
                Mahasiswa Prestasi
                <span class="badge-count"><?= $totalPrestasi ?> Mahasiswa</span>
            </div>
            <div class="total-tagihan">
                <i class="fas fa-coins"></i>
                Total Tagihan: Rp <?= number_format($totalTagihanPrestasi, 0, ',', '.') ?>
                <span style="font-size:12px; opacity:0.8;"> (Diskon 75%)</span>
            </div>
        </div>
        <div class="category-body">
            <?php if (!empty($dataPrestasi)): ?>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Mahasiswa</th>
                        <th>NIM</th>
                        <th>Semester</th>
                        <th>Instansi Beasiswa</th>
                        <th>Min. IPK</th>
                        <th>Rincian Tagihan</th>
                        <th>Total Tagihan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach ($dataPrestasi as $row): 
                        $obj = new MahasiswaPrestasi($row);
                        $rincian = $obj->getRincianTagihan();
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><strong><?= htmlspecialchars($row['nama_mahasiswa']) ?></strong></td>
                        <td><?= htmlspecialchars($row['nim']) ?></td>
                        <td>Semester <?= $row['semester'] ?></td>
                        <td><?= htmlspecialchars($row['nama_instansi_beasiswa'] ?? '-') ?></td>
                        <td><?= number_format($row['minimal_ipk_bersyarat'] ?? 0, 2) ?></td>
                        <td>
                            <div class="rincian-tagihan">
                                <div class="row">
                                    <span class="label">UKT Awal:</span>
                                    <span class="value">Rp <?= number_format($rincian['ukt_awal'], 0, ',', '.') ?></span>
                                </div>
                                <div class="row">
                                    <span class="label">Diskon 75%:</span>
                                    <span class="value negative">- Rp <?= number_format($rincian['diskon'], 0, ',', '.') ?></span>
                                </div>
                                <div class="row" style="border-top: 1px dashed #ccc; padding-top: 4px;">
                                    <span class="label">IPK Syarat:</span>
                                    <span class="value neutral"><?= number_format($rincian['ipk_syarat'], 2) ?></span>
                                </div>
                            </div>
                        </td>
                        <td class="tagihan-total prestasi">
                            Rp <?= number_format($rincian['total'], 0, ',', '.') ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr style="background: #f5f5f5; font-weight: 600;">
                        <td colspan="7" style="text-align: right;">Total Tagihan Prestasi (setelah diskon):</td>
                        <td style="color: #f57c00;">Rp <?= number_format($totalTagihanPrestasi, 0, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
            <?php else: ?>
            <p style="text-align: center; color: #999; padding: 30px;">
                <i class="fas fa-info-circle"></i> Belum ada data mahasiswa prestasi.
            </p>
            <?php endif; ?>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- ===== REKAPITULASI AKHIR ===== -->
    <!-- ============================================================ -->
    <div style="background: #e8eaf6; border-radius: 10px; padding: 20px; margin-top: 20px;">
        <h3 style="color: #1a237e; margin-bottom: 15px;">
            <i class="fas fa-chart-pie"></i> Rekapitulasi Tagihan
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <div style="background: white; padding: 15px; border-radius: 8px; border-left: 4px solid #1976d2;">
                <div style="color: #666; font-size: 12px;">MANDIRI</div>
                <div style="font-size: 20px; font-weight: bold; color: #1976d2;">
                    Rp <?= number_format($totalTagihanMandiri, 0, ',', '.') ?>
                </div>
                <div style="font-size: 12px; color: #999;"><?= $totalMandiri ?> mahasiswa</div>
            </div>
            <div style="background: white; padding: 15px; border-radius: 8px; border-left: 4px solid #388e3c;">
                <div style="color: #666; font-size: 12px;">BIDIKMISI</div>
                <div style="font-size: 20px; font-weight: bold; color: #388e3c;">
                    Rp 0
                </div>
                <div style="font-size: 12px; color: #999;"><?= $totalBidikmisi ?> mahasiswa (Gratis)</div>
            </div>
            <div style="background: white; padding: 15px; border-radius: 8px; border-left: 4px solid #f57c00;">
                <div style="color: #666; font-size: 12px;">PRESTASI</div>
                <div style="font-size: 20px; font-weight: bold; color: #f57c00;">
                    Rp <?= number_format($totalTagihanPrestasi, 0, ',', '.') ?>
                </div>
                <div style="font-size: 12px; color: #999;"><?= $totalPrestasi ?> mahasiswa (Diskon 75%)</div>
            </div>
            <div style="background: white; padding: 15px; border-radius: 8px; border-left: 4px solid #c62828;">
                <div style="color: #666; font-size: 12px;">TOTAL KESELURUHAN</div>
                <div style="font-size: 24px; font-weight: bold; color: #c62828;">
                    Rp <?= number_format($totalTagihanSemua, 0, ',', '.') ?>
                </div>
                <div style="font-size: 12px; color: #999;"><?= $totalSemua ?> mahasiswa</div>
            </div>
        </div>
    </div>

    <!-- ===== FOOTER ===== -->
    <div class="footer">
        <div class="total-rekap">
            <span>Total Mahasiswa: <strong><?= $totalSemua ?></strong></span>
            <span>Mandiri: <strong><?= $totalMandiri ?></strong></span>
            <span>Bidikmisi: <strong><?= $totalBidikmisi ?></strong></span>
            <span>Prestasi: <strong><?= $totalPrestasi ?></strong></span>
        </div>
        <p style="font-size: 12px; color: #999;">
            <i class="far fa-copyright"></i> <?= date('Y') ?> - Sistem Informasi Mahasiswa OOP PHP 
            | Implementasi Polimorfisme & Method Overriding
        </p>
    </div>

</div>
</body>
</html>