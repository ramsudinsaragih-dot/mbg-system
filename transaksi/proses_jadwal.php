<?php
session_start();
if(!isset($_SESSION['username'])){
    header('location:../login.php?pesan=belum_login');
    exit();
}
require_once '../config/koneksi.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $action = $_POST['action'];
    
    if($action == 'tambah'){
        $tanggal = $_POST['tanggal'];
        $jam = $_POST['jam'];
        $lokasi = mysqli_real_escape_string($koneksi, $_POST['lokasi']);
        $petugas = mysqli_real_escape_string($koneksi, $_POST['petugas']);
        $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

        $query = "INSERT INTO jadwal (tanggal, jam, lokasi, petugas, keterangan) 
                  VALUES ('$tanggal', '$jam', '$lokasi', '$petugas', '$keterangan')";
        
        if(mysqli_query($koneksi, $query)){
            header('location:jadwal.php?pesan=berhasil');
        } else {
            header('location:jadwal.php?pesan=gagal');
        }
    }
} else if(isset($_GET['action']) && $_GET['action'] == 'hapus'){
    $id = $_GET['id'];
    $query = "DELETE FROM jadwal WHERE id_jadwal = $id";
    
    if(mysqli_query($koneksi, $query)){
        header('location:jadwal.php?pesan=hapus_berhasil');
    } else {
        header('location:jadwal.php?pesan=hapus_gagal');
    }
}
?>