<?php

/**
 * File: MahasiswaPrestasi.php
 * Class MahasiswaPrestasi - Subclass dari Mahasiswa
 */

class MahasiswaPrestasi extends Mahasiswa {
    // Properti tambahan
    private $namaInstansiBeasiswa;
    private $minimalIpkSyarat;
    
    // Konstanta diskon beasiswa
    const DISKON_BEASISWA = 0.75; // 75% diskon
    
    /**
     * Constructor
     * @param array $data Array asosiatif dari hasil query database
     */
    public function __construct($data = []) {
        parent::__construct($data);
        $this->namaInstansiBeasiswa = $data['nama_instansi_beasiswa'] ?? '';
        $this->minimalIpkSyarat = $data['minimal_ipk_bersyarat'] ?? 0;
    }
    
    // Getter dan Setter
    public function getNamaInstansiBeasiswa() {
        return $this->namaInstansiBeasiswa;
    }
    
    public function setNamaInstansiBeasiswa($namaInstansiBeasiswa) {
        $this->namaInstansiBeasiswa = $namaInstansiBeasiswa;
        return $this;
    }
    
    public function getMinimalIpkSyarat() {
        return $this->minimalIpkSyarat;
    }
    
    public function setMinimalIpkSyarat($minimalIpkSyarat) {
        $this->minimalIpkSyarat = $minimalIpkSyarat;
        return $this;
    }
    
    /**
     * METHOD OVERRIDE: hitungTagihanSemester()
     * Logika: Total Tagihan = tarifUKTNominal * 0.25 (diskon 75%)
     * 
     * @return float Total tagihan semester setelah diskon
     */
    public function hitungTagihanSemester() {
        // Mahasiswa Prestasi mendapat diskon 75%, bayar 25% dari UKT
        return $this->tarifUKTNominal * (1 - self::DISKON_BEASISWA);
        // Sama dengan: $this->tarifUKTNominal * 0.25
    }
    
    /**
     * Method untuk mendapatkan rincian tagihan
     * @return array Rincian tagihan
     */
    public function getRincianTagihan() {
        $diskon = $this->tarifUKTNominal * self::DISKON_BEASISWA;
        $total = $this->hitungTagihanSemester();
        
        return [
            'ukt_awal' => $this->tarifUKTNominal,
            'diskon' => $diskon,
            'persentase_diskon' => self::DISKON_BEASISWA * 100 . '%',
            'total' => $total,
            'instansi' => $this->namaInstansiBeasiswa,
            'ipk_syarat' => $this->minimalIpkSyarat
        ];
    }
    
    /**
     * METHOD OVERRIDE: tampilkanSpesifikasiAkademik()
     */
    public function tampilkanSpesifikasiAkademik() {
        $rincian = $this->getRincianTagihan();
        
        echo "\n=== SPESIFIKASI MAHASISWA PRESTASI ===\n";
        echo "Instansi Beasiswa   : " . $this->namaInstansiBeasiswa . "\n";
        echo "Minimal IPK Syarat  : " . number_format($this->minimalIpkSyarat, 2) . "\n";
        echo "Status              : Penerima Beasiswa\n";
        echo "Diskon Beasiswa     : " . self::DISKON_BEASISWA * 100 . "%\n";
        echo "------------------------------------\n";
        echo "UKT Awal            : Rp " . number_format($rincian['ukt_awal'], 0, ',', '.') . "\n";
        echo "Potongan Beasiswa   : Rp " . number_format($rincian['diskon'], 0, ',', '.') . "\n";
        echo "Tagihan Semester    : Rp " . number_format($rincian['total'], 0, ',', '.') . "\n";
        echo "====================================\n";
    }
    
    /**
     * Method untuk mengambil data mahasiswa prestasi dari database
     * @param PDO $db Koneksi database
     * @param int $id ID mahasiswa (opsional)
     * @return array Hasil query
     */
    public function getDataMahasiswaPrestasi($db, $id = null) {
        try {
            $sql = "SELECT 
                        id_mahasiswa,
                        nama_mahasiswa,
                        nim,
                        semester,
                        tarif_ukt_nominal,
                        jenis_pembiayaan,
                        nama_instansi_beasiswa,
                        minimal_ipk_bersyarat
                    FROM tabel_mahasiswa 
                    WHERE jenis_pembiayaan = 'Prestasi'";
            
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
     * Method untuk mengambil data mahasiswa prestasi berdasarkan instansi beasiswa
     * @param PDO $db Koneksi database
     * @param string $instansi Nama instansi beasiswa
     * @return array Hasil query
     */
    public function getDataByInstansiBeasiswa($db, $instansi) {
        try {
            $sql = "SELECT 
                        id_mahasiswa,
                        nama_mahasiswa,
                        nim,
                        semester,
                        tarif_ukt_nominal,
                        nama_instansi_beasiswa,
                        minimal_ipk_bersyarat
                    FROM tabel_mahasiswa 
                    WHERE jenis_pembiayaan = 'Prestasi' 
                    AND nama_instansi_beasiswa = :instansi
                    ORDER BY id_mahasiswa ASC";
            
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':instansi', $instansi, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
    
    /**
     * Method untuk mengambil data mahasiswa prestasi dengan IPK syarat >= nilai tertentu
     * @param PDO $db Koneksi database
     * @param float $minIpk Nilai minimal IPK syara
     * @return array Hasil query
     */
    public function getDataByMinIpkSyarat($db, $minIpk = 3.00) {
        try {
            $sql = "SELECT 
                        id_mahasiswa,
                        nama_mahasiswa,
                        nim,
                        semester,
                        tarif_ukt_nominal,
                        nama_instansi_beasiswa,
                        minimal_ipk_bersyarat
                    FROM tabel_mahasiswa 
                    WHERE jenis_pembiayaan = 'Prestasi' 
                    AND minimal_ipk_bersyarat >= :min_ipk
                    ORDER BY minimal_ipk_bersyarat DESC";
            
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':min_ipk', $minIpk, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
}

?>