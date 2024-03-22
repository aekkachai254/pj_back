<?php
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json; charset=utf-8');

    // Validate and sanitize input
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id <= 0) {
        $response['error'] = 'Invalid product ID';
        echo json_encode($response);
        exit();
    }

    // Database connection
    $database_hostname = 'localhost';
    $database_username = 'team';
    $database_password = 'Te@m1234!';
    $database_databasename = 'management';

    $connect_management = new mysqli($database_hostname, $database_username, $database_password, $database_databasename);

    // Check database connection
    if ($connect_management->connect_error) {
        $response['error'] = 'Database Connect Failed: ' . $connect_management->connect_error;
        echo json_encode($response);
        exit();
    }

    // Prepare and execute SQL query
    $sql = "SELECT * FROM ms_product WHERE id = ?";
    $stmt = $connect_management->prepare($sql);

    if (!$stmt) {
        $response['error'] = 'Prepare statement error';
        echo json_encode($response);
        $connect_management->close();
        exit();
    }

    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    // Process the result
    $products = array();

    while ($row = $result->fetch_array()) {
        $product = array(
            'id' => $row['id'],
            'brand' => $row['brand'],
            'shelf' => $row['shelf'],
            'price' => $row['price'],
            'amount_balance' => $row['amount_balance'],
            'status' => $row['status']
        );

        $products[] = $product;
    }

    if (!empty($products)) {
        echo json_encode($products);
    } else {
        $response['error'] = 'No product found';
        echo json_encode($response);
    }

    // Close database connection
    $connect_management->close();
} else {
    $response['error'] = 'Method not allowed';
    echo json_encode($response);
}
?>
