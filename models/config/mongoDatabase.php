<?php 
    class MongoDatabase {
        private $host = "mongodb://localhost:27017";
        public $client;
        public $productDocument;

        public function getConnection(){
            require_once '../vendor/autoload.php'; // include Composer's autoloader

            $this->client = null;
            try{
                $this->client = new MongoDB\Client("mongodb://localhost:27017");
                // $this->collection = $this->client->lazada->product; // parent collection là demo, child là beers, insert document có attributes name và brewery
                $this->productDocument = $this->client->lazada->products; // parent collection là demo, child là beers, insert document có attributes name và brewery

            }catch(PDOException $exception){
                echo "Database could not be connected: " . $exception->getMessage();
            }
            return $this->client;
        }
    }  
?>