<?php
    require_once '../vendor/autoload.php'; // include Composer's autoloader
    include_once '../config/mongoDatabase.php';
    include_once '../src/product/ProductGet.php';
    include_once '../config/mysqlDatabase.php';
    
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header('Access-Control-Allow-Credentials: true');
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Headers: Content-Type, origin, Access-Control-Allow-Methods,Access-Control-Allow-Origin, Access-Control-Allow-Credentials, Authorization, X-Requested-With");
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == "OPTIONS") {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
        header("HTTP/1.1 200 OK");
        die();
    } else {
        $mySqlDatabase = new MySQLDatabase();
        $mysqlConn = $mySqlDatabase->getConnection('lazada_customer', 'password');

        $mongoDb = new MongoDatabase();
        $client = $mongoDb->getConnection();
        $productDocument = $mongoDb->productDocument; 

        $data = json_decode(file_get_contents("php://input"));

        $productService = new ProductGet($productDocument, $mysqlConn);
        $product = $productService->getOneProduct($data->productID);

    
        if ($product == false) {
            echo json_encode(
                array("message" => $productService->err)
            );
            return http_response_code(400);
        } else {
            echo json_encode($product);
            return http_response_code(200);
        }
    }
    

    
?>