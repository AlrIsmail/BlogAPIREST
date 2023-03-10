<?php
require DATABASE_PATH . "Publish.php";
require DATABASE_PATH . "Article.php";
require DATABASE_PATH . "User.php";

class ArticleController
{
    // GET /article (get all article)
    public function getAction($data)
    {
        $article = new Article();
        $article->selectAll();
    }

    // GET /article/{id} (get article by id)
    public function idGetAction($data)
    {
        $article = new Article();
        $article->select($data['id']);
    }

    // POST /article (create new article)
    public function postAction($data)
    {
        $article = new Article();
        $article->insert($data);
    }

    // PUT /article/{id} (update article by id)
    public function idPutAction($data)
    {
        $article = new Article();
        $article->update($data['id'], $data);
    }

    // DELETE /article/{id} (delete article by id)
    public function idDeleteAction($data)
    {
        $article = new Article();
        $article->delete($data['id']);
    }

    // GET /article/publisher (get all publisher)
    public function publisherGetAction($data)
    {
        $publish = new User();
        $publish->selectAll("publisher");
    }

    // GET /article/publisher/{id} (get publisher by id)
    public function publisherIdGetAction($data)
    {
        $publish = new User();
        $publish->select($data['id'], "publisher");
    }

    // GET /article/author (get all author)
    public function authorGetAction($data)
    {
        $author = new User();
        $author->selectAll("author");
    }

    // GET /article/author/{id} (get author by id)
    public function authorIdGetAction($data)
    {
        $author = new User();
        $author->select($data['id'], "author");
    }

    // GET /article/publish (get all publish)
    public function publishGetAction($data)
    {
        $publish = new Publish();
        $publish->selectAll();
    }

    // GET /article/publish/{id} (get publish by id)
    public function publishIdGetAction($data)
    {
        $publish = new Publish();
        $publish->select($data['id']);
    }

    // POST /article/publish (create new publish)
    public function publishPostAction($data)
    {
        $publish = new Publish();
        $publish->insert($data);
    }

}