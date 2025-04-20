-- Database: db_galeri
CREATE DATABASE IF NOT EXISTS `db_galeri`;
USE `db_galeri`;

-- Tabel: seniman
CREATE TABLE IF NOT EXISTS `seniman` (
  `id_seniman` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `asal` varchar(100) NOT NULL,
  `gaya_seni` varchar(100) NOT NULL,
  PRIMARY KEY (`id_seniman`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel: karya
CREATE TABLE IF NOT EXISTS `karya` (
  `id_karya` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(100) NOT NULL,
  `id_seniman` int(11) NOT NULL,
  `tahun` int(4) NOT NULL,
  PRIMARY KEY (`id_karya`),
  FOREIGN KEY (`id_seniman`) REFERENCES `seniman` (`id_seniman`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel: pameran
CREATE TABLE IF NOT EXISTS `pameran` (
  `id_pameran` int(11) NOT NULL AUTO_INCREMENT,
  `id_karya` int(11) NOT NULL,
  `tempat` varchar(100) NOT NULL,
  `tanggal` date NOT NULL,
  PRIMARY KEY (`id_pameran`),
  FOREIGN KEY (`id_karya`) REFERENCES `karya` (`id_karya`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sampel data
INSERT INTO `seniman` (`nama`, `asal`, `gaya_seni`) VALUES
('Leonardo da Vinci', 'Italia', 'Renaissance'),
('Vincent van Gogh', 'Belanda', 'Post-Impressionism'),
('Raden Saleh', 'Indonesia', 'Romanticism');

INSERT INTO `karya` (`judul`, `id_seniman`, `tahun`) VALUES
('Mona Lisa', 1, 1503),
('The Starry Night', 2, 1889),
('Penangkapan Pangeran Diponegoro', 3, 1857);

INSERT INTO `pameran` (`id_karya`, `tempat`, `tanggal`) VALUES
(1, 'Louvre Museum, Paris', '2025-05-15'),
(2, 'MoMA, New York', '2025-06-20'),
(3, 'Galeri Nasional Indonesia', '2025-08-17');