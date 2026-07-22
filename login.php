<?php 
session_start();
require 'config/database.php';
require 'config/controller.php';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    $result = mysqli_query($db, "SELECT * FROM akun WHERE username = '$username'");

    if (mysqli_num_rows($result) == 1) {

        $hasil = mysqli_fetch_assoc($result);

        if (password_verify($password, $hasil['password'])) {

            $_SESSION['login']    = true;
            $_SESSION['id_akun']  = $hasil['id_akun'];
            $_SESSION['nama']     = $hasil['nama'];
            $_SESSION['username'] = $hasil['username'];
            $_SESSION['email']    = $hasil['email'];
            $_SESSION['level']    = $hasil['level'];

            header("Location: index.php");
            exit;
        }
    }

    $error = true;
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title>Login</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sign-in/">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

<link rel="icon" href="/docs/5.0/assets/img/favicons/favicon.ico">
<meta name="theme-color" content="#7952b3">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    <link href="assets/css/signin.css" rel="stylesheet">
  </head>
  <body class="text-center">
    
<main class="form-signin">
  <form action="" method="post"> 
    <img class="mb-4" src="/docs/5.0/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57">
    <h1 class="h3 mb-3 fw-normal">Admin Login</h1>

    <?php if (isset($error)) : ?>
        <p class="alert alert-danger text-center">Username / Password salah</p>
    <?php endif; ?>

    <div class="form-floating">
      <input type="text"  name="username" class="form-control" id="floatingInput">
      <label for="floatingInput">Username</label>
    </div>
    <div class="form-floating">
      <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password">
      <label for="floatingPassword">Password</label>
    </div>

    <div class="g-recaptcha" data-sitekey="6LdTGF8tAAAAAPO9x9AwWrae1H-jv8XgdREyhwAh"></div>

    <button class="w-100 btn btn-lg btn-primary" type="submit" name="login">Login</button>
  </form>
</main>

    
  </body>
</html>
