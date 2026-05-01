<?php 
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'tambah') {
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $kdProdi = mysqli_real_escape_string($conn, $_POST['kdProdi']);
    $thnMsk = mysqli_real_escape_string($conn, $_POST['thnMsk']);

    mysqli_query($conn, "INSERT INTO mahasiswa VALUES('$nim','$nama','$kdProdi','$thnMsk')");
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'update') {

    $old_nim = mysqli_real_escape_string($conn, $_POST['old_nim']); 
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);         
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $kdProdi = mysqli_real_escape_string($conn, $_POST['kdProdi']);
    $thnMsk = mysqli_real_escape_string($conn, $_POST['thnMsk']);

    mysqli_query($conn, "UPDATE mahasiswa SET 
        nim='$nim',
        nama='$nama',
        kdProdi='$kdProdi',
        thnMsk='$thnMsk'
        WHERE nim='$old_nim'
    ");

    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'hapus') {
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    mysqli_query($conn, "DELETE FROM mahasiswa WHERE nim='$nim'");
    header("Location: index.php");
    exit;
}

if (isset($_GET['getdata'])) {
    $nim = mysqli_real_escape_string($conn, $_GET['nim']);
    $query = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE nim='$nim'");
    $data = mysqli_fetch_assoc($query);
    echo json_encode($data);
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Mahasiswa</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container-main">
    <div class="card-custom">

        <div class="header-section">
            <div class="title-group">
                <img src="upj.png" class="title-logo">
                <h1>Tabel Mahasiswa</h1>
            </div>

            <button class="btn-action btn-add" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <span class="btn-icon">+</span>
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Prodi</th>
                        <th>Tahun</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                <?php
                $data = mysqli_query($conn, "SELECT * FROM mahasiswa");
                while ($d = mysqli_fetch_array($data)) {
                ?>
                    <tr>
                        <td><?= $d['nim']; ?></td>
                        <td><?= $d['nama']; ?></td>
                        <td><?= $d['kdProdi']; ?></td>
                        <td><?= $d['thnMsk']; ?></td>
                        <td>
                            <button class="btn-action btn-edit"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEdit"
                                onclick="editData('<?= $d['nim']; ?>')">
                                ✎
                            </button>

                            <button class="btn-action btn-delete"
                                data-bs-toggle="modal"
                                data-bs-target="#modalHapus"
                                onclick="setDeleteNim('<?= $d['nim']; ?>')">
                                🗑
                            </button>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>

            </table>
        </div>

    </div>
</div>

<div class="modal fade" id="modalTambah">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="tambah">

                    <input type="text" name="nim" class="form-control mb-2" placeholder="NIM" required>
                    <input type="text" name="nama" class="form-control mb-2" placeholder="Nama" required>
                    <input type="text" name="kdProdi" class="form-control mb-2" placeholder="Kode Prodi" required>
                    <input type="number" name="thnMsk" class="form-control mb-2" placeholder="Tahun Masuk" required>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="update">

                    <input type="hidden" name="old_nim" id="old_nim">

                    <input type="text" name="nim" id="edit_nim" class="form-control mb-2" required>

                    <input type="text" name="nama" id="edit_nama" class="form-control mb-2" required>
                    <input type="text" name="kdProdi" id="edit_kdProdi" class="form-control mb-2" required>
                    <input type="number" name="thnMsk" id="edit_thnMsk" class="form-control mb-2" required>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                    <button class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalHapus">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="hapus">
                    <input type="hidden" name="nim" id="delete_nim">

                    <p>Yakin ingin menghapus data ini dik?</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function editData(nim) {
    fetch('index.php?getdata=1&nim=' + nim)
    .then(res => res.json())
    .then(data => {
        document.getElementById('edit_nim').value = data.nim;
        document.getElementById('old_nim').value = data.nim; 

        document.getElementById('edit_nama').value = data.nama;
        document.getElementById('edit_kdProdi').value = data.kdProdi;
        document.getElementById('edit_thnMsk').value = data.thnMsk;
    });
}

function setDeleteNim(nim) {
    document.getElementById('delete_nim').value = nim;
}
</script>

</body>
</html>