<?php
require_once '../config/constants.php';
require_once '../models/Pedido.php';
require_once '../controllers/MailController.php';

class WebhookController
{
    public function atualizar()
    {
        $json = file_get_contents('php://input');
        $dados = json_decode($json, true);

        if (!isset($dados['id']) || !isset($dados['status'])) {
            http_response_code(400);
            echo json_encode(['erro' => 'Dados inválidos']);
            exit;
        }

        $pedidoId = (int)$dados['id'];
        $status = strtolower(trim($dados['status']));

        $pedido = new Pedido();
        $DadosPedido = $pedido->obterPorId($pedidoId);

        if(!empty($DadosPedido)){

            if ($status === 'cancelado' || $status === 'inativo') {
                $pedido->deletar($pedidoId);
            } else {
                $pedido->atualizarStatus($pedidoId, $status);
            }

            $emailCliente = $DadosPedido['email_cliente'];
            $tituloEmail = NOME_PROJETO . " - Atualização - Pedido número $pedidoId";
            $corpoEmail  = "Olá, \n\nSegue o novo status de seu pedido pedido de número $pedidoId:\n"
                            ."\n'".ucfirst($status)."'";

            $mail = new MailController();
            $mail->sendMail($emailCliente, $tituloEmail, $corpoEmail);

            echo json_encode(['sucesso' => true]);
        } else {
            echo json_encode(['sucesso' => false, 'message' => 'Pedido não encontrado']);
        }
    }
}
