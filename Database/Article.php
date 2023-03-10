<?php

class Article extends Database{
    public function __construct(){
        parent::getInstance('Articles');
    }

    public function select($id){
        return parent::select($id);
    }

    public function selectAll(){
        return parent::selectAll();
    }

    public function insert($data){
        return parent::insert($data);
    }

    public function update($id, $data){
        return parent::update($id, $data);
    }

    public function delete($id){
        return parent::delete($id);
    }
}