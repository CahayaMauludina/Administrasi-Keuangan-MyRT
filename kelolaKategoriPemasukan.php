<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/rns.png">
    <title>Dashboard | MyRT Keuangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
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

    <div class="container mt-4 border rounded-4 shadow">
        <div class="row">
            <div class="col">
                <div class="m-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        Tambah Kategori
                    </button>
                </div>
                <div class="p-3">
                    <table class="table">
                        <thead>
                            <tr class="table-primary">
                            <th scope="col">No.</th>
                            <th scope="col">Nama Kategori</th>
                            <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk tambah data -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalTambahLabel">Tambah Data</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formTambahKategori" >
                    <!-- <input type="hidden" name="nama_table" value="pemasukan"> -->
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="namaKategori" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control filter" id="namaKategori" name="namaKategori" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" name="tambahKategori" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    

    <!-- Modal untuk edit data -->
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalEditLabel">Edit Data</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="http://localhost:3000/edit-data/:id" method="POST" id="formEditKategori">
                    <input type="hidden" id="idKategori">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="namaKategori" class="form-label">Nama Kategori</label>      
                            <input type="text" class="form-control filter" id="namaKategori" name="namaKategori" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" name="editKategori" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>      
    </div>

    <div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalDeleteLabel">Delete Data</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formDeleteKategori">
                    <div class="modal-body">
                        <div class="mb-3">
												<input type="hidden" id="idKategori">
                            <label for="namaKategori" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control filter" id="namaKategori" name="nama_kategori" disabled required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
		<script src="/js/app.js"></script>
    <script>

			document.addEventListener("DOMContentLoaded", async function() {

				const dataKategori = await getDataKategori();
				//get data kataegori
				async function getDataKategori() {
					 const result = await fetch('http://localhost:3000/kategori');
					 const response = await result.json();
					 renderDataKategori(response.data); // Tampilkan data;
					 return response.data;
				}

				function renderDataKategori(data) {
					 const tbody = document.querySelector('tbody');
					 let html = '';
					 let number = 0;
					 data.forEach(kategori => {
						html += `<tr>`
						html += `<td>${++number}</td>`
						html += `<td>${kategori.nama_kategori}</td>`
						html += `<td><div class="d-flex">
									<button type="button" class="btn btn-primary me-3" data-bs-toggle="modal" data-bs-target="#modalEdit"
													data-id-kategori="${kategori.id_kategori}" data-nama-kategori="${kategori.nama_kategori}">
											Edit
									</button>
									<button type="button" class="btn btn-danger me-3" data-bs-toggle="modal" data-bs-target="#modalDelete"
													data-id-kategori="${kategori.id_kategori}" data-nama-kategori="${kategori.nama_kategori}">
											Delete
									</button>
							</div></td>`
						html += `<td>`
					 })
					 tbody.innerHTML = html;
				}

				// Isi data modal edit
				$('#modalEdit').on('show.bs.modal', function (event) {
						var button = $(event.relatedTarget);
						var idKategori = button.data('id-kategori');
						var namaKategori = button.data('nama-kategori');
						var modal = $(this);
						// Set nilai id_kategori ke dalam input tersembunyi
						modal.find('#idKategori').val(idKategori);
						modal.find('#namaKategori').val(namaKategori);
				});

				$('#modalDelete').on('show.bs.modal', function (event) {
						var button = $(event.relatedTarget);
						var idKategori = button.data('id-kategori');
						var namaKategori = button.data('nama-kategori');
						var modal = $(this);
						modal.find('#idKategori').val(idKategori);
						modal.find('#namaKategori').val(namaKategori);
				});

				// Saat formulir modal edit disubmit
				$('#formEditKategori').submit(function (event) {
						event.preventDefault(); // Hindari pengiriman formulir secara sinkron
						// Kirim data formulir menggunakan AJAX
						const modal = $('#modalEdit');
						const id = modal.find('#idKategori').val();
						const nama = modal.find('#namaKategori').val();
						
						$.ajax({
								type: 'POST',
								url: `http://localhost:3000/edit-data/${id}`,
								headers: {
									'Content-Type': 'application/json'
								},
								data: JSON.stringify({
									namaKategori: nama
								}),
								success: function (response) {
										// Menampilkan pesan sukses menggunakan Swal
										swal({
												title: "Success",
												text: response.message,
												icon: "success",
												button: "OK",
										})
										.then(() => {
												// Tutup modal
												$('#modalEdit').modal('hide');
												// Refresh halaman
												location.reload();
										});
								},
								error: function (xhr, status, error) {
									swal({
												title: "Error",
												text: error.message,
												icon: "error",
												button: "OK",
										})
								}
						});
				});

				// Tangani pengiriman formulir tambah kategori
				$('#formTambahKategori').submit(function(event) {
					event.preventDefault(); // Hindari pengiriman formulir secara sinkron
						// Kirim data formulir menggunakan AJAX
						const modal = $('#modalTambah');
						const nama = modal.find('#namaKategori').val();

						$.ajax({
								type: 'POST',
								url: `http://localhost:3000/tambah-kategori`,
								headers: {
									'Content-Type': 'application/json'
								},
								data: JSON.stringify({
									namaKategori: nama
								}),
								success: function (response) {
										// Menampilkan pesan sukses menggunakan Swal
										swal({
												title: "Success",
												text: response.message,
												icon: "success",
												button: "OK",
										})
										.then(() => {
												// Tutup modal
												$('#modalTambah').modal('hide');
												// Refresh halaman
												location.reload();
										});
								},
								error: function (xhr, status, error) {
									swal({
												title: "Error",
												text: error.message,
												icon: "error",
												button: "OK",
										})
								}
						});
				})

				$('#formDeleteKategori').submit(function(event) {
					event.preventDefault(); // Hindari pengiriman formulir secara sinkron
					const modal = $('#modalDelete');
					const id = modal.find('#idKategori').val();
					// Kirim data formulir menggunakan AJAX
					$.ajax({
							type: 'POST',
							url: `http://localhost:3000/hapus-kategori`,
							headers: {
								'Content-Type': 'application/json'
							},
							data: JSON.stringify({
								id_kategori: id
							}),
							success: function(response) {
									// Tampilkan pesan sukses menggunakan Swal
									swal({
											title: "Success",
											text: response.message,
											icon: "success",
											button: "OK",
									})
									.then(() => {
											// Redirect kembali ke halaman sebelumnya
											$('#modalDelete').modal('hide');
												// Refresh halaman
												location.reload();
									});
							},
							error: function(xhr, status, error) {
									// Tampilkan pesan error jika terjadi kesalahan
									swal({
											title: "Error",
											text: error.message,
											icon: "error",
											button: "OK",
									})
							}
					});
				});
			});
</script>
    
</body>
</html>

