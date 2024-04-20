<?php
include('host.php');

$response = array();
$purchaseorder = array();
$products = array();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Validate and sanitize input
    $document_no = isset($_GET['document_no']) ? $_GET['document_no'] : '';

    if (empty($document_no)) {
        $response['error'] = 'Invalid Document No';
        echo json_encode($response);
        exit();
    }

    // Check database connection
    if ($connect_management->connect_error) {
        $response['error'] = 'Database Connect Failed : '.$connect_management->connect_error;
        echo json_encode($response);
        exit();
    }
    $sql_purchaseorder = "SELECT * FROM ms_purchaseorder WHERE document_no = ?";
    $stmt_purchaseorder = $connect_management->prepare($sql_purchaseorder);
    if (!$stmt_purchaseorder) {
        $response['error'] = 'Prepare statement error';
        echo json_encode($response);
        $connect_management->close();
        exit();
    }
    $stmt_purchaseorder->bind_param('s', $document_no);
    $stmt_purchaseorder->execute();
    $result_purchaseorder = $stmt_purchaseorder->get_result();
    $row_purchaseorder = $result_purchaseorder->fetch_array();
    $stmt_purchaseorder->close();
    $purchaseorder = array (
        'purchaseorder_document_no' => $row_purchaseorder['document_no'],
    );
    echo json_encode($purchaseorder);

    $sql_purchaseorder_detail = "SELECT * FROM tr_purchaseorder_detail WHERE document_no = ?";
    $stmt_purchaseorder_detail = $connect_management->prepare($sql_purchaseorder_detail);
    if (!$stmt_purchaseorder_detail) {
        $response['error'] = 'Prepare statement error';
        echo json_encode($response);
        $connect_management->close();
        exit();
    }
    $stmt_purchaseorder_detail->bind_param('s', $document_no);
    $stmt_purchaseorder_detail->execute();
    $result_purchaseorder_detail = $stmt_purchaseorder_detail->get_result();
    
    // Fetch all product details
    while ($row_purchaseorder_detail = $result_purchaseorder_detail->fetch_assoc()) {
        $sql_product = "SELECT * FROM ms_product WHERE id = ?";
        $stmt_product = $connect_management->prepare($sql_product);
        if (!$stmt_product) {
            $response['error'] = 'Prepare statement error';
            echo json_encode($response);
            $connect_management->close();
            exit();
        }
        $stmt_product->bind_param('s', $row_purchaseorder_detail['product']);
        $stmt_product->execute();
        $result_product = $stmt_product->get_result();
        $row_product = $result_product->fetch_assoc();
        $stmt_product->close();

        if(empty($row_purchaseorder_detail['status_check']))
        {
            $status_check = '';
        }
        else
        {
            $status_check = $api_website_image.'/success.png';
        }
        $product = array(
            'id' => $row_product['id'],
            'brand' => $row_product['brand'],
            'shelf' => $row_product['shelf'],
            'price' => $row_product['price'],
            'product_amount' => $row_purchaseorder_detail['product_amount'],
            'status' => $status_check,
        );
        $products[] = $product;
    }
    
    // Check if products found
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
