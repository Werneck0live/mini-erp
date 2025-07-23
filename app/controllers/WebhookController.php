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

            $erroAtualizarStatus = false;
            if ($status === 'cancelado' || $status === 'inativo') {
                $erroAtualizarStatus = $pedido->deletar($pedidoId) ? false : true;
            } else {
                $erroAtualizarStatus = $pedido->atualizarStatus($pedidoId, $status) ? false : true;
            }

            if($erroAtualizarStatus){
                echo json_encode(["sucesso" => false, 'message' => "Erro ao atualizar pedido"]);
                exit();
            }

            $emailCliente = $DadosPedido['email_cliente'];
            $tituloEmail = NOME_PROJETO . " - Atualização - Pedido número $pedidoId";
            $corpoEmail  = "Olá, \n\nSegue o novo status de seu pedido pedido de número $pedidoId:\n"
                            ."\n'".ucfirst($status)."'";

            try {
                $mail = new MailController();
                $mail->sendMail($emailCliente, $tituloEmail, $corpoEmail);
            } catch (Exception $e) {
                echo json_encode(['sucesso' => false, 'message' => 'Aviso: Pedido atualizado. Houve problema no envio de email: ' . $e->getMessage().". Revise as configurações do seu servidor SMTP"]);
                exit();
            }

            echo json_encode(['sucesso' => true]);
        } else {
            echo json_encode(['sucesso' => false, 'message' => 'Pedido não encontrado']);
        }
    }
}
