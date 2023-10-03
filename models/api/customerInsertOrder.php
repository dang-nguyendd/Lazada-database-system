<?php
    include_once '../config/mysqlDatabase.php';
    include_once '../src/Customer.php';
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: *");
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == "OPTIONS") {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
        header("HTTP/1.1 200 OK");
        die();
    } else {
        $database = new MySQLDatabase();
        $db = $database->getConnection('lazada_customer', 'password');
        $data = json_decode(file_get_contents("php://input"));

        $customer = new Customer($db);
        $result = $customer->createOrderTransaction($data->oStatus, $data->customerID, $data->vendorID, $data->productID);
        
        if($result){
            http_response_code(200);
            echo 'Order created successfully.';
        } else{
            
            echo $customer->err;
            return http_response_code(404);
        
        }
    }


?>