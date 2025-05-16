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
</head>
<body>
    <header class="holder">
        <h1>Login</h1>
        <nav>
            <a href="../index.html">Início</a>
        </nav>
    </header>

    <main class="conteudo">
        <h2>Acesse sua conta</h2>

        <!-- Formulário de Login -->
        <form action="" method="POST">
            <label for="usuario">Usuário</label>
            <input type="text" name="usuario" id="usuario" placeholder="Usuário" required>

            <label for="senha">Senha</label>
            <input type="password" name="senha" id="senha" placeholder="Senha" required>

            <button type="submit">Entrar</button>
        </form>
            <br><h2>Não tem uma? Crie:</h2>
        <form action="../html/cadastro.html" method="post">
            <button type="submit">Cadastrar</button>
        </form>

        <!-- Exibição de mensagens de erro -->
        <?php if (!empty($error)) : ?>
            <p style="color: red;"><?= $error ?></p>
        <?php endif; ?>

    </main>
</body>
</html>
