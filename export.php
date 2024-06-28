<?phpdumprin
require 'vendor/autoload.php'; // Include PhpSpreadsheet library
include 'PHPExcel.php';
include 'PHPExcel/Writer/Excel2007.php';
$db = mysqli_connect('localhost', 'root', 'root', 'myrt');


// Execute your SQL query (replace with your actual query)
$sql = "SELECT users.id_user, users.username, pemasukan.tanggal, pemasukan.deskripsi, pemasukan.jumlah, pengeluaran.tanggal, pengeluaran.deskripsi, pengeluaran.jumlah
FROM users
JOIN pemasukan ON users.id_user = pemasukan.id_user
JOIN pengeluaran ON users.id_user = pengeluaran.id_user;
";
$result = $db->query($sql);

// Create a PHPExcel instance
$objPHPExcel = new PHPExcel();
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'id_user');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Username');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Tanggal Pemasukan');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Deskripsi Pemasukan');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Jumlah Pemasukan');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Tanggal Pengeluaran');
$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Deskripsi Pengeluaran');
$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Jumlah Pengeluaran');

// Add data from the database result
$row = 2;
while ($row_data = $result->fetch_assoc()) {
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $row_data['users.id_user']);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $row_data['users.username']);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $row_data['pemasukan.tanggal']);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $row_data['pemasukan.deskripsi']);
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $row, $row_data['pemasukan.jumlah']);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $row_data['pengeluaran.tanggal']);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $row, $row_data['pengeluaran.deskripsi']);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $row, $row_data['pengeluaran.jumlah']);
    $row++;
}

// Save Excel file
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$filename = 'exported_data.xlsx';
$objWriter->save($filename);

// Close the database connection
$conn->close();

// Return the filename to the JavaScript for downloading
// Return the filename to the JavaScript for downloading
// header('Content-Type: application/json');
// echo json_encode(['filename' => $filename]);
// Make sure to exit to prevent further execution
?>