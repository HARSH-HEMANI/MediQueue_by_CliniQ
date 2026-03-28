<?php

if (!function_exists('doctor_patient_age')) {
    function doctor_patient_age($dob)
    {
        if (empty($dob)) {
            return null;
        }
        $d = DateTime::createFromFormat('Y-m-d', $dob);
        if (!$d) {
            return null;
        }
        return (new DateTime())->diff($d)->y;
    }
}

if (!function_exists('doctor_appt_type_label')) {
    function doctor_appt_type_label($t)
    {
        if ($t === 'Follow Up') {
            return 'Follow-up';
        }
        return $t ?: '—';
    }
}
