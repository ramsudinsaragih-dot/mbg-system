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
    <title>Distribusi - MBG System</title>
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

        .form-control {
            border-radius: 10px;
            border: 1px solid #ddd;
            padding: 10px 15px;
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
            <li><a href="distribusi.php" class="active"><i class="fas fa-box"></i> Distribusi</a></li>
            
            <li class="menu-title">Lainnya</li>
            <li><a href="jadwal.php"><i class="fas fa-calendar"></i> Jadwal</a></li>
            <li><a href="../laporan/laporan.php"><i class="fas fa-file-pdf"></i> Laporan</a></li>
        </ul>
    </div>

    <!-- CONTENT -->
    <div class="content">
        <div class="navbar-top">
            <div class="navbar-title">
                <i class="fas fa-box"></i> Distribusi MBG
            </div>
            <button type="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="fas fa-plus"></i> Tambah Distribusi
            </button>
        </div>

        <div class="card-dashboard">
            <div class="search-box">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari berdasarkan Nama Penerima, Menu, atau Status...">
            </div>

            <div class="table-responsive">
                <table class="table table-hover" id="tabelDistribusi">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor</th>
                            <th>Tanggal</th>
                            <th>Nama Penerima</th>
                            <th>Menu</th>
                            <th>Jumlah</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT d.*, p.nama FROM distribusi d 
                                 JOIN penerima p ON d.id_penerima = p.id_penerima 
                                 ORDER BY d.tanggal DESC";
                        $result = mysqli_query($koneksi, $query);
                        $no = 1;
                        while($row = mysqli_fetch_assoc($result)){
                            $status_badge = '';
                            if($row['status'] == 'Menunggu') $status_badge = '<span class="badge bg-warning">Menunggu</span>';
                            else if($row['status'] == 'Disalurkan') $status_badge = '<span class="badge bg-info">Disalurkan</span>';
                            else if($row['status'] == 'Selesai') $status_badge = '<span class="badge bg-success">Selesai</span>';
                            
                            echo "<tr>
                                <td>{$no}</td>
                                <td>{$row['nomor']}</td>
                                <td>".date('d/m/Y', strtotime($row['tanggal']))."</td>
                                <td>{$row['nama']}</td>
                                <td>{$row['menu']}</td>
                                <td>{$row['jumlah']}</td>
                                <td>{$row['lokasi']}</td>
                                <td>{$status_badge}</td>
                                <td>
                                    <button class="btn-action btn-edit" onclick='editDistribusi({$row['id_distribusi']})'>Edit</button>
                                    <button class="btn-action btn-delete" onclick='deleteDistribusi({$row['id_distribusi']})'>Hapus</button>
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
                    <h5 class="modal-title" style="color: #0F4C81; font-weight: 700;">Tambah Distribusi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formDistribusi" method="POST" action="proses_distribusi.php" enctype="multipart/form-data">
                        <input type="hidden" name="id_distribusi" id="id_distribusi">
                        <input type="hidden" name="action" id="action" value="tambah">
                        
                        <div class="mb-3">
                            <label class="form-label">Nomor Distribusi</label>
                            <input type="text" class="form-control" name="nomor" id="nomor" placeholder="AUTO-001" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Penerima</label>
                            <select class="form-control" name="id_penerima" id="id_penerima" required>
                                <option value="">- Pilih Penerima -</option>
                                <?php
                                $penerima = mysqli_query($koneksi, "SELECT * FROM penerima WHERE status='Aktif'");
                                while($p = mysqli_fetch_assoc($penerima)){
                                    echo "<option value='{$p['id_penerima']}'>{$p['nama']} ({$p['nik']})</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal</label>
                                <input type="date" class="form-control" name="tanggal" id="tanggal" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Petugas</label>
                                <select class="form-control" name="id_petugas" id="id_petugas" required>
                                    <option value="">- Pilih Petugas -</option>
                                    <?php
                                    $petugas = mysqli_query($koneksi, "SELECT * FROM petugas");
                                    while($p = mysqli_fetch_assoc($petugas)){
                                        echo "<option value='{$p['id_petugas']}'>{$p['nama']} ({$p['jabatan']})</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Menu</label>
                                <input type="text" class="form-control" name="menu" id="menu" placeholder="Contoh: Nasi + Ayam" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jumlah Paket</label>
                                <input type="number" class="form-control" name="jumlah" id="jumlah" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Lokasi</label>
                            <textarea class="form-control" name="lokasi" id="lokasi" rows="2" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-control" name="status" id="status" required>
                                    <option value="Menunggu">Menunggu</option>
                                    <option value="Disalurkan">Disalurkan</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Upload Foto (Optional)</label>
                                <input type="file" class="form-control" name="foto" id="foto" accept="image/*">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #eee;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" onclick="simpanDistribusi()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        function simpanDistribusi() {
            document.getElementById('formDistribusi').submit();
        }

        function editDistribusi(id) {
            alert('Edit functionality coming soon!');
        }

        function deleteDistribusi(id) {
            Swal.fire({
                title: 'Hapus Data?',
                text: 'Anda yakin ingin menghapus data distribusi ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#D32F2F',
                cancelButtonColor: '#666',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'proses_distribusi.php?action=hapus&id=' + id;
                }
            });
        }

        document.getElementById('searchInput').addEventListener('keyup', function() {
            let searchValue = this.value.toLowerCase();
            let table = document.getElementById('tabelDistribusi');
            let rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            
            for (let row of rows) {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchValue) ? '' : 'none';
            }
        });
    </script>
</body>
</html>