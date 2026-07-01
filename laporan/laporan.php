<?php
session_start();
if(!isset($_SESSION['username'])){
    header('location:../login.php?pesan=belum_login');
    exit();
}
require_once '../config/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - MBG System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .sidebar {
            background: linear-gradient(180deg, #0F4C81 0%, #1a5fa0 100%);
            min-height: 100vh;
            padding: 20px 0;
            position: fixed;
            width: 250px;
            left: 0;
            top: 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .logo-app {
            text-align: center;
            padding: 20px;
            color: white;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }

        .logo-app h5 {
            font-weight: 700;
            font-size: 13px;
            margin-top: 10px;
            line-height: 1.3;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin: 0;
        }

        .sidebar-menu a {
            display: block;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            padding: 15px 20px;
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.1);
            border-left-color: #2E7D32;
            color: white;
        }

        .sidebar-menu .menu-title {
            color: rgba(255,255,255,0.5);
            font-size: 12px;
            padding: 15px 20px 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .content {
            margin-left: 250px;
            padding: 30px;
        }

        .navbar-top {
            background: white;
            padding: 15px 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .navbar-title {
            font-weight: 700;
            color: #0F4C81;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .filter-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .filter-row {
            display: flex;
            gap: 15px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .filter-group label {
            color: #0F4C81;
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
            font-size: 14px;
        }

        .filter-group input,
        .filter-group select {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 14px;
        }

        .btn-filter {
            background: linear-gradient(135deg, #0F4C81 0%, #1a5fa0 100%);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 14px;
        }

        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(15, 76, 129, 0.3);
            color: white;
        }

        .btn-export {
            background: linear-gradient(135deg, #2E7D32 0%, #1b5e20 100%);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            gap: 8px;
        }

        .btn-export:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 125, 50, 0.3);
            color: white;
        }

        .card-dashboard {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border: 1px solid rgba(0,0,0,0.05);
        }

        table thead {
            background: #f5f7fa;
        }

        table th {
            color: #0F4C81;
            font-weight: 600;
            border: none;
            padding: 15px;
        }

        table td {
            border: none;
            padding: 15px;
            vertical-align: middle;
        }

        table tbody tr:hover {
            background: #f9f9f9;
        }

        .badge {
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                min-height: auto;
            }

            .content {
                margin-left: 0;
            }

            .filter-row {
                flex-direction: column;
            }

            .filter-group {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="logo-app">
            <div style="font-size: 30px; color: #2E7D32;"><i class="fas fa-leaf"></i></div>
            <h5>MBG SYSTEM<br><small style="font-size: 11px; font-weight: 400;">Kelurahan Kerasaan I</small></h5>
        </div>

        <ul class="sidebar-menu">
            <li><a href="../dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            
            <li class="menu-title">Master Data</li>
            <li><a href="../master/penerima.php"><i class="fas fa-users"></i> Data Penerima</a></li>
            <li><a href="../master/petugas.php"><i class="fas fa-id-badge"></i> Data Petugas</a></li>
            
            <li class="menu-title">Transaksi</li>
            <li><a href="../transaksi/distribusi.php"><i class="fas fa-box"></i> Distribusi</a></li>
            
            <li class="menu-title">Lainnya</li>
            <li><a href="../transaksi/jadwal.php"><i class="fas fa-calendar"></i> Jadwal</a></li>
            <li><a href="laporan.php" class="active"><i class="fas fa-file-pdf"></i> Laporan</a></li>
        </ul>
    </div>

    <!-- CONTENT -->
    <div class="content">
        <div class="navbar-top">
            <div class="navbar-title">
                <i class="fas fa-file-pdf"></i> Laporan Distribusi
            </div>
        </div>

        <!-- FILTER SECTION -->
        <div class="filter-section">
            <form method="GET" id="formFilter">
                <div class="filter-row">
                    <div class="filter-group">
                        <label>Tanggal Awal</label>
                        <input type="date" name="tgl_awal" value="<?php echo isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : ''; ?>">
                    </div>

                    <div class="filter-group">
                        <label>Tanggal Akhir</label>
                        <input type="date" name="tgl_akhir" value="<?php echo isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : ''; ?>">
                    </div>

                    <div class="filter-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="">- Semua Status -</option>
                            <option value="Menunggu" <?php echo isset($_GET['status']) && $_GET['status'] == 'Menunggu' ? 'selected' : ''; ?>>Menunggu</option>
                            <option value="Disalurkan" <?php echo isset($_GET['status']) && $_GET['status'] == 'Disalurkan' ? 'selected' : ''; ?>>Disalurkan</option>
                            <option value="Selesai" <?php echo isset($_GET['status']) && $_GET['status'] == 'Selesai' ? 'selected' : ''; ?>>Selesai</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Cari</button>
                    <a href="laporan.php" class="btn-filter" style="background: #666;"><i class="fas fa-redo"></i> Reset</a>
                </div>
            </form>
        </div>

        <!-- BUTTONS SECTION -->
        <div style="margin-bottom: 25px; display: flex; gap: 15px; flex-wrap: wrap;">
            <a href="export_pdf.php?<?php echo isset($_GET['tgl_awal']) ? 'tgl_awal='.$_GET['tgl_awal'].'&tgl_akhir='.$_GET['tgl_akhir'].'&status='.$_GET['status'] : ''; ?>" class="btn-export">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            <a href="export_excel.php?<?php echo isset($_GET['tgl_awal']) ? 'tgl_awal='.$_GET['tgl_awal'].'&tgl_akhir='.$_GET['tgl_akhir'].'&status='.$_GET['status'] : ''; ?>" class="btn-export" style="background: linear-gradient(135deg, #F9A825 0%, #f57f17 100%);">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
            <button onclick="window.print()" class="btn-export" style="background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);">
                <i class="fas fa-print"></i> Cetak
            </button>
        </div>

        <!-- TABLE SECTION -->
        <div class="card-dashboard">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nomor Distribusi</th>
                            <th>Nama Penerima</th>
                            <th>Menu</th>
                            <th>Jumlah</th>
                            <th>Lokasi</th>
                            <th>Petugas</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT d.*, p.nama, pt.nama as nama_petugas FROM distribusi d 
                                 JOIN penerima p ON d.id_penerima = p.id_penerima 
                                 JOIN petugas pt ON d.id_petugas = pt.id_petugas 
                                 WHERE 1=1";

                        // Apply filters
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
                        $no = 1;
                        $total_jumlah = 0;

                        if(mysqli_num_rows($result) > 0){
                            while($row = mysqli_fetch_assoc($result)){
                                $status_badge = '';
                                if($row['status'] == 'Menunggu') $status_badge = '<span class="badge bg-warning">Menunggu</span>';
                                else if($row['status'] == 'Disalurkan') $status_badge = '<span class="badge bg-info">Disalurkan</span>';
                                else if($row['status'] == 'Selesai') $status_badge = '<span class="badge bg-success">Selesai</span>';
                                
                                $total_jumlah += $row['jumlah'];
                                
                                echo "<tr>
                                    <td>{$no}</td>
                                    <td>".date('d/m/Y', strtotime($row['tanggal']))."</td>
                                    <td>{$row['nomor']}</td>
                                    <td>{$row['nama']}</td>
                                    <td>{$row['menu']}</td>
                                    <td>{$row['jumlah']}</td>
                                    <td>{$row['lokasi']}</td>
                                    <td>{$row['nama_petugas']}</td>
                                    <td>{$status_badge}</td>
                                </tr>";
                                $no++;
                            }
                            echo "<tr style='background: #f5f7fa; font-weight: 600;'>
                                <td colspan='5' style='text-align: right;'>TOTAL PAKET:</td>
                                <td>{$total_jumlah}</td>
                                <td colspan='3'></td>
                            </tr>";
                        } else {
                            echo "<tr><td colspan='9' style='text-align: center; padding: 30px;'>Tidak ada data laporan</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>