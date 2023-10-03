<?php
    include_once '../config/mysqlDatabase.php';
    include_once '../src/DistanceVendorCustomer.php';

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET, POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    $database = new MySQLDatabase();
    $db = $database->getConnection('lazada_vendor','password');

    $items = new DistanceVendorCustomer($db);
    $data = json_decode(file_get_contents("php://input"));

    // search radius in km
    $stmt = $items->getVendorsByDistance($data->searchRadius, $data->customerID);

    http_response_code(200);
    echo json_encode($stmt);
    
?>


