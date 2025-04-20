<?php
class Karya {
    private $conn;
    private $table_name = "karya";

    public $id_karya;
    public $judul;
    public $id_seniman;
    public $tahun;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Mendapatkan semua data karya
    public function getAll() {
        $query = "SELECT k.*, s.nama as nama_seniman 
                  FROM " . $this->table_name . " k
                  LEFT JOIN seniman s ON k.id_seniman = s.id_seniman
                  ORDER BY k.id_karya ASC"; // Diubah menjadi ASC untuk mengurutkan dari ID terkecil
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Mendapatkan data karya berdasarkan ID
    public function getById($id) {
        $query = "SELECT k.*, s.nama as nama_seniman 
                  FROM " . $this->table_name . " k
                  LEFT JOIN seniman s ON k.id_seniman = s.id_seniman
                  WHERE k.id_karya = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->id_karya = $row['id_karya'];
            $this->judul = $row['judul'];
            $this->id_seniman = $row['id_seniman'];
            $this->tahun = $row['tahun'];
            return true;
        }
        return false;
    }

    // Mendapatkan karya berdasarkan seniman
    public function getByArtist($id_seniman) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_seniman = ? ORDER BY id_karya ASC"; // Diubah menjadi ASC
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_seniman);
        $stmt->execute();
        return $stmt;
    }

    // Menambahkan data karya baru
    public function save() {
        $query = "INSERT INTO " . $this->table_name . " (judul, id_seniman, tahun) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        // Sanitasi input
        $this->judul = htmlspecialchars(strip_tags($this->judul));
        $this->id_seniman = htmlspecialchars(strip_tags($this->id_seniman));
        $this->tahun = htmlspecialchars(strip_tags($this->tahun));

        // Bind parameter
        $stmt->bindParam(1, $this->judul);
        $stmt->bindParam(2, $this->id_seniman);
        $stmt->bindParam(3, $this->tahun);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Mengupdate data karya
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET judul = ?, id_seniman = ?, tahun = ? WHERE id_karya = ?";
        $stmt = $this->conn->prepare($query);

        // Sanitasi input
        $this->judul = htmlspecialchars(strip_tags($this->judul));
        $this->id_seniman = htmlspecialchars(strip_tags($this->id_seniman));
        $this->tahun = htmlspecialchars(strip_tags($this->tahun));
        $this->id_karya = htmlspecialchars(strip_tags($this->id_karya));

        // Bind parameter
        $stmt->bindParam(1, $this->judul);
        $stmt->bindParam(2, $this->id_seniman);
        $stmt->bindParam(3, $this->tahun);
        $stmt->bindParam(4, $this->id_karya);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Menghapus data karya
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_karya = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Pencarian karya berdasarkan judul
    public function search($keyword) {
        $query = "SELECT k.*, s.nama as nama_seniman 
                  FROM " . $this->table_name . " k
                  LEFT JOIN seniman s ON k.id_seniman = s.id_seniman
                  WHERE k.judul LIKE ? OR s.nama LIKE ?
                  ORDER BY k.id_karya ASC"; // Diubah menjadi ASC
        $stmt = $this->conn->prepare($query);
        $keyword = "%{$keyword}%";
        $stmt->bindParam(1, $keyword);
        $stmt->bindParam(2, $keyword);
        $stmt->execute();
        return $stmt;
    }
}
?>