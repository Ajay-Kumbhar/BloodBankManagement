<?php include 'dbconnection/database.php'?>
<?php session_start()?>
<?php
    if(isset($_SESSION['username'])){
        $username = filter_var($_SESSION['username']);
        $query = "SELECT * FROM hospital WHERE username='$username'";
        $result = mysqli_query($conn, $query);
        $hospitalinfo = mysqli_fetch_all($result, MYSQLI_ASSOC);

        $bloodarr = [];
        $bloodgrouplist = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
        foreach($bloodgrouplist as $bloodgroup){
            $query = "SELECT amount FROM bloodbank WHERE username='$username' AND bloodgroup='$bloodgroup'";
            $result = mysqli_query($conn, $query);
            $amountinfo = mysqli_fetch_all($result, MYSQLI_ASSOC);
            if(!count($amountinfo)){
                $bloodarr[$bloodgroup] = 0;
            }else{
                $bloodarr[$bloodgroup] = $amountinfo[0]['amount'];
            }
        }
    }else{
        header('Location: login.php');
    }

    function color_change($bg){
        global $bloodarr;
        if($bloodarr[$bg]==0){
            $colorclasses = "text-danger border border-danger bg-danger-subtle";
        }else if($bloodarr[$bg]>0 && $bloodarr[$bg]<5){
            $colorclasses = "text-warning border border-warning bg-warning-subtle";
        }else{
            $colorclasses = "text-success border border-success bg-success-subtle";
        }
        return $colorclasses;
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
    <style>
        .edit:hover{
            background-color:#6c757d;
        }
        .edit:hover>button{
            display:block !important;
            color:white;
        }
        .edit:hover>div, .edit:hover>span{
            display:none !important;
        }
    </style>
</head>
<body>
    <nav class="border-bottom" style="background-color:#ebedec">
        <div class="container pt-4 pb-3 d-flex flex-sm-row flex-column justify-content-between align-items-sm-center px-0">
            <a href="index.php" class="navbar-brand d-flex align-items-center ps-2 ps-sm-0"><i class="bi bi-droplet-fill text-danger fs-2"></i><span class="fs-3 ms-2">Life Blood</span></a>
            <div class="d-flex align-items-center justify-content-start ps-3 ps-sm-0 mt-sm-0 mt-3">
                <a href="availablesamples.php" class="nav-link d-inline me-5">Available Blood Samples</a>
                <div class="dropdown">
                    <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person"></i> <?php echo $username?>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="logout.php">Log out</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <div class="container text-end pt-4">
        <a class="btn btn-outline-secondary" href="viewrequests.php" role="button">View Requests</a>
    </div>
    <main class="container d-flex flex-wrap mt-5 justify-content-center">
        <?php foreach($bloodgrouplist as $bloodgroup):?>

            <?php
                $len = strlen($bloodgroup)-1;
                $bg = substr($bloodgroup, 0, $len);
                $last = $bloodgroup[-1];
                $c = $last=='+'?'plus':'minus';
                $bg = $bg.$c;
            ?>

            <div class="edit border border-secondary d-flex align-items-center justify-content-center rounded m-3 position-relative" style="width:160px; height:160px">
                <button class="d-none btn" data-bs-toggle="modal" data-bs-target="#<?php echo $bg?>Modal"><i class="bi bi-pencil-square fs-2"></i></button>
                <div class="d-flex align-items-center justify-content-center"><p class="fw-light fs-1 text-center"><?php echo $bloodgroup?></p></div>
                <span class="position-absolute right-0 bottom-0 w-100 text-end p-2"><span class="rounded-pill <?php echo color_change($bloodgroup)?> px-2 py-1 small"><?php echo $bloodarr[$bloodgroup]." units"?></span><span>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="<?php echo $bg?>Modal" tabindex="-1" aria-labelledby="<?php echo $bg?>ModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="<?php echo $bg?>ModalLabel">Modal title</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="editsamples.php" method="POST">
                            <div class="modal-body">
                                    <label for="bloodgroup" class="form-label">Blood Group</label>
                                    <input type="text" readonly class="form-control" id="bloodgroup" name="bloodgroup" value="<?php echo $bloodgroup?>">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input class="form-control" id="amount" name="amount" type="number" placeholder="<?php echo $bloodarr[$bloodgroup]?>" value="<?php echo $bloodarr[$bloodgroup]?>" min="0" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <input type="submit" class="btn btn-primary" name="save" value="Save Changes">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>