<?php

/**
 * File: MahasiswaBidikmisi.php
 * Class MahasiswaBidikmisi - Subclass dari Mahasisw
 */

class MahasiswaBidikmisi extends Mahasiswa {
    // Properti tambahan
    private $nomorKipKuliah;
    private $danaSakuSubsidi;
    
    /**
     * Constructor
     * @param array $data Array asosiatif dari hasil query database
     */
    public function __construct($data = []) {
        parent::__construct($data);
        $this->nomorKipKuliah = $data['nomor_kip_kuliah'] ?? '';
        $this->danaSakuSubsidi = $data['dana_saku_subsidi'] ?? 0;
    }
    
    // Getter dan Setter
    public function getNomorKipKuliah() {
        return $this->nomorKipKuliah;
    }
    
    public function setNomorKipKuliah($nomorKipKuliah) {
        $this->nomorKipKuliah = $nomorKipKuliah;
        return $this;
    }
    
    public function getDanaSakuSubsidi() {
        return $this->danaSakuSubsidi;
    }
    
    public function setDanaSakuSubsidi($danaSakuSubsidi) {
        $this->danaSakuSubsidi = $danaSakuSubsidi;
        return $this;
    }
    
    /**
     * METHOD OVERRIDE: hitungTagihanSemester()
     * Logika: Total Tagihan = 0 (digratiskan penuh oleh negara melalui KIP-Kuliah)
     * 
     * @return float Total tagihan semester (0)
     */
    public function hitungTagihanSemester() {
        // Mahasiswa Bidikmisi gratis 100% karena ditanggung negara
        return 0;
    }
    
    /**
     * Method untuk mendapatkan rincian tagihan
     * @return array Rincian tagihan
     */
    public function getRincianTagihan() {
        return [
            'ukt' => $this->tarifUKTNominal,
            'subsidi_negara' => $this->tarifUKTNominal,
            'dana_saku' => $this->danaSakuSubsidi,
            'total' => 0,
            'keterangan' => 'Ditanggung penuh oleh KIP-Kuliah'
        ];
    }
    
    /**
     * METHOD OVERRIDE: tampilkanSpesifikasiAkademik()
     */
    public function tampilkanSpesifikasiAkademik() {
        echo "\n=== SPESIFIKASI MAHASISWA BIDIKMISI ===\n";
        echo "Nomor KIP Kuliah  : " . $this->nomorKipKuliah . "\n";
        echo "Dana Saku Subsidi : Rp " . number_format($this->danaSakuSubsidi, 0, ',', '.') . "\n";
        echo "Status            : Penerima KIP Kuliah\n";
        echo "Subsidi Negara    : Rp " . number_format($this->tarifUKTNominal, 0, ',', '.') . "\n";
        echo "------------------------------------\n";
        echo "Tagihan Semester  : Rp " . number_format($this->hitungTagihanSemester(), 0, ',', '.') . " (GRATIS)\n";
        echo "====================================\n";
    }
    
    /**
     * Method untuk mengambil data mahasiswa bidikmisi dari databas
     * @param PDO $db Koneksi database
     * @param int $id ID mahasiswa (opsional)
     * @return array Hasil query
     */
    public function getDataMahasiswaBidikmisi($db, $id = null) {
        try {
            $sql = "SELECT 
                        id_mahasiswa,
                        nama_mahasiswa,
                        nim,
                        semester,
                        tarif_ukt_nominal,
                        jenis_pembiayaan,
                        nomor_kip_kuliah,
                        dana_saku_subsidi
                    FROM tabel_mahasiswa 
                    WHERE jenis_pembiayaan = 'Bidikmisi'";
            
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
     * Method untuk mengambil data mahasiswa bidikmisi berdasarkan nomor KIP
     * @param PDO $db Koneksi database
     * @param string $nomorKip Nomor KIP Kuliah
     * @return array Hasil query
     */
    public function getDataByNomorKip($db, $nomorKip) {
        try {
            $sql = "SELECT 
                        id_mahasiswa,
                        nama_mahasiswa,
                        nim,
                        semester,
                        tarif_ukt_nominal,
                        nomor_kip_kuliah,
                        dana_saku_subsidi
                    FROM tabel_mahasiswa 
                    WHERE jenis_pembiayaan = 'Bidikmisi' 
                    AND nomor_kip_kuliah = :nomor_kip
                    ORDER BY id_mahasiswa ASC";
            
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':nomor_kip', $nomorKip, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
    
    /**
     * Method untuk mengambil data mahasiswa bidikmisi dengan dana saku > nilai tertentu
     * @param PDO $db Koneksi database
     * @param float $minDana Nilai minimal dana saku
     * @return array Hasil query
     */
    public function getDataByMinDanaSaku($db, $minDana = 1000000) {
        try {
            $sql = "SELECT 
                        id_mahasiswa,
                        nama_mahasiswa,
                        nim,
                        semester,
                        tarif_ukt_nominal,
                        nomor_kip_kuliah,
                        dana_saku_subsidi
                    FROM tabel_mahasiswa 
                    WHERE jenis_pembiayaan = 'Bidikmisi' 
                    AND dana_saku_subsidi >= :min_dana
                    ORDER BY dana_saku_subsidi DESC";
            
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':min_dana', $minDana, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
}

?>