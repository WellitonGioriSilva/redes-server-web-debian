<?php
// public/cadastrar.php
session_start();
require_once __DIR__ . '/config.php';

header('Content-Type: application/json; charset=utf-8');

// Só aceita POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'msg' => 'Método não permitido.']);
    exit;
}

$nome  = trim($_POST['nome']  ?? '');
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

// Validações básicas
if ($nome === '' || $email === '' || $senha === '') {
    echo json_encode(['ok' => false, 'msg' => 'Preencha todos os campos.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['ok' => false, 'msg' => 'E-mail inválido.']);
    exit;
}

if (strlen($senha) < 6) {
    echo json_encode(['ok' => false, 'msg' => 'A senha deve ter ao menos 6 caracteres.']);
    exit;
}

try {
    $pdo = conectar();

    // Verifica se e-mail já existe
    $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode(['ok' => false, 'msg' => 'Este e-mail já está cadastrado.']);
        exit;
    }

    // Insere o novo usuário com senha hasheada
    $hash = password_hash($senha, PASSWORD_BCRYPT);
    $ins  = $pdo->prepare('INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)');
    $ins->execute([$nome, $email, $hash]);

    echo json_encode(['ok' => true, 'msg' => 'Cadastro realizado com sucesso!']);

} catch (PDOException $e) {
    http_response_code(500);
    // Em produção, não exponha $e->getMessage()
    echo json_encode(['ok' => false, 'msg' => 'Erro no servidor. Tente novamente.']);
}
