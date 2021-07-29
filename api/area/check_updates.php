<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    exit(json_encode(['message' => 'Invalid Request']));
}

// Check Parameters
if (!isset($_POST['rows'])) {
    exit(json_encode(['message' => 'Empty Fields']));
}

// Check Unique Columns
require '../../core/Database.php';
require '../../helpers/new_records_helper.php';
$value = $_POST['rows'];
$sql = "SELECT COUNT(id) FROM properties";
$stmt = $conn->prepare($sql);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($total_rows);
$stmt->fetch();
if ($total_rows == $value) {
    exit(json_encode(['status' => false, 'message'=> "No Changes: Prev:$value New:$total_rows"]));
}
$stmt->close();

exit(json_encode(['status' => true, 'message'=> "Database Changed: Prev:$value New:$total_rows"]));