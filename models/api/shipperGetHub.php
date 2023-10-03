<?php
    include_once '../config/mysqlDatabase.php';
    include_once '../src/Shipper.php';
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET, POST");
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
        $db = $database->getConnection('lazada_shipper','password');
        $shipper = new Shipper($db);
        $data = json_decode(file_get_contents("php://input"));
        $hub = $shipper->getHub($data->shipperID);
        
        if($hub == false){
            http_response_code(404);
            echo json_encode("Hub not found.");
        }
        else{
            http_response_code(200);
            echo json_encode($hub);
        }
    }


?>