<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | MediQueue</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="./css/login.css">
  <style>
    /* Toast Popup */
    .toast-popup {
      position: fixed;
      top: 24px;
      right: 24px;
      z-index: 9999;
      min-width: 300px;
      max-width: 420px;
      padding: 16px 20px;
      border-radius: 12px;
      display: flex;
      align-items: flex-start;
      gap: 14px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
      animation: slideIn 0.4s ease;
      font-family: 'Segoe UI', sans-serif;
    }

    .toast-popup.success {
      background: #fff;
      border-left: 5px solid #10b981;
    }

    .toast-popup.error {
      background: #fff;
      border-left: 5px solid #FF5A5F;
    }

    .toast-icon {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
      flex-shrink: 0;
    }

    .toast-popup.success .toast-icon {
      background: rgba(16, 185, 129, 0.12);
      color: #10b981;
    }

    .toast-popup.error .toast-icon {
      background: rgba(255, 90, 95, 0.12);
      color: #FF5A5F;
    }

    .toast-body {
      flex: 1;
    }

    .toast-title {
      font-weight: 700;
      font-size: 15px;
      color: #1a1a2e;
      margin-bottom: 3px;
    }

    .toast-msg {
      font-size: 13px;
      color: #666;
      line-height: 1.5;
    }

    .toast-close {
      background: none;
      border: none;
      color: #aaa;
      font-size: 18px;
      cursor: pointer;
    }

    .toast-close:hover {
      color: #555;
    }

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateX(60px);
      }

      to {
        opacity: 1;
        transform: translateX(0);
      }
    }
  </style>
</head>

<body>

  <?php if (isset($_SESSION['success'])): ?>
    <div class="toast-popup success" id="toast">
      <div class="toast-icon"><i class="fas fa-check"></i></div>
      <div class="toast-body">
        <div class="toast-title">Success!</div>
        <div class="toast-msg"><?php echo $_SESSION['success'];
                                unset($_SESSION['success']); ?></div>
      </div>
      <button class="toast-close" onclick="document.getElementById('toast').remove()">&times;</button>
    </div>
  <?php endif; ?>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="toast-popup error" id="toast">
      <div class="toast-icon"><i class="fas fa-times"></i></div>
      <div class="toast-body">
        <div class="toast-title">Error</div>
        <div class="toast-msg"><?php echo $_SESSION['error'];
                                unset($_SESSION['error']); ?></div>
      </div>
      <button class="toast-close" onclick="document.getElementById('toast').remove()">&times;</button>
    </div>
  <?php endif; ?>

  <div class="container" id="container">

    <!-- LOGIN FORM -->
    <div class="form login">
      <h2>Login</h2>

      <form action="./login_action.php" method="POST" id="loginform">
        <input type="text" placeholder="Email" name="email" id="email"
          data-validation="required|email">
        <small id="email_error"></small>

        <input type="password" name="password" placeholder="Password"
          data-validation="required|min" data-min="6">
        <small id="password_error"></small>

        <a href="./forgot_password.php" class="forgot">Forgot Password?</a>
        <button type="submit" name="submit">Login</button>
      </form>
    </div>

    <!-- REGISTER FORM -->
    <section id="register">
      <div class="form register" id="regform">
        <h2>Register</h2>

        <?php if (isset($_SESSION['reg_error'])): ?>
          <div style="background:#ffe5e5;color:#c0392b;padding:10px 14px;border-radius:8px;margin-bottom:12px;font-size:13px;">
            <?php echo $_SESSION['reg_error'];
            unset($_SESSION['reg_error']); ?>
          </div>
        <?php endif; ?>

        <form action="./register_action.php" method="POST" id="registerForm">

          <input type="text" name="full_name" placeholder="Full Name"
            data-validation="required|min" data-min="2">
          <small id="full_name_error"></small>

          <input type="email" name="email" placeholder="Email"
            data-validation="required|email">
          <small id="email_error"></small>

          <input type="date" name="dob"
            data-validation="required">
          <small id="dob_error"></small>

          <input type="tel" name="phone" placeholder="Phone Number"
            data-validation="required|number|min|max" data-min="10" data-max="10">
          <small id="phone_error"></small>

          <select name="gender" data-validation="required|select">
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
          </select>
          <small id="gender_error"></small>

          <textarea name="address" placeholder="Address"
            data-validation="required|min" data-min="10"></textarea>
          <small id="address_error"></small>

          <input type="number" name="pincode" placeholder="Pincode"
            data-validation="required">
          <small id="pincode_error"></small>

          <input type="password" name="password" id="regPassword" placeholder="Password"
            data-validation="required|strongPassword">
          <small id="regPassword_error"></small>

          <input type="password" name="confirm_password" placeholder="Confirm Password"
            data-validation="required|confirmPassword" data-match="regPassword">
          <small id="confirmPassword_error"></small>

          <button type="submit" name="submit">Register</button>

        </form>
      </div>

      <!-- OVERLAY -->
      <div class="overlay">
        <div class="overlay-content">
          <h1 id="title">Hello, Friend!</h1>
          <p id="text">Don't have an account?</p>
          <button id="toggle">Register</button>
        </div>
      </div>
    </section>

  </div>

  <script>
    const container = document.getElementById("container");
    const toggle = document.getElementById("toggle");
    const title = document.getElementById("title");
    const text = document.getElementById("text");

    let login = true;

    toggle.onclick = () => {
      container.classList.toggle("active");
      if (login) {
        title.innerText = "Welcome Back!";
        text.innerText = "Already have an account?";
        toggle.innerText = "Login";
      } else {
        title.innerText = "Hello, Friend!";
        text.innerText = "Don't have an account?";
        toggle.innerText = "Register";
      }
      login = !login;
    };

    // Auto-show register panel if redirected with #register
    if (window.location.hash === "#register") {
      container.classList.add("active");
      title.innerText = "Welcome Back!";
      text.innerText = "Already have an account?";
      toggle.innerText = "Login";
      login = false;
    }

    // Auto-dismiss toast after 5 seconds
    const toast = document.getElementById('toast');
    if (toast) {
      setTimeout(() => {
        toast.style.animation = 'slideIn 0.4s ease reverse';
        setTimeout(() => toast.remove(), 400);
      }, 5000);
    }
  </script>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="./js/validation.js"></script>

</body>

</html>