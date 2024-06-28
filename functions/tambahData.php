<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    header('Location: ../kelolaPengeluaran');
    exit;
}

$tanggal = htmlspecialchars($_POST["tanggal"]);
$deskripsi = htmlspecialchars($_POST["deskripsi"]);
$idKategori = htmlspecialchars($_POST["id_kategori"]);
$jumlah = htmlspecialchars($_POST["jumlah"]);
$idUser = $_SESSION['id'];
$table = htmlspecialchars($_POST["nama_table"]);

$db = mysqli_connect('localhost', 'root', 'root', 'myrt');

// Handle file upload
$uploadDir = '../uploads/'; // Lokasi untuk menyimpan file yang diunggah
$uploadedFile = $_FILES['gambar']['tmp_name']; // Path file yang diunggah pada server sementara
$fileName = $_FILES['gambar']['name']; // Nama file yang diunggah
$targetFile = $uploadDir . basename($fileName); // Path lengkap untuk menyimpan file di server

// Pindahkan file yang diunggah ke lokasi yang ditentukan
if (move_uploaded_file($uploadedFile, $targetFile)) {
    // File berhasil diunggah, tambahkan informasi ke database
    if ($table == "pengeluaran") {
        $query = "INSERT INTO pengeluaran VALUES ('', '$tanggal', '$deskripsi', '$idKategori', '$jumlah', '$idUser', '$fileName')";
    } elseif ($table == "pemasukan") {
        $query = "INSERT INTO pemasukan VALUES ('', '$tanggal', '$deskripsi', '$idKategori', '$jumlah', '$idUser', '$fileName')";
    }

    if (mysqli_query($db, $query)) {
        $_SESSION["success_msg"] = "Data berhasil ditambahkan!";
    } else {
        $_SESSION["error_msg"] = "Data tidak berhasil ditambahkan!";
    }

    if ($table == "pengeluaran") {
        header("Location: ../kelolaPengeluaran");
    } elseif ($table == "pemasukan") {
        header("Location: ../kelolaPemasukan");
    }
} else {
    // Jika file gagal diunggah, kembalikan ke halaman sebelumnya dengan pesan kesalahan
    $_SESSION["error_msg"] = "Gagal mengunggah file!";
    header("Location: {$_SERVER['HTTP_REFERER']}");
}
?>
