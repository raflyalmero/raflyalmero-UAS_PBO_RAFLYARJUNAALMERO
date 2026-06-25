<?php

/**
 * File: MahasiswaMandiri.php
 * Class MahasiswaMandiri - Subclass dari Mahasisw
 */

class MahasiswaMandiri extends Mahasiswa {
    // Properti tambahan
    private $golonganUkt;
    private $namaWali;
    
    // Konstanta biaya operasional
    const BIAYA_OPERASIONAL = 100000;
    
    /**
     * Constructor
     * @param array $data Array asosiatif dari hasil query database
     */
    public function __construct($data = []) {
        parent::__construct($data);
        $this->golonganUkt = $data['golongan_ukt'] ?? '';
        $this->namaWali = $data['nama_wali'] ?? '';
    }
    
    // Getter dan Setter
    public function getGolonganUkt() {
        return $this->golonganUkt;
    }
    
    public function setGolonganUkt($golonganUkt) {
        $this->golonganUkt = $golonganUkt;
        return $this;
    }
    
    public function getNamaWali() {
        return $this->namaWali;
    }
    
    public function setNamaWali($namaWali) {
        $this->namaWali = $namaWali;
        return $this;
    }
    
    /**
     * METHOD OVERRIDE: hitungTagihanSemester()
     * Logika: Total Tagihan = tarifUKTNominal + 100.000 (biaya operasional)
     * 
     * @return float Total tagihan semester
     */
    public function hitungTagihanSemester() {
        // Mahasiswa Mandiri membayar UKT penuh + biaya operasional
        return $this->tarifUKTNominal + self::BIAYA_OPERASIONAL;
    }
    
    /**
     * Method untuk mendapatkan rincian tagihan
     * @return array Rincian tagihan
     */
    public function getRincianTagihan() {
        return [
            'ukt' => $this->tarifUKTNominal,
            'biaya_operasional' => self::BIAYA_OPERASIONAL,
            'total' => $this->hitungTagihanSemester()
        ];
    }
    
    /**
     * METHOD OVERRIDE: tampilkanSpesifikasiAkademik()
     */
    public function tampilkanSpesifikasiAkademik() {
        echo "\n=== SPESIFIKASI MAHASISWA MANDIRI ===\n";
        echo "Golongan UKT       : " . $this->golonganUkt . "\n";
        echo "Nama Wali          : " . ($this->namaWali ?: 'Tidak ada wali') . "\n";
        echo "Status             : Mahasiswa Reguler\n";
        echo "Biaya Operasional  : Rp " . number_format(self::BIAYA_OPERASIONAL, 0, ',', '.') . "\n";
        echo "------------------------------------\n";
        echo "Tagihan Semester   : Rp " . number_format($this->hitungTagihanSemester(), 0, ',', '.') . "\n";
        echo "====================================\n";
    }
    
    /**
     * Method untuk mengambil data mahasiswa mandiri dari database
     * @param PDO $db Koneksi database
     * @param int $id ID mahasiswa (opsional)
     * @return array Hasil query
     */
    public function getDataMahasiswaMandiri($db, $id = null) {
        try {
            $sql = "SELECT 
                        id_mahasiswa,
                        nama_mahasiswa,
                        nim,
                        semester,
                        tarif_ukt_nominal,
                        jenis_pembiayaan,
                        golongan_ukt,
                        nama_wali
                    FROM tabel_mahasiswa 
                    WHERE jenis_pembiayaan = 'Mandiri'";
            
            if ($id !== null) {
                $sql .= " AND id_mahasiswa = :id";
            }
            
            $sql .= " ORDER BY id_mahasiswa ASC";
            
            $stmt = $db->prepare($sql);
            
            if ($id !== null) {
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
    
    /**
     * Method untuk mengambil data mahasiswa mandiri berdasarkan golongan UK
     * @param PDO $db Koneksi database
     * @param string $golongan Golongan UKT (A, B, C, D)
     * @return array Hasil query
     */
    public function getDataByGolonganUkt($db, $golongan) {
        try {
            $sql = "SELECT 
                        id_mahasiswa,
                        nama_mahasiswa,
                        nim,
                        semester,
                        tarif_ukt_nominal,
                        golongan_ukt,
                        nama_wali
                    FROM tabel_mahasiswa 
                    WHERE jenis_pembiayaan = 'Mandiri' 
                    AND golongan_ukt = :golongan
                    ORDER BY id_mahasiswa ASC";
            
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':golongan', $golongan, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
}

?>