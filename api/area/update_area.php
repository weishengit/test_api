<?php
// Check Request Method
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    exit(json_encode(['message' => 'Invalid Request']));
}

// Check Parameters
if(!isset($_POST['id'])) {
    exit(json_encode(['message' => 'id is required']));
}

if (!isset($_POST['name'])) {
    exit(json_encode(['message' => 'name is required']));
}

// Check If Area Exists
require '../../core/Database.php';
require '../../helpers/exist_helper.php';
if (!checkExist($conn, 'areas', 'id', $_POST['id'])) {
    exit(json_encode(['message' => 'Not found']));
}

// Check Unique Columns
require '../../helpers/duplicate_helper.php';
if (checkUnique($conn, 'areas', 'name', $_POST['name'])) {
    exit(json_encode(['message' => 'Name is already used']));
}

// Update Area
try {
    // Update Table
    $id = $_POST['id'];
    $name = $_POST['name'];
    $sql = 'UPDATE areas SET name = ?, updated_at = ? WHERE id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $name, $date, $id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->affected_rows < 1) {
        $res = ['message' => 'Failed'];
        exit(json_encode($res));
    }

    // Return Data
    $sql = "SELECT id, name, created_at, updated_at FROM areas WHERE id = ?";
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