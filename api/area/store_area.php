<?php
// Check Request Method
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    exit(json_encode(['message' => 'Invalid Request']));
}

// Check Parameters
if (!isset($_POST['name'])) {
    exit(json_encode(['message' => 'Name is required']));
}

// Check Unique Columns
require '../../core/Database.php';
require '../../helpers/duplicate_helper.php';
if (checkUnique($conn, 'areas', 'name', $_POST['name'])) {
    exit(json_encode(['message' => 'Name is already used']));
}

// Store Area
try {
    // Insert Area
    $name = $_POST['name'];
    $sql = 'INSERT INTO areas (name, created_at, updated_at) VALUES (?, ?, ?)';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $name, $date, $date);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->affected_rows < 1) {
        $res = ['message' => 'Failed'];
        exit(json_encode($res));
    }

    // Return Data
    $id = $stmt->insert_id;
    $sql = "SELECT `id`, `name`, `created_at`, `updated_at` FROM `areas` WHERE `id` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $name, $created_at, $updated_at);
    $stmt->fetch();
    $data = ['id' => $id, 'name' => $name, 'created_at' => $created_at, 'updated_at' => $updated_at];
    $stmt->close();

    exit(json_encode(['message' => 'Success', 'data' => $data]));

} catch (\Throwable $th) {
    exit(json_encode(['message' => 'An error occurred', 'error' => $th->getMessage()]));
}