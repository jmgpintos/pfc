<?php

class postModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getPosts()
    {
        $post = $this->_db->query("SELECT * FROM imagen");
        
        return $post->fetchAll(PDO::FETCH_ASSOC);
    }

}
