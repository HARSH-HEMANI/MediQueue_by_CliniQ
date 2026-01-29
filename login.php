<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Animated Login & Register</title>

  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="./css/login.css">
</head>

<body>

  <div class="container" id="container">

    <!-- LOGIN -->
    <div class="form login">
      <h2>Login</h2>

      <input type="email" placeholder="Email">
      <input type="password" placeholder="Password">

      <a href="#" class="forgot">Forgot Password?</a>
      <button>Login</button>

    </div>

    <!-- REGISTER (UPDATED) -->
    <div class="form register">
      <h2>Register</h2>

      <input type="text" placeholder="Full Name">
      <input type="email" placeholder="Email">
      <input type="date" placeholder="DOB">
      <input type="tel" placeholder="Phone Number">

      <select>
        <option value="">Select Gender</option>
        <option>Male</option>
        <option>Female</option>
        <option>Other</option>
      </select><br><br>

      <textarea placeholder="Address"></textarea>

      <input type="password" placeholder="Password">

      <button>Register</button>
    </div>

    <!-- OVERLAY -->
    <div class="overlay">
      <div class="overlay-content">
        <h1 id="title">Hello, Friend!</h1>
        <p id="text">Don't have an account?</p>
        <button id="toggle">Register</button>
      </div>
    </div>

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
  </script>

</body>

</html>