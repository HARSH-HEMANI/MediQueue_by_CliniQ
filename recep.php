<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Receptionist Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="./css/style.css">
<link rel="stylesheet" href="./css/recp_dashboard.css">

</head>
<body>

<!-- NAVBAR  -->

<header>
<nav class="navbar navbar-expand-lg navbar-light fixed-top" id="nav-home">
<div class="container-fluid px-4">

<a class="navbar-brand" href="#">
<img src="./img/mediq.png" style="height:60px;">
</a>

<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="menu">

<ul class="navbar-nav mx-auto">
    <li class="nav-item"><a class="nav-link">Home</a></li>
    <li class="nav-item"><a class="nav-link">Doctor</a></li>
    <li class="nav-item"><a class="nav-link active">Hospital</a></li>
    <li class="nav-item"><a class="nav-link">About</a></li>
    <li class="nav-item"><a class="nav-link">Contact</a></li>
    <li class="nav-item"><a class="nav-link">FAQ</a></li>
</ul>

</div>
</div>
</nav>
</header>

<div class="container-fluid my-4">

<h3>Receptionist Dashboard</h3>
<p class="text-muted">Welcome Receptionist</p>

<!-- SEARCH + WALKIN -->
<div class="row mb-3">

<div class="col-md-4">
<input class="form-control" placeholder="Search patient">
</div>

<div class="col-md-3">
<input type="date" class="form-control">
</div>

<div class="col-md-5 text-end">
<button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#walkin">
➕ Walk-In
</button>
</div>

</div>

<!-- STATS -->

<div class="row mb-4">

<div class="col-md-3">
<div class="feature-card animated-card text-center">
<h6>Today</h6>
<h3>12</h3>
</div>
</div>

<div class="col-md-3">
<div class="feature-card animated-card text-center">
<h6>Pending</h6>
<h3>4</h3>
</div>
</div>

<div class="col-md-3">
<div class="feature-card animated-card text-center">
<h6>Completed</h6>
<h3>6</h3>
</div>
</div>

<div class="col-md-3">
<div class="feature-card animated-card text-center">
<h6>Cancelled</h6>
<h3>1</h3>
</div>
</div>

</div>

<!-- TABLE -->

<div class="card dashboard-card shadow-lg">

<div class="card-body table-responsive">

<table class="table table-hover">

<thead>
<tr>
<th>No.</th>
<th>Name</th>
<th>Phone</th>
<th>Date</th>
<th>Time</th>
<th>Status</th>
<th>Notes</th>
</tr>
</thead>

<tbody>

<tr>
<td>1</td>
<td><a data-bs-toggle="modal" data-bs-target="#patient">Rahul Sharma</a></td>
<td>9876543210</td>
<td>2026-02-05</td>
<td>10:30</td>

<td>
<select class="form-select form-select-sm">
<option>Pending</option>
<option>Completed</option>
<option>Cancelled</option>
</select>
</td>

<td><input class="form-control form-control-sm"></td>
</tr>

<tr>
<td>2</td>
<td>Anita Patel</td>
<td>9123456780</td>
<td>2026-02-05</td>
<td>11:00</td>

<td>
<select class="form-select form-select-sm">
<option>Pending</option>
<option selected>Confirmed</option>
<option>Completed</option>
<option>Cancelled</option>
</select>
</td>

<td><input class="form-control form-control-sm"></td>
</tr>

</tbody>

</table>

</div>
</div>

</div>

<!-- WALKIN MODAL -->

<div class="modal fade" id="walkin">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
<h5>Add Walk-In</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<input class="form-control mb-2" placeholder="Name">
<input class="form-control mb-2" placeholder="Phone">
<input type="date" class="form-control mb-2">
<input type="time" class="form-control mb-2">

<button class="btn btn-success w-100">Save</button>

</div>

</div>
</div>
</div>

<!-- PATIENT MODAL -->

<div class="modal fade" id="patient">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
<h5>Patient Details</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<p>Name: Rahul Sharma</p>
<p>Phone: 9876543210</p>
<p>Visits: 2</p>
</div>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
