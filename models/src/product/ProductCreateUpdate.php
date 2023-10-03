<?php
    class ProductCreateUpdate {
        private $productMySQLConn;
        private $productID;
        private $pName;
        private $vendorID;
        private $price;
        public $err;

        public function __construct($mongoDB, $mysqlDB, $productID, $pName, $vendorID, $price){
            $this->productCollectionMongo = $mongoDB;
            $this->productMySQLConn = $mysqlDB;
            $this->pName=htmlspecialchars(strip_tags($pName));
            $this->price=floatval(htmlspecialchars(strip_tags($price)));
            $this->vendorID=$vendorID;
            $this->productID = $productID;
        }

        public function createProductMySQL(){
            $sqlQuery = "INSERT INTO Products SET
                    productID = :productID,
					pName = :pName, 
					vendorID = :vendorID, 
					Price = :price";

            try {
                $stmt = $this->productMySQLConn->prepare($sqlQuery);

                // bind data
                $stmt->bindParam(":productID", $this->productID);
                $stmt->bindParam(":pName", $this->pName);
                $stmt->bindParam(":price", $this->price);
                $stmt->bindParam(":vendorID", $this->vendorID, PDO::PARAM_INT);
                $stmt->execute();

                return true;
            } catch (PDOException $e) {
                $this->err = $e->getMessage();
                return false;
            }
        }

        public function createProductMongo($attributes){
            try {
                $this->productCollectionMongo->insertOne(
                    [
                        '_id' => $this->productID,
                        'attributes' => $attributes
                    ]
                );
                return true;
            } catch(Exception $e){
                $this->err = $e->getMessage();
                return false ;
            }
        }

        public function createProduct($attributes){

            if ($this->createProductMongo($attributes) && $this->createProductMySQL()){
                return true;
            } else{
                return false;
            }
        }


        public function updateProductMySQL() {
            $sqlQuery = "UPDATE Products 
            SET
            pName = :pName, 
            vendorID = :vendorID, 
            price = :price
            WHERE productID = :productID";
            
            try {
                $stmt = $this->productMySQLConn->prepare($sqlQuery);
                // bind param
                $stmt->bindParam(":pName", $this->pName);
                $stmt->bindParam(":price", $this->price);
                $stmt->bindParam(":vendorID", $this->vendorID);
                $stmt->bindParam(":productID", $this->productID);

                $stmt->execute();

                return true;
            } catch (PDOException $e) {
                $this->err = $e->getMessage();
                return false;
            }
        }
        
        public function updateProductMongo($attributes) {
            try {
                $res = $this->productCollectionMongo->updateOne(
                    ['_id' => $this->productID],
                    [
                        '$set' => [
                            'attributes' => $attributes
                        ]
                    ]
                );
                // var_dump($res);
                return true;
            } catch(Exception $e){
                $this->err = $e->getMessage();
                return false;
            }
        }
    }
?>