<?php
    session_start();

    if (($_SERVER['REQUEST_METHOD'] == 'GET') || ($_SESSION['role'] == 'user')) {
        header('Location: ../restricted');
        exit;
    }

    $idKategori = $_POST["id_kategori"];
    $namaKategori = htmlspecialchars($_POST["nama_kategori"]);
    $table = htmlspecialchars($_POST["nama_table"]);

    $db = mysqli_connect('localhost', 'root', 'root', 'myrt');

    if ($table == "pemasukan") {
        $query1 = "
                    UPDATE kategori_pengeluaran 
                    SET nama_kategori = '$namaKategori'
                    WHERE id_kategori = '$idKategori'
                ";
        $query2 = "
                UPDATE kategori_pemasukan
                SET nama_kategori = '$namaKategori'
                WHERE id_kategori = '$idKategori'
            ";
    }
    
    if (mysqli_query($db, $query1)) {
        $_SESSION["success_msg"] = "Kategori berhasil diperbarui!";
    } else {
        $_SESSION["error_msg"] = "Kategori tidak berhasil diperbarui!";
    }
    
    if (mysqli_query($db, $query2)) {
        $_SESSION["success_msg"] = "Kategori berhasil diperbarui!";
    } else {
        $_SESSION["error_msg"] = "Kategori tidak berhasil diperbarui!";
    }

    if ($table == "pengeluaran") {
        header("Location: ../kelolaKategoriPengeluaran");
    } elseif ($table == "pemasukan") {
        header("Location: ../kelolaKategoriPemasukan");
    }
?>