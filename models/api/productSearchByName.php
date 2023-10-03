<?php

    require_once '../vendor/autoload.php'; // include Composer's autoloader
    include_once '../config/mongoDatabase.php';
    include_once '../src/product/ProductGet.php';
    include_once '../config/mysqlDatabase.php';
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");
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
        getSearchProduct();
    }

    function getSearchProduct() {
        $mySqlDatabase = new MySQLDatabase();
        $mysqlConn = $mySqlDatabase->getConnection('lazada_customer', 'password');
        $mongoDb = new MongoDatabase();
    
        $client = $mongoDb->getConnection();
        $productDocument = $mongoDb->productDocument; // parent collection là demo, child là beers, insert document có attributes name và brewery
        
        $productService = new ProductGet($productDocument, $mysqlConn);
        $data = json_decode(file_get_contents("php://input"));

        // echo var_dump($data);

        $result = $productService->getProductsBasedNameMySQL($data->pageSize , $data->pageNo, $data->nameSearch);
        

        if ($result == false) {
            echo json_encode(
                array("message" => "Something is wrong. Cannot fetch products")
            );
            http_response_code(400);
        } else {
            echo json_encode($result) ;
            http_response_code(200);
        }
    
    }

?>