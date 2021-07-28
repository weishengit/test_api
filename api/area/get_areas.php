<?php
require '../../core/Database.php';

// Validation
$PAGE = 1;
if (isset($_GET['page'])) {
    if ($_GET['page'] <= 0 || filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT) == false) {
        exit(json_encode(['message' => 'Invalid Page']));
    }
    $PAGE = filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT);
}

// Get Areas
try {
    // Rows Per Page
    $LIMIT = $_GET['rows'] ?? 10;
    // Set Offset
    $OFFSET = $LIMIT * ($PAGE - 1);
    if ($PAGE <= 1) {
        $OFFSET = 0;
    }

    // Count Total Rows
    $sql = "SELECT COUNT(id) FROM properties";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($total_rows);
    $stmt->fetch();
    // Get Total Pages
    $total_pages = ceil($total_rows / $LIMIT);

    if ($PAGE > $total_pages) {
        exit(json_encode(['message' => 'Invalid Page']));
    }

    // No Page Set
    if ($PAGE <= 1) {
        $links = [
            'first' => "http://$_SERVER[SERVER_NAME]/api_test/api/area/get_areas.php?page=1&rows=$LIMIT",
            'last' => "http://$_SERVER[SERVER_NAME]/api_test/api/area/get_areas.php?page=$total_pages&rows=$LIMIT",
            'prev' => null,
            'next' => "http://$_SERVER[SERVER_NAME]/api_test/api/area/get_areas.php?page=2&rows=$LIMIT"
        ];
    }

    // Page Is Set
    if ($PAGE > 1) {
        $links = [
            'first' => "http://$_SERVER[SERVER_NAME]/api_test/api/area/get_areas.php?page=1&rows=$LIMIT",
            'last' => "http://$_SERVER[SERVER_NAME]/api_test/api/area/get_areas.php?page=$total_pages&rows=$LIMIT",
            'prev' => "http://$_SERVER[SERVER_NAME]/api_test/api/area/get_areas.php?page=" . $PAGE - 1 . "&rows=$LIMIT",
            'next' => "http://$_SERVER[SERVER_NAME]/api_test/api/area/get_areas.php?page=" . $PAGE + 1 . "&rows=$LIMIT"
        ];
    }

    // Paginate Data
    $sql = "SELECT id, title, created_at, updated_at FROM properties ORDER BY id ASC LIMIT $OFFSET, $LIMIT";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows <= 0) {
        exit(json_encode(['message' => 'table is empty']));
    }
    $stmt->bind_result($id, $title, $created_at, $updated_at);

    $index = 0;
    $data = array();
    while($stmt->fetch()) {
        $data[$index] = ['id' => $id, 'title' => $title, 'created_at' => $created_at, 'updated_at' => $updated_at];
        $index++;
    }
    $stmt->close();

    // Get All pages
    $index = 0;
    while($index < $total_pages) {
        $pages[$index] = [
            $index + 1 => "$_SERVER[SERVER_NAME]/api_test/api/area/get_areas.php?page=" . $index + 1 . "&rows=$LIMIT"
        ];
        $index++;
    }

    // Json response
    $response = [
        'data' => $data,
        'links' => $links,
        'pages' => $pages,
        'meta' => [
            'current_page' => $PAGE, 
            'total_rows' => $total_rows,
            'total_pages' => $total_pages
        ]
    ];
    exit(json_encode($response));

} catch (\Throwable $th) {
    exit(json_encode(['message' => 'An error occurred', 'error' => $th->getMessage()]));
}
