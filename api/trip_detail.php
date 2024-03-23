<?php
require_once '../db_config.php';

header('Access-Control-Allow-Origin: *'); // or set to allowed domain
header('Content-Type: application/json; charset=utf-8');

try {
    // Create a PDO connection using database configuration constants
    $pdo = new PDO("mysql:host=" . DB_HOSTNAME . ";dbname=" . DB_DATABASE, DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle preflight OPTIONS request
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        exit();
    }

    // Handle API request
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
        if ($id) {
            $tripData = fetchTripData($pdo, $id);
            echo json_encode($tripData);
        } else {
            throw new Exception('Missing ID parameter');
        }
    } else {
        throw new Exception('Unsupported request method');
    }
} catch (Exception $e) {
    $error = ['error' => $e->getMessage()];
    echo json_encode($error);
}

function fetchTripData($pdo, $id) {
    $sql = "SELECT * FROM ms_trip WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $tripData = [];
    while ($row_trip = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $tripData[] = [
            'id' => $row_trip['id'],
            'name' => $row_trip['name'],
            'date' => $row_trip['date'],
            'car' => fetchCarData($pdo, $row_trip['car']),
            'trip_detail' => fetchTripDetailData($pdo, $row_trip['id']),
        ];
    }
    return $tripData;
}

function fetchCarData($pdo, $carId) {
    $sql = "SELECT * FROM ms_car WHERE id = :carId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['carId' => $carId]);
    $carData = [];
    while ($row_car = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $carData = [
            'id' => $row_car['id'],
            'picture' => $row_car['picture'],
            'driver' => fetchDriverData($pdo, $row_car['driver']),
            'brand' => $row_car['brand'],
            'license' => $row_car['license'],
        ];
    }
    return $carData;
}

function fetchDriverData($pdo, $driverId) {
    $sql = "SELECT * FROM ms_personal WHERE id = :driverId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['driverId' => $driverId]);
    $driverData = [];
    while ($row_personal = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $driverData = [
            'id' => $row_personal['id'],
            'titlename' => $row_personal['titlename'],
            'firstname' => $row_personal['firstname'],
            'surname' => $row_personal['surname'],
            'telephone' => $row_personal['telephone'],
            'picture' => $row_personal['picture'],
        ];
    }
    return $driverData;
}

function fetchTripDetailData($pdo, $tripId) {
    $sql = "SELECT * FROM tr_trip_detail WHERE trip = :tripId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['tripId' => $tripId]);
    $tripDetailData = [];
    while ($row_detail = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $tripDetail = [
            'id' => $row_detail['id'],
            'purchaseorder' => $row_detail['purchaseorder'],
            'shop' => fetchShopData($pdo, $row_detail['shop']),
            'trip' => $row_detail['trip'],
            'status_check' => $row_detail['status_check'],
        ];
        $tripDetailData[] = $tripDetail;
    }
    return $tripDetailData;
}

function fetchShopData($pdo, $shopId) {
    $sql = "SELECT * FROM ms_shop WHERE id = :shopId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['shopId' => $shopId]);
    $shopData = [];
    while ($row_shop = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $shopData = [
            'id' => $row_shop['id'],
            'picture' => $row_shop['picture'],
            'name' => $row_shop['name'],
            'address' => $row_shop['address'],
        ];
    }
    return $shopData;
}
?>
