<?php
// public/login.php
session_start();
require_once __DIR__ . '/config.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'msg' => 'Método não permitido.']);
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

if ($email === '' || $senha === '') {
    echo json_encode(['ok' => false, 'msg' => 'Preencha todos os campos.']);
    exit;
}

try {
    $pdo  = conectar();
    $stmt = $pdo->prepare('SELECT id, nome, senha FROM usuarios WHERE email = ?');
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    if (!$usuario || !password_verify($senha, $usuario['senha'])) {
        echo json_encode(['ok' => false, 'msg' => 'E-mail ou senha incorretos.']);
        exit;
    }

    // Regenera o ID de sessão por segurança
    session_regenerate_id(true);
    $_SESSION['usuario_id']   = $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];

    echo json_encode([
        'ok'   => true,
        'msg'  => 'Login realizado!',
        'nome' => $usuario['nome'],
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'msg' => 'Erro no servidor. Tente novamente.']);
}
