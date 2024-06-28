const express = require('express');
const cors = require('cors');
const mysql = require('mysql');
const bodyParser = require('body-parser');
const excel = require('exceljs');
const bcrypt = require('bcryptjs');
const multer = require('multer');
const upload = multer();
const app = express();

// Konfigurasi MySQL
const connection = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: 'root',
  database: 'myrt'
});

// Koneksi ke database MySQL
connection.connect(function(err) {
  if (err) throw err;
  console.log('Terhubung ke database MySQL!');
});

// Middleware untuk mengurai body dari permintaan HTTP
app.use(cors());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: false }));
app.use((req, res, next) => {
  res.header('Access-Control-Allow-Origin', '*');
  res.header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
  next();
});
app.use(upload.none());

// Endpoint untuk login
app.post('/login', function(req, res) {
  const { username, password } = req.body;

  // Cek login ke database
  const query = `SELECT * FROM users WHERE username = '${username}'`;
	
  connection.query(query, function(err, result) {
    if (err) {
      res.status(500).json({ status: 'failed', error: 'Gagal melakukan login.' });
      throw err;
    }

    if (result.length == 0) {
			return res.status(404).json({
				status: 'failed', 
				message: 'Login gagal. Username tidak ditemukan.' 
			});
		}

		if(!bcrypt.compareSync(password, result[0].password)) {
			return res.status(401).json({ 
				status: 'failed', message: 'Login gagal. Periksa kembali username dan password.' 
			});
		}

    return res.status(200).json({ 
			status: 'success',
			data: {
				role: result[0].role,
				username: result[0].username,
				id: result[0].id_user
			},
			message: 'Login berhasil.' 
		});
  });
});

// Endpoint untuk mengekspor tabel menjadi file Excel
app.get('/export-excel', function(req, res) {
  const workbook = new excel.Workbook();
  const worksheet1 = workbook.addWorksheet('Pemasukan');
  const worksheet2 = workbook.addWorksheet('Pengeluaran');

  // Query untuk mengambil data dari database untuk MyTable1
  const query1 = 'SELECT * FROM pemasukan';
  connection.query(query1, function(err, result1) {
    if (err) throw err;
    // Menulis data ke worksheet1
    result1.forEach(row => {
      worksheet1.addRow(Object.values(row));
    });

    // Query untuk mengambil data dari database untuk MyTable2
    const query2 = 'SELECT * FROM pengeluaran';
    connection.query(query2, function(err, result2) {
      if (err) throw err;
      // Menulis data ke worksheet2
      result2.forEach(row => {
        worksheet2.addRow(Object.values(row));
      });

      // Mengatur header untuk respon
      res.setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      res.setHeader('Content-Disposition', 'attachment; filename="data.xlsx"');

      // Menyimpan workbook ke response
      workbook.xlsx.write(res)
        .then(function() {
          res.end();
        });
    });
  });
});

app.get('/export-excel-pemasukan', function(req, res) {
  const workbook = new excel.Workbook();
  const worksheet1 = workbook.addWorksheet('Laporan Pemasukan');
  const { id_user, id_kategori, tgl_awal, tgl_akhir, tipe_laporan } = req.query; // Menggunakan req.query untuk parameter GET

  // Query awal tanpa kondisi WHERE
  let query1 = 'SELECT * FROM pemasukan';

  // Array untuk menyimpan kondisi WHERE
  let conditions = [];

  // Memeriksa setiap inputan dan menambahkan kondisi WHERE sesuai kebutuhan
  if (id_user) {
    conditions.push(`id_user = '${id_user}'`);
  }
  if (id_kategori) {
    conditions.push(`id_kategori = '${id_kategori}'`);
  }
  if (tgl_awal && tgl_akhir) {
    conditions.push(`tanggal BETWEEN '${tgl_awal}' AND '${tgl_akhir}'`);
  } else if (tgl_awal) {
    conditions.push(`tanggal >= '${tgl_awal}'`);
  } else if (tgl_akhir) {
    conditions.push(`tanggal <= '${tgl_akhir}'`);
  }
  // Menambahkan kondisi WHERE ke dalam query jika ada kondisi yang ditetapkan
  if (conditions.length > 0) {
    query1 += ' WHERE ' + conditions.join(' AND ');
  }

  // Sekarang Anda dapat menggunakan query1 untuk menjalankan kueri ke database
  connection.query(query1, function(err, results) {
    if (err) {
      return res.status(500).json({ error: 'Gagal mengambil data dari database.' });
    }

    // Menuliskan hasil query ke dalam worksheet
    worksheet1.addRows(results);

    // Mengatur header untuk respon
    res.setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    res.setHeader('Content-Disposition', 'attachment; filename="data.xlsx"');

    // Menyimpan workbook ke response
    workbook.xlsx.write(res)
      .then(function() {
        res.end();
      });
  });
});


app.post('/tambah-data', function(req, res) {
  const { nama, harga } = req.body;

  // Masukkan data ke database
  const query = `INSERT INTO nama_tabel (nama, harga) VALUES ('${nama}', ${harga})`;
  connection.query(query, function(err, result) {
    if (err) {
      res.status(500).json({ error: 'Gagal menambahkan data ke database.' });
      throw err;
    }
    res.status(200).json({ message: 'Data berhasil ditambahkan ke database.' });
  });
});

app.get('/kategori', function(req, res) {
	const query = 'SELECT * FROM kategori_pemasukan';
	connection.query(query, function(err, result) {
		if (err) {
			return res.status(500).json({
				status: 'failed',
				error: 'Gagal mengambil data dari database.' 
			});
		}
		return res.status(200).json({
			status: 'success',
			message: 'Data kategori.',
			data: result
		});
	});
})

app.get('/users', function(req, res) {
	const query = `SELECT * FROM users where role != 'admin'`;
	connection.query(query, function(err, result) {
		if (err) {
			return res.status(500).json({
				status: 'failed',
				error: 'Gagal mengambil data dari database.' 
			});
		}
		return res.status(200).json({
			status: 'success',
			message: 'Data kategori.',
			data: result
		});
	});
})

app.post('/tambah-kategori', function(req, res) {
  const { namaKategori } = req.body;

  // Masukkan data ke database
  const query1 = `INSERT INTO kategori_pengeluaran (nama_kategori) VALUES ('${namaKategori}')`;
  const query2 = `INSERT INTO kategori_pemasukan (nama_kategori) VALUES ('${namaKategori}')`;

  connection.query(query1, function(err1, result1) {
    if (err1) {
      return res.status(500).json({
				status: 'failed',
				error: 'Gagal menambahkan data ke database.' 
			});
    }
    
    connection.query(query2, function(err2, result2) {
      if (err2) {
        return res.status(500).json({ 
					status: 'failed', 
					error: 'Gagal menambahkan data ke database.' 
				});
      }
      
      return res.status(200).json({ 
				status: 'success', 
				message: 'Data berhasil ditambahkan ke database.' 
			});
    });
  });
});

app.post('/edit-data/:id_kategori', function(req, res) {
  const id = req.params.id_kategori;
  const { namaKategori} = req.body;

  // Query untuk mengedit data di database
  const query = `UPDATE kategori_pemasukan SET nama_kategori = '${namaKategori}' WHERE id_kategori = ${id}`;
  const query1 = `UPDATE kategori_pengeluaran SET nama_kategori = '${namaKategori}' WHERE id_kategori = ${id}`; 
  
  connection.query(query, function(err, result) {
    if (err) {
      return res.status(500).json({ 
				status: 'failed',
				error: 'Gagal mengedit data di database.' 
			});
    }
    connection.query(query1, function(err2, result2) {
      if (err2) {
        res.status(500).json({ 
					status: 'failed',
					error: 'Gagal menambahkan data ke database.' 
				});
        throw err2;
      }
    res.status(200).json({ 
			status: 'success',
			message: 'Data berhasil diubah di database.' 
		});
    });
  });

});

app.post('/hapus-kategori', (req, res) => {
  // Ambil id_kategori dari body permintaan
  const idKategori = req.body.id_kategori;

  // Buat query untuk menghapus data dari database
  const query = `DELETE FROM kategori_pemasukan WHERE id_kategori = '${idKategori}'`;
  const query1 = `DELETE FROM kategori_pengeluaran WHERE id_kategori = '${idKategori}'`;

  // Eksekusi query
  connection.query(query, function(err, result) {
    if (err) {
      return res.status(500).json({ 
				status: 'failed',
				error: 'Gagal menghapus data di database.' 
			});
    }
    connection.query(query1, function(err2, result2) {
      if (err2) {
        return res.status(500).json({ 
					status: 'failed',
					error: 'Gagal menghapus data ke database.' 
				});
      }
    res.status(200).json({ 
			status: 'success',
			message: 'Data berhasil dihapus di database.' 
		});
    });
  });

});

app.get('/pengeluaran/:id', function(req, res) {
	const id = req.params.id;

	const getUser = 'SELECT role FROM users WHERE id_user = ?';
	connection.query(getUser, [id], function(err, userResult) {
		if (err) {
			return res.status(500).json({
				status: 'failed',
				error: 'Gagal mengambil data dari database.' 
			});
		}

		let query
		if(userResult[0].role === 'admin') {
			query = 'SELECT id_pengeluaran, tanggal, deskripsi, pengeluaran.id_kategori as id_kategori, nama_kategori, jumlah, username, pengeluaran.id_user as id_user FROM `pengeluaran` left join kategori_pemasukan ON (pengeluaran.id_kategori = kategori_pemasukan.id_kategori) left join users ON (pengeluaran.id_user = users.id_user)';
		} else {
			query = `SELECT id_pengeluaran, tanggal, deskripsi, pengeluaran.id_kategori as id_kategori, nama_kategori, jumlah, username FROM pengeluaran left join kategori_pemasukan ON (pengeluaran.id_kategori = kategori_pemasukan.id_kategori) left join users ON (pengeluaran.id_user = users.id_user) where pengeluaran.id_user = ${id}`;
		}

		connection.query(query, function(err, result) {
			if (err) {
				return res.status(500).json({
					status: 'failed',
					error: 'Gagal mengambil data dari database.' 
				});
			}
			return res.status(200).json({
				status: 'success',
				message: 'Data pengeluaran.',
				data: result
			});
		});
		
	});
})

app.get('/pemasukan/:id', function(req, res) {
	const id = req.params.id;

	const getUser = 'SELECT role FROM users WHERE id_user = ?';
	connection.query(getUser, [id], function(err, userResult) {
		if (err) {
			return res.status(500).json({
				status: 'failed',
				error: 'Gagal mengambil data dari database.' 
			});
		}

		let query
		if(userResult[0].role === 'admin') {
			query = 'SELECT id_pemasukan, tanggal, deskripsi, pemasukan.id_kategori as id_kategori, nama_kategori, jumlah, username, pemasukan.id_user as id_user FROM pemasukan left join kategori_pemasukan ON (pemasukan.id_kategori = kategori_pemasukan.id_kategori) left join users ON (pemasukan.id_user = users.id_user)';
		} else {
			query = `SELECT id_pemasukan, tanggal, deskripsi, pemasukan.id_kategori as id_kategori, nama_kategori, jumlah, username FROM pemasukan left join kategori_pemasukan ON (pemasukan.id_kategori = kategori_pemasukan.id_kategori) left join users ON (pemasukan.id_user = users.id_user) where pemasukan.id_user = ${id}`;
		}

		connection.query(query, function(err, result) {
			if (err) {
				return res.status(500).json({
					status: 'failed',
					error: 'Gagal mengambil data dari database.' 
				});
			}
			return res.status(200).json({
				status: 'success',
				message: 'Data pemasukan.',
				data: result
			});
		});
		
	});

	
})

//menambah pengeluaran
app.post('/tambah-pengeluaran', function(req, res) {
  const { tanggal, deskripsi, id_kategori, jumlah, id_user } = req.body;
  // Gunakan prepared statement untuk mencegah SQL injection
  const query = 'INSERT INTO pengeluaran (tanggal, deskripsi, id_kategori, jumlah, id_user) VALUES (?, ?, ?, ?, ?)';
  const values = [tanggal, deskripsi, id_kategori, jumlah, id_user];

  connection.query(query, values, function(err1, result1) {
    if (err1) {
      console.error("Error:", err1); // Log error untuk debugging
      return res.status(500).json({
				status: 'failed',
				error: 'Gagal menambahkan data ke database.' 
			});
    }
    return res.status(200).json({
			status: 'success',
			message: 'Data berhasil masuk ke database' 
		});
  });
});


//menambah pemasukan
app.post('/tambah-pemasukan', function(req, res) {
  const { tanggal, deskripsi, id_kategori, jumlah, id_user} = req.body;

  // Gunakan prepared statement untuk mencegah SQL injection
  const query = 'INSERT INTO pemasukan (tanggal, deskripsi, id_kategori, jumlah, id_user) VALUES (?, ?, ?, ?, ?)';
  const values = [tanggal, deskripsi, id_kategori, jumlah, id_user];

  connection.query(query, values, function(err1, result1) {
    if (err1) {
      console.error("Error:", err1); // Log error untuk debugging
      return res.status(500).json({ 
				status: 'failed', error: 'Gagal menambahkan data ke database.' 
			});
    }
    return res.status(200).json({ 
			status: 'success', message: 'Data berhasil masuk ke database' });
  });
});

app.post('/hapus-pengeluaran', (req, res) => {
  // Ambil id_kategori dari body permintaan
  const idPengeluaran = req.body.id_pengeluaran;
  // Buat query untuk menghapus data dari database
  const query = `DELETE FROM pengeluaran WHERE id_pengeluaran = '${idPengeluaran}'`;
  // Eksekusi query
  connection.query(query, function(err, result) {
    if (err) {
      return res.status(500).json({ 
				status: 'failed', error: 'Gagal menghapus data di database.' });
    }
    return res.status(200).json({ 
			status: 'success', message: 'Berhasil menghapus data di database'});
  });
});

app.post('/hapus-pemasukan', (req, res) => {
  // Ambil id_kategori dari body permintaan
  const idPemasukan = req.body.id_pemasukan;
  // Buat query untuk menghapus data dari database
  const query = `DELETE FROM pemasukan WHERE id_pemasukan = '${idPemasukan}'`;
  // Eksekusi query
  connection.query(query, function(err, result) {
    if (err) {
      return res.status(500).json({ 
				status: 'failed', error: 'Gagal menghapus data di database.' });
    }
    return res.status(200).json({ message: 'Berhasil menghapus data di database'});
  });
});

app.post('/edit-pemasukan/:id_pemasukan', function(req, res) {
  const id = req.params.id_pemasukan;
  const { tanggal, deskripsi, id_kategori, jumlah } = req.body;
  
  const query = 'UPDATE pemasukan SET tanggal=?, deskripsi=?, id_kategori=?, jumlah=? WHERE id_pemasukan = ?';
  const values = [tanggal, deskripsi, id_kategori, jumlah, id];
  
  connection.query(query, values, function(err, result) {
    if (err) {
      console.error("Error:", err); // Log error untuk debugging
      return res.status(500).json({ 
				status: 'failed', error: 'Gagal mengedit data di database.' });
    }
    return res.status(200).json({ 
			status: 'success', message: 'Data berhasil diubah di database.' });
  });
});


app.post('/edit-pengeluaran/:id_pengeluaran', function(req, res) {
  const id = req.params.id_pengeluaran;
  const { tanggal, deskripsi, id_kategori, jumlah } = req.body;
  
  const query = 'UPDATE pengeluaran SET tanggal=?, deskripsi=?, id_kategori=?, jumlah=? WHERE id_pengeluaran = ?';
  const values = [tanggal, deskripsi, id_kategori, jumlah, id];
  
  connection.query(query, values, function(err, result) {
    if (err) {
      console.error("Error:", err); // Log error untuk debugging
      return res.status(500).json({ 
				status: 'failed', error: 'Gagal mengedit data di database.' });
    }
    return res.status(200).json({ 
			status: 'success', message: 'Data berhasil diubah di database.' });
  });
});
// Port server
const port = 3000;

// Mulai server
app.listen(port, function() {
  console.log(`Server berjalan di http://localhost:${port}`);
});
