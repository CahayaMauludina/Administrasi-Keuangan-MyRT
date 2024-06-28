<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/logo.jpg">
    <title>Dashboard | MyRT Cash Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container my-4">
        

        <div class="row mt-5">
            <div class="col border rounded-4 shadow me-3">
                <div class="my-5 mx-3 pb-5 border-bottom position-relative">
                    <h3 class="text-center">
                        Ringkasan Keseluruhan
                    </h3>
                </div>
								<div id="chartReport">
									<canvas id="bar-chart-keseluruhan" class="mb-3"></canvas>
								</div>
            </div>
        </div>
    </div>
    
    <script>
			document.addEventListener("DOMContentLoaded", async function(){

				var namaBulan = [ 
					{ id: 1, bulan: "Januari"},
					{ id: 2, bulan: "Februari"},
					{ id: 3, bulan: "Maret"},
					{ id: 4, bulan: "April"},
					{ id: 5, bulan: "Mei"},
					{ id: 6, bulan: "Juni"},
					{ id: 7, bulan: "Juli"},
					{ id: 8, bulan: "Agustus"},
					{ id: 9, bulan: "September"},
					{ id: 10, bulan: "Oktober"},
					{ id: 11, bulan: "November"},
					{ id: 12, bulan: "Desember"},
				];

				let dataPemasukan, dataPengeluaran
				await getDataPemasukan();
				await getDataPengeluaran();

				async function getDataPemasukan() {
					const id = localStorage.getItem('id');
					const result = await fetch('http://localhost:3000/pemasukan/' + id);
					const response = await result.json();
					dataPemasukan = renderDataPemasukan(response.data); // Tampilkan data;
					
					return response.data;
				}

				async function getDataPengeluaran() {
					const id = localStorage.getItem('id');
					const result = await fetch('http://localhost:3000/pengeluaran/' + id);
					const response = await result.json();
					dataPengeluaran = renderDataPengeluaran(response.data); // Tampilkan data;
					
					return response.data;
				}

				function renderDataPengeluaran(data) {
					let result = [] 
					const groupBy = data.reduce((groups, item) => {
						const date = new Date(item.tanggal)
						const year = date.getFullYear();
						const month = (date.getMonth() + 1).toString().padStart(2, "0");
						const day = date.getDate().toString().padStart(2, "0");

						if (!groups[month]) {
							groups[month] = { jumlah: 0 };
							result.push(groups[month]);
						}
						groups[month].jumlah += item.jumlah;
						return groups;
					}, {});

					const dataRow = []
					for (const [key, value] of Object.entries(groupBy)) {
						dataRow.push({ ...value, month: namaBulan[key - 1] });
					}
					

					return dataRow;
				}

				function renderDataPemasukan(data) {
					let result = [] 
					const groupBy = data.reduce((groups, item) => {
						const date = new Date(item.tanggal)
						const year = date.getFullYear();
						const month = (date.getMonth() + 1).toString().padStart(2, "0");
						const day = date.getDate().toString().padStart(2, "0");

						if (!groups[month]) {
							groups[month] = { jumlah: 0 };
							result.push(groups[month]);
						}
						groups[month].jumlah += item.jumlah;
						return groups;
					}, {});

					const dataRow = []
					for (const [key, value] of Object.entries(groupBy)) {
						dataRow.push({ ...value, month: namaBulan[key - 1] });
					}

					return dataRow;
				}
        
				if(dataPemasukan.length > 0 || dataPengeluaran.length > 0) {
					const datasetPemasukan = namaBulan.map(row => {
						let jumlah = 0
						const pemasukan = dataPemasukan.find(item => item.month.id == row.id)
						return pemasukan ? pemasukan.jumlah : 0
					});

					const datasetPengeluaran = namaBulan.map(row => {
						let jumlah = 0
						const pengeluaran = dataPengeluaran.find(item => item.month.id == row.id)
						return pengeluaran ? pengeluaran.jumlah : 0
					});

					new Chart(
						document.getElementById('bar-chart-keseluruhan'),
						{
								type: 'bar',
								data: {
										labels: namaBulan.map(row => row.bulan),
										datasets: [
												{
														label: 'Pemasukan',
														data: datasetPemasukan,
														backgroundColor: 'rgba(75, 192, 192, 0.2)',
														borderColor: 'rgb(75, 192, 192)',
														borderWidth: 1,
														stack: 'Stack 0',
												},
												{
														label: 'Pengeluaran',
														data: datasetPengeluaran,
														backgroundColor: 'rgba(255, 99, 132, 0.2)',
														borderColor: 'rgb(255, 99, 132)',
														borderWidth: 1,
														stack: 'Stack 1',
												},
										]
								},
								options : {
										plugins : {
												title : {
														display : true,
												},
										},
										responsive: true,
										interaction: {
												intersect: false,
										},
										scales : {
												x: {
														stacked: true,
												},
												y: {
														stacked : true
												}
										}
								}
						}
					);
				} else {
					document.querySelector('#chartReport').innerHTML = '<div class="text-center mb-3">Belum ada pemasukan dan pengeluaran.</div>';
				}
        

			})
        
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
		<script src="/js/app.js"></script>
</body>
</html>