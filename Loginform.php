<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="#" style="color: black"><b>💩</b>File</a>
        </div>
       
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                <form action="login.php" method="post">
                    <div class="input-group mb-3">
                        <input type="text" name="uname" class="form-control" placeholder="Username">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <?php if(isset($_GET['error'])) { ?>
                        <p class="text-danger"><?php echo $_GET['error']; ?></p>
                    <?php } ?>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">Remember Me</label>
                            </div>
                        </div>
                        
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                       
                    </div>
                </form>
            </div>
          
            <div class="card-footer">
                <p class="mb-0">
                    Don't have an account? <a href="register.php" class="text-center">Register Here</a>
                </p>
            </div>
        </div>
    </div>
    
    <script src="dist/js/adminlte.min.js"></script>
</body>
</html>
