<?php
    require 'functions/query.php'; 
    session_start();
    
    if(isset($_COOKIE['login'])) {
        if ($_COOKIE['level'] == 'admin') {
            $_SESSION['login'] = true;
            $username = $_COOKIE['login'];
        } 
        
        elseif ($_COOKIE['level'] == 'user') {
            $_SESSION['login'] = true;
            header('Location: restricted');
        }
    } 

    elseif ($_SESSION['role'] == 'admin') {
        $username = $_SESSION['user'];
    } 
    
    else {
        if ($_SESSION['role'] == 'user') {
            header('Location: restricted');
            exit;
        }
    }

    if(empty($_SESSION['login'])) {
        header('Location: login');
        exit;
    } 

    $kategoriPengeluaran = query("SELECT * FROM kategori_pengeluaran");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/rns.png">
    <title>Dashboard | Renus Money Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-white shadow sticky-top">
        <div class="container-fluid">
            <img src="img/rns.png" width="35px">
            <div class="ms-2 navbar-brand fw-bold">Renus Money Manager</div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="dashboardAdmin">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="dashboard">Kelola Pengguna</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link active dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Kelola Kategori
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="kelolaKategoriPengeluaran">Kategori Pengeluaran</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="kelolaKategoriPemasukan">Kategori Pemasukan</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="dropdown">
                <button class="btn dropdown-toggle" style="background: #73c2fb" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Welcome, <?= $username ?>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="functions/logout">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4 border rounded-4 shadow">
        <div class="row">
            <div class="col">
                <div class="m-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        Tambah Kategori
                    </button>
                </div>
                <div class="p-3">
                    <table class="table">
                        <thead>
                            <tr class="table-primary">
                            <th scope="col">No.</th>
                            <th scope="col">Nama Kategori</th>
                            <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $index = 1 ?>
                            <?php foreach($kategoriPengeluaran as $kategori) : ?>
                            <tr>
                                <th scope="row"> <?= $index ?> </th>
                                <td> <?= $kategori['nama_kategori'] ?> </td>
                                <td>
                                    <div class="d-flex">
                                        <button type="button" class="btn btn-primary me-3" data-bs-toggle="modal" data-bs-target="#modalEdit"
                                                data-id-kategori="<?= $kategori['id_kategori'] ?>" data-nama-kategori="<?= $kategori['nama_kategori'] ?>">
                                            Edit
                                        </button>
                                        <form action="functions/hapusKategori" method="POST">
                                            <input type="hidden" name="nama_table" value="pengeluaran">
                                            <input type="hidden" name="id_kategori" value="<?= $kategori['id_kategori'] ?>">
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php $index++; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk tambah data -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalTambahLabel">Tambah Data</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="functions/tambahKategori" method="POST">
                    <input type="hidden" name="nama_table" value="pengeluaran">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="namaKategori" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control filter" id="namaKategori" name="nama_kategori" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" name="tambahKategori" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal untuk edit data -->
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalEditLabel">Edit Data</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="functions/editKategori" method="POST">
                    <input type="hidden" name="nama_table" value="pengeluaran">
                    <input type="hidden" name="id_kategori">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="namaKategori" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control filter" id="namaKategori" name="nama_kategori" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" name="editKategori" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
        // JS untuk isi data modal edit
        $('#modalEdit').on('show.bs.modal', function(e) {
            var idKategori = $(e.relatedTarget).data('id-kategori');
            var namaKategori = $(e.relatedTarget).data('nama-kategori');

            $(e.currentTarget).find('input[name="id_kategori"]').val(idKategori);
            $(e.currentTarget).find('input[name="nama_kategori"]').val(namaKategori);
        });
    </script>
</body>
</html>

<?php
    if(isset($_SESSION["success_msg"])){
        $msg = $_SESSION["success_msg"];
        echo "
            <script>
                swal('Success','$msg','success');
            </script>
        ";

        unset($_SESSION["success_msg"]);
    }

    if(isset($_SESSION["error_msg"])){
        $msg = $_SESSION["error_msg"];
        echo "
            <script>
                swal('Error','$msg','error');
            </script>
        ";

        unset($_SESSION["error_msg"]);
    }
?>