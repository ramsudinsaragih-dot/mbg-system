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
        $nomor = mysqli_real_escape_string($koneksi, $_POST['nomor']);
        $id_penerima = $_POST['id_penerima'];
        $id_petugas = $_POST['id_petugas'];
        $tanggal = $_POST['tanggal'];
        $menu = mysqli_real_escape_string($koneksi, $_POST['menu']);
        $jumlah = $_POST['jumlah'];
        $lokasi = mysqli_real_escape_string($koneksi, $_POST['lokasi']);
        $status = $_POST['status'];
        $foto = '';

        // Handle file upload
        if(isset($_FILES['foto']) && $_FILES['foto']['size'] > 0){
            $file_name = time() . '_' . $_FILES['foto']['name'];
            $file_tmp = $_FILES['foto']['tmp_name'];
            move_uploaded_file($file_tmp, "../assets/img/distribusi/" . $file_name);
            $foto = $file_name;
        }

        $query = "INSERT INTO distribusi (nomor, id_penerima, id_petugas, tanggal, menu, jumlah, lokasi, status, foto) 
                  VALUES ('$nomor', $id_penerima, $id_petugas, '$tanggal', '$menu', $jumlah, '$lokasi', '$status', '$foto')";
        
        if(mysqli_query($koneksi, $query)){
            header('location:distribusi.php?pesan=berhasil');
        } else {
            header('location:distribusi.php?pesan=gagal');
        }
    }
} else if(isset($_GET['action']) && $_GET['action'] == 'hapus'){
    $id = $_GET['id'];
    $query = "DELETE FROM distribusi WHERE id_distribusi = $id";
    
    if(mysqli_query($koneksi, $query)){
        header('location:distribusi.php?pesan=hapus_berhasil');
    } else {
        header('location:distribusi.php?pesan=hapus_gagal');
    }
}
?>