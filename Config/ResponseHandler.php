<?php
/// Envoi de la réponse au Client
/**
 * @throws Exception
 */
function deliver_response($status, $status_message, $data){
    /// gestion des exceptions
    if (!is_numeric($status)){
        throw new \RuntimeException("status n'est pas un nombre");
    }
    if (!is_string($status_message)){
        throw new \RuntimeException("status_message n'est pas un string");
    }
    if (!is_array($data) && !is_null($data)){
        throw new \RuntimeException("data n'est pas un array");
    }
    /// Paramétrage de l'entête HTTP, suite
    header("HTTP/1.1 $status $status_message");
    /// Paramétrage de la réponse retournée
    $response['status'] = $status;
    $response['status_message'] = $status_message;
    $response['data'] = $data;
    /// Mapping de la réponse au format JSON
    $json_response = json_encode($response, JSON_THROW_ON_ERROR);
    /// Envoi de la réponse au Client
    echo $json_response;
}
