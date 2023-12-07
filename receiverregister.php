<?php include "dbconnection/database.php"?>
<?php session_start()?>
<?php 

    if(isset($_POST['register'])){
        $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $name = filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $age = filter_var($_POST['age'], FILTER_SANITIZE_NUMBER_INT);
        $gender = filter_var($_POST['gender'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $bloodgroup = filter_var($_POST['bloodgroup'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $number = filter_var($_POST['number'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $alert = 0;
        $query = "SELECT username FROM receiver WHERE username='$username'";
        $result = mysqli_query($conn, $query);
        $receivers = mysqli_fetch_all($result, MYSQLI_ASSOC);
        if(!count($receivers)){
            $query = "INSERT INTO receiver VALUES ('$username', '$password', '$name', '$age', '$gender', '$bloodgroup', '$email', '$number')";

            if(mysqli_query($conn, $query)){
                $_SESSION['user'] = $username;
                $_SESSION['as'] = 'receiver';
                header('Location: receiverdashboard.php');
            }else{
                echo "Error occured". mysqli_error($conn);
            }
        }else{
            $alert = 1;
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
        <div class="alert alert-danger col-10 col-lg-8 mx-auto <?php echo $alert==0?'d-none':'d-block'?>" role="alert">
            Username already exists
        </div>
        <div class="card col-10 col-lg-8 mx-auto my-3">
            <div class="card-body p-4">
              <h4 class="card-title mb-5 border-bottom pb-3">Receiver Registration</h4>
              <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST">
                <div class="d-flex align-items-center">
                    <div class="w-50">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="name" required>
                    </div>
                    <div class="ms-5 w-50">
                        <label class="form-label">Gender</label>
                        <div class="d-flex">
                            <div class="w-50">
                                <label for="male" class="form-label">Male</label>
                                <input type="radio" id="male" name="gender" value="male" class="form-check-input" checked>
                            </div>
                            <div class="w-50">
                                <label for="female" class="form-label">Female</label>
                                <input type="radio" id="female" name="gender" value="female" class="form-check-input">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-5">
                    <div class="flex-grow-1">
                        <label for="age" class="form-label">Age</label>
                        <input type="number" class="form-control" name="age" id="age" min="1" max="100" required>
                    </div>
                    <div class="ms-5 flex-grow-1">
                        <label for="bloodgroup" class="form-label d-block">Blood Group</label>
                        <select class="form-select" name="bloodgroup" id="bloodgroup">
                            <option value="A+" selected>A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-5">
                    <div class="flex-grow-1">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>
                    <div class="ms-5 flex-grow-1">
                        <label for="number" class="form-label">Mobile Number</label>
                        <input type="text" class="form-control" name="number" id="number" pattern="[0-9]{10}" title="Number must be numeric and of length 10" required/>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-5">
                    <div class="flex-grow-1">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" id="username" required>
                    </div>
                    <div class="ms-5 flex-grow-1">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,16}" title="8 to 16 characters which contain at least one lowercase letter, one uppercase letter, one numeric digit, and one special character" required>
                    </div>
                </div>
                <input type="submit" value="Register" name="register" class="mt-4 btn btn-outline-primary">
              </form>
              <p class="mt-4">Already have an account? <a href="login.php" class="link opacity-75" style="cursor:pointer;">Login</a></p>
            </div>
        </div>
    </main>
</body>
</html>