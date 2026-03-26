<?php
/**
 * Run this once to add prescription columns to consultation_notes table.
 */
include "db.php";

$queries = [
    "ALTER TABLE consultation_notes ADD COLUMN IF NOT EXISTS diagnosis VARCHAR(255) AFTER appointment_id;",
    "ALTER TABLE consultation_notes ADD COLUMN IF NOT EXISTS medicines TEXT AFTER note_text;",
    "ALTER TABLE consultation_notes ADD COLUMN IF NOT EXISTS follow_up_date DATE AFTER medicines;"
];

$success = true;
foreach ($queries as $q) {
    if (!mysqli_query($con, $q)) {
        echo "Error: " . mysqli_error($con) . "<br>";
        $success = false;
    }
}

if ($success) {
    echo "Successfully updated consultation_notes table.<br>";
}
?>
