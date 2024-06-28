<?php
    session_start();

    if (($_SERVER['REQUEST_METHOD'] == 'GET') || ($_SESSION['role'] == 'user')) {
        header('Location: ../restricted');
        exit;
    }
    
    $namaKategori = htmlspecialchars($_POST["nama_kategori"]);
    $table = htmlspecialchars($_POST["nama_table"]);

    // $db = mysqli_connect('localhost', 'root', '', 'myrt');

    $db = mysqli_connect('localhost', 'u749483805_cash', 'Ramadani1234?', 'u749483805_cash');
    
    if ($table == "pemasukan") {
        $query1 = "INSERT INTO kategori_pengeluaran VALUES ('', '$namaKategori')";
        $query2 = "INSERT INTO kategori_pemasukan VALUES ('', '$namaKategori')";
    } elseif ($table == "pemasukan") {
        
    }
    
    if (mysqli_query($db, $query2)) {
        $_SESSION["success_msg"] = "Kategori baru berhasil ditambahkan!";
    } else {
        $_SESSION["error_msg"] = "Kategori baru tidak berhasil ditambahkan!";
    }

    if (mysqli_query($db, $query1)) {
        $_SESSION["success_msg"] = "Kategori baru berhasil ditambahkan!";
    } else {
        $_SESSION["error_msg"] = "Kategori baru tidak berhasil ditambahkan!";
    }

    if ($table == "pengeluaran") {
        header("Location: ../kelolaKategoriPengeluaran");
    } elseif ($table == "pemasukan") {
        header("Location: ../kelolaKategoriPemasukan");
    }
?>