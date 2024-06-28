
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="imglogo.jpg">
    <title>Dashboard | MyRT Keuangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="./table2excel.js"></script>
    <script src= "https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"> </script> 
</head>
<body>
<?php include 'navbar.php'; ?>

    <div class="container mt-4 border rounded-4 shadow">
        <div class="row">
            <div class="col">
								<div class="m-3">
									<button type="button" id="tampilkanLaporan" class="btn btn-primary">
											Tampilkan Laporan
									</button>
								</div>
                <div class="m-3">
                    <table class="table table-striped text-center" id="table" data-excel-name="Tabel Pengeluaran" border="1">
                        <thead>
                            <tr class="table-primary">
                            <th scope="col">No.</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Deskripsi Pengeluaran</th>
                            <th scope="col">Kategori Pengeluaran</th>
                            <th scope="col">Jumlah Pengeluaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
                <div class="m-3">
                	<!-- <button onclick="exportToExcel()" class="btn btn-success">Export to Excel</button>       -->
                    
                </div>
            </div>
        </div>
    </div>

    <div class="container my-4 border rounded-4 shadow">
        <div class="row m-3 pb-3 border-bottom">
            <div class="col">
                <h3 class="text-center">
                    Ringkasan Laporan
                </h3>
            </div>
        </div>
        <div class="row m-3">
            <div class="col">
						<div id="chartReport">
                <canvas id="bar-chart"></canvas>
						</div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
			document.addEventListener("DOMContentLoaded", async function(){

				document.querySelector('#tampilkanLaporan').addEventListener('click', async () => {
					const data = await getDataPengeluaran();

					let result = [] 
					const groupBy = data.reduce((groups, item) => {
						const date = new Date(item.tanggal).toLocaleDateString('id-ID');
						if (!groups[date]) {
							groups[date] = { tanggal: date, jumlah: 0, date: new Date(item.tanggal) };
							result.push(groups[date]);
						}
						groups[date].jumlah += item.jumlah;
						return groups;
					}, {});

					renderChart(groupBy);
				});

				function renderChart(data) {

					document.querySelector("#chartReport").innerHTML = '<canvas id="bar-chart"></canvas>';

					const dataRow = []
					for (const [key, value] of Object.entries(data)) {
						dataRow.push(value);
					}

					dataRow.sort((a, b) => a.date - b.date);

					new Chart(
						document.getElementById('bar-chart'),
						{
							type: 'bar',
							data: {
								labels: dataRow.map(row => row.tanggal),
								datasets: [
									{
										label: 'Total Pengeluaran',
										data: dataRow.map(row => row.jumlah),
										backgroundColor: 'rgba(255, 99, 132, 0.2)',
										borderColor: 'rgb(255, 99, 132)',
										borderWidth: 1
									}
								]
							}
						}
					);
				}

				async function getDataPengeluaran() {
					const id = localStorage.getItem('id');
					const result = await fetch('http://localhost:3000/pengeluaran/' + id);
					const response = await result.json();
					renderDataPengeluaran(response.data); // Tampilkan data;
					return response.data;
				}

				function renderDataPengeluaran(data) {
					 const table = document.querySelector('#table tbody');
					 table.innerHTML = '';
					 let number = 0
					 data.forEach(item => {
						 const tr = document.createElement('tr');
						 tr.innerHTML = `
							 <td>${++number}</td>
							 <td>${new Date(item.tanggal).toLocaleDateString('id-ID')}</td>
							 <td>${item.deskripsi}</td>
							 <td>${item.nama_kategori}</td>
							 <td>${item.jumlah}</td>`
						 table.appendChild(tr);
					});
				}


			})

    </script>
     <script>
        function exportToExcel() {
            let table = document.getElementById("myTable1");
            let html = table.outerHTML;
            let blob = new Blob([html], { type: "application/vnd.ms-excel" });
            let url = URL.createObjectURL(blob);
            let a = document.createElement("a");
            a.href = url;
            a.download = "Tabel_Pemasukan.xls";
            document.body.appendChild(a);
            a.click();
            setTimeout(() => {
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);  
            }, 0);
        }
    </script>
		<script src="/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>