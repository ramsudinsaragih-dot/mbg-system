<?php
session_start();
if(!isset($_SESSION['username'])){
    header('location:../login.php?pesan=belum_login');
    exit();
}
require_once '../config/koneksi.php';

// Get total data
$total_penerima = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM penerima"))['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penerima - MBG System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-title {
            font-weight: 700;
            color: #0F4C81;
            font-size: 24px;
        }

        .btn-add {
            background: linear-gradient(135deg, #2E7D32 0%, #1b5e20 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            font-weight: 600;
        }

        .btn-add:hover {
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

        .search-box {
            margin-bottom: 20px;
        }

        .search-box input {
            border-radius: 10px;
            border: 1px solid #ddd;
            padding: 10px 15px;
            font-size: 14px;
        }

        .table-responsive {
            border-radius: 10px;
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

        table tbody tr {
            border-bottom: 1px solid #eee;
            transition: all 0.3s ease;
        }

        table tbody tr:hover {
            background: #f9f9f9;
        }

        .btn-action {
            padding: 5px 12px;
            font-size: 13px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            margin-right: 5px;
        }

        .btn-edit {
            background: #2196F3;
            color: white;
        }

        .btn-edit:hover {
            background: #1976D2;
            color: white;
        }

        .btn-delete {
            background: #D32F2F;
            color: white;
        }

        .btn-delete:hover {
            background: #b71c1c;
            color: white;
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
            <li><a href="penerima.php" class="active"><i class="fas fa-users"></i> Data Penerima</a></li>
            <li><a href="petugas.php"><i class="fas fa-id-badge"></i> Data Petugas</a></li>
            
            <li class="menu-title">Transaksi</li>
            <li><a href="../transaksi/distribusi.php"><i class="fas fa-box"></i> Distribusi</a></li>
            
            <li class="menu-title">Lainnya</li>
            <li><a href="../transaksi/jadwal.php"><i class="fas fa-calendar"></i> Jadwal</a></li>
            <li><a href="../laporan/laporan.php"><i class="fas fa-file-pdf"></i> Laporan</a></li>
        </ul>
    </div>

    <!-- CONTENT -->
    <div class="content">
        <div class="navbar-top">
            <div class="navbar-title">
                <i class="fas fa-users"></i> Data Penerima
            </div>
            <button type="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="fas fa-plus"></i> Tambah Penerima
            </button>
        </div>

        <div class="card-dashboard">
            <div class="search-box">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari berdasarkan Nama, NIK, atau Alamat...">
            </div>

            <div class="table-responsive">
                <table class="table table-hover" id="tabelPenerima">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Tanggal Lahir</th>
                            <th>Alamat</th>
                            <th>No HP</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM penerima ORDER BY id_penerima DESC";
                        $result = mysqli_query($koneksi, $query);
                        $no = 1;
                        while($row = mysqli_fetch_assoc($result)){
                            $status_badge = $row['status'] == 'Aktif' ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>';
                            echo "<tr>
                                <td>{$no}</td>
                                <td>{$row['nik']}</td>
                                <td>{$row['nama']}</td>
                                <td>{$row['jk']}</td>
                                <td>".date('d/m/Y', strtotime($row['tgl_lahir']))."</td>
                                <td>{$row['alamat']}</td>
                                <td>{$row['no_hp']}</td>
                                <td>{$status_badge}</td>
                                <td>
                                    <button class="btn-action btn-edit" onclick='editPenerima({$row['id_penerima']})'>Edit</button>
                                    <button class="btn-action btn-delete" onclick='deletePenerima({$row['id_penerima']})'>Hapus</button>
                                </td>
                            </tr>";
                            $no++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH/EDIT -->
    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 15px;">
                <div class="modal-header" style="border-bottom: 1px solid #eee;">
                    <h5 class="modal-title" style="color: #0F4C81; font-weight: 700;">Tambah Data Penerima</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formPenerima" method="POST" action="proses_penerima.php">
                        <input type="hidden" name="id_penerima" id="id_penerima">
                        <input type="hidden" name="action" id="action" value="tambah">
                        
                        <div class="mb-3">
                            <label class="form-label">NIK</label>
                            <input type="text" class="form-control" name="nik" id="nik" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama" id="nama" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Kelamin</label>
                                <select class="form-control" name="jk" id="jk" required>
                                    <option value="">- Pilih -</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" name="tgl_lahir" id="tgl_lahir" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" id="alamat" rows="3" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nomor HP</label>
                                <input type="text" class="form-control" name="no_hp" id="no_hp">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-control" name="status" id="status" required>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Tidak Aktif">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #eee;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" onclick="simpanPenerima()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        function simpanPenerima() {
            document.getElementById('formPenerima').submit();
        }

        function editPenerima(id) {
            // This would fetch data via AJAX
            alert('Edit functionality coming soon!');
        }

        function deletePenerima(id) {
            Swal.fire({
                title: 'Hapus Data?',
                text: 'Anda yakin ingin menghapus data ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#D32F2F',
                cancelButtonColor: '#666',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'proses_penerima.php?action=hapus&id=' + id;
                }
            });
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let searchValue = this.value.toLowerCase();
            let table = document.getElementById('tabelPenerima');
            let rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            
            for (let row of rows) {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchValue) ? '' : 'none';
            }
        });
    </script>
</body>
</html>