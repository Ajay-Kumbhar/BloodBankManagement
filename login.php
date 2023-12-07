<?php include 'dbconnection/database.php'?>
<?php session_start()?>
<?php
  if(isset($_POST['login'])){
    $username = filter_var($_POST['username']);
    $password = filter_var($_POST['password']);
    $as = $_POST['as'];
    $alert = 0;
    if($as=='hospital'){
      $query = "SELECT * FROM hospital WHERE username='$username' AND password='$password'";
      $result = mysqli_query($conn, $query);
      $accounts = mysqli_fetch_all($result, MYSQLI_ASSOC);
      if(!count($accounts)){
        $alert = 1;
      }else{
        $_SESSION['username'] = $username;
        $_SESSION['as'] = 'hospital';
        header('Location: hospitaldashboard.php');
      }
    }else{
      $query = "SELECT * FROM receiver WHERE username='$username' AND password='$password'";
      $result = mysqli_query($conn, $query);
      $accounts = mysqli_fetch_all($result, MYSQLI_ASSOC);
      if(!count($accounts)){
        $alert = 1;
      }else{
        $_SESSION['user'] = $username;
        $_SESSION['as'] = 'receiver';
        header('Location: receiverdashboard.php');
      }
    }

  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <title>Document</title>
</head>
<body>
    <nav class="border-bottom" style="background-color:#ebedec">
        <div class="container py-4 d-flex justify-content-between align-items-center">
            <a href="index.php" class="navbar-brand d-flex align-items-center ps-4 ps-md-0"><i class="bi bi-droplet-fill text-danger fs-2"></i><span class="fs-3 ms-2">Life Blood</span></a>
            <a href="index.php" class="nav-link pe-md-0 pe-4">Home</a>
        </div>
    </nav>
    <main class="py-5">
      <div class="alert alert-danger mt-3 col-8 col-md-5 col-lg-5 col-xl-3 mx-auto <?php echo $alert==0?'d-none':'d-block'?>" role="alert">
        Invalid Login Credentials
      </div>
      <div class="card col-8 col-md-5 col-lg-5 col-xl-3 mx-auto my-3">
        <div class="card-body p-4">
          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
            <label for="password" class="form-label mt-3 d-block">Password</label>
            <input class="form-control" type="password" id="password" name="password" required>
            <label for="as" class="form-label my-3 d-block">Login as</label>
            <label for="receiver" class="form-label">Receiver</label>
            <input type="radio" id="receiver" name="as" value="receiver" class="form-check-input ms-2" checked>
            <label for="hospital" class="ms-sm-5 ms-3 form-label">Hospital</label>
            <input type="radio" id="hospital" name="as" value="hospital" class="form-check-input ms-2">
            
            <input type="submit" value="Login" name="login" class="d-block btn btn-outline-primary mt-4">
          </form>
          <hr class="hr mt-4">
          <p class="mt-4">New User? <a data-bs-toggle="modal" data-bs-target="#registerModal" class="link opacity-75" style="cursor:pointer;">Register</a></p>

          <!--Register Modal-->
          <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-column align-items-center py-5">
                  <a href="receiverregister.php" role="button" class="btn btn-outline-dark w-50">Register as Receiver</a>
                  <a href="hospitalregister.php" role="button" class="btn btn-outline-primary mt-3 w-50">Register as Hospital</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>