<?php
// config/ErrorHandler.php

class ErrorHandler {
    /**
     * Define uma mensagem de erro e faz o redirecionamento.
     *
     * @param string $mensagem Mensagem de erro.
     * @param string $redirecionar Para onde redirecionar após a mensagem.
     */
    public static function handleError($mensagem, $redirecionar) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['mensagem_erro'] = $mensagem;

        header("Location: $redirecionar");
        exit();
    }
}
