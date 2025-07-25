<?php

require_once '../models/Cupom.php';
require_once '../validators/CupomValidator.php';
require_once '../config/constants.php';


class CupomController {

    public function salvar(){
        $codigo = $_POST['codigo'];
        $valor_minimo = $_POST['valor_minimo'];
        $percentual_desconto = $_POST['percentual_desconto'];
        $validade = $_POST['validade'];

        try {
            CupomValidator::validarSalvar($codigo, $valor_minimo, $percentual_desconto, $validade);
            $cupom = new Cupom();
            $cupom = $cupom->salvar($codigo, $valor_minimo, $percentual_desconto, $validade);

            $this->listarTodos();
        } catch (Exception $e) {
            ErrorHandler::handleError($e->getMessage(), "../../cupom/listarTodos");
        }
    }

    public function validar(){
        header('Content-Type: application/json');

        $codigo = $_POST['codigo'] ?? '';
        $subtotal = floatval($_POST['subtotal'] ?? 0);

        try {
            CupomValidator::validarValidar($codigo, $subtotal);
            $cupomModel = new Cupom();
            $cupom = $cupomModel->buscarPorCodigo($codigo, $subtotal);
            
            if (!$cupom) {
                echo json_encode(['sucesso' => false, 'mensagem' => 'Cupom não encontrado.']);
                exit;
            }

            // echo "<pre>";die(var_dump([
            //     'subtotal' => $subtotal,
            //     'cupom' => $cupom['valor_minimo']
            // ]));

            if ($subtotal < $cupom['valor_minimo']) {
                echo json_encode(['sucesso' => false, 'mensagem' => "Valor subtotal insuficiente. \nValor mínimo: R$".$cupom['valor_minimo']]);
                exit;
            }        

            $desconto = $subtotal * ($cupom['percentual'] / 100);

            echo json_encode([
                'sucesso' => true,
                'desconto' => $desconto
            ]);
            exit;
        } catch (Exception $e) {
            echo json_encode(['sucesso' => false, 'mensagem' => $e->getMessage()]);
            exit;
        }
    }

    public static function listarTodos() {
        $cupons = new Cupom();        
        $cupons = $cupons->listarTodos();
        require_once '../views/cupons/index.php';
    }
}

?>