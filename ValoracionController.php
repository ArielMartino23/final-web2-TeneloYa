<?php
require_once "TeneloYa/ValoracionModel.php";
require_once "AuthHelper.php";
require_once "View.php";
require_once "PedidoModel.php";
require_once "UsuarioModel.php";

class ValoracionController {
    
    private $valoracionModel;
    private $authHelper;
    private $view;
    private $pedidoModel;
    private $usuarioModel;

    public function __construct() {
        $this->valoracionModel = new ValoracionModel();
        $this->authHelper = new AuthHelper();
        // $this->view = new View();
        $this->pedidoModel = new PedidoModel();
        $this->usuarioModel = new UsuarioModel();
    }

    public function addValoracion(){
        $this->authHelper->verifyLogin();
        $parametros = array($_POST["idEmpresa"], $_POST["idUsuario"], 
            $_POST["valoracion"], $_POST["resena"], $_POST["inadecuada"]);

        

        $valoracion = $this->valoracionModel->getValoracion($_POST["idEmpresa"], $_POST["idUsuario"]);
        $pedidosAnteriores = $this->pedidoModel->getPedidosUsuarioEmpresa($_POST["idEmpresa"], $_POST["idUsuario"]);

        if (!empty($pedidosAnteriores)){
            if (!$valoracion){
                if ($this->parametrosValidos($parametros)){
                    $this->valoracionModel->addValoracion($_POST["idEmpresa"], $_POST["idUsuario"], 
                    $_POST["valoracion"], $_POST["resena"], $_POST["inadecuada"]);
                }else {
                    $this->view->showMensaje("Los parametros no se cargaron correctamente");
                }
            }else {
                $this->view->showMensaje("Ya valoraste este establecimiento");
            }
        }else{
            $this->view->showMensaje("No hiciste pedidos en este establecimiento");
        }
    }

    private function parametrosValidos($array){
        if (isset($array)){
            foreach ($array as $var) {
                // Quiero $var === 0
                if ($var !== "0" || empty($var)){
                    return false;
                }
            }
        }

        return false;
    }

    public function getResenasInadecuadas(){
        $this->authHelper->verifyLogin();

        if ($this->authHelper->esAdmin()){
            $usuarios = $this->usuarioModel->getUsuarios();
            $resenasAuditadas = [];

            if (!empty($usuarios)){
                foreach ($usuarios as $usuario) {
                    $valoraciones = $this->valoracionModel->getValoracionesUsuario($usuario->id);
                    if (!empty($valoraciones)){
                        $cantValoraciones = count($valoraciones);
                        $nombreUsuario = $usuario->nombre;
                        $cantValoracionesInadecuadas = 0;
                        $valInadecuadas = [];

                        foreach ($valoraciones as $val) {
                            if ($val->inadecuada === 1){
                                $cantValoracionesInadecuadas += 1;
                                $valInadecuadas[] = $val;
                            }
                        }
                        
                        $objetoResena = (object) ["cantValoraciones" => $cantValoraciones,
                                                    "nombreUsuario" => $nombreUsuario,
                                                    "cantValoracionesInadecuadas" => $cantValoracionesInadecuadas,
                                                    "valoracionesInadecuadas" => $valInadecuadas];

                        $resenasAuditadas[] = $objetoResena;
                    }
                }
            }

            $this->view->showResenasAuditadas($resenasAuditadas);
        }else {
            $this->view->showMensaje("No tenes permiso para realizar esta accion");
        }
    }
}
