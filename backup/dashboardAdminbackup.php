<?php
    require 'functions/query.php'; 
    session_start();
    $db = mysqli_connect('localhost', 'u749483805_cash', 'Ramadani1234?', 'u749483805_cash');
    

// Execute your SQL query (replace with your actual query)
    $sql_pemasukan = "SELECT users.id_user, users.username, pemasukan.tanggal, pemasukan.deskripsi, pemasukan.jumlah
    FROM users
    JOIN pemasukan ON users.id_user = pemasukan.id_user";

    $sql_pengeluaran = "SELECT users.id_user, users.username, pengeluaran.tanggal, pengeluaran.deskripsi, pengeluaran.jumlah
    FROM users
    JOIN pengeluaran ON users.id_user = pengeluaran.id_user";
    
    $result = mysqli_query($db,$sql_pemasukan);
    $result2 = mysqli_query($db,$sql_pengeluaran);
    // $result = $db->query($sql);
    if(isset($_COOKIE['login'])) {
        if ($_COOKIE['level'] == 'admin') {
            $_SESSION['login'] = true;
            $username = $_COOKIE['login'];
        } 
        
        elseif ($_COOKIE['level'] == 'user') {
            $_SESSION['login'] = true;
            header('Location: dashboard');
        }
    } 

    elseif ($_SESSION['role'] == 'admin') {
        $username = $_SESSION['user'];
    } 
    
    else {
        if ($_SESSION['role'] == 'user') {
            header('Location: dashboard');
            exit;
        }
    }

    if(empty($_SESSION['login'])) {
        header('Location: login');
        exit;
    } 

    $users = query("SELECT * FROM users");

    $tglAwalTahun = date("Y-01-01");
    $tglAkhirTahun = date("Y-12-31");

    $queryKategoriPengeluaran = "";
    $queryKategoriPemasukan = "";

    $queryUserPengeluaran = "";
    $queryUserPemasukan = "";

    if(isset($_GET["id_kategori_pengeluaran"])) {
        if($_GET["id_kategori_pengeluaran"] != "All"){
            $idKategoriPengeluaran = $_GET["id_kategori_pengeluaran"];
            $queryKategoriPengeluaran = "AND id_kategori = $idKategoriPengeluaran";
        }
    }

    if(isset($_GET["id_kategori_pemasukan"])) {
        if($_GET["id_kategori_pemasukan"] != "All"){
            $idKategoriPemasukan = $_GET["id_kategori_pemasukan"];
            $queryKategoriPemasukan = "AND id_kategori = $idKategoriPemasukan";
        }
    }

    if(isset($_GET["id_user_pengeluaran"])) {
        if($_GET["id_user_pengeluaran"] != "All"){
            $idUserPengeluaran = $_GET["id_user_pengeluaran"];
            $queryUserPengeluaran = "AND id_user = $idUserPengeluaran";

            $_GET["id_user_pemasukan"] = $idUserPengeluaran;
            $idUserPemasukan = $_GET["id_user_pemasukan"];
            $queryUserPemasukan = "AND id_user = $idUserPemasukan";
        }
    }

    if(isset($_GET["id_user_pemasukan"])) {
        if($_GET["id_user_pemasukan"] != "All"){
            $idUserPemasukan = $_GET["id_user_pemasukan"];
            $queryUserPemasukan = "AND id_user = $idUserPemasukan";

            $_GET["id_user_pengeluaran"] = $idUserPemasukan;
            $idUserPengeluaran = $_GET["id_user_pengeluaran"];
            $queryUserPengeluaran = "AND id_user = $idUserPengeluaran";
        }
    }

    $queryPengeluaranTahunan = "SELECT id_user, DATE_FORMAT(tanggal, '%c') AS bulan, SUM(jumlah) AS total_pengeluaran FROM pengeluaran
                            WHERE tanggal >= '$tglAwalTahun' AND tanggal <= '$tglAkhirTahun'"
                        . $queryKategoriPengeluaran .
                        "
                            GROUP BY id_user, bulan
                            ORDER BY id_user, bulan
                        ";
    $queryPemasukanTahunan = "SELECT id_user, DATE_FORMAT(tanggal, '%c') AS bulan, SUM(jumlah) AS total_pemasukan FROM pemasukan
                        WHERE tanggal >= '$tglAwalTahun' AND tanggal <= '$tglAkhirTahun'"
                        . $queryKategoriPemasukan .
                    "
                        GROUP BY id_user, bulan
                        ORDER BY id_user, bulan
                    ";
    
    $queryUserPengeluaran= "SELECT id_user, DATE_FORMAT(tanggal, '%c') AS bulan, SUM(jumlah) AS total_pengeluaran FROM pengeluaran
                    WHERE tanggal >= '$tglAwalTahun' AND tanggal <= '$tglAkhirTahun'"
                . $queryUserPengeluaran .
                "
                    GROUP BY id_user, bulan
                    ORDER BY id_user, bulan
                ";

    $queryUserPemasukan = "SELECT id_user, DATE_FORMAT(tanggal, '%c') AS bulan, SUM(jumlah) AS total_pemasukan FROM pemasukan
                        WHERE tanggal >= '$tglAwalTahun' AND tanggal <= '$tglAkhirTahun'"
                        . $queryUserPemasukan .
                    "
                        GROUP BY id_user, bulan
                        ORDER BY id_user, bulan
                    ";
    
    $ringkasanPengeluaranTahunan = query($queryPengeluaranTahunan);
    $ringkasanPemasukanTahunan = query($queryPemasukanTahunan);

    $ringkasanUserPemasukan = query($queryUserPemasukan);
    $ringkasanUserPengeluaran = query($queryUserPengeluaran);
  
    $totalPengeluaranTahunan = 0;
    foreach($ringkasanPengeluaranTahunan as $data){
        $totalPengeluaranTahunan += $data["total_pengeluaran"];
    }

    $totalPemasukanTahunan = 0;
    foreach($ringkasanPemasukanTahunan as $data){
        $totalPemasukanTahunan += $data["total_pemasukan"];
    }

    $totalUserPemasukan = 0;
    foreach($ringkasanUserPemasukan as $data){
        $totalUserPemasukan += $data["total_pemasukan"];
    }
    
    $totalUserPengeluaran = 0;
    foreach($ringkasanUserPengeluaran as $data){
        $totalUserPengeluaran += $data["total_pengeluaran"];
    }

    $queryPengeluaranPie = "SELECT username, SUM(jumlah) AS total_pengeluaran FROM pengeluaran
                            JOIN users ON pengeluaran.id_user = users.id_user
                            WHERE tanggal >= '$tglAwalTahun' AND tanggal <= '$tglAkhirTahun'"
                            . $queryKategoriPengeluaran .
                            "
                            GROUP BY username
                            ";

    $queryPemasukanPie = "SELECT username, SUM(jumlah) AS total_pemasukan FROM pemasukan
                            JOIN users ON pemasukan.id_user = users.id_user
                            WHERE tanggal >= '$tglAwalTahun' AND tanggal <= '$tglAkhirTahun'"
                            . $queryKategoriPemasukan .
                            "
                            GROUP BY username
                            ";

    $ringkasanPengeluaranPie = query($queryPengeluaranPie);
    $ringkasanPemasukanPie = query($queryPemasukanPie);

    $kategoriPengeluaran = query("SELECT * FROM kategori_pengeluaran");
    $kategoriPemasukan = query("SELECT * FROM kategori_pemasukan");

    $kategoriUser = query("SELECT * FROM users");
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/rns.png">
    <title>Kelola Pengguna | Renus Cash Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script src= "https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"> </script> 
    <script src="./jquery-table2excel-master/src/jquery.table2excel.js"></script> 
    <script src="./table2excel.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

</head>
<body>
        <nav class="navbar navbar-expand-lg bg-white shadow sticky-top">
        <div class="container-fluid">
            <img src="img/rns.png" width="35px">
            <div class="ms-2 navbar-brand fw-bold">Renus Cash Manager</div>
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
                    <li class="nav-item">
                        <a class="nav-link active " href="kelolaKategoriPemasukan" aria-current="page" >
                            Kelola Kategori
                        </a>
                        <!-- <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="kelolaKategoriPengeluaran">Kategori Pengeluaran</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="kelolaKategoriPemasukan">Kategori Pemasukan</a></li>
                        </ul> -->
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

    <div class="container mt-4">
        <div class="row">
            <div class="col border rounded-4 shadow me-3">
                <div class="my-5 mx-3 pb-5 border-bottom position-relative">
                    <h3 class="text-center">
                        Grafik Pengeluaran Tahunan
                    </h3>
                    <div class="position-absolute end-0" style="top: -15px;">
                        <form action="dashboardAdmin" method="GET">
                            <div class="d-flex flex-column mb-3 align-items-end">
                                <label for="kategori" class="form-label">Filter kategori</label>
                                <?php if(isset($_GET["id_kategori_pemasukan"])) : ?>
                                    <input type="hidden" name="id_kategori_pemasukan" value=<?= $_GET["id_kategori_pemasukan"] ?>>
                                <?php endif; ?>
                                <select class="form-select" style="width: 150px;" id="kategori_pengeluaran" name="id_kategori_pengeluaran" onchange="submit()">
                                    <option
                                        <?php if(!isset($_GET["id_kategori_pengeluaran"])) : ?>
                                        selected
                                        <?php endif; ?>
                                    >
                                        All
                                    </option>
                                    <?php foreach($kategoriPengeluaran as $kategori) : ?>
                                    <option value="<?= $kategori['id_kategori'] ?>"
                                        <?php if(isset($_GET["id_kategori_pengeluaran"])) : ?>
                                            <?php if($_GET["id_kategori_pengeluaran"] == $kategori['id_kategori']) : ?>
                                            selected
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    > 
                                        <?= $kategori['nama_kategori'] ?> 
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <?php if (count($ringkasanPengeluaranTahunan) > 0) : ?>
                <canvas id="bar-chart-pengeluaran" class="mb-3"></canvas>
                <?php else : ?>
                <div class="text-center mb-3">Belum ada pengeluaran di tahun ini.</div>
                <?php endif; ?>
            </div>
        </div>
        <div class="row my-5">
            <div class="col border rounded-4 shadow me-3">
                <div class="my-5 mx-3 pb-5 border-bottom position-relative">
                    <h3 class="text-center">
                        Ringkasan Pemasukan Tahunan
                    </h3>
                    <div class="position-absolute end-0" style="top: -15px;">
                        <form action="dashboardAdmin" method="GET">
                            <div class="d-flex flex-column mb-3 align-items-end">
                                <label for="kategori" class="form-label">Filter kategori</label>
                                <?php if(isset($_GET["id_kategori_pengeluaran"])) : ?>
                                    <input type="hidden" name="id_kategori_pengeluaran" value=<?= $_GET["id_kategori_pengeluaran"] ?>>
                                <?php endif; ?>
                                <select class="form-select" style="width: 150px;" id="kategori_pemasukan" name="id_kategori_pemasukan" onchange="submit()">
                                    <option
                                        <?php if(!isset($_GET["id_kategori_pemasukan"])) : ?>
                                        selected
                                        <?php endif; ?>
                                    >
                                        All
                                    </option>
                                    <?php foreach($kategoriPemasukan as $kategori) : ?>
                                    <option value="<?= $kategori['id_kategori'] ?>"
                                        <?php if(isset($_GET["id_kategori_pemasukan"])) : ?>
                                            <?php if($_GET["id_kategori_pemasukan"] == $kategori['id_kategori']) : ?>
                                            selected
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    > 
                                        <?= $kategori['nama_kategori'] ?> 
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <?php if (count($ringkasanPemasukanTahunan) > 0) : ?>
                <canvas id="bar-chart-pemasukan" class="mb-3"></canvas>
                <?php else : ?>
                <div class="text-center mb-3">Belum ada pemasukan di tahun ini.</div>
                <?php endif; ?>
            </div>
        </div>
        <div class="row my-5">
            <div class="col border rounded-4 shadow me-3">
                <div class="m-3 pb-2 border-bottom">
                    <h3 class="text-center">
                        Total Pemasukan Secara Keseluruhan
                    </h3>
                    <div>
                        <form action="dashboardAdmin" method="GET">
                            <div class="d-flex flex-column mb-3">
                                <label for="kategori" class="form-label">Filter kategori</label>
                                <?php if(isset($_GET["id_kategori_pengeluaran"])) : ?>
                                    <input type="hidden" name="id_kategori_pengeluaran" value=<?= $_GET["id_kategori_pengeluaran"] ?>>
                                <?php endif; ?>
                                <select class="form-select" style="width: 150px;" id="kategori_pemasukan" name="id_kategori_pemasukan" onchange="submit()">
                                    <option
                                        <?php if(!isset($_GET["id_kategori_pemasukan"])) : ?>
                                        selected
                                        <?php endif; ?>
                                    >
                                        All
                                    </option>
                                    <?php foreach($kategoriPemasukan as $kategori) : ?>
                                    <option value="<?= $kategori['id_kategori'] ?>"
                                        <?php if(isset($_GET["id_kategori_pemasukan"])) : ?>
                                            <?php if($_GET["id_kategori_pemasukan"] == $kategori['id_kategori']) : ?>
                                            selected
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    > 
                                        <?= $kategori['nama_kategori'] ?> 
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <h4 class="text-center my-5">
                    Rp. <?= number_format($totalPemasukanTahunan, 0, ",", ".") ?>
                </h4>
            </div>
            <div class="col border rounded-4 shadow me-3">
                <div class="m-3 pb-2 border-bottom">
                    <h3 class="text-center">
                        Total Pengeluaran Secara Keseluruhan
                    </h3>
                    <div>
                        <form action="dashboardAdmin" method="GET">
                            <div class="d-flex flex-column mb-3">
                                <label for="kategori" class="form-label">Filter kategori</label>
                                <?php if(isset($_GET["id_kategori_pemasukan"])) : ?>
                                    <input type="hidden" name="id_kategori_pemasukan" value=<?= $_GET["id_kategori_pemasukan"] ?>>
                                <?php endif; ?>
                                <select class="form-select" style="width: 150px;" id="kategori_pengeluaran" name="id_kategori_pengeluaran" onchange="submit()">
                                    <option
                                        <?php if(!isset($_GET["id_kategori_pengeluaran"])) : ?>
                                        selected
                                        <?php endif; ?>
                                    >
                                        All
                                    </option>
                                    <?php foreach($kategoriPengeluaran as $kategori) : ?>
                                    <option value="<?= $kategori['id_kategori'] ?>"
                                        <?php if(isset($_GET["id_kategori_pengeluaran"])) : ?>
                                            <?php if($_GET["id_kategori_pengeluaran"] == $kategori['id_kategori']) : ?>
                                            selected
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    > 
                                        <?= $kategori['nama_kategori'] ?> 
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <h4 class="text-center my-5">
                    Rp. <?= number_format($totalPengeluaranTahunan, 0, ",", ".") ?>
                </h4>
            </div>
            
            <div class="col border rounded-4 shadow me-3">
                <div class="m-3 pb-2 border-bottom">
                    <h3 class="text-center">
                        Total Saldo
                    </h3>
                </div>
                <h4 class="text-center my-5">
                    Rp. <?= number_format($totalPemasukanTahunan - $totalPengeluaranTahunan, 0, ",", ".") ?>
                </h4>
            </div>
        </div>


        <!-- berdasarkan user mulai dari sini -->
        <div class="row my-5">
            <div class="col border rounded-4 shadow me-3">
                <div class="m-3 pb-2 border-bottom">
                    <h3 class="text-center">
                        Total Pemasukan User
                    </h3>
                    <div>
                        <form action="dashboardAdmin" method="GET">
                            <div class="d-flex flex-column mb-3">
                                <label for="kategori" class="form-label">Filter kategori</label>
                                <?php if(isset($_GET["id_user_pemasukan"])) : ?>
                                    <input type="hidden" name="id_user_pemasukan" value=<?= $_GET["id_user_pemasukan"] ?>>
                                <?php endif; ?>
                                <select class="form-select" style="width: 150px;" id="kategori_pemasukan" name="id_user_pemasukan" onchange="submit()">

                                    <option
                                        <?php if(!isset($_GET["id_user_pemasukan"])) : ?>
                                        selected
                                        <?php endif; ?>
                                    >
                                        All
                                    </option>
                                    <?php foreach($kategoriUser as $kategori) : ?>
                                    <option value="<?= $kategori['id_user'] ?>"
                                        <?php if(isset($_GET["id_user_pemasukan"])) : ?>
                                            <?php if($_GET["id_user_pemasukan"] == $kategori['id_user']) : ?>
                                            selected
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    > 
                                        <?= $kategori['username'] ?> 
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <h4 class="text-center my-5">
                    Rp. <?= number_format($totalUserPemasukan, 0, ",", ".") ?>
                </h4>
            </div>

            <div class="col border rounded-4 shadow me-3">
                <div class="m-3 pb-2 border-bottom">
                    <h3 class="text-center">
                        Total Pengeluaran User
                    </h3>
                    <div>
                        <form action="dashboardAdmin" method="GET">
                            <div class="d-flex flex-column mb-3">
                                <label for="kategori" class="form-label">Filter kategori</label>
                                <?php if(isset($_GET["id_user_pengeluaran"])) : ?>
                                    <input type="hidden" name="id_user_pengeluaran" value=<?= $_GET["id_user_pengeluaran"] ?>>
                                <?php endif; ?>
                                <select class="form-select" style="width: 150px;" id="kategori_pengeluaran" name="id_user_pengeluaran" onchange="submit()">

                                    <option
                                        <?php if(!isset($_GET["id_user_pengeluaran"])) : ?>
                                        selected
                                        <?php endif; ?>
                                    >
                                        All
                                    </option>
                                    <?php foreach($kategoriUser as $kategori) : ?>
                                    <option value="<?= $kategori['id_user'] ?>"
                                        <?php if(isset($_GET["id_user_pengeluaran"])) : ?>
                                            <?php if($_GET["id_user_pengeluaran"] == $kategori['id_user']) : ?>
                                            selected
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    > 
                                        <?= $kategori['username'] ?> 
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <h4 class="text-center my-5">
                    Rp. <?= number_format($totalUserPengeluaran, 0, ",", ".") ?>
                </h4>
            </div>
            
            <div class="col border rounded-4 shadow me-3">
                <div class="m-3 pb-2 border-bottom">
                    <h3 class="text-center">
                        Total Saldo
                    </h3>
                </div>
                <h4 class="text-center my-5">
                    Rp. <?= number_format($totalUserPemasukan - $totalUserPengeluaran, 0, ",", ".") ?>
                </h4>
            </div>
        </div>
        <table id="myTable1" hidden data-excel-name="Tabel Pemasukan">
    <thead>
        <tr>
            <th>id_user</th>
            <th>Username</th>
            <th>Tanggal Pemasukan</th>
            <th>Deskripsi Pemasukan</th>
            <th>Jumlah Pemasukan</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Loop through the retrieved data and generate table rows
        while($row = mysqli_fetch_array($result)){
            echo "<tr>";
            echo "<td>{$row['id_user']}</td>";
            echo "<td>{$row['username']}</td>";
            echo "<td>{$row['tanggal']}</td>";
            echo "<td>{$row['deskripsi']}</td>";
            echo "<td>{$row['jumlah']}</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
        </table>
        <table id="myTable2" hidden data-excel-name="Tabel Pengeluaran">
            <thead>
                <tr>
                    <th>id_user</th>
                    <th>Username</th>
                    <th>Tanggal Pengeluaran</th>
                    <th>Deskripsi Pengeluaran</th>
                    <th>Jumlah Pengeluaran</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop through the retrieved data and generate table rows
                while($row1 = mysqli_fetch_array($result2)){
                    echo "<tr>";
                    echo "<td>{$row1['id_user']}</td>";
                    echo "<td>{$row1['username']}</td>";
                    echo "<td>{$row1['tanggal']}</td>";
                    echo "<td>{$row1['deskripsi']}</td>";
                    echo "<td>{$row1['jumlah']}</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
       
        <div class="row my-5">
            <div class="col border rounded-4 shadow me-3">
                <div class="m-3 pb-2 border-bottom">
                    <h3 class="text-center">
                        Ringkasan Pengeluaran Tahunan
                    </h3>
                    <div>
                        <form action="dashboardAdmin" method="GET">
                            <div class="d-flex flex-column mb-3">
                                <label for="kategori" class="form-label">Filter kategori</label>
                                <?php if(isset($_GET["id_kategori_pemasukan"])) : ?>
                                    <input type="hidden" name="id_kategori_pemasukan" value=<?= $_GET["id_kategori_pemasukan"] ?>>
                                <?php endif; ?>
                                <select class="form-select" style="width: 150px;" id="kategori_pengeluaran" name="id_kategori_pengeluaran" onchange="submit()">
                                    <option
                                        <?php if(!isset($_GET["id_kategori_pengeluaran"])) : ?>
                                        selected
                                        <?php endif; ?>
                                    >
                                        All
                                    </option>
                                    <?php foreach($kategoriPengeluaran as $kategori) : ?>
                                    <option value="<?= $kategori['id_kategori'] ?>"
                                        <?php if(isset($_GET["id_kategori_pengeluaran"])) : ?>
                                            <?php if($_GET["id_kategori_pengeluaran"] == $kategori['id_kategori']) : ?>
                                            selected
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    > 
                                        <?= $kategori['nama_kategori'] ?> 
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <?php if (count($ringkasanPengeluaranPie) > 0) : ?>
                <canvas id="pie-chart-pengeluaran" class="mb-3"></canvas>
                <?php else : ?>
                <div class="text-center mb-3">Belum ada pengeluaran di tahun ini.</div>
                <?php endif; ?>
            </div>
            <div class="col border rounded-4 shadow me-3">
                <div class="m-3 pb-2 border-bottom">
                    <h3 class="text-center">
                        Ringkasan Pemasukan Tahunan
                    </h3>
                    <div>
                        <form action="dashboardAdmin" method="GET">
                            <div class="d-flex flex-column mb-3">
                                <label for="kategori" class="form-label">Filter kategori</label>
                                <?php if(isset($_GET["id_kategori_pengeluaran"])) : ?>
                                    <input type="hidden" name="id_kategori_pengeluaran" value=<?= $_GET["id_kategori_pengeluaran"] ?>>
                                <?php endif; ?>
                                <select class="form-select" style="width: 150px;" id="kategori_pemasukan" name="id_kategori_pemasukan" onchange="submit()">
                                    <option
                                        <?php if(!isset($_GET["id_kategori_pemasukan"])) : ?>
                                        selected
                                        <?php endif; ?>
                                    >
                                        All
                                    </option>
                                    <?php foreach($kategoriPemasukan as $kategori) : ?>
                                    <option value="<?= $kategori['id_kategori'] ?>"
                                        <?php if(isset($_GET["id_kategori_pemasukan"])) : ?>
                                            <?php if($_GET["id_kategori_pemasukan"] == $kategori['id_kategori']) : ?>
                                            selected
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    > 
                                        <?= $kategori['nama_kategori'] ?> 
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <?php if (count($ringkasanPemasukanPie) > 0) : ?>
                <canvas id="pie-chart-pemasukan" class="mb-3"></canvas>
                <?php else : ?>
                <div class="text-center mb-3">Belum ada pemasukan di tahun ini.</div>
                <?php endif; ?>
            </div>
        </div>
        <div class="row my-5 btn-succes">
        <button id="export">Export to excel</button>


        </div>
    </div>


    
    
    <script type="text/javascript">
        function generateCodeName(name){
            var consonants = name.replace(/[aeiou]/gi, '').toUpperCase();
            // console.log(consonants.length);

            var codeName = "";
            if(consonants.length >= 3){
                codeName += consonants[0] + consonants[1] + consonants[consonants.length - 1];
            }
            else{
                codeName = name.slice(0, 3).toUpperCase();
            }
            
            return codeName;
        }
    </script>
    <script type="text/javascript">
        var namaBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "July", "Agustus", "September", "Oktober", "November", "Desember"];
        var usernames = [];

        <?php foreach($users as $user) : ?>
            usernames[<?= $user['id_user'] ?>] = "<?= $user['username'] ?>"
        <?php endforeach; ?>
        
        //chart pengeluaran
        var data = [
            <?php foreach($ringkasanPengeluaranTahunan as $data) : ?>
                { id_user: <?= $data['id_user'] ?>, bulan: "<?= $data['bulan'] ?>", totalPengeluaran: <?= $data['total_pengeluaran'] ?> },
            <?php endforeach; ?>
        ];
        var grouped_data = Object.groupBy(data, ({id_user}) => id_user);
        var data_for_chart = [];
        var usernames_for_chart = [];
        
        for (var [id, user] of Object.entries(grouped_data)) {
            user = Object.groupBy(user, ({bulan}) => bulan);
            usernames_for_chart.push(usernames[id]);
            var temp_data = [];
            
            for (let i = 1; i <= 12; i++) {
                (user[i]) ? temp_data.push(user[i][0].totalPengeluaran) : temp_data.push(0);
            }

            data_for_chart.push(temp_data);
        };

        barChartPengeluaran = new Chart(
            document.getElementById('bar-chart-pengeluaran'),
            {
                type: 'bar',
                data: {
                    labels: namaBulan,
                    datasets: data_for_chart.map((row, idx) => ({
                        label: generateCodeName(usernames_for_chart[idx]),
                        data: row
                    }))                 
                }
            }
        );

        // buat pemasukan
        data = [
            <?php foreach($ringkasanPemasukanTahunan as $data) : ?>
                { id_user: <?= $data['id_user'] ?>, bulan: "<?= $data['bulan'] ?>", totalPemasukan: <?= $data['total_pemasukan'] ?> },
            <?php endforeach; ?>
        ];

        grouped_data = Object.groupBy(data, ({id_user}) => id_user);
        data_for_chart = [];
        usernames_for_chart = [];

        for (var [id, user] of Object.entries(grouped_data)) {
            user = Object.groupBy(user, ({bulan}) => bulan);
            usernames_for_chart.push(usernames[id]);
            var temp_data = [];

            for (let i = 1; i <= 12; i++) {
                (user[i]) ? temp_data.push(user[i][0].totalPemasukan) : temp_data.push(0);
            }

            data_for_chart.push(temp_data);
        };


        barChartPemasukan = new Chart(
            document.getElementById('bar-chart-pemasukan'),
            {
                type: 'bar',
                data: {
                    labels: namaBulan,
                    datasets: data_for_chart.map((row, idx) => ({
                        label: generateCodeName(usernames_for_chart[idx]),
                        data: row
                    }))                 
                }
            }
        );

//chart ringkasan

var dataPengeluaran = [
    <?php foreach($ringkasanPengeluaranTahunan as $data) : ?>
        { id_user: <?= $data['id_user'] ?>, bulan: "<?= $data['bulan'] ?>", totalPengeluaran: <?= $data['total_pengeluaran'] ?> },
    <?php endforeach; ?>
];

var dataPemasukan = [
    <?php foreach($ringkasanPemasukanTahunan as $data) : ?>
        { id_user: <?= $data['id_user'] ?>, bulan: "<?= $data['bulan'] ?>", totalPemasukan: <?= $data['total_pemasukan'] ?> },
    <?php endforeach; ?>
];

var grouped_dataPemasukan = Object.groupBy(dataPemasukan, ({id_user}) => id_user);
var grouped_dataPengeluaran = Object.groupBy(dataPengeluaran, ({id_user}) => id_user);
var data_for_chart = [];
var usernames_for_chart = [];


for (var [id, user] of Object.entries(grouped_dataPemasukan)) {
    userPemasukan = Object.groupBy(user, ({bulan}) => bulan);
    userPengeluaran = Object.groupBy(grouped_dataPengeluaran[id], ({bulan}) => bulan);
    usernames_for_chart.push(usernames[id]);

    var temp_data = [];

    for (let i = 1; i <= 12; i++) {
        var totalPemasukan = (userPemasukan[i]) ? userPemasukan[i][0].totalPemasukan : 0;
        var totalPengeluaran = (userPengeluaran[i]) ? userPengeluaran[i][0].totalPengeluaran : 0;

        temp_data.push({
            totalPemasukan: totalPemasukan,
            totalPengeluaran: totalPengeluaran
        });
    }

    data_for_chart.push(temp_data);
};

barChartRingkasan = new Chart(
    document.getElementById('bar-chart-ringkasan'),
    {
        type: 'bar',
        data: {
            labels: namaBulan,
            datasets: data_for_chart.map((user, idx) => ({
                label: generateCodeName(usernames_for_chart[idx]),
                data: user.map(row => row.totalPemasukan - row.totalPengeluaran),
                stack: 'Stack ' + idx,
            }))
        }
    }
);










        
//     barChartPemasukan = new Chart(
//     document.getElementById('bar-chart-pemasukan'),
//     {
//         type: 'bar',
//         data: {
//             labels: namaBulan,
//             datasets: data_for_chart.map((row, idx) => ({
//                 label: generateCodeName(usernames_for_chart[idx]),
//                 data: row
//             }))
//         },
//         options: {
//             plugins: {
//                 legend: {
//                     display: true,
//                     position: 'top', // Atur posisi legenda (top, bottom, left, right)
//                 }
//             },
//             scales: {
//                 x: {
//                     grid: {
//                         display: false
//                     }
//                 },
//                 y: {
//                     beginAtZero: true
//                 }
//             },
//             annotation: {
//                 annotations: data_for_chart.map((row, idx) => ({
//                     type: 'line',
//                     mode: 'vertical',
//                     scaleID: 'x',
//                     value: idx, // Atur indeks sesuai dengan posisi bar
//                     borderColor: 'red', // Warna garis
//                     borderWidth: 2, // Ketebalan garis
//                     label: {
//                         content: generateCodeName(usernames_for_chart[idx]), // Nama yang akan ditampilkan di atas bar
//                         enabled: true,
//                         position: 'top'
//                     }
//                 }))
//             }
//         }
//     }
// );
    </script>
    <script type="text/javascript">
        var data = [
            <?php foreach($ringkasanPengeluaranPie as $data) : ?>
                { username: "<?= $data['username'] ?>", totalPengeluaran: <?= $data['total_pengeluaran'] ?> },
            <?php endforeach; ?>
        ];

        var totalSeluruhPengeluaran = 0;
        data.forEach((row) => {
            totalSeluruhPengeluaran += row.totalPengeluaran;
        });

        pieChartPengeluaran = new Chart(
            document.getElementById('pie-chart-pengeluaran'),
            {
                type: 'pie',
                data: {
                    labels: data.map(row => generateCodeName(row.username)),
                    datasets: [
                        {
                            data: data.map(row => row.totalPengeluaran),
                            backgroundColor: function(context){
                                var colors = [
                                    'rgb(255, 99, 132)',
                                    'rgb(255, 159, 64)',
                                    'rgb(255, 205, 86)',
                                    'rgb(75, 192, 192)',
                                    'rgb(54, 162, 235)',
                                    'rgb(153, 102, 255)',
                                    'rgb(201, 203, 207)'
                                ];

                                return colors[context.dataIndex % 7];
                            }
                        }
                    ],
                },
                plugins: [ChartDataLabels],
                options: {
                     plugins: {
                        tooltip: {
                            enabled: false
                        },
                        datalabels: {
                            color: 'white',
                            font: {
                                size: 14
                            },
                            textShadowColor: 'black',
                            textShadowBlur: 10,
                            formatter: (value, context) => {
                                const percent = (value / totalSeluruhPengeluaran * 100).toFixed(2);
                                return `${percent}%`;
                            }
                        }
                     }
                }
            }
        );

        data = [
            <?php foreach($ringkasanPemasukanPie as $data) : ?>
                { username: "<?= $data['username'] ?>", totalPemasukan: <?= $data['total_pemasukan'] ?> },
            <?php endforeach; ?>
        ];

        var totalSeluruhPemasukan = 0;
        data.forEach((row) => {
            totalSeluruhPemasukan += row.totalPemasukan;
        });

        pieChartPemasukan = new Chart(
            document.getElementById('pie-chart-pemasukan'),
            {
                type: 'pie',
                data: {
                    labels: data.map(row => generateCodeName(row.username)),
                    datasets: [
                        {
                            data: data.map(row => row.totalPemasukan),
                            backgroundColor: function(context){
                                var colors = [
                                    'rgb(153, 102, 255)',
                                    'rgb(54, 162, 235)',
                                    'rgb(75, 192, 192)',
                                    'rgb(255, 205, 86)',
                                    'rgb(255, 159, 64)',
                                    'rgb(255, 99, 132)'
                                ];

                                return colors[context.dataIndex % 7];
                            }
                        }
                    ]
                },
                plugins: [ChartDataLabels],
                options: {
                     plugins: {
                        tooltip: {
                            enabled: false
                        },
                        datalabels: {
                            color: 'white',
                            font: {
                                size: 14
                            },
                            textShadowColor: 'black',
                            textShadowBlur: 10,
                            formatter: (value, context) => {
                                const percent = (value / totalSeluruhPemasukan * 100).toFixed(2);
                                return `${percent}%`;
                            }
                        }
                     }
                }
            }
        );
    //     function exportToExcel() {
    //     // Mengambil data dari kedua tabel
    //     const table1 = document.getElementById('table1');
    //     const table2 = document.getElementById('table2');

    //     // Mengubah format data agar sesuai dengan struktur Excel
    //     var table2excel = new Table2Excel();
    //     var tables = [table1,table2];
    //     table2excel.export(tables,'multiple_table');
    // }
    

    
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
    document.getElementById('export').addEventListener('click', function(){
        const table1 = document.getElementById('myTable1');
        const table2 = document.getElementById('myTable2');
        
        const excelName1 = table1.getAttribute('data-excel-name');
        const excelName2 = table2.getAttribute('data-excel-name');
        
        const table2excel = new Table2Excel();
        
        const wb = XLSX.utils.book_new(); // Membuat workbook baru
        
        // Mengonversi data tabel menjadi array of arrays (AOA)
        const data1 = tableToArray(table1);
        const data2 = tableToArray(table2);
        
        // Menambahkan data AOA ke dalam sheet baru
        const ws1 = XLSX.utils.aoa_to_sheet(data1);
        const ws2 = XLSX.utils.aoa_to_sheet(data2);
        
        // Menambahkan sheet ke dalam workbook
        XLSX.utils.book_append_sheet(wb, ws1, excelName1);
        XLSX.utils.book_append_sheet(wb, ws2, excelName2);
        
        // Menyimpan workbook ke dalam file Excel
        XLSX.writeFile(wb, 'combined_tables.xlsx');
    });

    // Fungsi untuk mengonversi data tabel menjadi array of arrays (AOA)
    function tableToArray(table) {
        const rows = Array.from(table.querySelectorAll('tr'));
        return rows.map(row => Array.from(row.querySelectorAll('td, th')).map(cell => cell.textContent));
    }
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