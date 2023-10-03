<?php
    require_once '../vendor/autoload.php'; // include Composer's autoloader
    include_once '../config/mongoDatabase.php';
    include_once '../src/product/ProductCreateUpdate.php';
    include_once '../config/mysqlDatabase.php';
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
        $mySqlDatabase = new MySQLDatabase();
        $mysqlConn = $mySqlDatabase->getConnection('lazada_vendor', 'password');
        $mongoDb = new MongoDatabase();
    
        $client = $mongoDb->getConnection();
        $productDocument = $mongoDb->productDocument; 
        $data = json_decode(file_get_contents("php://input"));

        if (!$data || preg_match('/^\s*$/', $data->productID) || preg_match('/^\s*$/', $data->pName) || preg_match('/^\s*$/', $data->price) || preg_match('/^\s*$/', $data->vendorID)) {
            echo json_encode(
                array("message" => "ERROR: Name, Price, ProductID and vendorID must not be blank")
            );
            return http_response_code(400); 
        }

        $productService = new ProductCreateUpdate(
            $productDocument, 
            $mysqlConn,
            $data->productID,
            $data->pName,
            $data->vendorID,
            $data->price
        );
        if($productService->updateProductMongo($data->attributes) && $productService->updateProductMySQL()){
            echo json_encode(
                array("message" => "Product updated successfully!")
            );
            return http_response_code(200);
        } else{
            echo json_encode(
                array("message" => "Product could not be updated")
            );
            return http_response_code(400); 
        }
    }
    
?>