<?php include 'dbconnection/database.php'?>
<?php session_start()?>
<?php
    
    $query = "SELECT * FROM bloodbank WHERE amount>0";
    $result = mysqli_query($conn, $query);
    $bloodbankinfo = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $query = "SELECT DISTINCT username FROM bloodbank";
    $result = mysqli_query($conn, $query);
    $hospitals = [];
    while ($row = $result->fetch_row()) {
        $hospitals[] = $row[0];
    }

    $hospitalsinfo = [];
    foreach($hospitals as $hospital){
        $query = "SELECT * FROM hospital WHERE username='$hospital'";
        $result = mysqli_query($conn, $query);
        while($row = $result->fetch_row()){
            $hospitalsinfo[$hospital]  = $row;
        }
    }

    if(isset($_SESSION['as'])){
        if($_SESSION['as']=='hospital'){
            if(isset($_SESSION['username'])){
                $username = $_SESSION['username'];
            }else{
                header('Location: login.php');
            }
        }elseif($_SESSION['as']=='receiver'){
            if(isset($_SESSION['user'])){
                $username = $_SESSION['user'];
            }else{
                header('Location: login.php');
            }
        }else{
            $username = 'Guest';
        }

    }else{
        $_SESSION['as']='guest';
        $username = 'Guest';
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
        <div class="container pt-4 pb-3 d-flex justify-content-between align-items-center px-sm-0 px-2">
            <a href="index.php" class="navbar-brand d-flex align-items-center ps-2 ps-sm-0"><i class="bi bi-droplet-fill text-danger fs-2"></i><span class="fs-3 ms-2">Life Blood</span></a>
            <div class="dropdown">
                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person"></i> <?php echo $username?>
                </button>
                <?php if($_SESSION['as']=='hospital'):?>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="hospitaldashboard.php">Go to Dashboard</a></li>
                        <li><a class="dropdown-item" href="logout.php">Log out</a></li>
                    </ul>
                <?php elseif($_SESSION['as']=='receiver'):?>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="receiverdashboard.php">Go to Dashboard</a></li>
                        <li><a class="dropdown-item" href="logout.php">Log out</a></li>
                    </ul>
                <?php else:?>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="login.php">Login</a></li>
                        <li><a role="button" data-bs-toggle="modal" data-bs-target="#registerModal" class="btn">Register</a></li>
                    </ul>
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
                <?php endif;?>
            </div>
        </div>
    </nav>

    <?php
        $display = "";
        $message = "";
        if(isset($_SESSION['message'])){
            if($_SESSION['message']=='Incompatible'){
                $_SESSION['message']="";
                $message = "Incompatible blood group";
            }else if($_SESSION['message']=='Ineligible'){
                $_SESSION['message']="";
                $message = "Hospitals are ineligible for requests";
            }else{
                $display="d-none";
            }
        }else{
            $display = "d-none";
        }
    
    ?>

    <div class="container mt-5 col-8 alert alert-warning <?php echo $display?>" role="alert">
        <?php echo $message?>
    </div>

    <main class="container d-flex flex-wrap pt-5 justify-content-center">
        <?php foreach($bloodbankinfo as $item):?>
            <?php 
                $hos = $item['username']; 
                $hospitalname = $hos;
                $bloodgroup = $item['bloodgroup'];
                $len = strlen($bloodgroup)-1;
                $bg = substr($bloodgroup, 0, $len);
                $last = $bloodgroup[-1];
                $c = $last=='+'?'plus':'minus';
                $bg = $bg.$c;
                $hos = $hos.$bg;
                $btntext = "Request";
                $color = "btn-outline-primary";

                /*$query = "SELECT * FROM requests WHERE bloodgroup='$bloodgroup' AND receivername='$username' AND hospitalname='$hospitalname' AND status='requested'";
                $result = mysqli_query($conn, $query);
                $bloodbankinfo = mysqli_fetch_all($result, MYSQLI_ASSOC);
                $disable = 0;
                if(count($bloodbankinfo)>0){
                    $disable = 1;
                    $btntext = "Requested";
                }*/

                $query = "SELECT * FROM requests WHERE bloodgroup='$bloodgroup' AND receivername='$username' AND hospitalname='$hospitalname'";
                $result = mysqli_query($conn, $query);
                $bloodbankinfo = mysqli_fetch_all($result, MYSQLI_ASSOC);
                $disable = 0;
                if(count($bloodbankinfo)>0){
                    $disable = 1;
                    if($bloodbankinfo[0]['status']=='requested'){
                        $btntext = "Requested";
                    }elseif($bloodbankinfo[0]['status']=='Accepted'){
                        $btntext = "Accepted";
                        $color = "btn-outline-success";
                    }else{
                        $btntext = "Rejected";
                        $color = "btn-outline-danger";
                    }
                }

                $query = "SELECT amount FROM bloodbank WHERE bloodgroup='$bloodgroup' AND username='$hospitalname'";
                $result = mysqli_query($conn, $query);
                $available = mysqli_fetch_all($result, MYSQLI_ASSOC);
                $available = $available[0]['amount'];
            ?>
            <div class="card m-3" style="width:330px;">
                <div class="card-header fs-5 d-flex align-items-center my-1"><p class="rounded-pill border border-danger-subtle text-danger text-center m-0" style="width:50px;"><?php echo $item['bloodgroup']?><p></div>
                <div class="card-body">
                    <h5 class="card-title fw-normal pt-1"><i class="bi bi-hospital me-1"></i> <?php echo $hospitalsinfo[$item['username']][2]?></h5>
                    <p class="card-text fs-6 py-3"><i class="bi bi-geo-alt-fill me-1"></i> <?php echo $hospitalsinfo[$item['username']][3]?></p>
                    <button class="btn <?php echo !$disable?null:'disabled'?> <?php echo $color?>" data-bs-toggle="modal" data-bs-target="#<?php echo $hos?>-Modal" style="width:100px;"><?php echo $btntext?></button>
                    <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#<?php echo $hos?>Modal" style="width:90px;">Info</button>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="<?php echo $hos?>-Modal" tabindex="-1" aria-labelledby="<?php echo $hos?>-ModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="<?php echo $hos?>-ModalLabel">Modal title</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="requestsamples.php" method="POST">
                            <div class="modal-body">
                                <label for="bloodgroup" class="form-label">Blood Group</label>
                                <input type="text" readonly class="form-control" id="bloodgroup" name="bloodgroup" value="<?php echo $bloodgroup?>">
                                <input type="text" readonly class="form-control d-none" name="receivername" value="<?php echo $username?>">
                                <input type="text" readonly class="form-control d-none" name="hospitalname" value="<?php echo $hospitalsinfo[$item['username']][0]?>">
                                <label for="available" class="form-label">Available amount</label>
                                <input class="form-control" readonly id="available" name="available" type="number" value="<?php echo $available?>" required>
                                <label for="amount" class="form-label">Amount</label>
                                <input class="form-control" id="amount" name="amount" type="number" min="1" max="<?php echo $available?>" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button>
                                <input type="submit" class="btn btn-primary" name="request" value="REQUEST">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- InfoModal -->
            <div class="modal fade" id="<?php echo $hos?>Modal" tabindex="-1" aria-labelledby="<?php echo $hos?>ModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="<?php echo $hos?>ModalLabel"><?php echo $hospitalsinfo[$item['username']][2]?></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><i class="bi bi-droplet-fill me-1"></i> <?php echo $bloodgroup." (".$available." units)" ?></p>
                    <p><i class="bi bi-geo-alt-fill me-1"></i> <?php echo $hospitalsinfo[$item['username']][3]?></p>
                    <p><i class="bi bi-envelope me-1"></i> <?php echo $hospitalsinfo[$item['username']][4]?></p>
                    <p><i class="bi bi-telephone me-1"></i> <?php echo $hospitalsinfo[$item['username']][5]?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
            </div>
        <?php endforeach;?>
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>