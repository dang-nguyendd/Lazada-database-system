<?php
    require_once '../vendor/autoload.php'; // include Composer's autoloader
    include_once '../config/mongoDatabase.php';
    include_once '../config/mysqlDatabase.php';
    include_once '../src/product/ProductGet.php';
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
   
    $mySqlDatabase = new MySQLDatabase();
    $mysqlConn = $mySqlDatabase->getConnection('lazada_customer', 'password');
    $mongoDb = new MongoDatabase();

    $client = $mongoDb->getConnection();
    $productDocument = $mongoDb->productDocument; // parent collection là demo, child là beers, insert document có attributes name và brewery
    
    $productService = new ProductGet($productDocument, $mysqlConn);


    $data = json_decode(file_get_contents("php://input"));
    

    $result = $productService->getAllProductsByPage($data->pageSize , $data->pageNo);
    
    // echo json_encode($productsInPage);
    echo json_encode($result);
    // echo json_encode( array(['totalPages' => $totalPages, 'productsInPage' => $productsInPage])) ;

?>