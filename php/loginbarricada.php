<?php 
// Iniciar sessão de forma segura
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Limpar qualquer sessão anterior para evitar problemas durante o teste
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: loginbarricada.php');
    exit;
}

// Verificar se o usuário já está logado
if (isset($_SESSION['account_loggedin']) && $_SESSION['account_loggedin'] === true) {
    header('Location: perfil.php');
    exit;
}

// Conectar ao banco de dados
include 'conectar.php';

// Inicializar a variável de mensagem de erro
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obter dados do formulário
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Consulta segura usando prepared statement
    $stmt = $conn->prepare("SELECT * FROM user WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar se a consulta retornou resultados
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verificar a senha usando password_verify
        if (password_verify($senha, $row['senha'])) {
            $_SESSION['account_loggedin'] = true;
            $_SESSION['usuario'] = $usuario;
            header('Location: perfil.php');
            exit;
        } else {
            $error = "Senha incorreta!";
        }
    } else {
        $error = "Usuário não encontrado!";
    }

    $stmt->close();
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - BYB</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="icon" type="image/png" href="../imgs/logoBYB.jpg">
</head>
<body>
    <header class="holder">
        <h1 style="font-size:var(--font-title);">Login</h1>
        <nav>
            <a href="../index.html">Início</a>
        </nav>
    </header>
    <main class="conteudo" style="max-width:var(--container-max-width);padding:var(--container-padding);">
        <h2 style="font-size:2em;letter-spacing:1px;margin-bottom:24px;text-shadow:0 2px 8px #fbc2eb99;">Acesse sua conta</h2>
        <!-- Formulário de Login -->
        <form action="" method="POST" style="max-width:350px;margin:0 auto;display:flex;flex-direction:column;gap:10px;">
            <input type="text" name="usuario" id="usuario" placeholder="Usuário" required>
            <input type="password" name="senha" id="senha" placeholder="Senha" required>
            <button style="font-size:var(--button-font-size);padding:var(--button-padding);border-radius:var(--button-radius);">Entrar</button>
        </form>
        <h2 style="font-size:1.2em;margin:32px 0 12px 0;text-shadow:0 2px 8px #a1c4fd55;">Não tem uma? Crie:</h2>
        <form action="../html/cadastro.html" method="post" style="max-width:350px;margin:0 auto;">
            <button type="submit">Cadastrar</button>
        </form>
                <br><br>
        <h2 style="font-size:1.2em;margin:32px 0 12px 0;text-shadow:0 2px 8px #a1c4fd55;">Problemas? Contate-nos em cnt.suportebyb@gmail.com<br>Ou preencha o formulário:</h2>
        <form action="https://docs.google.com/forms/d/e/1FAIpQLSf2KBSUuejohx_E6nR1qhZqpmzXM7Xbxp0NucBzYJhRrT8TjQ/viewform?usp=dialog" method="get" target="_blank" style="max-width:350px;margin:0 auto;">
            <button type="submit">Suporte</button>
        </form>


        <!-- Exibição de mensagens de erro -->
        <?php if (!empty($error)) : ?>
            <p style="color: #d7263d;font-weight:bold;max-width:350px;margin:18px auto 0 auto;"><?= $error ?></p>
        <?php endif; ?>
    </main>
        <footer class="holder" style="text-align:center;padding:20px 0;">
        <p style="font-size:var(--font-base);margin:0;">Site desenvolvido para pessoas em obesidade/sobre-peso, ou para quem deseja seguir uma vida mais saudável e ativa.</p>
        <p style="font-size:var(--font-base);">© 2025 BYB - Build Your Body. Todos os direitos reservados.</p>

</body>
</html>
