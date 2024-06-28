<?php
    session_start();
    $db = mysqli_connect('localhost', 'root', 'root', 'myrt', 3306);

    if (isset($_POST["login"])) {
        login($_POST);
    } 

    function login($dataLogin)
    {
        global $db;

        $email = $dataLogin['identifier'];
        $username = $dataLogin['identifier'];
        $password = $dataLogin['password'];

        $cekUser = mysqli_query($db, "SELECT * FROM users WHERE email = '$email' OR username = '$username'");

        if (mysqli_num_rows($cekUser) === 1) {
            $hasil = mysqli_fetch_assoc($cekUser);
						$_SESSION['pass'] = $hasil["password"];

            if (password_verify($password, $hasil["password"])) {
                if ($hasil['status'] == 'aktif') {
                    $_SESSION['user'] = $hasil['username'];
                    $_SESSION['login'] = true;
                    $_SESSION['id'] = $hasil['id_user'];

                    if ($hasil['role'] == 'admin') {
                        $_SESSION['role'] = 'admin';
                        header('Location: ../dashboardAdmin');
                    } elseif ($hasil['role'] == 'user') {
                        $_SESSION['role'] = 'user';
                        header('Location: ../dashboard');
                    }

                    if (isset($_POST['rememberme'])) {
                        setcookie('login', $hasil['username'], time() + 3600);
                        setcookie('role', $hasil['role'], time() + 3600);
                        setcookie('id', $hasil['id_user'], time() + 3600);
                        setcookie('key', hash('sha256', $hasil['username']), time() + 3600);
                    }
                } elseif ($hasil['status'] == 'tidak aktif') {
                    $_SESSION["login_error"] = "Akun anda dinonaktifkan oleh admin!";
                    header('Location: ../login');
                }
            } else {
                $_SESSION["login_error"] = "Username / password salahd!";
                header('Location: ../login');
            }
        } else {
            $_SESSION["login_error"] = "Username / password salah!s";
            header('Location: ../login');
        }

        return mysqli_affected_rows($db);
    }
?>