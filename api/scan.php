<?php
require_once '../db_config.php';

header('Access-Control-Allow-Origin: *'); // Set to allowed domain in production
header('Content-Type: application/json; charset=utf-8');

try {
    // Create a PDO connection using database configuration constants
    $pdo = new PDO("mysql:host=" . DB_HOSTNAME . ";dbname=" . DB_DATABASE, DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle preflight OPTIONS request
    handlePreflightRequest();

    // Handle API request
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        handleGetRequest($pdo);
    } else {
        throw new Exception('Unsupported request method');
    }
} catch (Exception $e) {
    $error = ['error' => $e->getMessage()];
    echo json_encode($error);
}

function handlePreflightRequest() {
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        exit();
    }
}

function handleGetRequest($pdo) {
    if (isset($_GET['product_id']) && isset($_GET['document_no'])) {
        $product_id = $_GET['product_id'];
        $document_no = $_GET['document_no'];

        checkProductAndPurchaseOrder($pdo, $product_id, $document_no);
    } else {
        throw new Exception('Missing parameters');
    }
}

function checkProductAndPurchaseOrder($pdo, $product_id, $document_no) {
    $product_row = getProductDetails($pdo, $product_id);

    if ($product_row) {
        $purchase_order_row = getPurchaseOrderDetails($pdo, $document_no);

        if ($purchase_order_row) {
            echo json_encode(['message' => 'รหัสสินค้านี้ถูกสแกนไปแล้ว']);
        } else {
            updatePurchaseOrderStatus($pdo, $document_no, $product_id);

            $purchaseOrderDetail = [
                'id' => $product_row['id'],
                'document_no' => $document_no,
                'product' => $product_id,
                'status_check' => '1',
            ];

            echo json_encode(['message' => 'ตรวจสอบรายการสินค้าสำเร็จ', 'data' => $purchaseOrderDetail]);
        }
    } else {
        echo json_encode(['message' => 'ไม่พบรหัสสินค้านี้']);
    }
}

function getProductDetails($pdo, $product_id) {
    $sql_product = "SELECT * FROM ms_product WHERE id = ?";
    $stmt_product = $pdo->prepare($sql_product);
    $stmt_product->execute([$product_id]);
    return $stmt_product->fetch(PDO::FETCH_ASSOC);
}

function getPurchaseOrderDetails($pdo, $document_no) {
    $sql_purchase_order = "SELECT * FROM tr_purchaseorder_detail WHERE document_no = ?";
    $stmt_purchase_order = $pdo->prepare($sql_purchase_order);
    $stmt_purchase_order->execute([$document_no]);
    return $stmt_purchase_order->fetch(PDO::FETCH_ASSOC);
}

function updatePurchaseOrderStatus($pdo, $document_no, $product_id) {
    $update_sql = "UPDATE tr_purchaseorder_detail SET status_check = '1' WHERE document_no = ? AND product = ?";
    $stmt_update = $pdo->prepare($update_sql);
    $stmt_update->execute([$document_no, $product_id]);
}
?>
