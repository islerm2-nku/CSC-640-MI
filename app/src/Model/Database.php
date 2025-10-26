<?php
namespace App\Model;

class Database
{
    public static function getPdo(): \PDO
    {
        static $pdo;
        if ($pdo) return $pdo;

        $host = getenv('DB_HOST') ?: 'db';
        $port = getenv('DB_PORT') ?: '3306';
        $db = getenv('DB_DATABASE') ?: 'app';
        $user = getenv('DB_USER') ?: 'appuser';
        $pass = getenv('DB_PASSWORD') ?: 'apppass';
        $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
        $pdo = new \PDO($dsn, $user, $pass, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ]);
        return $pdo;
    }
}