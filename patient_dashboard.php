<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Patient Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/patient.css">

</head>

<body>

<!-- NAVBAR -->

<nav class="navbar navbar-expand-lg navbar-light fixed-top" id="nav-home">
<div class="container-fluid px-4">

<a class="navbar-brand" href="index.php">
<img src="./img/mediq.png" style="height:60px;">
</a>

<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="nav">

<ul class="navbar-nav mx-auto">
<li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
<li class="nav-item"><a class="nav-link" href="#">Doctor/Hospital</a></li>
<li class="nav-item"><a class="nav-link" href="#">About</a></li>
<li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
<li class="nav-item"><a class="nav-link" href="#">FAQs</a></li>
</ul>

</div>
</div>
</nav>

<div class="dashboard-wrapper">

    <!-- SIDEBAR -->
    <div class="dash-sidebar">
        <h4>Patient Panel</h4>

        <div onclick="openTab('profile')" class="dash-link">Profile</div>
        <div onclick="openTab('appointments')" class="dash-link">Manage Appointments</div>
        <div onclick="openTab('history')" class="dash-link">History</div>

        <a href="logout.php" class="dash-logout">Logout</a>
    </div>

    <!-- MAIN CONTENT -->
    <div class="dash-main">

        <!-- PROFILE -->
        <div id="profile" class="dash-tab active">
            <h2>My Profile</h2><br><br>

        <p>Name : Garvi Jagani</p>
        <p>Email : gjagani275@rku.ac.in</p>
        <p>Phone number : 8160211010 </p>
        <p>Age : 18</p>
        <p>BirthDate : 02/08/2007</p><br>

        <a href="update_profile.php" class="hero-btn">Update Profile</a>
        </div>

        <!-- APPOINTMENTS -->
        <div id="appointments" class="dash-tab">

            <h2>Manage Appointments</h2>

            <a href="book_appointment.php" class="hero-btn">Book Appointment</a>
            <a href="#" class="hero-btn">Edit appointment</a>
            <a href="#" class="hero-btn">Delete appointment</a>

  

        </div>

        <!-- HISTORY -->
        <div id="history" class="dash-tab">

            <h2>History</h2>

            <table>
                <tr><th>Date</th><th>Doctor</th><th>Status</th></tr>
                <tr><td>2025-11-10</td><td>Dr John</td><td>Completed</td></tr>
            </table>

        </div>

    </div>

</div>

<script>
function openTab(tab){
    document.querySelectorAll('.dash-tab').forEach(t=>t.classList.remove('active'));
    document.getElementById(tab).classList.add('active');
}
</script>
    <?php include "./footer.php"; ?>
</body>
