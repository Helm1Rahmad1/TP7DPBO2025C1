<?php
include_once 'config/db.php';
include_once 'class/Pameran.php';
include_once 'class/Karya.php';

$database = new Database();
$db = $database->getConnection();
$pameran = new Pameran($db);
$karya = new Karya($db);

$action = isset($_GET['action']) ? $_GET['action'] : "";

// Form pencarian
echo "<div class='search-container'>";
echo "<form method='GET' action='index.php'>";
echo "<input type='hidden' name='page' value='pameran'>";
echo "<input type='text' name='search' placeholder='Cari pameran...'>";
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
    $stmt = $pameran->search($_GET['search']);
    echo "<h2>Hasil Pencarian untuk: " . $_GET['search'] . "</h2>";
    echo "<a href='index.php?page=pameran' class='button back'>Kembali</a>";
} else {
    // Jika tidak ada pencarian, tampilkan semua data
    // Ditambahkan parameter ORDER BY id_pameran DESC untuk mengurutkan dari terbaru
    $stmt = $pameran->getAll("ORDER BY id_pameran DESC");
}

// Tampilkan tombol tambah data
echo "<div class='action-buttons'>";
echo "<a href='index.php?page=pameran&action=create' class='button add'>Tambah Pameran</a>";
echo "</div>";

// Tampilkan form tambah/edit data pameran
if($action == "create" || $action == "edit"){
    echo "<div class='form-container'>";
    echo "<h2>" . ($action == "create" ? "Tambah" : "Edit") . " Pameran</h2>";
    
    // Jika edit, ambil data pameran
    if($action == "edit"){
        $id = isset($_GET['id']) ? $_GET['id'] : die('ID tidak valid');
        $pameran->getById($id);
    }
    
    echo "<form method='post' action='index.php?page=pameran&action=" . ($action == "create" ? "store" : "update") . "'>";
    
    if($action == "edit"){
        echo "<input type='hidden' name='id_pameran' value='" . $pameran->id_pameran . "'>";
    }
    
    echo "<div class='form-group'>";
    echo "<label>Karya Seni</label>";
    echo "<select name='id_karya' required>";
    
    // Ambil daftar karya untuk dropdown
    $karya_stmt = $karya->getAll("ORDER BY judul ASC");
    while($row_karya = $karya_stmt->fetch(PDO::FETCH_ASSOC)){
        $selected = ($action == "edit" && $pameran->id_karya == $row_karya['id_karya']) ? "selected" : "";
        echo "<option value='" . $row_karya['id_karya'] . "' {$selected}>" . $row_karya['judul'] . " (" . $row_karya['nama_seniman'] . ")</option>";
    }
    
    echo "</select>";
    echo "</div>";
    
    echo "<div class='form-group'>";
    echo "<label>Tempat</label>";
    echo "<input type='text' name='tempat' value='" . ($action == "edit" ? $pameran->tempat : "") . "' required>";
    echo "</div>";
    
    echo "<div class='form-group'>";
    echo "<label>Tanggal</label>";
    echo "<input type='date' name='tanggal' value='" . ($action == "edit" ? $pameran->tanggal : "") . "' required>";
    echo "</div>";
    
    echo "<div class='form-group'>";
    echo "<button type='submit' class='button save'>Simpan</button>";
    echo "<a href='index.php?page=pameran' class='button cancel'>Batal</a>";
    echo "</div>";
    
    echo "</form>";
    echo "</div>";
}
// Proses penyimpanan data baru
else if($action == "store"){
    $pameran->id_karya = $_POST['id_karya'];
    $pameran->tempat = $_POST['tempat'];
    $pameran->tanggal = $_POST['tanggal'];
    
    if($pameran->save()){
        $_SESSION['message'] = "Pameran berhasil ditambahkan.";
    } else {
        $_SESSION['message'] = "Gagal menambahkan pameran.";
    }
    
    header("Location: index.php?page=pameran");
    exit();
}
// Proses update data
else if($action == "update"){
    $pameran->id_pameran = $_POST['id_pameran'];
    $pameran->id_karya = $_POST['id_karya'];
    $pameran->tempat = $_POST['tempat'];
    $pameran->tanggal = $_POST['tanggal'];
    
    if($pameran->update()){
        $_SESSION['message'] = "Pameran berhasil diperbarui.";
    } else {
        $_SESSION['message'] = "Gagal memperbarui pameran.";
    }
    
    header("Location: index.php?page=pameran");
    exit();
}
// Proses hapus data
else if($action == "delete"){
    $id = isset($_GET['id']) ? $_GET['id'] : die('ID tidak valid');
    
    if($pameran->delete($id)){
        $_SESSION['message'] = "Pameran berhasil dihapus.";
    } else {
        $_SESSION['message'] = "Gagal menghapus pameran.";
    }
    
    header("Location: index.php?page=pameran");
    exit();
}
// Tampilkan daftar pameran
else {
    echo "<h2>Daftar Pameran</h2>";
    
    // Cek apakah data tersedia
    if($stmt->rowCount() > 0){
        echo "<table>";
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Karya</th>";
        echo "<th>Seniman</th>";
        echo "<th>Tempat</th>";
        echo "<th>Tanggal</th>";
        echo "<th>Aksi</th>";
        echo "</tr>";
        
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            
            echo "<tr>";
            echo "<td>{$id_pameran}</td>";
            echo "<td>{$judul_karya}</td>";
            echo "<td>{$nama_seniman}</td>";
            echo "<td>{$tempat}</td>";
            echo "<td>" . date('d-m-Y', strtotime($tanggal)) . "</td>";
            echo "<td>";
            echo "<a href='index.php?page=pameran&action=edit&id={$id_pameran}' class='button edit'>Edit</a>";
            echo "<a href='index.php?page=pameran&action=delete&id={$id_pameran}' class='button delete' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>";
            echo "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<div class='no-data'>Tidak ada data pameran yang tersedia.</div>";
    }
}
?>