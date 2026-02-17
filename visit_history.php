<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Patient Dashboard</title>

<link rel="stylesheet" href="../bootstrap.css/bootstrap.css.css">
<link rel="stylesheet" href="css/style_mq.css">
<link rel="stylesheet" href="css/reception_mq.css">

<script src="../bootstrap.js/bootstrap.bundle.js"></script>
<!-- <style>

body{
  background: #f2f4f8;
}

h2 {
  color: black;
  font-size: xx-large;
  font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
}
span {
  color: #FF5A5F;
}

/* full body center */
.container{
  padding: 30px;
  align-items: center;
}

.card{
  background: white;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0,0,0,.1);
}
.card:hover{
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

/* Button */
.btn-primary{
    background-color: #08306b;
    border: none;
    border-radius: 8px;
    padding: 8px 20px;
}

.btn-primary:hover{
    background-color: #ff8387dd;
    color: black;
}
</style> -->
</head>
<body>
<?php include './includes/patient-sidebar.php'; ?>
<br><br><br>
    
<div class="container">

<h2>Visit <span>History</span></h2><br><br>

<!-- Filter Section -->
    <div class="card shadow border-0 mb-4 p-3">
        <form method="GET" action="ma.php" class="row g-3">
            <div class="col-md-3">
                <label for="city" class="form-label">City</label>
                <select name="city" id="city" class="form-select">
                    <option value="">Select City</option>
                    <option value="Delhi">Delhi</option>
                    <option value="Mumbai">Mumbai</option>
                    <option value="Bangalore">Bangalore</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="hospital" class="form-label">Hospital</label>
                <select name="hospital" id="hospital" class="form-select">
                    <option value="">Select Hospital</option>
                    <option value="Apollo">Apollo</option>
                    <option value="Fortis">Fortis</option>
                    <option value="Max">Max</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="department" class="form-label">Department</label>
                <select name="department" id="department" class="form-select">
                    <option value="">Select Department</option>
                    <option value="General">General</option>
                    <option value="Cardiology">Cardiology</option>
                    <option value="Neurology">Neurology</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="doctor" class="form-label">Doctor</label>
                <select name="doctor" id="doctor" class="form-select">
                    <option value="">Select Doctor</option>
                    <option value="Dr. Sharma">Dr. Sharma</option>
                    <option value="Dr. Mehta">Dr. Mehta</option>
                    <option value="Dr. Patel">Dr. Patel</option>
                </select>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Show Appointments</button>
            </div>
        </form>
    </div>

<div class="card shadow border-0">
    <table class="table table-hover mb-0">
        <thead class="table-success">
            <tr>
                <th>Date</th>
                <th>Doctor</th>
                <th>Department</th>
                <th>City</th>
                <th>Hospital</th>
                <th>Hospital Address</th>
                <th>Diagnosis</th>
                <th>Report</th>
            </tr>
        </thead>
        
        <tbody>
            <tr>
                <td>12-01-2025</td>
                <td>Dr. Sharma</td>
                <td>General</td>
                <td>Delhi</td>
                <td>City Hospital</td>
                <td>123 MG Road, Delhi</td>
                <td>Fever</td>
                <td><a href="#" class="btn btn-sm btn-outline-primary">View</a></td>
            </tr>
        
            <tr>
                <td>02-12-2024</td>
                <td>Dr. Mehta</td>
                <td>Cardiology</td>
                <td>Mumbai</td>
                <td>Heart Care Clinic</td>
                <td>45 Marine Drive, Mumbai</td>
                <td>BP Issue</td>
                <td><a href="#" class="btn btn-sm btn-outline-primary">View</a></td>
            </tr>
            
            <tr>
                <td>15-11-2024</td>
                <td>Dr. Patel</td>
                <td>Orthopedic</td>
                <td>Bangalore</td>
                <td>Bone & Joint Center</td>
                <td>78 MG Road, Bangalore</td>
                <td>Back Pain</td>
                <td><a href="#" class="btn btn-sm btn-outline-primary">View</a></td>
            </tr>
        </tbody>
    </table>
</div>

</div>

</body>
</html>