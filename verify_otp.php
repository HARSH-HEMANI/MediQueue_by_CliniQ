<?php
session_start();

// If no email in session, redirect to login
if (!isset($_SESSION['verify_email'])) {
    header("Location: ./login.php");
    exit();
}

$email = $_SESSION['verify_email'];
$name  = $_SESSION['verify_name'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP | MediQueue</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
            background:
                radial-gradient(circle at 8% 15%, rgba(255, 90, 95, 0.18) 0%, transparent 40%),
                radial-gradient(circle at 92% 85%, rgba(110, 231, 183, 0.16) 0%, transparent 40%),
                linear-gradient(145deg, #f0f4fd 0%, #fcf7f7 100%);
        }

        .card {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 20px;
            padding: 50px 40px;
            text-align: center;
            max-width: 460px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(31, 38, 135, 0.1);
        }

        .icon {
            width: 70px;
            height: 70px;
            background: rgba(255, 90, 95, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: #FF5A5F;
            margin: 0 auto 20px;
        }

        h2 {
            font-size: 24px;
            color: #1a1a2e;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #777;
            font-size: 14px;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .subtitle span {
            color: #FF5A5F;
            font-weight: 600;
        }

        /* OTP input boxes */
        .otp-inputs {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 24px;
        }

        .otp-inputs input {
            width: 52px;
            height: 58px;
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            outline: none;
            transition: border-color 0.2s;
            color: #1a1a2e;
            background: #fafafa;
        }

        .otp-inputs input:focus {
            border-color: #FF5A5F;
            background: #fff5f5;
        }

        .btn {
            width: 100%;
            padding: 14px;
            background: #FF5A5F;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn:hover {
            background: #e04e53;
        }

        .resend {
            margin-top: 20px;
            font-size: 13px;
            color: #888;
        }

        .resend a {
            color: #FF5A5F;
            text-decoration: none;
            font-weight: 600;
        }

        .resend a:hover {
            text-decoration: underline;
        }

        .error-msg {
            background: #ffe5e5;
            color: #c0392b;
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 13px;
        }

        .logo {
            font-size: 13px;
            color: #aaa;
            margin-top: 24px;
        }

        .logo span {
            color: #FF5A5F;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <div class="card">
        <div class="icon"><i class="fas fa-envelope-open-text"></i></div>
        <h2>Check Your Email</h2>
        <p class="subtitle">We sent a 6-digit verification code to<br><span><?php echo htmlspecialchars($email); ?></span></p>

        <?php if (isset($_SESSION['otp_error'])): ?>
            <div class="error-msg"><?php echo $_SESSION['otp_error'];
                                    unset($_SESSION['otp_error']); ?></div>
        <?php endif; ?>

        <form action="./verify_action.php" method="POST">
            <div class="otp-inputs">
                <input type="text" name="otp1" maxlength="1" id="otp1">
                <input type="text" name="otp2" maxlength="1" id="otp2">
                <input type="text" name="otp3" maxlength="1" id="otp3">
                <input type="text" name="otp4" maxlength="1" id="otp4">
                <input type="text" name="otp5" maxlength="1" id="otp5">
                <input type="text" name="otp6" maxlength="1" id="otp6">
            </div>
            <button type="submit" class="btn" name="submit">Verify & Activate Account</button>
        </form>

        <p id="timer" style="margin-top:10px; color:#FF5A5F; font-weight:600;">
            OTP expires in 60 seconds
        </p>
        <p class="resend">Didn't receive the code? <a href="./resend_otp.php">Resend OTP</a></p>
        <p class="logo">Powered by <span>MediQueue</span> by CliniQ</p>
    </div>

    <script>
        // Auto-jump to next input box
        const inputs = document.querySelectorAll('.otp-inputs input');
        inputs.forEach((input, i) => {
            input.addEventListener('input', () => {
                if (input.value.length === 1 && i < inputs.length - 1) {
                    inputs[i + 1].focus();
                }
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && input.value === '' && i > 0) {
                    inputs[i - 1].focus();
                }
            });
        });

        // Auto-focus first input
        inputs[0].focus();
    </script>

    <script>
        let timeLeft = 60;
        const timer = document.getElementById("timer");

        const countdown = setInterval(() => {
            timeLeft--;

            timer.textContent = "OTP expires in " + timeLeft + " seconds";

            if (timeLeft <= 0) {
                clearInterval(countdown);
                timer.textContent = "OTP expired!";
                timer.style.color = "red";
            }
        }, 1000);

        const btn = document.querySelector("button");

        if (timeLeft <= 0) {
            btn.disabled = true;
        }
    </script>
</body>

</html>