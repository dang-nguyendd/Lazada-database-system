<?php
    include_once '../config/mysqlDatabase.php';
    include_once '../src/Shipper.php';
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == "OPTIONS") {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
        header("HTTP/1.1 200 OK");
        die();
    } else {
        $database = new MySQLDatabase();
        $db = $database->getConnection('lazada_admin','password');
        
        $shipper = new Shipper($db);
        
        $data = json_decode(file_get_contents("php://input"));
        
        
        if($shipper->updateOrder($data->oStatus, $data->orderID)){
            echo json_encode("Order data updated.");
        } else{
            echo json_encode("Data could not be updated");
        }
    }

?>