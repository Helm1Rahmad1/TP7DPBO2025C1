<?php
class Pameran {
    private $conn;
    private $table_name = "pameran";

    public $id_pameran;
    public $id_karya;
    public $tempat;
    public $tanggal;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Mendapatkan semua data pameran
    public function getAll() {
        $query = "SELECT p.*, k.judul as judul_karya, s.nama as nama_seniman 
                  FROM " . $this->table_name . " p
                  LEFT JOIN karya k ON p.id_karya = k.id_karya
                  LEFT JOIN seniman s ON k.id_seniman = s.id_seniman
                  ORDER BY p.id_pameran ASC"; // Diubah menjadi ASC untuk mengurutkan dari ID terkecil
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Mendapatkan data pameran berdasarkan ID
    public function getById($id) {
        $query = "SELECT p.*, k.judul as judul_karya 
                  FROM " . $this->table_name . " p
                  LEFT JOIN karya k ON p.id_karya = k.id_karya
                  WHERE p.id_pameran = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->id_pameran = $row['id_pameran'];
            $this->id_karya = $row['id_karya'];
            $this->tempat = $row['tempat'];
            $this->tanggal = $row['tanggal'];
            return true;
        }
        return false;
    }

    // Mendapatkan pameran berdasarkan karya
    public function getByArtwork($id_karya) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_karya = ? ORDER BY tanggal DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_karya);
        $stmt->execute();
        return $stmt;
    }

    // Menambahkan data pameran baru
    public function save() {
        $query = "INSERT INTO " . $this->table_name . " (id_karya, tempat, tanggal) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        // Sanitasi input
        $this->id_karya = htmlspecialchars(strip_tags($this->id_karya));
        $this->tempat = htmlspecialchars(strip_tags($this->tempat));
        $this->tanggal = htmlspecialchars(strip_tags($this->tanggal));

        // Bind parameter
        $stmt->bindParam(1, $this->id_karya);
        $stmt->bindParam(2, $this->tempat);
        $stmt->bindParam(3, $this->tanggal);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Mengupdate data pameran
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET id_karya = ?, tempat = ?, tanggal = ? WHERE id_pameran = ?";
        $stmt = $this->conn->prepare($query);

        // Sanitasi input
        $this->id_karya = htmlspecialchars(strip_tags($this->id_karya));
        $this->tempat = htmlspecialchars(strip_tags($this->tempat));
        $this->tanggal = htmlspecialchars(strip_tags($this->tanggal));
        $this->id_pameran = htmlspecialchars(strip_tags($this->id_pameran));

        // Bind parameter
        $stmt->bindParam(1, $this->id_karya);
        $stmt->bindParam(2, $this->tempat);
        $stmt->bindParam(3, $this->tanggal);
        $stmt->bindParam(4, $this->id_pameran);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Menghapus data pameran
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_pameran = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Pencarian pameran berdasarkan tempat atau karya
    public function search($keyword) {
        $query = "SELECT p.*, k.judul as judul_karya, s.nama as nama_seniman 
                  FROM " . $this->table_name . " p
                  LEFT JOIN karya k ON p.id_karya = k.id_karya
                  LEFT JOIN seniman s ON k.id_seniman = s.id_seniman
                  WHERE p.tempat LIKE ? OR k.judul LIKE ? OR s.nama LIKE ?
                  ORDER BY p.tanggal DESC";
        $stmt = $this->conn->prepare($query);
        $keyword = "%{$keyword}%";
        $stmt->bindParam(1, $keyword);
        $stmt->bindParam(2, $keyword);
        $stmt->bindParam(3, $keyword);
        $stmt->execute();
        return $stmt;
    }
}
?>