<?php
    include_once '../../config/mysqlDatabase.php';
    include_once '../../src/auth/register.php';

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
        signUpAPI();
    }

    function signUpAPI()
    {
        $db = new MySQLDatabase();
        $data = json_decode(file_get_contents("php://input"));

        $reg = new UserRegistration(
            $data->name,
            $data->username,
            $data->password,
            $data->role,
            $data->address,
            $data->long,
            $data->lat,
            $data->hubID
        );

        $userExist = $reg->checkUserExist($db->getConnection('lazada_guest', 'password'));

        if ($userExist == true) {
            echo json_encode(
                array("message" => "Username already exists. Please choose a different username")
            );
            return http_response_code(400);
            exit();
        } else {
            $isSignedUp = $reg->signup($db->getConnection('lazada_guest', 'password'));

            if ($isSignedUp == true) {
                http_response_code(200);
                echo json_encode(
                    array("message" => "New user successfully created")
                );
            } else {
                http_response_code(400);
                echo json_encode(
                    array("message" => $reg->err)
                );
            }
        }
    }
