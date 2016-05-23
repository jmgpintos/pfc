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

    public function getPost($id)
    {
        $id = (int) $id;
        $post = $this->_db->query("SELECT * FROM posts WHERE id = " . $id);
        return $post->fetch(PDO::FETCH_ASSOC); //TODO devolver ASSOC, NUM o BOTH
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

    public function editarPost($id, $titulo, $cuerpo)
    {
        $id = (int) $id;
        //el metodo prepare limpia los parametros de inyecciones SQL y de XSS
        $this->_db->prepare("UPDATE posts SET titulo = :titulo, cuerpo = :cuerpo WHERE id = :id")
                ->execute(
                        array(
                            ':id' => $id,
                            ':titulo' => $titulo,
                            ':cuerpo' => $cuerpo
                        )
        );
    }

    public function eliminarPost($id)
    {
        $id = (int) $id;

        $post = $this->_db->query("DELETE FROM posts WHERE id = " . $id);
    }

    public function crear100()
    {
        for ($i = 0; $i < 100; $i++) {
            $r = rand(00000, 99999);
            $titulo = 'post_' . $r;
            $cuerpo = 'cuerpo del post # ' . $r;
            $this->insertarPost($titulo, $cuerpo);
        }
    }
    

}
