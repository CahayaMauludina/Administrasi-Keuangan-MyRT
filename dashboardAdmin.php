<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/rns.png">
    <title>Kelola Pengguna | MyRT Keuangan</title>
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
            <img src="img/logo.jpg" width="35px">
            <div class="ms-2 navbar-brand fw-bold">MyRT Keuangan</div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="dashboardAdmin">Dashboard</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="kelolaPengguna">Kelola Pengguna</a>
                    </li> -->
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
                    Welcome, <span id="username"></span>
                </button>
                <ul class="dropdown-menu">
								<li><a class="dropdown-item" href="#" id="logout">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        
        
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
                                <select class="form-select" style="width: 150px;" id="kategori_pemasukan">
																		<option value="0">All</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <h4 class="text-center my-5">
                    <span id="totalPemasukanKategori"></span>
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
                                <select class="form-select" style="width: 150px;" id="kategori_pengeluaran">
																<option value="0">All</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <h4 class="text-center my-5">
								<span id="totalPengeluaranKategori"></span>
                </h4>
            </div>
            
            <div class="col border rounded-4 shadow me-3">
                <div class="m-3 pb-2 border-bottom">
                    <h3 class="text-center">
                        Total Saldo
                    </h3>
                </div>
                <h4 class="text-center my-5">
										<span id="totalSaldoKategori"></span>
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
                                <label for="kategori" class="form-label">Filter Kategori</label>
                                <select class="form-select" style="width: 150px;" id="kategori_pemasukan_user">
																		<option value="0">All</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <h4 class="text-center my-5">
										<span id="totalPemasukanUser"></span>
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
                                <label for="kategori" class="form-label">Filter Kategori</label>
                                <select class="form-select" style="width: 150px;" id="kategori_pengeluaran_user" >
																<option value="0">All</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <h4 class="text-center my-5">
										<span id="totalPengeluaranUser"></span>
                </h4>
            </div>
            
            <div class="col border rounded-4 shadow me-3">
                <div class="m-3 pb-2 border-bottom">
                    <h3 class="text-center">
                        Total Saldo
                    </h3>
                </div>
                <h4 class="text-center my-5">
										<span id="totalSaldoUser"></span>
                </h4>
            </div>
        </div>
       
        <div class="row my-5">
            <div class="col border rounded-4 shadow me-3">
                <div class="m-3 pb-2 border-bottom">
                    <h3 class="text-center">
                        Ringkasan Pengeluaran Tahunan
                    </h3>
                </div>
								<div id="chart-pengeluaran-report">
									<canvas id="pie-chart-pengeluaran" class="mb-3"></canvas>
								</div>
            </div>
            <div class="col border rounded-4 shadow me-3">
                <div class="m-3 pb-2 border-bottom">
                    <h3 class="text-center">
                        Ringkasan Pemasukan Tahunan
                    </h3>
                </div>
								<div id="chart-pemasukan-report">
                <canvas id="pie-chart-pemasukan" class="mb-3"></canvas>
								</div>
            </div>
        </div>
        <div class="row my-5 btn-succes">
        <!-- <a href="http://localhost:3000/export-excel"><button id="">Export to excel</button></a> -->


        </div>
    </div>


    
    <script src="/js/app.js"></script>
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
			document.addEventListener("DOMContentLoaded", async function(){
				var namaBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "July", "Agustus", "September", "Oktober", "November", "Desember"];
        var usernames = [];

				let dataPemasukanByKategori, dataPengeluaranByKategori, dataPemasukanByUser, dataPengeluaranByUser, dataPemasukanByUsername, dataPengeluaranByUsername
				await getDataKategori();
				await getDataUser();
				await getDataPemasukan();
				await getDataPengeluaran();
				totalSaldoKategori();
				totalSaldoUser();

				var $selectKategori = $('#kategori_pemasukan,#kategori_pengeluaran').change(function() {
					$selectKategori.val(this.value);
					
					if(this.value == 0) {
						const totalPengeluaranKategori = dataPengeluaranByKategori.reduce((total, item) => total + item.jumlah, 0);
						document.getElementById("totalPengeluaranKategori").innerHTML = formatRupiah(totalPengeluaranKategori);

						const totalPemasukanKategori = dataPemasukanByKategori.reduce((total, item) => total + item.jumlah, 0);
						document.getElementById("totalPemasukanKategori").innerHTML = formatRupiah(totalPemasukanKategori);

						document.getElementById("totalSaldoKategori").innerHTML = formatRupiah(totalPemasukanKategori-totalPengeluaranKategori)
						
					} else {
						const filterPengeluaran = dataPengeluaranByKategori.find((item) => item.id == this.value);
						document.getElementById("totalPengeluaranKategori").innerHTML = formatRupiah(filterPengeluaran.jumlah);

						const filterPemasukan = dataPemasukanByKategori.find((item) => item.id == this.value);
						document.getElementById("totalPemasukanKategori").innerHTML = formatRupiah(filterPemasukan.jumlah);

						document.getElementById("totalSaldoKategori").innerHTML = formatRupiah(filterPemasukan.jumlah-filterPengeluaran.jumlah)
					}
				})

				var $selectUser = $('#kategori_pemasukan_user,#kategori_pengeluaran_user').change(function() {
					$selectUser.val(this.value);
					
					if(this.value == 0) {
						const totalPengeluaranUser = dataPengeluaranByUser.reduce((total, item) => total + item.jumlah, 0);
						document.getElementById("totalPengeluaranUser").innerHTML = formatRupiah(totalPengeluaranUser);

						const totalPemasukanUser = dataPemasukanByUser.reduce((total, item) => total + item.jumlah, 0);
						document.getElementById("totalPemasukanUser").innerHTML = formatRupiah(totalPemasukanUser);

						document.getElementById("totalSaldoUser").innerHTML = formatRupiah(totalPemasukanUser-totalPengeluaranUser)
						
					} else {
						const filterPengeluaran = dataPengeluaranByUser.find((item) => item.id == this.value);
						let pengeluaran = 0
						if(typeof filterPengeluaran != 'undefined') {
							pengeluaran = filterPengeluaran.jumlah
						} 
						document.getElementById("totalPengeluaranUser").innerHTML = formatRupiah(pengeluaran);

						const filterPemasukan = dataPemasukanByUser.find((item) => item.id == this.value);
						let pemasukan = 0
						if(typeof filterPengeluaran != 'undefined') {
							pemasukan = filterPemasukan.jumlah
						}
						document.getElementById("totalPemasukanUser").innerHTML = formatRupiah(pemasukan);

						document.getElementById("totalSaldoUser").innerHTML = formatRupiah(pemasukan-pengeluaran)
					}
				})

				function totalSaldoKategori() {
					const totalPemasukanKategori = dataPemasukanByKategori.reduce((total, item) => total + item.jumlah, 0);
					const totalPengeluaranKategori = dataPengeluaranByKategori.reduce((total, item) => total + item.jumlah, 0);

					document.getElementById("totalSaldoKategori").innerHTML = formatRupiah(totalPemasukanKategori-totalPengeluaranKategori)
				}

				function totalSaldoUser() {
					const totalPemasukanKategori = dataPemasukanByUser.reduce((total, item) => total + item.jumlah, 0);
					const totalPengeluaranKategori = dataPengeluaranByUser.reduce((total, item) => total + item.jumlah, 0);

					document.getElementById("totalSaldoUser").innerHTML = formatRupiah(totalPemasukanKategori-totalPengeluaranKategori)
				}

				async function getDataPemasukan() {
					const id = localStorage.getItem('id');
					const result = await fetch('http://localhost:3000/pemasukan/' + id);
					const response = await result.json();
					dataPemasukanByKategori = renderDataPemasukan(response.data, "id_kategori");
					dataPemasukanByUser = renderDataPemasukan(response.data, "id_user");
					dataPemasukanByUsername = renderDataPemasukan(response.data, "username");

					const totalPemasukanKategori = dataPemasukanByKategori.reduce((total, item) => total + item.jumlah, 0);
					document.getElementById("totalPemasukanKategori").innerHTML = formatRupiah(totalPemasukanKategori);

					const totalPemasukanUser = dataPemasukanByUser.reduce((total, item) => total + item.jumlah, 0);
					document.getElementById("totalPemasukanUser").innerHTML = formatRupiah(totalPemasukanUser);
				}

				function renderDataPemasukan(data, by) {
					let result = [] 
					const groupBy = data.reduce((groups, item) => {

						if (!groups[item[by]]) {
							groups[item[by]] = { jumlah: 0, id: item[by] };
							result.push(groups[item[by]]);
						}
						groups[item[by]].jumlah += item.jumlah;
						return groups;
					}, {});

					const dataRow = []
					for (const [key, value] of Object.entries(groupBy)) {
						dataRow.push({ ...value });
					}

					return dataRow;
				}

				async function getDataPengeluaran() {
					const id = localStorage.getItem('id');
					const result = await fetch('http://localhost:3000/pengeluaran/' + id);
					const response = await result.json();
					dataPengeluaranByKategori = renderDataPengeluaran(response.data, "id_kategori");
					dataPengeluaranByUser = renderDataPengeluaran(response.data, "id_user");
					dataPengeluaranByUsername = renderDataPengeluaran(response.data, "username");

					const totalPengeluaranKategori = dataPengeluaranByKategori.reduce((total, item) => total + item.jumlah, 0);
					document.getElementById("totalPengeluaranKategori").innerHTML = formatRupiah(totalPengeluaranKategori);

					const totalPengeluaranUser = dataPengeluaranByUser.reduce((total, item) => total + item.jumlah, 0);
					document.getElementById("totalPengeluaranUser").innerHTML = formatRupiah(totalPengeluaranUser);
				}

				function renderDataPengeluaran(data, by) {
					let result = [] 
					const groupBy = data.reduce((groups, item) => {

						if (!groups[item[by]]) {
							groups[item[by]] = { jumlah: 0, id: item[by] };
							result.push(groups[item[by]]);
						}
						groups[item[by]].jumlah += item.jumlah;
						return groups;
					}, {});

					const dataRow = []
					for (const [key, value] of Object.entries(groupBy)) {
						dataRow.push({ ...value });
					}

					return dataRow;
				}

				async function getDataKategori() {
					 const result = await fetch('http://localhost:3000/kategori');
					 const response = await result.json();
					 renderDataKategori(response.data); // Tampilkan data;
					 return response.data;
				}

				async function getDataUser() {
					 const result = await fetch('http://localhost:3000/users');
					 const response = await result.json();
					 renderDataUser(response.data); // Tampilkan data;
					 return response.data;
				}

				function renderDataKategori(data) {
					 const selectPemasukan = document.querySelector('#kategori_pemasukan');
					 const selectPengeluaran = document.querySelector('#kategori_pengeluaran');
					 data.forEach(item => {
						 const option = document.createElement('option');
						 option.value = item.id_kategori;
						 option.text = item.nama_kategori;
						 selectPemasukan.appendChild(option);
						 selectPengeluaran.appendChild(option.cloneNode(true));
					});
				}

				function renderDataUser(data) {
					 const selectPemasukan = document.querySelector('#kategori_pemasukan_user');
					 const selectPengeluaran = document.querySelector('#kategori_pengeluaran_user');
					 data.forEach(item => {
						 const option = document.createElement('option');
						 option.value = item.id_user;
						 option.text = item.username;
						 selectPemasukan.appendChild(option);
						 selectPengeluaran.appendChild(option.cloneNode(true));
					});
				}

				function formatRupiah(angka){
					return Intl.NumberFormat('id-ID', { 
						style: 'currency', 
						currency: 'IDR',
						minimumFractionDigits: 0 
					}).format(angka);
				}
        
        var data = dataPengeluaranByUsername;
        var totalSeluruhPengeluaran = 0;
        data.forEach((row) => {
            totalSeluruhPengeluaran += row.jumlah;
        });


				if(dataPengeluaranByUsername.length > 0) {
					new Chart(
            document.getElementById('pie-chart-pengeluaran'),
            {
                type: 'pie',
                data: {
                    labels: data.map(row => row.id),
                    datasets: [
                        {
                            data: data.map(row => row.jumlah),
                            backgroundColor: function(context){
                                var colors = [
                                    'rgb(255, 99, 132, 0.5)',
                                    'rgb(255, 159, 64, 0.5)',
                                    'rgb(255, 205, 86, 0.5)',
                                    'rgb(75, 192, 192, 0.5)',
                                    'rgb(54, 162, 235, 0.5)',
                                    'rgb(153, 102, 255, 0.5)',
                                    'rgb(201, 203, 207, 0.5)'
                                ];

                                return colors[context.dataIndex % 7];
                            },
														borderColor: function(context){
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
				} else {
					document.querySelector('#chart-pengeluaran-report').innerHTML = '<div class="text-center mb-3">Belum ada pengeluaran ditahun ini.</div>';
				}
        

        data = dataPemasukanByUsername;

        var totalSeluruhPemasukan = 0;
        data.forEach((row) => {
            totalSeluruhPemasukan += row.jumlah;
        });

				if(dataPemasukanByUsername.length > 0) {
					new Chart(
							document.getElementById('pie-chart-pemasukan'),
							{
									type: 'pie',
									data: {
											labels: data.map(row => row.id),
											datasets: [
													{
															data: data.map(row => row.jumlah),
															backgroundColor: function(context){
																	var colors = [
																		'rgb(153, 102, 255, 0.5)',
																		'rgb(54, 162, 235, 0.5)',
																		'rgb(75, 192, 192, 0.5)',
																		'rgb(255, 205, 86, 0.5)',
																		'rgb(255, 159, 64, 0.5)',
																		'rgb(255, 99, 132, 0.5)'
																	];

																	return colors[context.dataIndex % 7];
															},
															borderColor: function(context){
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
				} else {
					document.querySelector('#chart-pemasukan-report').innerHTML = '<div class="text-center mb-3">Belum ada pengeluaran ditahun ini.</div>';
				}
    //     function exportToExcel() {
    //     // Mengambil data dari kedua tabel
    //     const table1 = document.getElementById('table1');
    //     const table2 = document.getElementById('table2');

    //     // Mengubah format data agar sesuai dengan struktur Excel
    //     var table2excel = new Table2Excel();
    //     var tables = [table1,table2];
    //     table2excel.export(tables,'multiple_table');
    // }
    

		})
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