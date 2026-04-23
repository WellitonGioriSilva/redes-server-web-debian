<?php
// public/config.php
// Configurações de conexão com o banco de dados
// ATENÇÃO: não coloque este arquivo dentro da pasta pública em produção.
//          Aqui está junto por simplicidade do exemplo.

define('DB_HOST', 'localhost');
define('DB_NAME', 'sistema_login');
define('DB_USER', 'appuser');
define('DB_PASS', 'SenhaForte123!');  // mesma senha do SQL
define('DB_CHARSET', 'utf8mb4');

function conectar(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            DB_HOST, DB_NAME, DB_CHARSET
        );
        $opcoes = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $opcoes);
    }
    return $pdo;
}
