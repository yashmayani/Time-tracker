<?php
session_start();
include("./config.php");

if (isset($_SESSION['user_id'])) {
    header("Location:dashboard.php"); 
}  

if(isset($_SESSION['message'])) {
    echo '<div style="padding: 10px; background-color: #f2dede; color: #a94442;">'.$_SESSION['message'].'</div>';
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="loginform.css">
   
</head>

<body style="background-color: #f8f9fa;">
    <section class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="card" style="max-width: 410px; width: 700px;">
        <img class="login-logo" src="./site_img/podev logo.png" width='150px' alt="Screenshot">
            <div class="card-body">
                <h1 class=" h1 card-title text-center" ><b class="hey">Log in  to Time Tracker</b></h1>
                <p class=" p card-text text-center" >Hey,Enter your details to get sign in  to  your account</p>

                <form action="login.php" method="POST">
                    <div class="mb-3">
                  
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" 
                            id="exampleInputEmail1" aria-describedby="emailHelp" required>
                    </div>
                    
                    <div class="mb-3">
                            <div class="input-group">
                            <input type="password" name="password" class="form-control"
                                placeholder="Password" id="exampleInputPassword1" required>
                            <span class="input-group-text" id="showPassword">
                                <img src="./site_img/eyes.png" alt="Show Password" id="togglePassword" style="cursor: pointer;">
                            </span>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <button name="login" class=" buttn" style="width: 100%; margin-top:5px; margin-bottom:-5px !important;">Login</button><br/><br/>
                    </div>
                </form>          
            </div> 
        </div> 
    </section> 
 
     <script>
     const togglePassword = document.querySelector('#togglePassword');
     const password = document.querySelector('#exampleInputPassword1');
 
     togglePassword.addEventListener('click', function (e) {
         const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
         password.setAttribute('type', type);
 
         // Change the icon based on password visibility
         if (type === 'password') {
             this.src = './site_img/eyes.png'; // Replace with your eye icon SVG
            this.alt = 'Show Password';
        } else {
            this.src = './site_img/eyes2.png'; // Replace with your closed eye icon SVG
            this.alt = 'Hide Password';
        }
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
</body>

</html>
