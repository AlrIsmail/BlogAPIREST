<?php
// using the API AUTH url : http://localhost/BlogAPIREST/index.php/v1/Api/Auth//
// using the API BLOG url GET ALL Articles and votes : http://localhost/BlogAPIREST/index.php/v1/Api/Blog/Acrticles/
// using the API BLOG url GET ONE Article and votes : http://localhost/BlogAPIREST/index.php/v1/Api/Blog/Acrticle/{id}
// using the API BLOG url POST ONE Article : http://localhost/BlogAPIREST/index.php/v1/Api/Blog/Publish/
// using the API BLOG url PUT ONE Article : http://localhost/BlogAPIREST/index.php/v1/Api/Blog/Publish/{id}
// using the API BLOG url DELETE ONE Article : http://localhost/BlogAPIREST/index.php/v1/Api/Blog/Publish/{id}
// using the API BLOG url Post ONE Vote : http://localhost/BlogAPIREST/index.php/v1/Api/Blog/Vote/{id}
// using the API BLOG url PUT ONE Vote : http://localhost/BlogAPIREST/index.php/v1/Api/Blog/Vote/{id}
// using the API BLOG url DELETE ONE Vote : http://localhost/BlogAPIREST/index.php/v1/Api/Blog/Vote/{id}

// ex : $result = file_get_contents($url, false, stream_context_create(array('http' => array('method' => 'GET'))));
//           return json_decode($result, true, 512, JSON_THROW_ON_ERROR);


class Client {

    private static string $url ="https://blogfi.faister.fr/index.php/v1/Api/Blog/";
    private static string $urlAuth ="https://blogfi.faister.fr/index.php/v1/Api/Auth//";
    /**
     * @throws JsonException
     */
    public static function getAuth($username, $password){
        $url = self::$urlAuth;
        $data = array('username' => $username, 'password' => $password);
        $data_string = json_encode($data, JSON_THROW_ON_ERROR);
        $result = file_get_contents(
            $url,
            false,
            stream_context_create(
                array(
                    'http' => array(
                        'method' => 'POST',
                        'content' => $data_string,
                        'header' => array(
                            'Content-Type: application/json' . "\r\n"
                            . 'Content-Length: ' . strlen($data_string) . "\r\n"
                        )
                    )
                )
            )
        );
        return json_decode($result, true, 512);
    }
    /**
     * @throws JsonException
     */
    public static function getArticles()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $url = self::$url.'Articles/';
        // add the token to the header if exist
        if (empty($_SESSION['token'])){
            $result = file_get_contents($url, false, stream_context_create(array('http' => array('method' => 'GET'))));
        }else{
            $token = $_SESSION['token'];
            $result = file_get_contents($url, false, stream_context_create(array('http' => array('method' => 'GET', 'header' => array('Authorization: Bearer ' . $token)))));
        }
        
        return json_decode($result, true, 512);
    }

    /**
     * @throws JsonException
     */
    public static function getArticle($id)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $url = self::$url.'Article/' . $id;
        $result = file_get_contents($url, false, stream_context_create(array('http' => array('method' => 'GET'))));
        return json_decode($result, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws JsonException
     */
    public static function postArticle($title, $content){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $url = self::$url.'Publish/';
        $data = array('title' => $title, 'content' => $content, 'dateCreated' => 'null', 'dateModified' => 'null');
        $data_string = json_encode($data, JSON_THROW_ON_ERROR);
        // add bearer token to the header
        $result = @file_get_contents(
            $url,
            false,
            stream_context_create(
                array(
                    'http' => array(
                        'method' => 'POST',
                        'content' => $data_string,
                        'header' => "Authorization: Bearer " . $_SESSION['token'] . "\r\n" . 'Content-Type: application/json' . "\r\n"
                            . 'Content-Length: ' . strlen($data_string) . "\r\n"

                    )
                )
            )
        );
        return json_decode($result, true, 512);
    }

    /**
     * @throws JsonException
     */
    public static function putArticle($id, $data){
        $url = self::$url.'Publish/' . $id;
        $data_string = json_encode($data, JSON_THROW_ON_ERROR);
        $result = file_get_contents(
            $url,
            false,
            stream_context_create(
                array(
                    'http' => array(
                        'method' => 'PUT',
                        'content' => $data_string,
                        'header' => array(
                            'Content-Type: application/json' . "\r\n"
                            . 'Content-Length: ' . strlen($data_string) . "\r\n"
                        )
                    )
                )
            )
        );
        return json_decode($result, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws JsonException
     */
    public static function deleteArticle($id){
        $url = self::$url.'Publish/' . $id;
        $result = file_get_contents($url, false, stream_context_create(array('http' => array('method' => 'DELETE'))));
        return json_decode($result, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws JsonException
     */
    public static function postVote($id, $data){
        $url = self::$url.'Vote/' . $id;
        $data_string = json_encode($data, JSON_THROW_ON_ERROR);
        $result = file_get_contents(
            $url,
            false,
            stream_context_create(
                array(
                    'http' => array(
                        'method' => 'POST',
                        'content' => $data_string,
                        'header' => array(
                            'Content-Type: application/json' . "\r\n"
                            . 'Content-Length: ' . strlen($data_string) . "\r\n"
                        )
                    )
                )
            )
        );
        return json_decode($result, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws JsonException
     */
    public static function putVote($id, $data){
        $url = self::$url.'Vote/' . $id;
        $data_string = json_encode($data, JSON_THROW_ON_ERROR);
        $result = file_get_contents(
            $url,
            false,
            stream_context_create(
                array(
                    'http' => array(
                        'method' => 'PUT',
                        'content' => $data_string,
                        'header' => array(
                            'Content-Type: application/json' . "\r\n"
                            . 'Content-Length: ' . strlen($data_string) . "\r\n"
                        )
                    )
                )
            )
        );
        return json_decode($result, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws JsonException
     */
    public static function deleteVote($id){
        $url = self::$url.'Vote/' . $id;
        $result = file_get_contents($url, false, stream_context_create(array('http' => array('method' => 'DELETE'))));
        return json_decode($result, true, 512, JSON_THROW_ON_ERROR);
    }

}

