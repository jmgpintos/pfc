<?php

class postModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getPosts()
    {
        $post = $this->_db->query("SELECT * FROM posts");

        return $post->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertarPost($titulo, $cuerpo)
    {
        $this->_db->prepare("INSERT INTO posts VALUES(null, :titulo, :cuerpo)")
                ->execute(
                        array(
                            ':titulo' => $titulo,
                            ':cuerpo' => $cuerpo
                        )
        );
    }

}
