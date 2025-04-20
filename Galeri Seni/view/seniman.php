<?php
include_once 'config/db.php';
include_once 'class/Seniman.php';

$database = new Database();
$db = $database->getConnection();
$seniman = new Seniman($db);

$action = isset($_GET['action']) ? $_GET['action'] : "";

// Form pencarian
echo "<div class='search-container'>";
echo "<form method='GET' action='index.php'>";
echo "<input type='hidden' name='page' value='seniman'>";
echo "<input type='text' name='search' placeholder='Cari seniman...'>";
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
    $stmt = $seniman->search($_GET['search']);
    echo "<h2>Hasil Pencarian untuk: " . $_GET['search'] . "</h2>";
    echo "<a href='index.php?page=seniman' class='button back'>Kembali</a>";
} else {
    // Jika tidak ada pencarian, tampilkan semua data
    // Ditambahkan parameter ORDER BY id_seniman DESC untuk mengurutkan dari terbaru
    $stmt = $seniman->getAll("ORDER BY id_seniman DESC");
}

// Tampilkan tombol tambah data
echo "<div class='action-buttons'>";
echo "<a href='index.php?page=seniman&action=create' class='button add'>Tambah Seniman</a>";
echo "</div>";

// Tampilkan form tambah/edit data seniman
if($action == "create" || $action == "edit"){
    echo "<div class='form-container'>";
    echo "<h2>" . ($action == "create" ? "Tambah" : "Edit") . " Seniman</h2>";
    
    // Jika edit, ambil data seniman
    if($action == "edit"){
        $id = isset($_GET['id']) ? $_GET['id'] : die('ID tidak valid');
        $seniman->getById($id);
    }
    
    echo "<form method='post' action='index.php?page=seniman&action=" . ($action == "create" ? "store" : "update") . "'>";
    
    if($action == "edit"){
        echo "<input type='hidden' name='id_seniman' value='" . $seniman->id_seniman . "'>";
    }
    
    echo "<div class='form-group'>";
    echo "<label>Nama</label>";
    echo "<input type='text' name='nama' value='" . ($action == "edit" ? $seniman->nama : "") . "' required>";
    echo "</div>";
    
    echo "<div class='form-group'>";
    echo "<label>Asal</label>";
    echo "<input type='text' name='asal' value='" . ($action == "edit" ? $seniman->asal : "") . "' required>";
    echo "</div>";
    
    echo "<div class='form-group'>";
    echo "<label>Gaya Seni</label>";
    echo "<input type='text' name='gaya_seni' value='" . ($action == "edit" ? $seniman->gaya_seni : "") . "' required>";
    echo "</div>";
    
    echo "<div class='form-group'>";
    echo "<button type='submit' class='button save'>Simpan</button>";
    echo "<a href='index.php?page=seniman' class='button cancel'>Batal</a>";
    echo "</div>";
    
    echo "</form>";
    echo "</div>";
}
// Proses penyimpanan data baru
else if($action == "store"){
    $seniman->nama = $_POST['nama'];
    $seniman->asal = $_POST['asal'];
    $seniman->gaya_seni = $_POST['gaya_seni'];
    
    if($seniman->save()){
        $_SESSION['message'] = "Seniman berhasil ditambahkan.";
    } else {
        $_SESSION['message'] = "Gagal menambahkan seniman.";
    }
    
    header("Location: index.php?page=seniman");
    exit();
}
// Proses update data
else if($action == "update"){
    $seniman->id_seniman = $_POST['id_seniman'];
    $seniman->nama = $_POST['nama'];
    $seniman->asal = $_POST['asal'];
    $seniman->gaya_seni = $_POST['gaya_seni'];
    
    if($seniman->update()){
        $_SESSION['message'] = "Seniman berhasil diperbarui.";
    } else {
        $_SESSION['message'] = "Gagal memperbarui seniman.";
    }
    
    header("Location: index.php?page=seniman");
    exit();
}
// Proses hapus data
else if($action == "delete"){
    $id = isset($_GET['id']) ? $_GET['id'] : die('ID tidak valid');
    
    if($seniman->delete($id)){
        $_SESSION['message'] = "Seniman berhasil dihapus.";
    } else {
        $_SESSION['message'] = "Gagal menghapus seniman.";
    }
    
    header("Location: index.php?page=seniman");
    exit();
}
// Tampilkan daftar seniman
else {
    echo "<h2>Daftar Seniman</h2>";
    
    // Cek apakah data tersedia
    if($stmt->rowCount() > 0){
        echo "<table>";
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Nama</th>";
        echo "<th>Asal</th>";
        echo "<th>Gaya Seni</th>";
        echo "<th>Aksi</th>";
        echo "</tr>";
        
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            
            echo "<tr>";
            echo "<td>{$id_seniman}</td>";
            echo "<td>{$nama}</td>";
            echo "<td>{$asal}</td>";
            echo "<td>{$gaya_seni}</td>";
            echo "<td>";
            echo "<a href='index.php?page=seniman&action=edit&id={$id_seniman}' class='button edit'>Edit</a>";
            echo "<a href='index.php?page=seniman&action=delete&id={$id_seniman}' class='button delete' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>";
            echo "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<div class='no-data'>Tidak ada data seniman yang tersedia.</div>";
    }
}
?>