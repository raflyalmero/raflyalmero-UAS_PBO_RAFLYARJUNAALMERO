<?php

/**
 * File: index.php
 * Halaman utama untuk menampilkan semua data mahasiswa dengan rincian tagihan
 */

require_once 'database.php';

$db = getDB();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Mahasiswa - Polimorfisme</title>
    <style>
        /* Styles sama seperti sebelumnya, tambahan untuk rincian tagihan */
        .rincian-tagihan {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 6px;
            margin-top: 5px;
            font-size: 12px;
            border-left: 3px solid #1a237e;
        }
        .rincian-tagihan .label {
            color: #666;
        }
        .rincian-tagihan .value {
            font-weight: bold;
            color: #1a237e;
        }
        .tagihan-gratis {
            color: #2e7d32;
            font-weight: bold;
        }
        .tagihan-mandiri {
            color: #c62828;
            font-weight: bold;
        }
        .tagihan-prestasi {
            color: #ef6c00;
            font-weight: bold;
        }
        .diskon-badge {
            background: #4caf50;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>
        🎯 Polimorfisme - Sistem Informasi Mahasiswa
        <small>Method Overriding hitungTagihanSemester()</small>
    </h1>

    <?php
    try {
        $mandiri = new MahasiswaMandiri();
        $bidikmisi = new MahasiswaBidikmisi();
        $prestasi = new MahasiswaPrestasi();
        
        $dataMandiri = $mandiri->getDataMahasiswaMandiri($db);
        $dataBidikmisi = $bidikmisi->getDataMahasiswaBidikmisi($db);
        $dataPrestasi = $prestasi->getDataMahasiswaPrestasi($db);
        
        $totalMandiri = count($dataMandiri);
        $totalBidikmisi = count($dataBidikmisi);
        $totalPrestasi = count($dataPrestasi);
        $totalSemua = $totalMandiri + $totalBidikmisi + $totalPrestasi;
        
    } catch (Exception $e) {
        echo "<div style='background: #ffebee; color: #c62828; padding: 15px; border-radius: 6px; border: 1px solid #ef9a9a;'>";
        echo "<strong>Error:</strong> " . $e->getMessage();
        echo "</div>";
        $dataMandiri = $dataBidikmisi = $dataPrestasi = [];
        $totalMandiri = $totalBidikmisi = $totalPrestasi = $totalSemua = 0;
    }
    ?>

    <!-- ============ STATISTIK ============ -->
    <div class="stats">
        <div class="stat-box">
            <div class="number"><?= $totalSemua ?></div>
            <div class="label">Total Mahasiswa</div>
        </div>
        <div class="stat-box" style="border-color: #1976d2;">
            <div class="number" style="color: #1976d2;"><?= $totalMandiri ?></div>
            <div class="label">💼 Mandiri (UKT + Rp100.000)</div>
        </div>
        <div class="stat-box" style="border-color: #388e3c;">
            <div class="number" style="color: #388e3c;"><?= $totalBidikmisi ?></div>
            <div class="label">🎓 Bidikmisi (GRATIS)</div>
        </div>
        <div class="stat-box" style="border-color: #f57c00;">
            <div class="number" style="color: #f57c00;"><?= $totalPrestasi ?></div>
            <div class="label">🏆 Prestasi (Diskon 75%)</div>
        </div>
    </div>

    <!-- ============ LEGENDA ============ -->
    <div style="background: #e3f2fd; padding: 12px 15px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid #1976d2;">
        <strong>📌 Logika Perhitungan Tagihan:</strong>
        <div style="display: flex; gap: 30px; flex-wrap: wrap; margin-top: 8px; font-size: 14px;">
            <div>🔵 <strong>Mandiri:</strong> UKT + Rp 100.000 (biaya operasional)</div>
            <div>🟢 <strong>Bidikmisi:</strong> Rp 0 (gratis, ditanggung negara)</div>
            <div>🟠 <strong>Prestasi:</strong> UKT × 25% (diskon 75%)</div>
        </div>
    </div>

    <!-- ============ SECTION 1: MANDIRI ============ -->
    <div class="section">
        <h2>💼 Mahasiswa Mandiri <span style="font-size:14px;font-weight:normal;color:#666;">(<?= $totalMandiri ?> data)</span></h2>
        
        <?php if (!empty($dataMandiri)): ?>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Semester</th>
                    <th>UKT</th>
                    <th>Golongan</th>
                    <th>Rincian Tagihan</th>
                    <th>Total Tagihan</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                $totalAll = 0;
                foreach ($dataMandiri as $row): 
                    $obj = new MahasiswaMandiri($row);
                    $rincian = $obj->getRincianTagihan();
                    $totalAll += $rincian['total'];
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><strong><?= htmlspecialchars($row['nama_mahasiswa']) ?></strong></td>
                    <td><?= htmlspecialchars($row['nim']) ?></td>
                    <td><?= $row['semester'] ?></td>
                    <td>Rp <?= number_format($row['tarif_ukt_nominal'], 0, ',', '.') ?></td>
                    <td><span class="badge badge-ukt-<?= strtolower($row['golongan_ukt'] ?? 'b') ?>"><?= $row['golongan_ukt'] ?? '-' ?></span></td>
                    <td>
                        <div class="rincian-tagihan">
                            <span class="label">UKT:</span> <span class="value">Rp <?= number_format($rincian['ukt'], 0, ',', '.') ?></span><br>
                            <span class="label">+ Biaya Operasional:</span> <span class="value">Rp <?= number_format($rincian['biaya_operasional'], 0, ',', '.') ?></span>
                        </div>
                    </td>
                    <td class="tagihan-mandiri">Rp <?= number_format($rincian['total'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7" style="text-align:right;font-weight:bold;">Total Tagihan Keseluruhan:</td>
                    <td style="font-weight:bold;color:#c62828;">Rp <?= number_format($totalAll, 0, ',', '.') ?></td>
                </tr>
            </tfoot>
        </table>
        <?php else: ?>
        <p style="color: #999; font-style: italic;">Belum ada data mahasiswa mandiri.</p>
        <?php endif; ?>
    </div>

    <!-- ============ SECTION 2: BIDIKMISI ============ -->
    <div class="section">
        <h2>🎓 Mahasiswa Bidikmisi <span style="font-size:14px;font-weight:normal;color:#666;">(<?= $totalBidikmisi ?> data)</span></h2>
        
        <?php if (!empty($dataBidikmisi)): ?>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Semester</th>
                    <th>UKT</th>
                    <th>No. KIP</th>
                    <th>Rincian Tagihan</th>
                    <th>Total Tagihan</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                $totalDanaSaku = 0;
                foreach ($dataBidikmisi as $row): 
                    $obj = new MahasiswaBidikmisi($row);
                    $rincian = $obj->getRincianTagihan();
                    $totalDanaSaku += $row['dana_saku_subsidi'] ?? 0;
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><strong><?= htmlspecialchars($row['nama_mahasiswa']) ?></strong></td>
                    <td><?= htmlspecialchars($row['nim']) ?></td>
                    <td><?= $row['semester'] ?></td>
                    <td>Rp <?= number_format($row['tarif_ukt_nominal'], 0, ',', '.') ?></td>
                    <td><?= htmlspecialchars($row['nomor_kip_kuliah'] ?? '-') ?></td>
                    <td>
                        <div class="rincian-tagihan" style="border-left-color: #388e3c;">
                            <span class="label">UKT:</span> <span class="value">Rp <?= number_format($rincian['ukt'], 0, ',', '.') ?></span><br>
                            <span class="label">Subsidi Negara:</span> <span class="value" style="color:#388e3c;">Rp <?= number_format($rincian['subsidi_negara'], 0, ',', '.') ?></span><br>
                            <span class="label">Dana Saku:</span> <span class="value">Rp <?= number_format($rincian['dana_saku'], 0, ',', '.') ?></span>
                        </div>
                    </td>
                    <td class="tagihan-gratis">Rp 0 (GRATIS)</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" style="text-align:right;font-weight:bold;">Total Dana Saku:</td>
                    <td><strong>Rp <?= number_format($totalDanaSaku, 0, ',', '.') ?></strong></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        <?php else: ?>
        <p style="color: #999; font-style: italic;">Belum ada data mahasiswa bidikmisi.</p>
        <?php endif; ?>
    </div>

    <!-- ============ SECTION 3: PRESTASI ============ -->
    <div class="section">
        <h2>🏆 Mahasiswa Prestasi <span style="font-size:14px;font-weight:normal;color:#666;">(<?= $totalPrestasi ?> data)</span></h2>
        
        <?php if (!empty($dataPrestasi)): ?>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Semester</th>
                    <th>UKT Awal</th>
                    <th>Instansi Beasiswa</th>
                    <th>Rincian Tagihan</th>
                    <th>Total Tagihan</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                $totalDiskon = 0;
                $totalBayar = 0;
                foreach ($dataPrestasi as $row): 
                    $obj = new MahasiswaPrestasi($row);
                    $rincian = $obj->getRincianTagihan();
                    $totalDiskon += $rincian['diskon'];
                    $totalBayar += $rincian['total'];
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><strong><?= htmlspecialchars($row['nama_mahasiswa']) ?></strong></td>
                    <td><?= htmlspecialchars($row['nim']) ?></td>
                    <td><?= $row['semester'] ?></td>
                    <td>Rp <?= number_format($row['tarif_ukt_nominal'], 0, ',', '.') ?></td>
                    <td><?= htmlspecialchars($row['nama_instansi_beasiswa'] ?? '-') ?></td>
                    <td>
                        <div class="rincian-tagihan" style="border-left-color: #f57c00;">
                            <span class="label">Diskon 75%:</span> 
                            <span class="value" style="color:#2e7d32;">- Rp <?= number_format($rincian['diskon'], 0, ',', '.') ?></span><br>
                            <span class="label">IPK Syarat:</span> 
                            <span class="value"><?= number_format($rincian['ipk_syarat'], 2) ?></span>
                        </div>
                    </td>
                    <td class="tagihan-prestasi">Rp <?= number_format($rincian['total'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" style="text-align:right;font-weight:bold;">Total Diskon:</td>
                    <td><strong style="color:#2e7d32;">Rp <?= number_format($totalDiskon, 0, ',', '.') ?></strong></td>
                    <td><strong style="color:#ef6c00;">Rp <?= number_format($totalBayar, 0, ',', '.') ?></strong></td>
                </tr>
            </tfoot>
        </table>
        <?php else: ?>
        <p style="color: #999; font-style: italic;">Belum ada data mahasiswa prestasi.</p>
        <?php endif; ?>
    </div>

    <!-- ============ FOOTER ============ --
    <div class="footer">
        <p>
            <strong>Total Mahasiswa:</strong> <?= $totalSemua ?> | 
            <strong>Mandiri:</strong> <?= $totalMandiri ?> | 
            <strong>Bidikmisi:</strong> <?= $totalBidikmisi ?> | 
            <strong>Prestasi:</strong> <?= $totalPrestasi ?>
        </p>
        <p style="font-size: 12px; color: #999; margin-top: 5px;">
            Implementasi Polimorfisme - Method Overriding hitungTagihanSemester()
        </p>
    </div>

</div>
</body>
</html>