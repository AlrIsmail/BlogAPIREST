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

    public function PostAction($data){
        // définir the secret key pour la génération des jetons JWT
        $jwt_secret = 'secret';

        // define the period of validity of the token
        $jwt_duration = 3600; // 1 heure

        $data = (array) json_decode(file_get_contents("php://input"), TRUE);

        // username and password given by the user
        $username = $data['username'];
        $password = $data['password'];

        // username and password stored in the database
        $user = new User();
        $result = $user->selectUserPass($username, $password);
        $valid_username = $result[0]['UserName'];
        $valid_password = $result[0]['Password'];
        // vérifier si les identifiants sont valides
        if ($username === $valid_username && $password === $valid_password) {
            // générer un jeton JWT
            $jwt_headers = array('alg' => 'HS256', 'typ' => 'JWT');
            $jwt_payload = array('username' => $username, 'exp' => time() + $jwt_duration);
            $jwt = generate_jwt($jwt_headers, $jwt_payload, $jwt_secret);
            deliver_response(200, "Login successful", (array) $jwt);
        }
        else {
            deliver_response(401, "Login failed", NULL);
        }
    }
}