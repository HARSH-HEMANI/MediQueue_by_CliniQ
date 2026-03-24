<?php
/**
 * Run this once to add schedule & appointment preference columns to doctors table.
 */
include "db.php";

$alterations = [
    "ALTER TABLE doctors ADD COLUMN IF NOT EXISTS start_time TIME DEFAULT NULL",
    "ALTER TABLE doctors ADD COLUMN IF NOT EXISTS end_time TIME DEFAULT NULL",
    "ALTER TABLE doctors ADD COLUMN IF NOT EXISTS break_time INT DEFAULT 0",
    "ALTER TABLE doctors ADD COLUMN IF NOT EXISTS max_patients_online INT DEFAULT 0",
    "ALTER TABLE doctors ADD COLUMN IF NOT EXISTS max_patients_offline INT DEFAULT 0",
    "ALTER TABLE doctors ADD COLUMN IF NOT EXISTS allow_walkins TINYINT(1) DEFAULT 0",
    "ALTER TABLE doctors ADD COLUMN IF NOT EXISTS allow_emergency TINYINT(1) DEFAULT 0",
];

echo "<h3>Setting up doctor preference columns...</h3>";

foreach ($alterations as $sql) {
    if (mysqli_query($con, $sql)) {
        echo "<p style='color:green;'>✅ OK: " . htmlspecialchars($sql) . "</p>";
    } else {
        $err = mysqli_error($con);
        // Column may already exist in some MySQL versions without IF NOT EXISTS support
        if (strpos($err, 'Duplicate column') !== false) {
            echo "<p style='color:orange;'>⚠️ Already exists - skipped</p>";
        } else {
            echo "<p style='color:red;'>❌ Error: " . htmlspecialchars($err) . "</p>";
        }
    }
}

echo "<br><a href='doctor/login.php'>Go to Doctor Login →</a>";
