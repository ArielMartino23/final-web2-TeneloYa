<?php

class UsuarioModel{
    
    private $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;'.'dbname=db_toktak;charset=utf8', 'root', '');
    }

    public function getUsuario($id_usuario){
        $query = $this->db->prepare("SELECT * FROM usuarios WHERE id = ?");
        $query->execute([$id_usuario]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function getUsuarios(){
        $query = $this->db->prepare("SELECT * FROM usuarios");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
}
