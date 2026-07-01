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
        $nik = mysqli_real_escape_string($koneksi, $_POST['nik']);
        $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
        $jk = $_POST['jk'];
        $tgl_lahir = $_POST['tgl_lahir'];
        $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
        $no_hp = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
        $status = $_POST['status'];

        $query = "INSERT INTO penerima (nik, nama, jk, tgl_lahir, alamat, no_hp, status) 
                  VALUES ('$nik', '$nama', '$jk', '$tgl_lahir', '$alamat', '$no_hp', '$status')";
        
        if(mysqli_query($koneksi, $query)){
            header('location:penerima.php?pesan=berhasil');
        } else {
            header('location:penerima.php?pesan=gagal');
        }
    }
} else if(isset($_GET['action']) && $_GET['action'] == 'hapus'){
    $id = $_GET['id'];
    $query = "DELETE FROM penerima WHERE id_penerima = $id";
    
    if(mysqli_query($koneksi, $query)){
        header('location:penerima.php?pesan=hapus_berhasil');
    } else {
        header('location:penerima.php?pesan=hapus_gagal');
    }
}
?>