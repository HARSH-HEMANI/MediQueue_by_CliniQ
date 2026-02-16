<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | MediQueue</title>

  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="./css/login.css">
</head>

<body>
  <div class="container" id="container">

    <!-- LOGIN -->
    <div class="form login">
      <h2>Login</h2>
      <form action="" method="post" id="loginform">
        <input type="text" placeholder="Email" name="email" id="email" data-validation="required|email">
        <small id="email_error"></small>
        <input type="password" name="password" placeholder="Password" data-validation="required|min" data-min="6">
        <small id="password_error"></small>
        <a href="./forgot_password.php" class="forgot">Forgot Password?</a>
        <button>Login</button>
      </form>
    </div>

    <!-- REGISTER (UPDATED) -->
    <section id="register">
      <div class="form register" id="regform">
        <h2>Register</h2>

        <form id="registerForm">

          <input type="text"
            name="firstName"
            placeholder="First Name"
            data-validation="required|min"
            data-min="2">
          <small id="firstName_error"></small>

          <input type="text"
            name="lastName"
            placeholder="Last Name"
            data-validation="required|min"
            data-min="3">
          <small id="lastName_error"></small>

          <input type="email"
            name="regEmail"
            placeholder="Email"
            data-validation="required|email">
          <small id="regEmail_error"></small>

          <input type="date"
            name="dob"
            data-validation="required">
          <small id="dob_error"></small>

          <input type="tel"
            name="phone"
            placeholder="Phone Number"
            data-validation="required|number|min|max"
            data-min="10"
            data-max="10">
          <small id="phone_error"></small>

          <select name="gender"
            data-validation="required|select">
            <option value="">Select Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
          </select>
          <small id="gender_error"></small>

          <textarea name="address"
            placeholder="Address"
            data-validation="required|min"
            data-min="10"></textarea>
          <small id="address_error"></small>

          <input type="password"
            name="regPassword"
            id="regPassword"
            placeholder="Password"
            data-validation="required|strongPassword">
          <small id="regPassword_error"></small>

          <input type="password"
            name="confirmPassword"
            placeholder="Confirm Password"
            data-validation="required|confirmPassword"
            data-match="regPassword">
          <small id="confirmPassword_error"></small>

          <button type="submit">Register</button>

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
    if (window.location.hash === "#register") {
      container.classList.add("active");

      title.innerText = "Welcome Back!";
      text.innerText = "Already have an account?";
      toggle.innerText = "Login";
      login = false;
    }
  </script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="./js/validation.js"></script>

</body>

</html>