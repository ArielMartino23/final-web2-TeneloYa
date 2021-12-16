<?php

// USUARIO(id: int; nombre: string, email: string, password: string)
// EMPRESA(id: int; nombre: string, direccion: string, premium: boolean)
// PEDIDO(id: int; id_usuario: int, id_empresa: int, pedido: string, fecha: date)
// VALORACION(id: int; id_empresa: int, id_usuario: int, valoracion: int, resena:
// string, inadecuada: boolean)

class PedidoModel {
    
    private $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;'.'dbname=db_teneloya;charset=utf8', 'root', '');
    }

    public function getPedidosUsuarioEmpresa($id_empresa, $id_usuario){
        $query = $this->db->prepare("SELECT * FROM pedido WHERE id_empresa = ? AND id_usuario = ?");
        $query->execute([$id_empresa, $id_usuario]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
}
