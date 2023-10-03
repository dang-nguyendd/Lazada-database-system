<?php
    include_once '../../config/mysqlDatabase.php';
    include_once '../../src/auth/auth.php';
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
        loginAPI();
    }


    function loginAPI()
    {
        $db = new MySQLDatabase();
        $data = json_decode(file_get_contents("php://input"));

        $auth = new UserAuthentication($data->username, $data->password);

        $isUser = $auth->checkUserAndRole($db->getConnection('lazada_guest', 'password'));

        if ($isUser == false) {
            http_response_code(404);
            echo json_encode(
                array("message" => "No users found.")
            );
        } else {
            $isLoggedIn = false;
            if ($auth->getRole() == 'Vendor') {
                $isLoggedIn = $auth->login('Vendors', 'vUsername', 'vPassword', $db->getConnection('lazada_auth', 'password'));
            } elseif ($auth->getRole() == 'Customer') {
                $isLoggedIn = $auth->login('Customers', 'cUsername', 'cPassword', $db->getConnection('lazada_auth', 'password'));
            } else {
                $isLoggedIn = $auth->login('Shippers', 'sUsername', 'sPassword', $db->getConnection('lazada_auth', 'password'));
            }

            if ($isLoggedIn == true) {
                $user = array(
                    "id" => $auth->getID(),
                    "username" =>  $auth->getUsername(),
                    "role" => $auth->getRole()
                );
                echo json_encode($user);
                http_response_code(200);
            } else {
                echo json_encode(
                    array("message" => "Invalid username or password")
                );
                http_response_code(401);
            }
        }
    }


