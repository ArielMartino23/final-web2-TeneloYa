<?php
require_once "ValoracionModel.php";
require_once "AuthHelper.php";
require_once "View.php";
require_once "EmpresaModel.php";

class EmpresaController {

    private $valoracionModel;
    private $authHelper;
    private $view;
    private $empresaModel;

    public function __construct() {
        $this->valoracionModel = new ValoracionModel();
        $this->authHelper = new AuthHelper();
        // $this->view = new View();
        $this->empresaModel = new EmpresaModel();
    }

    public function getEmpresasPremium(){
        $this->authHelper->verifyLogin();
        
        if ($this->authHelper->esAdmin()){
            $valoracionPremium = $_POST["valoracion"];
            $empresas = $this->empresaModel->getEmpresas();
            if ($empresas && !empty($valoracionPremium)){
                foreach ($empresas as $empresa) {
                    $valoracionEmpresa = $this->valoracionModel->getPromedioEmpresa($empresa->id)->promedio;
                    if ($valoracionEmpresa > $valoracionPremium){
                        $this->empresaModel->updatePremium($empresa->id);
                    }
                }
            }else {
                $this->view->showMensaje("No hay empresas o no cargaste valoracion");
            }
        }else {
            $this->view->showMensaje("No tenes permiso de realizar esta accion");
        }
    }
}
