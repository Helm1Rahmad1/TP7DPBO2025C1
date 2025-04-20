<?php
include_once 'config/db.php';
include_once 'class/Karya.php';
include_once 'class/Seniman.php';

$database = new Database();
$db = $database->getConnection();
$karya = new Karya($db);
$seniman = new Seniman($db);

$action = isset($_GET['action']) ? $_GET['action'] : "";

// Form pencarian
echo "<div class='search-container'>";
echo "<form method='GET' action='index.php'>";
echo "<input type='hidden' name='page' value='karya'>";
echo "<input type='text' name='search' placeholder='Cari karya seni...'>";
echo "<button type='submit'>Cari</button>";
echo "</form>";
echo "</div>";

// Tampilkan pesan CRUD jika ada
if(isset($_SESSION['message'])){
    echo "<div class='message'>" . $_SESSION['message'] . "</div>";
    unset($_SESSION['message']);
}

// Proses pencarian
if(isset($_GET['search'])){
    $stmt = $karya->search($_GET['search']);
    echo "<h2>Hasil Pencarian untuk: " . $_GET['search'] . "</h2>";
    echo "<a href='index.php?page=karya' class='button back'>Kembali</a>";
} else {
    // Jika tidak ada pencarian, tampilkan semua data
    // Ditambahkan parameter ORDER BY id_pameran DESC untuk mengurutkan dari terbaru
    $stmt = $karya->getAll();
}

// Tampilkan tombol tambah data
echo "<div class='action-buttons'>";
echo "<a href='index.php?page=karya&action=create' class='button add'>Tambah Karya Seni</a>";
echo "</div>";

// Tampilkan form tambah/edit data karya
if($action == "create" || $action == "edit"){
    echo "<div class='form-container'>";
    echo "<h2>" . ($action == "create" ? "Tambah" : "Edit") . " Karya Seni</h2>";
    
    // Jika edit, ambil data karya
    if($action == "edit"){
        $id = isset($_GET['id']) ? $_GET['id'] : die('ID tidak valid');
        $karya->getById($id);
    }
    
    echo "<form method='post' action='index.php?page=karya&action=" . ($action == "create" ? "store" : "update") . "'>";
    
    if($action == "edit"){
        echo "<input type='hidden' name='id_karya' value='" . $karya->id_karya . "'>";
    }
    
    echo "<div class='form-group'>";
    echo "<label>Judul</label>";
    echo "<input type='text' name='judul' value='" . ($action == "edit" ? $karya->judul : "") . "' required>";
    echo "</div>";
    
    echo "<div class='form-group'>";
    echo "<label>Seniman</label>";
    echo "<select name='id_seniman' required>";
    
    // Ambil daftar seniman untuk dropdown
    $seniman_stmt = $seniman->getAll();
    while($row_seniman = $seniman_stmt->fetch(PDO::FETCH_ASSOC)){
        $selected = ($action == "edit" && $karya->id_seniman == $row_seniman['id_seniman']) ? "selected" : "";
        echo "<option value='" . $row_seniman['id_seniman'] . "' {$selected}>" . $row_seniman['nama'] . "</option>";
    }
    
    echo "</select>";
    echo "</div>";
    
    echo "<div class='form-group'>";
    echo "<label>Tahun</label>";
    echo "<input type='number' name='tahun' min='1' max='2100' value='" . ($action == "edit" ? $karya->tahun : "") . "' required>";
    echo "</div>";
    
    echo "<div class='form-group'>";
    echo "<button type='submit' class='button save'>Simpan</button>";
    echo "<a href='index.php?page=karya' class='button cancel'>Batal</a>";
    echo "</div>";
    
    echo "</form>";
    echo "</div>";
}
// Proses penyimpanan data baru
else if($action == "store"){
    $karya->judul = $_POST['judul'];
    $karya->id_seniman = $_POST['id_seniman'];
    $karya->tahun = $_POST['tahun'];
    
    if($karya->save()){
        $_SESSION['message'] = "Karya berhasil ditambahkan.";
    } else {
        $_SESSION['message'] = "Gagal menambahkan karya.";
    }
    
    header("Location: index.php?page=karya");
    exit();
}
// Proses update data
else if($action == "update"){
    $karya->id_karya = $_POST['id_karya'];
    $karya->judul = $_POST['judul'];
    $karya->id_seniman = $_POST['id_seniman'];
    $karya->tahun = $_POST['tahun'];
    
    if($karya->update()){
        $_SESSION['message'] = "Karya berhasil diperbarui.";
    } else {
        $_SESSION['message'] = "Gagal memperbarui karya.";
    }
    
    header("Location: index.php?page=karya");
    exit();
}
// Proses hapus data
else if($action == "delete"){
    $id = isset($_GET['id']) ? $_GET['id'] : die('ID tidak valid');
    
    if($karya->delete($id)){
        $_SESSION['message'] = "Karya berhasil dihapus.";
    } else {
        $_SESSION['message'] = "Gagal menghapus karya.";
    }
    
    header("Location: index.php?page=karya");
    exit();
}
// Tampilkan daftar karya
else {
    echo "<h2>Daftar Karya Seni</h2>";
    
    // Cek apakah data tersedia
    if($stmt->rowCount() > 0){
        echo "<table>";
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Judul</th>";
        echo "<th>Seniman</th>";
        echo "<th>Tahun</th>";
        echo "<th>Aksi</th>";
        echo "</tr>";
        
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            
            echo "<tr>";
            echo "<td>{$id_karya}</td>";
            echo "<td>{$judul}</td>";
            echo "<td>{$nama_seniman}</td>";
            echo "<td>{$tahun}</td>";
            echo "<td>";
            echo "<a href='index.php?page=karya&action=edit&id={$id_karya}' class='button edit'>Edit</a>";
            echo "<a href='index.php?page=karya&action=delete&id={$id_karya}' class='button delete' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>";
            echo "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<div class='no-data'>Tidak ada data karya yang tersedia.</div>";
    }
}
?>