<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/logo.jpg">
    <title>Dashboard | MyRT Keuangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<body>
		<?php include 'navbar.php'; ?>

    <div class="container mt-4 border rounded-4 shadow">
        <div class="row">
            <div class="col">
                
                
                <div class="m-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        Tambah Data
                    </button>
                </div>
                <div class="p-3">
                    <table class="table table-striped text-center" id="tablePemasukan">
                        <thead>
                            <tr class="table-primary">
                            <th scope="col">No.</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Deskripsi Pemasukan</th>
                            <th scope="col">Kategori Pemasukan</th>
                            <th scope="col">Jumlah Pemasukan</th>
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
                <form id="formTambah">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <input type="text" class="form-control filter" id="deskripsi" name="deskripsi" required>
                        </div>
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select class="form-select" id="kategori" name="id_kategori">
                              
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah</label>
                            <input type="number"  class="form-control" id="jumlah" name="jumlah" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" name="tambahPengeluaran" class="btn btn-primary">Tambah</button>
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
                <form id="formEdit">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <input type="text" class="form-control filter" id="deskripsi" name="deskripsi" required>
                        </div>
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select class="form-select" id="kategoriEdit" name="id_kategori">
                                
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah</label>
                            <input type="number"  class="form-control" id="jumlah" name="jumlah" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" name="editPengeluaran" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
		<script src="/js/app.js"></script>
    <script>
      document.addEventListener("DOMContentLoaded", async function(){

				
				const dataKategori = await getDataKategori();
				const dataPengeluaran = await getDataPemasukan();

				async function getDataPemasukan() {
					const id = localStorage.getItem('id');
					const result = await fetch('http://localhost:3000/pemasukan/' + id);
					const response = await result.json();
					renderDataPengeluaran(response.data); // Tampilkan data;
					return response.data;
				}

				async function getDataKategori() {
					 const result = await fetch('http://localhost:3000/kategori');
					 const response = await result.json();
					 renderDataKategori(response.data); // Tampilkan data;
					 return response.data;
				}

				function renderDataPengeluaran(data) {
					 const table = document.querySelector('#tablePemasukan tbody');
					 table.innerHTML = '';
					 let number = 0
					 data.forEach(item => {
						 const tr = document.createElement('tr');
						 tr.innerHTML = `
							 <td>${++number}</td>
							 <td>${new Date(item.tanggal).toLocaleDateString('id-ID')}</td>
							 <td>${item.deskripsi}</td>
							 <td>${item.nama_kategori}</td>
							 <td>${item.jumlah}</td>
							 <td><div class="d-flex">
									<button type="button" class="btn btn-primary me-3" data-bs-toggle="modal" data-bs-target="#modalEdit"
													data-id="${item.id_pemasukan}">
											Edit
									</button>
									<button type="button" class="btn btn-danger me-3 btnDelete" data-id="${item.id_pemasukan}">
											Delete
									</button>
							</div></td>`;
						 table.appendChild(tr);
					});
				}

				function renderDataKategori(data) {
					 const select = document.querySelector('#kategori');
					 data.forEach(item => {
						 const option = document.createElement('option');
						 option.value = item.id_kategori;
						 option.text = item.nama_kategori;
						 select.appendChild(option);
					});
				}
            // JS untuk isi data modal edit
				$('#modalEdit').on('show.bs.modal', function(e) {

					var button = $(e.relatedTarget);
					var id = button.data('id');
					const data = dataPengeluaran.find(item => item.id_pemasukan == id);

					const select = document.querySelector('#kategoriEdit');
					 dataKategori.forEach(item => {
						 const option = document.createElement('option');
						 option.value = item.id_kategori;
						 option.text = item.nama_kategori;
						 select.appendChild(option);
					});

					var modal = $(this);
					const date = new Date(data.tanggal)
					const year = date.getFullYear();
					const month = (date.getMonth() + 1).toString().padStart(2, "0");
					const day = date.getDate().toString().padStart(2, "0");
					// Set nilai id_kategori ke dalam input tersembunyi
					modal.find('#tanggal').val(`${year}-${month}-${day}`);
					modal.find('#deskripsi').val(data.deskripsi);
					modal.find('#kategoriEdit').val(data.id_kategori);
					modal.find('#jumlah').val(data.jumlah);
				});


				$('#formTambah').submit(function(event) {
						event.preventDefault(); // Hindari pengiriman formulir secara sinkron
						// Kirim data formulir menggunakan AJAX
						const modal = $('#modalTambah');
						const tanggal = modal.find('#tanggal').val();
						const deskripsi = modal.find('#deskripsi').val();
						const kategori = modal.find('#kategori').val();
						const jumlah = modal.find('#jumlah').val();
						const user = localStorage.getItem('id');

						$.ajax({
								type: 'POST',
								url: 'http://localhost:3000/tambah-pemasukan',
								headers: {
									'Content-Type': 'application/json'
								},
								data: JSON.stringify({
									tanggal, 
									deskripsi, 
									id_kategori: kategori, 
									jumlah, 
									id_user: user
								}),
								success: function(response) {
										swal({
											title: "Success",
											text: response.message,
											icon: "success",
											button: "OK",
										})
										.then(() => {
											location.reload();
										});
								},
								error : function(xhr, status, error){
									swal({
										title: "Error",
										text: error.message,
										icon: "error",
										button: "OK",
									})
								}
						})
				});

				$('#formEdit').submit(function (event) {
					event.preventDefault(); // Hindari pengiriman formulir secara sinkron

					const modal = $('#modalEdit');
					const tanggal = modal.find('#tanggal').val();
					const deskripsi = modal.find('#deskripsi').val();
					const kategori = modal.find('#kategoriEdit').val();
					const jumlah = modal.find('#jumlah').val();
					const user = localStorage.getItem('id');
					// Kirim data formulir menggunakan AJAX
					$.ajax({
								type: 'POST',
								url: 'http://localhost:3000/edit-pemasukan/' + user,
								headers: {
									'Content-Type': 'application/json'
								},
								data: JSON.stringify({
									tanggal, 
									deskripsi, 
									id_kategori: kategori, 
									jumlah, 
									id_user: user
								}),
								success: function(response) {
										swal({
											title: "Success",
											text: response.message,
											icon: "success",
											button: "OK",
										})
										.then(() => {
											location.reload();
										});
								},
								error : function(xhr, status, error){
									swal({
										title: "Error",
										text: error.message,
										icon: "error",
										button: "OK",
									})
								}
						})
				});

				$('#tablePemasukan').on('click', '.btnDelete', function (e) {
					const id = $(this).data('id');
					swal({
						title: "Anda yakin menghapus data ?",
						text: "Data yang dihapus tidak dapat dikembalikan!",
						icon: "warning",
						buttons: true,
						dangerMode: true,
					})
					.then((willDelete) => {
						if (willDelete) {
							$.ajax({
								type: 'POST',
								url: 'http://localhost:3000/hapus-pemasukan',
								headers: {
									'Content-Type': 'application/json'
								},
								data: JSON.stringify({
									id_pemasukan: id
								}),
								success: function(response) {
										swal({
											title: "Success",
											text: response.message,
											icon: "success",
											button: "OK",
										})
										.then(() => {
											location.reload();
										});
								},
								error : function(xhr, status, error){
									swal({
										title: "Error",
										text: error.message,
										icon: "error",
										button: "OK",
									})
								}
							})
						} 
					});
				});
    });
    </script>
</body>
</html>