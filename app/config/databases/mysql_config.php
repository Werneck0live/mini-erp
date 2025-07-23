<?php
require_once __DIR__ . '/../env_loader.php';
loadEnv(__DIR__ . '/../../.env');

class MySQLDatabase {
    private static $pdo = null;

    public static function getConnection() {

        if (self::$pdo === null) {
            $host = getenv('MYSQL_HOST') ?: 'mysql';
            $dbname = getenv('MYSQL_DBNAME') ?: 'erp';
            $username = getenv('MYSQL_USERNAME') ?: 'localdocker';
            $password = getenv('MYSQL_PASSWORD') ?: 'localdocker';

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