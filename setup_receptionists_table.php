<?php
include_once "db.php";

echo "<h3>MediQueue - Receptionists Table Setup</h3>";

// Check if table exists
$table_check = mysqli_query($con, "SHOW TABLES LIKE 'receptionists'");

if (mysqli_num_rows($table_check) > 0) {
    echo "Table 'receptionists' already exists. Checking for missing columns...<br>";

    // Check if doctor_id column exists
    $col_check = mysqli_query($con, "SHOW COLUMNS FROM receptionists LIKE 'doctor_id'");
    if (mysqli_num_rows($col_check) == 0) {
        $alter = "ALTER TABLE receptionists ADD COLUMN doctor_id INT NOT NULL DEFAULT 1 AFTER receptionist_id";
        if (mysqli_query($con, $alter)) {
            echo "✅ Added 'doctor_id' column<br>";

            // Add foreign key
            $fk = "ALTER TABLE receptionists ADD FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id) ON DELETE CASCADE";
            if (mysqli_query($con, $fk)) {
                echo "✅ Added foreign key for doctor_id<br>";
            } else {
                echo "⚠️ Could not add FK for doctor_id (doctors table may not exist yet): " . mysqli_error($con) . "<br>";
            }
        } else {
            echo "❌ Error adding doctor_id: " . mysqli_error($con) . "<br>";
        }
    } else {
        echo "✅ 'doctor_id' column already exists<br>";
    }

    // Check if gender column exists
    $col_check2 = mysqli_query($con, "SHOW COLUMNS FROM receptionists LIKE 'gender'");
    if (mysqli_num_rows($col_check2) == 0) {
        $alter2 = "ALTER TABLE receptionists ADD COLUMN gender ENUM('Male','Female','Other') DEFAULT 'Male' AFTER phone";
        if (mysqli_query($con, $alter2)) {
            echo "✅ Added 'gender' column<br>";
        } else {
            echo "❌ Error adding gender: " . mysqli_error($con) . "<br>";
        }
    } else {
        echo "✅ 'gender' column already exists<br>";
    }

    echo "<br><strong>Done! Table is up to date.</strong>";

} else {
    // Create table fresh
    $q = "CREATE TABLE receptionists (
        receptionist_id INT AUTO_INCREMENT PRIMARY KEY,
        doctor_id INT NOT NULL,
        clinic_id INT NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE,
        password VARCHAR(255) NOT NULL,
        phone VARCHAR(15),
        gender ENUM('Male','Female','Other') DEFAULT 'Male',
        address TEXT,
        is_active BOOLEAN DEFAULT TRUE,
        FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id) ON DELETE CASCADE,
        FOREIGN KEY (clinic_id) REFERENCES clinics(clinic_id) ON DELETE CASCADE
    )";

    if (mysqli_query($con, $q)) {
        echo "✅ Receptionists table created successfully<br>";
    } else {
        echo "❌ Error: " . mysqli_error($con);
    }
}
