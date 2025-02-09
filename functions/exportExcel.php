<?php 
    if (isset($_POST['exportExcel'])) {
        $output = '';

        $idUser = $_POST['id_user'];
        $idKategori = $_POST['id_kategori'];
        $tglAwal = $_POST['tgl_awal'];
        $tglAkhir = $_POST['tgl_akhir'];

        $db = mysqli_connect('localhost', 'root', 'root', 'myrt');

        if ($_POST['tipe_laporan'] == "Pengeluaran" && $_POST['id_kategori'] == "all"){
            $query = "
                    SELECT * FROM pengeluaran
                    JOIN kategori_pengeluaran ON pengeluaran.id_kategori = kategori_pengeluaran.id_kategori
                    WHERE tanggal >= '$tglAwal' AND tanggal <= '$tglAkhir'
                    AND id_user = '$idUser'
                ";
        } else if($_POST['tipe_laporan'] == "Pengeluaran"){
            $query = "
                    SELECT * FROM pengeluaran
                    JOIN kategori_pengeluaran ON pengeluaran.id_kategori = kategori_pengeluaran.id_kategori
                    WHERE tanggal >= '$tglAwal' AND tanggal <= '$tglAkhir'
                    AND pengeluaran.id_kategori = '$idKategori' AND id_user = '$idUser'
                ";

        }else if ($_POST['tipe_laporan'] == "Pemasukan" && $_POST['id_kategori'] == "all") {
            $query = "
                    SELECT * FROM pemasukan
                    JOIN kategori_pemasukan ON pemasukan.id_kategori = kategori_pemasukan.id_kategori
                    WHERE tanggal >= '$tglAwal' AND tanggal <= '$tglAkhir'
                    AND id_user = '$idUser'
                ";
        } else {
            $query = " 
                    SELECT * FROM pemasukan
                    JOIN kategori_pemasukan ON pemasukan.id_kategori = kategori_pemasukan.id_kategori
                    WHERE tanggal >= '$tglAwal' AND tanggal <= '$tglAkhir'
                    AND pemasukan.id_kategori = '$idKategori' AND id_user = '$idUser'
                ";
        }
        
        $data = mysqli_query($db, $query);
        $index = 1;

        if (mysqli_num_rows($data) > 0) {
            $output .= '
                <table class="table" style="text-align: center;" border="1" cellspacing="0" cellpadding="3">
                    <tr>
                        <th>No.</th>   
                        <th>Tanggal</th>
                        <th>Deskripsi ' . $_POST['tipe_laporan'] . '</th>
                        <th>Kategori ' . $_POST['tipe_laporan'] . '</th>
                        <th>Jumlah ' . $_POST['tipe_laporan'] . '</th>
                    </tr>
            ';
            $totalJumlahData = 0;
            while ($row = mysqli_fetch_assoc($data)) {

                 // Mengambil data gambar dari database
                $gambarData = $row['nama_file_gambar']; // Misalnya, ambil nama file gambar dari database

                // Baca file gambar dan encode menjadi base64
                $gambarPath = "../uploads/" . $gambarData; // Sesuaikan dengan path yang benar
                $gambarEncoded = base64_encode(file_get_contents($gambarPath)); // Encode gambar menjadi base64

                $output .= '
                <tr>
                    <td>' . $index . '</td>
                    <td>' . $row["tanggal"] . '</td>
                    <td>' . $row["deskripsi"] . '</td>
                    <td>' . $row["nama_kategori"] . '</td>
                    <td>' . str_replace(',', '.', number_format($row['jumlah'])) . '</td>
                </tr>
                ';
                $totalJumlahData += $row['jumlah'];
                $index++;
            }

            $output .= '
                <tr>
                    <td colspan="4">Total Pengeluaran</td>
                    <td>' . str_replace(',', '.', number_format($totalJumlahData)) . '</td>
                </tr>
            ';
            $output .= '</table>';

            $fileName = "laporan_" . strtolower($_POST['tipe_laporan']) . "_" . $tglAwal . "_" . $tglAkhir . ".xls"; 

            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=\"$fileName\"");

            echo $output;
        }
    }

?>