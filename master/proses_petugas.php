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
        $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
        $jabatan = mysqli_real_escape_string($koneksi, $_POST['jabatan']);
        $username = mysqli_real_escape_string($koneksi, $_POST['username']);
        $password = hash('sha256', $_POST['password']);
        $no_hp = mysqli_real_escape_string($koneksi, $_POST['no_hp']);

        $query = "INSERT INTO petugas (nama, jabatan, username, password, no_hp) 
                  VALUES ('$nama', '$jabatan', '$username', '$password', '$no_hp')";
        
        if(mysqli_query($koneksi, $query)){
            header('location:petugas.php?pesan=berhasil');
        } else {
            header('location:petugas.php?pesan=gagal');
        }
    }
} else if(isset($_GET['action']) && $_GET['action'] == 'hapus'){
    $id = $_GET['id'];
    $query = "DELETE FROM petugas WHERE id_petugas = $id";
    
    if(mysqli_query($koneksi, $query)){
        header('location:petugas.php?pesan=hapus_berhasil');
    } else {
        header('location:petugas.php?pesan=hapus_gagal');
    }
}
?>