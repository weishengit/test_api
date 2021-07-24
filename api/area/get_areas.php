<?php
require '../../core/Database.php';

// Get Areas
try {
    // Return Data
    $id = 5;
    $sql = "SELECT id, name, created_at, updated_at FROM areas";
    $stmt = $conn->prepare($sql);
    // $stmt->bind_param('s', $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows <= 0) {
        exit(json_encode(['message' => 'table is empty']));
    }

    $stmt->bind_result($id, $name, $created_at, $updated_at);
    $index = 0;
    $data = array();
    while($stmt->fetch()) {
        $data[$index] = ['id' => $id, 'name' => $name, 'created_at' => $created_at, 'updated_at' => $updated_at];
        $index++;
    }
    $stmt->close();

    $response = [
        'message' => 'Success', 
        'data' => $data
    ];
    exit(json_encode($response));

} catch (\Throwable $th) {
    exit(json_encode(['message' => 'An error occurred', 'error' => $th->getMessage()]));
}
