<?php

class DB {
    private static $pdo = null;

    public static function getConnection() {
        if (self::$pdo === null) {
            $host = 'mysql';
            $dbname = 'erp';
            $username = 'localdocker';
            $password = 'localdocker';

            try {
                self::$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Erro na conexão: ' . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}