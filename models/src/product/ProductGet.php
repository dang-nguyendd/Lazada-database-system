<?php

    class ProductGet {
        private $productMySQLConn;
        private $productCollectionMongo;
        private $singleProduct;
        public $err;

        public function __construct($mongoDB, $mysqlDB){
            $this->productCollectionMongo = $mongoDB;
            $this->productMySQLConn = $mysqlDB;
        }

    // -------------------------------------- Get One Product ------------------------- //
        public function getOneProductMySQL($productID){
            $sqlQuery =  "SELECT * FROM Products WHERE productID = :productID"  ;
            $productID = htmlspecialchars(strip_tags($productID));
            

            try {
                $stmt = $this->productMySQLConn->prepare($sqlQuery);
                // bind data
                $stmt->bindParam(":productID", $productID, PDO::PARAM_INT);
                $stmt->execute();
                // echo var_dump($stmt->fetch(PDO::FETCH_ASSOC));
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $this->err = $e->getMessage();
                return false;
            }
        }

        public function getOneProductMongoSQL($productID){
            try {
                return $this->productCollectionMongo->findOne(['_id' => $productID]);
            } catch(Exception $e){
                $this->err = $e->getMessage();
                return false;
            }
        }

        public function getOneProduct($productID){
            if ($this->getOneProductMySQL($productID) == false && $this->getOneProductMongoSQL($productID) == false){
                return false;
            } else{
                $this->singleProduct = $this->getOneProductMySQL($productID);
                
                if ($this->getOneProductMongoSQL($productID) != NULL) {
                    $this->singleProduct['attributes'] = $this->getOneProductMongoSQL($productID)['attributes'];
                }
                return $this->singleProduct;
            }
        }

    // -------------------------------------- Get One Product ------------------------- //



    // -------------------------------------- Get Products By Vendor ------------------------- //




        // GET a Vendor's Products
        public function getProductsByVendorID($v) {
            $vendor = floatval(htmlspecialchars(strip_tags($v)));
            $sql = "SELECT * FROM Products WHERE vendorID = :vendorID 
                    ORDER BY createdDate";

            try {
                $stmt = $this->productMySQLConn->prepare($sql);
                $stmt->bindParam(":vendorID", $vendor);
                $stmt->execute();
                // echo var_dump($stmt->fetchAll(PDO::FETCH_ASSOC));
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $this->err = $e->getMessage();
                return false;
            }
            
        }
    // -------------------------------------- Get Products By Vendor ------------------------- //

    // -------------------------------------- Get Products By Page ------------------------- //

        // GET Num Of PAGEs
        public function getTotalNumOfPages($pageSize){
            $result = $this->productCollectionMongo->count();
            // return ceil($result / $pageSize);

            $sqlQuery =  "SELECT COUNT(*) as 'totalPages' FROM Products"   ;

            try {
                $stmt = $this->productMySQLConn->prepare($sqlQuery);
                $stmt->execute();
                return  ceil($stmt->fetch(PDO::FETCH_ASSOC)['totalPages'] / $pageSize);
            } catch (PDOException $e) {
                $this->err = $e->getMessage();
                return -1;
            }
        }

        // GET ALL
        public function getAllProductsByPageMySQL($pageSize, $pageNo){

            $sqlQuery =  "SELECT * FROM Products ORDER BY createdDate DESC LIMIT :pageSize OFFSET :pageOffset"  ;
            $pageOffset = $pageNo * $pageSize;

            try {
                $stmt = $this->productMySQLConn->prepare($sqlQuery);
                // bind data
                $stmt->bindParam(":pageSize", $pageSize, PDO::PARAM_INT);
                $stmt->bindParam(":pageOffset", $pageOffset, PDO::PARAM_INT);
                $stmt->execute();
                return  $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $this->err = $e->getMessage();
                return false;
            }
        }

        public function getAllProductsByPage($pageSize, $pageNo){

            $totalPages = $this->getTotalNumOfPages($pageSize) ;
            $productsInPage = $this->getAllProductsByPageMySQL($pageSize, $pageNo);
            
            if ( $productsInPage && ($totalPages != -1)){
                return ['totalPages' => $totalPages , 'productsInPage' => $productsInPage ];
            }else {
                return -1;
            }
        }

    // -------------------------------------- Get Products By Page ------------------------- //


    // -------------------------------------- Get Products By Name ------------------------- //

        // // Search by name (MySQL)
        public function getProductsBasedNameMySQL($pageSize, $pageNo, $nameSearch){

            $sqlQuery =  "SELECT * FROM Products WHERE pName REGEXP :nameSearch ORDER BY createdDate DESC LIMIT :pageSize OFFSET :pageOffset"  ;
            $pageOffset = $pageNo * $pageSize;

            try {
                $stmt = $this->productMySQLConn->prepare($sqlQuery);
                // bind data
                $stmt->bindValue(":nameSearch", '^' . $nameSearch);
                $stmt->bindParam(":pageSize", $pageSize, PDO::PARAM_INT);
                $stmt->bindParam(":pageOffset", $pageOffset, PDO::PARAM_INT);
                $stmt->execute();
                $productsInPage =  $stmt->fetchAll(PDO::FETCH_ASSOC);
                $totalPages = $this->getTotalProductsBasedNameMySQL($pageSize,  $nameSearch);
                return [ 'totalPages' => $totalPages, 'productsInPage' => $productsInPage];
            } catch (PDOException $e) {
                $this->err = $e->getMessage();
                return false;
            }

        }

        // // GET Num Of PAGEs MySQL
        public function getTotalProductsBasedNameMySQL($pageSize,  $nameSearch){

            $sqlQuery =  "SELECT count(*) as 'totalPages'  FROM Products WHERE pName REGEXP :nameSearch  "  ;

            try {  
                $stmt = $this->productMySQLConn->prepare($sqlQuery);
                // bind data
                $stmt->bindValue(":nameSearch", '^' . $nameSearch);
                $stmt->execute();
                return ceil($stmt->fetch(PDO::FETCH_ASSOC)['totalPages'] / $pageSize);
            } catch (PDOException $e) {
                $this->err = $e->getMessage();
                return false;
            }
        }
    // -------------------------------------- Get Products By Name ------------------------- //


    // -------------------------------------- Get Products By Price ------------------------- //


        public function getProductsBasedPriceMySQL($pageSize, $pageNo, $priceLower, $priceUpper){
            
            $sqlQuery =  "SELECT * from Products where price between :priceLower and :priceUpper ORDER BY createdDate desc limit :pageSize OFFSET :pageOffset"  ;

            $pageOffset = $pageNo * $pageSize;

            try {
                $stmt = $this->productMySQLConn->prepare($sqlQuery);
                // bind data
                $stmt->bindParam(":priceLower", $priceLower, PDO::PARAM_INT);
                $stmt->bindParam(":priceUpper", $priceUpper, PDO::PARAM_INT);
                $stmt->bindParam(":pageSize", $pageSize, PDO::PARAM_INT);
                $stmt->bindParam(":pageOffset", $pageOffset, PDO::PARAM_INT);
                $stmt->execute();
                $productsInPage =  $stmt->fetchAll(PDO::FETCH_ASSOC);
                $totalPages = $this->getTotalProductsBasedPriceMySQL($pageSize, $priceLower, $priceUpper);
                return [ 'totalPages' => $totalPages, 'productsInPage' => $productsInPage];
            } catch (PDOException $e) {
                $this->err = $e->getMessage();
                return false;
            }
            
        }

        public function getTotalProductsBasedPriceMySQL($pageSize, $priceLower, $priceUpper){

            $sqlQuery =  "SELECT COUNT(*) as 'totalPages'   FROM Products WHERE price between :priceLower and :priceUpper "  ;

            try {
                $stmt = $this->productMySQLConn->prepare($sqlQuery);

                // // bind data
                $stmt->bindParam(":priceLower", $priceLower, PDO::PARAM_INT);
                $stmt->bindParam(":priceUpper", $priceUpper, PDO::PARAM_INT);
    
                $stmt->execute();
                return ceil($stmt->fetch(PDO::FETCH_ASSOC)['totalPages'] / $pageSize);
            } catch (PDOException $e) {
                $this->err = $e->getMessage();
                return false;                
            }


        }

    // -------------------------------------- Get Products By Price ------------------------- //

    // -------------------------------------- Get Products By Custom ------------------------- //

        // get product based custom search
        public function searchProductBasedCustomMongoDB($pageSize, $pageNo, $searchAttr, $searchValue){

            $productQuery = array('attributes.'.$searchAttr => $searchValue );
            $options = array(
                "limit" => $pageSize,
                "skip" => $pageNo * $pageSize
            );
            try {
                $result = $this->productCollectionMongo->find($productQuery,$options );

                return $result->toArray();
            } catch(Exception $e) {
                $this->err = $e->getMessage();
                return false;                
            }

        }


        // get product based custom search
        public function getTotalProductBasedCustomMongoDB($pageSize, $searchAttr, $searchValue){

            $productQuery = array('attributes.'.$searchAttr => $searchValue );

            try {
                $result = $this->productCollectionMongo->count($productQuery );

                return ceil($result / $pageSize);
            } catch(Exception $e) {
                $this->err = $e->getMessage();
                return false;                
            }

        }
        

        public function searchProductBasedCustom($pageSize, $pageNo, $searchAttr, $searchValue){
            
            $array = $this->searchProductBasedCustomMongoDB($pageSize , $pageNo, $searchAttr ,$searchValue);

            // echo var_dump($array);
            $productList = [];

            foreach ($array as $value) {
                $product = $this->getOneProductMySQL($value['_id']);
                $product[$searchAttr] = $searchValue;
                $productList[] = $product;
            }

            $totalPages = $this->getTotalProductBasedCustomMongoDB($pageSize, $searchAttr, $searchValue);
            $result = array( 'totalPages' => $totalPages, 'productsInPage' => $productList);
            return $result;
        }


    // -------------------------------------- Get Products By Custom ------------------------- //

    }    
?>