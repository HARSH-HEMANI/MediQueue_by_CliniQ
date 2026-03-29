<?php
/**
 * Shared queue/dashboard data for logged-in doctor. Expects $con (db.php) and $_SESSION['doctor_id'].
 * Sets: $queue_date, $doctor_id, $queue_stats, $current_token, $waiting_rows, $avg_consult_min, $emergency_waiting_rows
 */
require_once __DIR__ . '/doctor-helpers.inc.php';

if (!isset($con) || !isset($_SESSION['doctor_id'])) {
    return;
}

$doctor_id = (int) $_SESSION['doctor_id'];

$raw_date = $_GET['date'] ?? date('Y-m-d');
$queue_date = preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $raw_date) ? $raw_date : date('Y-m-d');
$esc_date = mysqli_real_escape_string($con, $queue_date);

$queue_stats = [
    'total_tokens'      => 0,
    'waiting'           => 0,
    'in_progress'       => 0,
    'completed'         => 0,
    'skipped'           => 0,
    'emergency_pending' => 0,
];

$sq = mysqli_query($con, "SELECT 
    COUNT(*) AS total_tokens,
    COALESCE(SUM(CASE WHEN t.status='Waiting' THEN 1 ELSE 0 END),0) AS waiting,
    COALESCE(SUM(CASE WHEN t.status='In Progress' THEN 1 ELSE 0 END),0) AS in_progress,
    COALESCE(SUM(CASE WHEN t.status='Completed' THEN 1 ELSE 0 END),0) AS completed,
    COALESCE(SUM(CASE WHEN t.status='Skipped' THEN 1 ELSE 0 END),0) AS skipped
FROM tokens t
INNER JOIN appointments a ON t.appointment_id = a.appointment_id
WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$esc_date'");

if ($sq && ($row = mysqli_fetch_assoc($sq))) {
    foreach (['total_tokens', 'waiting', 'in_progress', 'completed', 'skipped'] as $k) {
        $queue_stats[$k] = (int) ($row[$k] ?? 0);
    }
}

$eq = mysqli_query($con, "SELECT COUNT(*) AS c FROM tokens t
INNER JOIN appointments a ON t.appointment_id = a.appointment_id
WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$esc_date'
AND a.appointment_type = 'Emergency' AND t.status = 'Waiting'");
if ($eq && ($r = mysqli_fetch_assoc($eq))) {
    $queue_stats['emergency_pending'] = (int) $r['c'];
}

$current_token = null;
$res = mysqli_query($con, "SELECT t.token_id, t.token_no, t.status, t.called_at,
    a.appointment_id, a.appointment_type, a.visit_reason,
    p.patient_id, p.full_name, p.date_of_birth, p.gender, p.phone
FROM tokens t
INNER JOIN appointments a ON t.appointment_id = a.appointment_id
INNER JOIN patients p ON a.patient_id = p.patient_id
WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$esc_date' AND t.status = 'In Progress'
LIMIT 1");
if ($res && mysqli_num_rows($res)) {
    $current_token = mysqli_fetch_assoc($res);
}

$waiting_rows = [];
$wq = mysqli_query($con, "SELECT t.token_id, t.token_no, t.queue_position, t.status,
    a.appointment_id, a.appointment_type, a.appointment_time,
    p.patient_id, p.full_name, p.date_of_birth, p.gender
FROM tokens t
INNER JOIN appointments a ON t.appointment_id = a.appointment_id
INNER JOIN patients p ON a.patient_id = p.patient_id
WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$esc_date' AND t.status = 'Waiting'
ORDER BY (a.appointment_type = 'Emergency') DESC, t.queue_position ASC");
if ($wq) {
    while ($row = mysqli_fetch_assoc($wq)) {
        $waiting_rows[] = $row;
    }
}

$emergency_waiting_rows = array_values(array_filter($waiting_rows, function ($r) {
    return ($r['appointment_type'] ?? '') === 'Emergency';
}));

$avg_consult_min = null;
$aq = mysqli_query($con, "SELECT AVG(TIMESTAMPDIFF(MINUTE, t.called_at, t.completed_at)) AS avg_m
FROM tokens t
INNER JOIN appointments a ON t.appointment_id = a.appointment_id
WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$esc_date'
AND t.status = 'Completed' AND t.called_at IS NOT NULL AND t.completed_at IS NOT NULL");
if ($aq && ($ar = mysqli_fetch_assoc($aq)) && $ar['avg_m'] !== null) {
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

    $avg_consult_min = max(0, (int) round((float) $ar['avg_m']));
}

if (!function_exists('doctor_token_status_badge')) {
    function doctor_token_status_badge($status)
    {
        $map = [
            'Waiting'     => 'bg-warning',
            'In Progress' => 'bg-success',
            'Completed'   => 'bg-secondary',
            'Skipped'     => 'bg-info text-dark',
        ];
        $cls = $map[$status] ?? 'bg-secondary';
        return '<span class="badge ' . htmlspecialchars($cls) . '">' . htmlspecialchars($status) . '</span>';
    }
}
