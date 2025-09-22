<?php
require_once __DIR__ . '/../env_loader.php';
loadEnv(__DIR__ . '/../../.env');

class MySQLDatabase {
    private static $pdo = null;

    public static function getConnection() {

        if (self::$pdo === null) {
            $host = getenv('MYSQL_HOST') ?: 'mysql';
            $dbname = getenv('MYSQL_DATABASE') ?: 'erp';
            $username = getenv('MYSQL_USER') ?: 'mysql_user';
            $password = getenv('MYSQL_PASSWORD') ?: 'mysql123';
            $port = getenv('MYSQL_PORT') ?: '3306';

            try {
                self::$pdo = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $username, $password);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Erro na conexão: ' . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}