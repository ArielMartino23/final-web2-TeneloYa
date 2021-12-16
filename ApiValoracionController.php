<?php

class ApiValoracionController {
    private $valoracionModel;
    private $empresaModel;
    private $view;
    private $data;

    public function __construct() {
        $this->valoracionModel = new ValoracionModel();
        $this->empresaModel = new EmpresaModel();
        $this->view = new ApiView();
        $this->data = file_get_contents("php://input");
    }

    public function getData(){
        return json_decode($this->data);
    }

    public function getResenasPorEmpresa($params = null){
        $idEmpresa = $params[":ID"];
        $empresa = $this->empresaModel($idEmpresa);
        
        if ($empresa){
            $resenas = $this->valoracionModel->getResenasPorEmpresa($idEmpresa);
            return $this->view->response($resenas, 200);
        }else {
            return $this->view->response("La empresa con el id=$idEmpresa no existe", 404);
        }
    }

    public function editarResena($params = null){
        $idResena = $params["idResena"];
        $resena = $this->valoracionModel->getValoracionApi($idResena);
        $body = $this->getData();

        try {
            if ($resena){
                $this->valoracionModel->editarResena($body->id_empresa, $body->id_usuario, $body->valoracion, 
                                                        $body->resena, $body->inadecuada);
                return $this->view->response("Se edito con exito la reseña con id=$idResena", 200);
            }else {
                return $this->view->response("La reseña con id=$idResena no existe", 404);
            }
        } catch (\Throwable $th) {
            return $this->view->response("Hubo un error al editar la reseña con id=$idResena", 501);
        }
        
    }
}
