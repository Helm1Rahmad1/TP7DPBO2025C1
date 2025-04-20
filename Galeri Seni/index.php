<?php
// Mulai session
session_start();

// Include files
include_once 'config/db.php';

// Default page
$page = isset($_GET['page']) ? $_GET['page'] : "home";

// Include header
include_once 'view/header.php';

// Function to get recent data
function getRecentData($db, $table, $limit = 3) {
    $query = "SELECT * FROM " . $table . " ORDER BY id DESC LIMIT " . $limit;
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Load page yang dipilih
switch($page){
    case "seniman":
        include_once 'view/seniman.php';
        break;
    case "karya":
        include_once 'view/karya.php';
        break;
    case "pameran":
        include_once 'view/pameran.php';
        break;
    default:
        // Connect to database
        $database = new Database();
        $db = $database->getConnection();
        
        // Halaman beranda yang ditingkatkan
        echo "<div class='home-container'>";
        echo "<h2>Galeri Seni Indonesia</h2>";
        echo "<p>Sistem manajemen untuk mengorganisir seniman, karya seni, dan pameran dengan mudah. Eksplorasi kreativitas tanpa batas di satu platform yang terintegrasi.</p>";
        
        // Tampilkan statistik dengan animasi
        // Hitung data seniman
        $query_seniman = "SELECT COUNT(*) as total FROM seniman";
        $stmt_seniman = $db->prepare($query_seniman);
        $stmt_seniman->execute();
        $row_seniman = $stmt_seniman->fetch(PDO::FETCH_ASSOC);
        
        // Hitung data karya
        $query_karya = "SELECT COUNT(*) as total FROM karya";
        $stmt_karya = $db->prepare($query_karya);
        $stmt_karya->execute();
        $row_karya = $stmt_karya->fetch(PDO::FETCH_ASSOC);
        
        // Hitung data pameran
        $query_pameran = "SELECT COUNT(*) as total FROM pameran";
        $stmt_pameran = $db->prepare($query_pameran);
        $stmt_pameran->execute();
        $row_pameran = $stmt_pameran->fetch(PDO::FETCH_ASSOC);
        
        echo "<div class='statistics'>";
        echo "<div class='stat-box'><h3>Seniman</h3><p>" . $row_seniman['total'] . "</p><span class='stat-description'>Seniman Terdaftar</span></div>";
        echo "<div class='stat-box'><h3>Karya Seni</h3><p>" . $row_karya['total'] . "</p><span class='stat-description'>Koleksi Karya</span></div>";
        echo "<div class='stat-box'><h3>Pameran</h3><p>" . $row_pameran['total'] . "</p><span class='stat-description'>Pameran Diselenggarakan</span></div>";
        echo "</div>";
        
        // Tambahkan bagian quick access
        echo "<div class='quick-access-section'>";
        echo "<h3>Akses Cepat</h3>";
        echo "<div class='quick-buttons'>";
        echo "<a href='index.php?page=seniman&action=add' class='quick-button seniman-btn'><span class='icon'>👤</span>Tambah Seniman</a>";
        echo "<a href='index.php?page=karya&action=add' class='quick-button karya-btn'><span class='icon'>🎨</span>Tambah Karya</a>";
        echo "<a href='index.php?page=pameran&action=add' class='quick-button pameran-btn'><span class='icon'>🏛️</span>Tambah Pameran</a>";
        echo "</div>";
        echo "</div>";
        
        echo "</div>"; // End latest-data-section
        
        echo "</div>"; // End home-container
}

// Include footer
include_once 'view/footer.php';
?>