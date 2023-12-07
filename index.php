<?php session_start();?>
<?php
  if(isset($_POST['submit'])){
    $bloodgroup = filter_var($_POST['bloodgroup']);
    $_SESSION['as']='guest';
    $_SESSION['bloodgroup']=$bloodgroup;
    header('Location: findblood.php');
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
            <a href="index.php" class="navbar-brand d-flex align-items-center ps-2 ps-sm-0"><i class="bi bi-droplet-fill text-danger fs-2"></i><span class="fs-3 ms-2">Life Blood</span></a>
            <a href="availablesamples.php" class="nav-link d-inline">Available Blood Samples</a>
        </div>
    </nav>

    <header class="container pt-5 mt-md-5 mt-0">
        <h1 class="fs-2 fw-light text-center text-md-start" ><span class="fw-normal">Donate Blood</span> Give the Gift of Life</h1>
    </header>

    <main class="container mt-3 pt-md-0 pt-4" style="height:350px">
        <div class="row justify-content-center text-center h-100 align-items-center">
            <div class="col-md col-9 border border-dark rounded border-opacity-25 py-5" style="background-color:#ebedec">
              <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" class="w-50 mx-auto" method="POST">
                <select class="form-select shadow" name="bloodgroup" id="bloodgroup">
                    <option value="Blood group">Blood Group</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                </select>
                <input type="submit" value="Find Blood" name="submit" class="btn btn-outline-primary w-100 mt-3">
              </form>
            </div>
            <div class="col-1 h-75 d-md-block d-none">
                <div class="vr h-100"></div>
            </div>
            <div class="col-10 d-md-none d-block">
                <hr class="hr">
            </div>
            <div class="col-md col-9 border border-dark rounded border-opacity-25 py-5" style="background-color:#ebedec">
                <a href="login.php" class="btn btn-outline-primary w-50" role="button">Login</a>
                <button class="btn btn-outline-dark w-50 mt-3" data-bs-toggle="modal" data-bs-target="#registerModal">Register</button>

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