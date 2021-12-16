<?php

// USUARIO(:id int; nombre: string, email: string, password: string)
// EMPRESA(id: int; nombre: string, direccion: string, premium: boolean)
// PEDIDO(id: int; id_usuario: int, id_empresa: int, pedido: string, fecha: date)
// VALORACION(id: int; id_empresa: int, id_usuario: int, valoracion: int, resena:
// string, inadecuada: boolean)

class EmpresaModel {
    
    private $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;'.'dbname=db_teneloya;charset=utf8', 'root', '');
    }

    public function getEmpresas(){
        $query = $this->db->prepare("SELECT * FROM empresa");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function updatePremium($id){
        $query = $this->db->prepare("UPDATE empresa SET premium = 1 WHERE id = ?");
        $query->execute([$id]);
    }
}
