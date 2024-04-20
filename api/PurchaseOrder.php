<?php
include('host.php');

$response = array();

if (!$connect_management) {
    $response['database_message'] = 'ไม่สามารถเชื่อมต่อฐานข้อมูลได้';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} else {
    $response['database_message'] = 'เชื่อมต่อฐานข้อมูลเรียบร้อยแล้ว';
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $purchaseorder_id = isset($_GET['purchaseorder_id']) ? $_GET['purchaseorder_id'] : '';
        $username = isset($_GET['username']) ? $_GET['username'] : '';

        if (empty($purchaseorder_id)) {
            $response['error'] = 'รหัสใบสั่งซื้อไม่ถูกต้อง';
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        } else {
            $sql_purchaseorder = "SELECT * FROM ms_purchaseorder WHERE id = ?";
            $stmt_purchaseorder = $connect_management->prepare($sql_purchaseorder);
            if (!$stmt_purchaseorder) {
                $response['error'] = 'เกิดข้อผิดพลาดในการเตรียมคำสั่ง';
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                $connect_management->close();
                exit();
            }
            $stmt_purchaseorder->bind_param('s', $purchaseorder_id);
            $stmt_purchaseorder->execute();
            $result_purchaseorder = $stmt_purchaseorder->get_result();
            $row_purchaseorder = $result_purchaseorder->fetch_array();
            $stmt_purchaseorder->close();

            if ($row_purchaseorder) {
                $response['purchaseorder'] = array(
                    'document_no' => $row_purchaseorder['document_no']
                );

                $sql_purchaseorder_detail = "SELECT * FROM tr_purchaseorder_detail WHERE document_no = ?";
                $stmt_purchaseorder_detail = $connect_management->prepare($sql_purchaseorder_detail);
                if (!$stmt_purchaseorder_detail) {
                    $response['error'] = 'เกิดข้อผิดพลาดในการเตรียมคำสั่ง';
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    $connect_management->close();
                    exit();
                }
                $stmt_purchaseorder_detail->bind_param('s', $row_purchaseorder['document_no']);
                $stmt_purchaseorder_detail->execute();
                $result_purchaseorder_detail = $stmt_purchaseorder_detail->get_result();

                $products = array();
                while ($row_purchaseorder_detail = $result_purchaseorder_detail->fetch_array()) {
                    $sql_product = "SELECT * FROM ms_product WHERE id = ?";
                    $stmt_product = $connect_management->prepare($sql_product);
                    if (!$stmt_product) {
                        $response['error'] = 'เกิดข้อผิดพลาดในการเตรียมคำสั่ง';
                        echo json_encode($response, JSON_UNESCAPED_UNICODE);
                        $connect_management->close();
                        exit();
                    }
                    $stmt_product->bind_param('s', $row_purchaseorder_detail['product']);
                    $stmt_product->execute();
                    $result_product = $stmt_product->get_result();
                    $row_product = $result_product->fetch_array();
                    $stmt_product->close();
                    if(empty($row_product['picture_1']))
                    {
                        $picture_1 = $api_website_image.'/product.png';
                    }
                    else
                    {
                        $picture_1 = $api_website_image.'/product/'.$row_product['picture_1'];
                    }
                    $status_check = $row_purchaseorder_detail['status_check'] ? $api_website_image . '/success.png' : '';
                    $product = array(
                        'id' => $row_product['id'],
                        'picture_1' => $picture_1,
                        'name' => $row_product['name'],
                        'size' => $row_product['size'],
                        'package' => $row_product['package'],
                        'shelf'  => $row_product['shelf'],
                        'product_amount' => $row_purchaseorder_detail['product_amount'],
                        'product_amount_check' => $row_purchaseorder_detail['product_amount_check'],
                        'status_check' => $status_check,
                    );  
                    $products[] = $product;
                }
                $response['products'] = $products;
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
            } else {
                $response['error'] = 'ไม่พบใบสั่งซื้อ';
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
            }
        }
    } else {
        $response['error'] = 'รูปแบบการร้องขอต้องเป็น GET เท่านั้น';
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
}

$connect_management->close();
?>
