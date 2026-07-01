<?php
session_start();
if(!isset($_SESSION['username'])){
    header('location:../login.php?pesan=belum_login');
    exit();
}
require_once '../config/koneksi.php';

// Include PHPExcel library
require_once '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set column widths
$sheet->getColumnDimension('A')->setWidth(5);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(10);
$sheet->getColumnDimension('G')->setWidth(25);
$sheet->getColumnDimension('H')->setWidth(15);
$sheet->getColumnDimension('I')->setWidth(15);

// Add header
$sheet->setCellValue('A1', 'LAPORAN DISTRIBUSI MBG');
$sheet->setCellValue('A2', 'BADAN GIZI NASIONAL - Kelurahan Kerasaan I');
$sheet->setCellValue('A3', 'Periode: '.date('d/m/Y'));
$sheet->setCellValue('A4', '');

// Add table headers
$sheet->setCellValue('A5', 'No');
$sheet->setCellValue('B5', 'Tanggal');
$sheet->setCellValue('C5', 'Nomor');
$sheet->setCellValue('D5', 'Nama Penerima');
$sheet->setCellValue('E5', 'Menu');
$sheet->setCellValue('F5', 'Jumlah');
$sheet->setCellValue('G5', 'Lokasi');
$sheet->setCellValue('H5', 'Petugas');
$sheet->setCellValue('I5', 'Status');

// Style header
for($col = 'A'; $col <= 'I'; $col++){
    $sheet->getStyle($col.'5')->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
    $sheet->getStyle($col.'5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF0F4C81');
}

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

// Add data
$row = 6;
$no = 1;
$total_jumlah = 0;

while($data = mysqli_fetch_assoc($result)){
    $total_jumlah += $data['jumlah'];
    
    $sheet->setCellValue('A'.$row, $no);
    $sheet->setCellValue('B'.$row, date('d/m/Y', strtotime($data['tanggal'])));
    $sheet->setCellValue('C'.$row, $data['nomor']);
    $sheet->setCellValue('D'.$row, $data['nama']);
    $sheet->setCellValue('E'.$row, $data['menu']);
    $sheet->setCellValue('F'.$row, $data['jumlah']);
    $sheet->setCellValue('G'.$row, $data['lokasi']);
    $sheet->setCellValue('H'.$row, $data['nama_petugas']);
    $sheet->setCellValue('I'.$row, $data['status']);
    
    $row++;
    $no++;
}

// Add total
$sheet->setCellValue('D'.$row, 'TOTAL PAKET:');
$sheet->setCellValue('F'.$row, $total_jumlah);
$sheet->getStyle('D'.$row.':F'.$row)->getFont()->setBold(true);

// Write file
$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Laporan_Distribusi_'.date('Y-m-d').'.xlsx"');
$writer->save('php://output');
?>