<?php
class Seniman {
    private $conn;
    private $table_name = "seniman";

    public $id_seniman;
    public $nama;
    public $asal;
    public $gaya_seni;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Mendapatkan semua data seniman
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id_seniman ASC"; // Diubah menjadi ASC untuk mengurutkan dari ID terkecil
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Mendapatkan data seniman berdasarkan ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_seniman = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->id_seniman = $row['id_seniman'];
            $this->nama = $row['nama'];
            $this->asal = $row['asal'];
            $this->gaya_seni = $row['gaya_seni'];
            return true;
        }
        return false;
    }

    // Menambahkan data seniman baru
    public function save() {
        $query = "INSERT INTO " . $this->table_name . " (nama, asal, gaya_seni) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        // Sanitasi input
        $this->nama = htmlspecialchars(strip_tags($this->nama));
        $this->asal = htmlspecialchars(strip_tags($this->asal));
        $this->gaya_seni = htmlspecialchars(strip_tags($this->gaya_seni));

        // Bind parameter
        $stmt->bindParam(1, $this->nama);
        $stmt->bindParam(2, $this->asal);
        $stmt->bindParam(3, $this->gaya_seni);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Mengupdate data seniman
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nama = ?, asal = ?, gaya_seni = ? WHERE id_seniman = ?";
        $stmt = $this->conn->prepare($query);

        // Sanitasi input
        $this->nama = htmlspecialchars(strip_tags($this->nama));
        $this->asal = htmlspecialchars(strip_tags($this->asal));
        $this->gaya_seni = htmlspecialchars(strip_tags($this->gaya_seni));
        $this->id_seniman = htmlspecialchars(strip_tags($this->id_seniman));

        // Bind parameter
        $stmt->bindParam(1, $this->nama);
        $stmt->bindParam(2, $this->asal);
        $stmt->bindParam(3, $this->gaya_seni);
        $stmt->bindParam(4, $this->id_seniman);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Menghapus data seniman
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_seniman = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Pencarian seniman berdasarkan nama
    public function search($keyword) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE nama LIKE ? ORDER BY id_seniman ASC"; // Diubah menjadi ASC
        $stmt = $this->conn->prepare($query);
        $keyword = "%{$keyword}%";
        $stmt->bindParam(1, $keyword);
        $stmt->execute();
        return $stmt;
    }
}
?>