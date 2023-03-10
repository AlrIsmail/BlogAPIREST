<?php
#TODO : remake
require DATABASE_PATH . "User.php";
require DATABASE_PATH . "Publish.php";

class userController
{
    // GET /user/publisher (get all publisher)
    public function publisherGetAction($data)
    {
        $publish = new User();
        $publish->selectAll("publisher");
    }

    // GET /user/publisher/{id} (get publisher by id)
    public function publisherIdGetAction($data)
    {
        $publish = new User();
        $publish->select($data['id'], "publisher");
    }



}