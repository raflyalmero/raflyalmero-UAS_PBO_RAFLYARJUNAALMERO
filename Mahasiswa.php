<?php

/**
 * File: Mahasiswa.php
 * Abstract class Mahasisw
 */

abstract class Mahasiswa {
    // Properti terenkapsulasi (protected)
    protected $id_mahasiswa;
    protected $nama_mahasiswa;
    protected $nim;
    protected $semester;
    protected $tarifUKTNominal;
    
    /**
     * Constructor untuk menginisialisasi properti
     * @param array $data Array asosiatif dari hasil query database
     */
    public function __construct($data = []) {
        if (!empty($data)) {
            $this->id_mahasiswa = $data['id_mahasiswa'] ?? null;
            $this->nama_mahasiswa = $data['nama_mahasiswa'] ?? '';
            $this->nim = $data['nim'] ?? '';
            $this->semester = $data['semester'] ?? 0;
            $this->tarifUKTNominal = $data['tarif_ukt_nominal'] ?? 0;
        }
    }
    
    // Getter dan Setter
    public function getIdMahasiswa() {
        return $this->id_mahasiswa;
    }
    
    public function setIdMahasiswa($id_mahasiswa) {
        $this->id_mahasiswa = $id_mahasiswa;
        return $this;
    }
    
    public function getNamaMahasiswa() {
        return $this->nama_mahasiswa;
    }
    
    public function setNamaMahasiswa($nama_mahasiswa) {
        $this->nama_mahasiswa = $nama_mahasiswa;
        return $this;
    }
    
    public function getNim() {
        return $this->nim;
    }
    
    public function setNim($nim) {
        $this->nim = $nim;
        return $this;
    }
    
    public function getSemester() {
        return $this->semester;
    }
    
    public function setSemester($semester) {
        $this->semester = $semester;
        return $this;
    }
    
    public function getTarifUKTNominal() {
        return $this->tarifUKTNominal;
    }
    
    public function setTarifUKTNominal($tarifUKTNominal) {
        $this->tarifUKTNominal = $tarifUKTNominal;
        return $this;
    }
    
    /**
     * Metode abstrak untuk menghitung tagihan per semester
     * Akan di-override oleh setiap subclass
     */
    abstract public function hitungTagihanSemester();
    
    /**
     * Metode abstrak untuk menampilkan spesifikasi akademik
     */
    abstract public function tampilkanSpesifikasiAkademik();
    
    /**
     * Metode umum untuk menampilkan informasi dasar mahasisw
     */
    public function tampilkanInfoDasar() {
        echo "ID Mahasiswa    : " . $this->id_mahasiswa . "\n";
        echo "Nama            : " . $this->nama_mahasiswa . "\n";
        echo "NIM             : " . $this->nim . "\n";
        echo "Semester        : " . $this->semester . "\n";
        echo "Tarif UKT       : Rp " . number_format($this->tarifUKTNominal, 0, ',', '.') . "\n";
    }
}

?>