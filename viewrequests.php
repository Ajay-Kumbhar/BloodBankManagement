<?php include 'dbconnection/database.php'?>
<?php session_start()?>
<?php
    $hospitalname = $_SESSION['username'];
    $query = "SELECT * FROM requests where hospitalname='$hospitalname'";
    $result = mysqli_query($conn, $query);
    $bloodbankinfo = mysqli_fetch_all($result, MYSQLI_ASSOC);

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
        <div class="container pt-4 pb-3 d-flex flex-sm-row flex-column justify-content-between align-items-sm-center px-0">
            <a href="index.php" class="navbar-brand d-flex align-items-center ps-2 ps-sm-0"><i class="bi bi-droplet-fill text-danger fs-2"></i><span class="fs-3 ms-2">Life Blood</span></a>
            <div class="d-flex align-items-center justify-content-start ps-3 ps-sm-0 mt-sm-0 mt-3">
                <a href="availablesamples.php" class="nav-link d-inline me-5">Available Blood Samples</a>
                <div class="dropdown">
                    <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person"></i> <?php echo $hospitalname?>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="logout.php">Log out</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <?php
        $display = "";
        $message = "";
        if(isset($_SESSION['request'])){
            if($_SESSION['request']=='invalid'){
                $_SESSION['request']="";
                $message = "Insufficient blood sample";
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
        <?php if(!count($bloodbankinfo)):?>
            <p class="text-center p-3">No Available requests</p>
        <?php endif;?>
        <?php foreach($bloodbankinfo as $item):?>
            <?php 
               $username = $item['receivername'];
               $query = "SELECT * FROM receiver where username='$username'";
               $result = mysqli_query($conn, $query);
               $userinfo = mysqli_fetch_all($result, MYSQLI_ASSOC); 
               $requestedbg = $item['bloodgroup'];
               $query = "SELECT amount FROM bloodbank where username='$hospitalname' AND bloodgroup='$requestedbg'";
               $result = mysqli_query($conn, $query);
               $bloodinfo = mysqli_fetch_all($result, MYSQLI_ASSOC); 
            ?>
            <div class="card m-3" style="width:330px;">
                <div class="card-header fs-5 d-flex align-items-center my-1"><p class="rounded-pill border border-danger-subtle text-danger text-center m-0" style="width:50px;"><?php echo $userinfo[0]['bloodgroup'] ?><p></div>
                <div class="card-body">
                    <p class="card-text fs-6 "><span class="fw-semibold">Name: </span><?php echo $userinfo[0]['name']?></p>
                    <p class="card-text fs-6 "><span class="fw-semibold">Age: </span><?php echo $userinfo[0]['age']?></p>
                    <p class="card-text fs-6 "><span class="fw-semibold">Gender: </span><?php echo ucwords($userinfo[0]['gender'])?></p>
                    <p class="card-text fs-6 "><span class="fw-semibold">Requested: </span><?php echo $requestedbg." (".$item['amount']." units)"?></p>
                    <p class="card-text fs-6 "><span class="fw-semibold">Available: </span><?php echo $bloodinfo[0]['amount']." units"?></p>
                    <p class="card-text fs-6 "><span class="fw-semibold">Time: </span><?php echo $item['time']?></p>
                    <?php if($item['status']=='requested'):?>
                    <form action="acceptrequest.php" class="d-inline" method="POST">
                        <input class="d-none"type="text" name="hospitalname" value="<?php echo $hospitalname?>">
                        <input class="d-none" type="text" name="receivername" value="<?php echo $username?>">
                        <input class="d-none" type="number" name="amount" value="<?php echo $item['amount']?>">
                        <input class="d-none" type="number" name="available" value="<?php echo  $bloodinfo[0]['amount']?>">
                        <input class="d-none" type="text" name="bloodgroup" value="<?php echo $requestedbg?>">
                        <input type="submit" name="Accept" value="Accept" class="btn btn-outline-primary" style="width:100px;">
                    </form>
                    <form action="rejectrequest.php" class="d-inline" method="POST">
                        <input class="d-none"type="text" name="hospitalname" value="<?php echo $hospitalname?>">
                        <input class="d-none" type="text" name="receivername" value="<?php echo $username?>">
                        <input class="d-none" type="number" name="amount" value="<?php echo $item['amount']?>">
                        <input class="d-none" type="number" name="available" value="<?php echo  $bloodinfo[0]['amount']?>">
                        <input class="d-none" type="text" name="bloodgroup" value="<?php echo $requestedbg?>">
                        <input type="submit" name="Reject" value="Reject" class="btn btn-outline-secondary" style="width:100px;">
                    </form>
                    <?php elseif($item['status']=='Accepted'):?>
                        <button class="btn btn-outline-success disabled" style="width:100px;">Accepted</button>
                    <?php else:?>
                        <button class="btn btn-outline-danger disabled" style="width:100px;">Rejected</button>
                    <?php endif;?>
                </div>
            </div>
        <?php endforeach;?>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>