<?php
require_once '../db_config.php';

// Initialize and handle CORS headers
$corsHandler = new CorsHandler();
$corsHandler->handle();

// Set response content type
header('Content-Type: application/json; charset=utf-8');

try {
    // Create a database connection using PDO
    $dbManager = new DatabaseManager(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

    // Fetch trips data
    $tripsData = $dbManager->fetchTripsData();

    // Close the database connection
    $dbManager->close();

    // Send response
    echo json_encode($tripsData);
} catch (Exception $e) {
    $errorResponse = ['error' => $e->getMessage()];
    echo json_encode($errorResponse);
}

// Class to handle CORS headers
class CorsHandler {
    public function handle() {
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header('Access-Control-Allow-Origin: *'); // Set to specific domain if required
            header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization'); // Add Authorization if needed
            exit;
        }
        header('Access-Control-Allow-Origin: *'); // Set to specific domain if required
    }
}

// Class for managing database operations
class DatabaseManager {
    private $connection;

    public function __construct($hostname, $username, $password, $database) {
        $this->connection = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        //set http status code to 500 if connect fail
        http_response_code(500);

        if ($this->connection) {
            http_response_code(200);
        }

    }

    public function fetchTripsData() {
        $tripsData = ['ms_trip' => []];

        $sqlTrip = "SELECT * FROM ms_trip";
        $stmtTrip = $this->connection->query($sqlTrip);

        while ($rowTrip = $stmtTrip->fetch(PDO::FETCH_ASSOC)) {
            $tripData = [
                'id' => $rowTrip['id'],
                'name' => $rowTrip['name'],
                'date' => $rowTrip['date'],
                'car' => $rowTrip['car'],
                'create_by' => $rowTrip['create_by'],
                'status_trip' => $rowTrip['status_trip'],
            ];

            $tripData['tr_trip_detail'] = $this->fetchTripDetails($rowTrip['id']);

            $tripsData['ms_trip'][] = $tripData;
        }

        return $tripsData;
    }

    private function fetchTripDetails($tripId) {
        $tripDetails = [];

        $sqlDetail = "SELECT * FROM tr_trip_detail WHERE trip = :tripId";
        $stmtDetail = $this->connection->prepare($sqlDetail);
        $stmtDetail->bindParam(':tripId', $tripId);
        $stmtDetail->execute();

        while ($rowDetail = $stmtDetail->fetch(PDO::FETCH_ASSOC)) {
            $tripDetail = [
                'id' => $rowDetail['id'],
                'purchaseorder' => $rowDetail['purchaseorder'],
                'shop' => $rowDetail['shop'],
                'trip' => $rowDetail['trip'],
                'status_check' => $rowDetail['status_check'],
            ];

            $tripDetails[] = $tripDetail;
        }

        return $tripDetails;
    }

    public function close() {
        $this->connection = null;
    }
}
?>
