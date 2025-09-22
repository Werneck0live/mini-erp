<?php

class WebhookValidator
{

    public static function validarPayload($dados)
    {
        if (!isset($dados['id']) || !isset($dados['status'])) {
            return ['erro' => 'Dados invalidos: "id" e "status" sao obrigatorios'];
        }

        if ((int)$dados['id'] <= 0 || !filter_var($dados['id'], FILTER_VALIDATE_INT)) {
            return ['erro' => 'O campo "id" deve ser um numero inteiro positivo'];
        }

        $status = strtolower(trim($dados['status']));
        if (empty($status)) {
            return ['erro' => 'O campo "status" nao pode ser vazio'];
        }

        return null;
    }
}
