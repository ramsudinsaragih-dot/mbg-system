-- Create Database
CREATE DATABASE IF NOT EXISTS mbg;
USE mbg;

-- Users Table
CREATE TABLE users (
  id_user INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  level ENUM('admin', 'petugas') DEFAULT 'petugas',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Penerima (Recipients) Table
CREATE TABLE penerima (
  id_penerima INT AUTO_INCREMENT PRIMARY KEY,
  nik VARCHAR(16) UNIQUE NOT NULL,
  nama VARCHAR(100) NOT NULL,
  jk ENUM('Laki-laki', 'Perempuan') NOT NULL,
  tgl_lahir DATE NOT NULL,
  alamat TEXT NOT NULL,
  no_hp VARCHAR(15),
  status ENUM('Aktif', 'Tidak Aktif') DEFAULT 'Aktif',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Petugas (Staff) Table
CREATE TABLE petugas (
  id_petugas INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  jabatan VARCHAR(50) NOT NULL,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  no_hp VARCHAR(15),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Distribusi (Distribution) Table
CREATE TABLE distribusi (
  id_distribusi INT AUTO_INCREMENT PRIMARY KEY,
  nomor VARCHAR(20) UNIQUE NOT NULL,
  id_penerima INT NOT NULL,
  id_petugas INT NOT NULL,
  tanggal DATE NOT NULL,
  menu VARCHAR(100) NOT NULL,
  jumlah INT NOT NULL,
  lokasi TEXT NOT NULL,
  status ENUM('Menunggu', 'Disalurkan', 'Selesai') DEFAULT 'Menunggu',
  foto VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_penerima) REFERENCES penerima(id_penerima) ON DELETE CASCADE,
  FOREIGN KEY (id_petugas) REFERENCES petugas(id_petugas) ON DELETE CASCADE
);

-- Jadwal (Schedule) Table
CREATE TABLE jadwal (
  id_jadwal INT AUTO_INCREMENT PRIMARY KEY,
  tanggal DATE NOT NULL,
  jam TIME NOT NULL,
  lokasi TEXT NOT NULL,
  petugas VARCHAR(100) NOT NULL,
  keterangan TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Sample Data
INSERT INTO users (nama, username, password, level) VALUES
('Admin MBG', 'admin', SHA2('admin123', 256), 'admin');

INSERT INTO penerima (nik, nama, jk, tgl_lahir, alamat, no_hp, status) VALUES
('1234567890123456', 'Ibu Siti', 'Perempuan', '1970-01-15', 'Jl. Kerasaan No.1', '081234567890', 'Aktif'),
('1234567890123457', 'Bapak Budi', 'Laki-laki', '1965-05-20', 'Jl. Kerasaan No.2', '081234567891', 'Aktif');

INSERT INTO jadwal (tanggal, jam, lokasi, petugas, keterangan) VALUES
(CURDATE(), '08:00:00', 'Kelurahan Kerasaan I', 'Petugas 1', 'Distribusi MBG Rutin'),
(CURDATE(), '10:00:00', 'Kelurahan Kerasaan II', 'Petugas 2', 'Distribusi MBG Rutin');