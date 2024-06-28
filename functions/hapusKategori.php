<?php
    session_start();

    if (($_SERVER['REQUEST_METHOD'] == 'GET') || ($_SESSION['role'] == 'user')) {
        header('Location: ../restricted');
        exit;
    }

    $idKategori = $_POST['id_kategori'];
    $table = htmlspecialchars($_POST["nama_table"]);

    $db = mysqli_connect('localhost', 'root', 'root', 'myrt');

    if ($table == "pemasukan") {
        $query1 = "DELETE FROM kategori_pengeluaran WHERE id_kategori = '$idKategori'";
        $query2 = "DELETE FROM kategori_pemasukan WHERE id_kategori = '$idKategori'";
    } elseif ($table == "pemasukan") {
        
    }
    
    if (mysqli_query($db, $query1)) {
        $_SESSION["success_msg"] = "Kategori berhasil dihapus!";
    } else {
        $_SESSION["error_msg"] = "Kategori tidak berhasil dihapus!";
    }

    if (mysqli_query($db, $query2)) {
        $_SESSION["success_msg"] = "Kategori berhasil dihapus!";
    } else {
        $_SESSION["error_msg"] = "Kategori tidak berhasil dihapus!";
    }

    if ($table == "pengeluaran") {
        header("Location: ../kelolaKategoriPengeluaran");
    } elseif ($table == "pemasukan") {
        header("Location: ../kelolaKategoriPemasukan");
    }
?>