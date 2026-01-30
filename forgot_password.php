<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | MediQueue</title>
    <link rel="stylesheet" href="./css/bootstrap/css/bootstrap.css">
    <script src="./css/bootstrap/js/bootstrap.bundle.js"></script>
    <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/forgot_password.css">
</head>

<body class="auth-body">
    <div class="auth-container">
        <div class="auth-card">

            <!-- LEFT : FORM -->
            <div class="auth-left">
                <h2>Forgot Password</h2>
                <p class="auth-subtext">
                    Enter your registered email address and we'll send you instructions to reset your password.
                </p>

                <form>
                    <div class="mb-3">
                        <label>Email Address</label>
                        <input type="email" class="form-control auth-input" placeholder="Enter your email">
                    </div>

                    <button type="submit" class="btn auth-btn w-100">
                        Send Reset Link
                    </button>
                </form>

                <div class="auth-links">
                    <a href="login.php">Back to Login</a>
                </div>
            </div>

            <!-- RIGHT : INFO PANEL -->
            <div class="auth-right">
                <h2>Hello, Friend!</h2>
                <p>
                    Remembered your password?<br>
                    Login to continue managing appointments seamlessly.
                </p>
                <a href="login.php" class="btn auth-outline-btn">
                    Login
                </a>
            </div>

        </div>
    </div>

</body>

</html>