<?php
session_start();
if(!isset($_SESSION['username'])){
    header('location:../login.php?pesan=belum_login');
    exit();
}
require_once '../config/koneksi.php';

// Include MPDF library
require_once '../vendor/autoload.php';
use Mpdf\Mpdf;

$mpdf = new Mpdf();

// Build query
$query = "SELECT d.*, p.nama, pt.nama as nama_petugas FROM distribusi d 
         JOIN penerima p ON d.id_penerima = p.id_penerima 
         JOIN petugas pt ON d.id_petugas = pt.id_petugas 
         WHERE 1=1";

if(isset($_GET['tgl_awal']) && $_GET['tgl_awal'] != ''){
    $query .= " AND DATE(d.tanggal) >= '".$_GET['tgl_awal']."'";
}
if(isset($_GET['tgl_akhir']) && $_GET['tgl_akhir'] != ''){
    $query .= " AND DATE(d.tanggal) <= '".$_GET['tgl_akhir']."'";
}
if(isset($_GET['status']) && $_GET['status'] != ''){
    $query .= " AND d.status = '".$_GET['status']."'";
}

$query .= " ORDER BY d.tanggal DESC";

$result = mysqli_query($koneksi, $query);

// HTML content
$html = "<style>
    body { font-family: Arial, sans-serif; }
    .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #0F4C81; padding-bottom: 10px; }
    .header h2 { color: #0F4C81; margin: 0; }
    .header p { margin: 5px 0; color: #666; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th { background: #0F4C81; color: white; padding: 10px; text-align: left; font-size: 12px; }
    td { padding: 10px; border-bottom: 1px solid #ddd; font-size: 11px; }
    tr:nth-child(even) { background: #f5f5f5; }
    .footer { margin-top: 20px; text-align: right; font-size: 10px; color: #666; }
</style>

<div class='header'>
    <h2>LAPORAN DISTRIBUSI MBG</h2>
    <p>BADAN GIZI NASIONAL - Kelurahan Kerasaan I</p>
    <p>Periode: ".date('d/m/Y')."
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Nomor</th>
            <th>Nama Penerima</th>
            <th>Menu</th>
            <th>Jumlah</th>
            <th>Lokasi</th>
            <th>Petugas</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>";

$no = 1;
$total_jumlah = 0;
while($row = mysqli_fetch_assoc($result)){
    $total_jumlah += $row['jumlah'];
    $html .= "<tr>
        <td>{$no}</td>
        <td>".date('d/m/Y', strtotime($row['tanggal']))."</td>
        <td>{$row['nomor']}</td>
        <td>{$row['nama']}</td>
        <td>{$row['menu']}</td>
        <td>{$row['jumlah']}</td>
        <td>{$row['lokasi']}</td>
        <td>{$row['nama_petugas']}</td>
        <td>{$row['status']}</td>
    </tr>";
    $no++;
}

$html .= "<tr style='background: #e8e8e8; font-weight: bold;'>
    <td colspan='5' style='text-align: right;'>TOTAL PAKET:</td>
    <td>{$total_jumlah}</td>
    <td colspan='3'></td>
</tr>
    </tbody>
</table>

<div class='footer'>
    <p>Dihasilkan pada: ".date('d/m/Y H:i:s')."</p>
</div>";

$mpdf->WriteHTML($html);
$mpdf->Output('Laporan_Distribusi_'.date('Y-m-d').'.pdf', 'D');
?>