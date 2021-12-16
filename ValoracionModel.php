<?php

// USUARIO(id: int; nombre: string, email: string, password: string)
// EMPRESA(id: int; nombre: string, direccion: string, premium: boolean)
// PEDIDO(id: int; id_usuario: int, id_empresa: int, pedido: string, fecha: date)
// VALORACION(id: int; id_empresa: int, id_usuario: int, valoracion: int, resena:
// string, inadecuada: boolean)
// JOIN(id: int; id_empresa: int, id_usuario: int, valoracion: int, resena:
// string, inadecuada: boolean, nombre_empresa: string, nombre_usuario: string)

class ValoracionModel {
    
    private $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;'.'dbname=db_teneloya;charset=utf8', 'root', '');
    }

    public function addValoracion($id_empresa, $id_usuario, $valoracion, $resena, $inadecuada){
        $query = $this->db->prepare("INSERT INTO valoracion 
            (id_empresa, id_usuario, valoracion, resena, inadecuada) VALUES (?,?,?,?,?)");
        $query->execute([$id_empresa, $id_usuario, $valoracion, $resena, $inadecuada]);
    }

    public function getValoracion($id_empresa, $id_usuario){
        $query = $this->db->prepare("SELECT * FROM valoracion
             WHERE id_empresa = ? AND id_usuario = ?");
        $query->execute([$id_empresa, $id_usuario]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function getPromedioEmpresa($id_empresa){
        $query = $this->db->prepare("SELECT AVG(valoracion) AS promedio 
            FROM valoracion WHERE id_empresa = ?");
        $query->execute([$id_empresa]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function getValoracionesUsuario($id_usuario){
        $query = $this->db->prepare(
            "SELECT valoracion.*, 
            empresa.nombre AS nombre_empresa,
            usuario.nombre AS nombre_usuario
            FROM valoracion
            INNER JOIN empresa
            ON valoracion.id_empresa = empresa.id
            INNER JOIN usuario
            ON valoracion.id_usuario = usuario.id");
        $query->execute([$id_usuario]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
}
