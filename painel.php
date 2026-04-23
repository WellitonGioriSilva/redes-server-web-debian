<?php
// public/painel.php  — página protegida
session_start();
require_once __DIR__ . '/config.php';

if (empty($_SESSION['usuario_id'])) {
    header('Location: index.html');
    exit;
}

$nome = htmlspecialchars($_SESSION['usuario_nome'], ENT_QUOTES, 'UTF-8');

// Busca dados completos do usuário
$pdo  = conectar();
$stmt = $pdo->prepare('SELECT nome, email, criado_em FROM usuarios WHERE id = ?');
$stmt->execute([$_SESSION['usuario_id']]);
$u    = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel — Sistema Login</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@400;500;600&display=swap');

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:      #0f0f13;
            --surface: #1a1a24;
            --accent:  #7c6af7;
            --text:    #e8e8f0;
            --muted:   #8888aa;
            --radius:  14px;
        }

        body {
            min-height: 100vh;
            background: var(--bg);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .card {
            background: var(--surface);
            border: 1px solid rgba(124,106,247,.25);
            border-radius: var(--radius);
            padding: 2.5rem 3rem;
            max-width: 480px;
            width: 100%;
            text-align: center;
            animation: fadeUp .5s ease;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .avatar {
            width: 72px; height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), #c084fc);
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem;
            margin: 0 auto 1.5rem;
        }

        h1 { font-family: 'DM Serif Display', serif; font-size: 1.8rem; margin-bottom: .5rem; }
        p.sub { color: var(--muted); font-size: .95rem; margin-bottom: 2rem; }

        .info { text-align: left; background: rgba(255,255,255,.04); border-radius: 10px; padding: 1.25rem 1.5rem; margin-bottom: 2rem; }
        .info div { display: flex; justify-content: space-between; padding: .4rem 0; border-bottom: 1px solid rgba(255,255,255,.06); font-size: .9rem; }
        .info div:last-child { border-bottom: none; }
        .info span:first-child { color: var(--muted); }

        a.btn-logout {
            display: inline-block;
            padding: .75rem 2rem;
            background: transparent;
            border: 1.5px solid rgba(124,106,247,.5);
            color: var(--accent);
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background .2s, border-color .2s;
        }
        a.btn-logout:hover { background: rgba(124,106,247,.15); border-color: var(--accent); }
    </style>
</head>
<body>
    <div class="card">
        <div class="avatar">👤</div>
        <h1>Olá, <?= $nome ?>!</h1>
        <p class="sub">Você está autenticado com sucesso.</p>

        <div class="info">
            <div><span>Nome</span>      <span><?= htmlspecialchars($u['nome']) ?></span></div>
            <div><span>E-mail</span>    <span><?= htmlspecialchars($u['email']) ?></span></div>
            <div><span>Cadastrado em</span><span><?= date('d/m/Y H:i', strtotime($u['criado_em'])) ?></span></div>
        </div>

        <a href="logout.php" class="btn-logout">Sair da conta</a>
    </div>
</body>
</html>
