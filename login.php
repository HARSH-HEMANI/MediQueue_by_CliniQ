<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | MediQueue</title>
    <link rel="stylesheet" href="./css/bootstrap/css/bootstrap.css">
    <script src="./css/bootstrap/js/bootstrap.bundle.js"></script>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/login.css">
</head>

<body>

    <main>
        <div class="login">
            <div class="image">
                <img src="" alt="">
            </div>

            <!------------------ Form starts ------------------------>
            <form action="patient.php" name="loginForm" id="loginForm" method="post">
                <div class="form">
                    <div class="formdesign" id="select">
                        <label for="dropdown-menu">
                            <!------- user selection ------>
                            <select name="user" id="dropdown-menu">
                                <option value="#">Select</option>
                                <option name="user" value="patient,html">User</option>
                                <option name="user" value="doctor.html">Doctor</option>
                                <option name="user" value="admin.html">Admin</option>
                            </select>
                        </label>

                        <div class="formdesign" id="uusername">
                            <label for="username">User ID</label>
                            <input type="text" name="username" id="username">
                        </div>
                        <div class="formdesign" id="upassword">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password">
                        </div>

                        <div class="button">
                            <button type="submit" class="btn btn-sm btn-primary">Login</button>
                            <a href="forgot_password.php">Forgot password?</a>
                            <a href="registration.php">New Member?</a>
                        </div>
                    </div>
                </div>
            </form>
            <!------- form ends ------>
        </div>
    </main>

</body>

</html>