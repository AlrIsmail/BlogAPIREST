<?php
require DATABASE_PATH . "User.php";

class AuthController{
    public function loginAction($data){
        $user = new User();
        $user->login($data);
    }
    public function testAction(){
        echo "test";
    }
}