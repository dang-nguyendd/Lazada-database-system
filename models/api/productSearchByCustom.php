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
        getSearchProductByCustom();
    }
    
    function getSearchProductByCustom(){
        $mySqlDatabase = new MySQLDatabase();
        $mysqlConn = $mySqlDatabase->getConnection('lazada_customer', 'password');
        $mongoDb = new MongoDatabase();

        $client = $mongoDb->getConnection();
        $productDocument = $mongoDb->productDocument; 
        $productService = new ProductGet($productDocument, $mysqlConn);
        $data = json_decode(file_get_contents("php://input"));
   
        $result = $productService->searchProductBasedCustom($data->pageSize , $data->pageNo, $data->searchAttr ,$data->searchValue);

        echo json_encode( $result) ;
    }

?>