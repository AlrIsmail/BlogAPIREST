<?php
require_once DATABASE_PATH . "Database.php";
require_once DATABASE_PATH . "User.php";
require_once MODEL_PATH . "jwt_utils.php";

class AuthController{
    // public function loginAction($data){
    //     $user = new User();
    //     $user->login($data);
    // }
    public function testAction(){
        echo "test";
    }

    /**
     * @throws JsonException
     */
    public function PostAction($data){
        // définir the secret key pour la génération des jetons JWT
        $jwt_secret = 'secret';

        // define the period of validity of the token
        $jwt_duration = 3600; // 1 heure

        $data = (array)json_decode(file_get_contents("php://input"), TRUE, 512, JSON_THROW_ON_ERROR);

        // username and password given by the user
        $username = $data['username'];
        $password = $data['password'];

        // username and password stored in the database
        $user = new User();
        $result = $user->selectUserPass($username, $password);
        // check if the username and password are correct
        if(!empty($result) && 
            end($result)['UserName'] === $username &&
            end($result)['Password'] === $password){
            // generate a JWT token
            $role = end($result)['Role'];
            $idUser = end($result)['IdUser'];
            $jwt_headers = array('alg' => 'HS256', 'typ' => 'JWT');
            $jwt_payload = array('user' => $idUser, 'role'=>$role, 'exp' => time() + $jwt_duration);
            $jwt = generate_jwt($jwt_headers, $jwt_payload, $jwt_secret);
            deliver_response(200, "Login successful", (array) $jwt);
        }else{
            deliver_response(401, "Login failed username or password incorrect", NULL);
        }
    }
}