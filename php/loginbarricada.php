<?php
// Iniciar sessão de forma segura
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Conectar ao banco de dados
include 'conectar.php';

// Verificar se o usuário já está logado
if (isset($_SESSION['account_loggedin'])) {
    header('Location: home.php');
    exit;
}

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
    // Debug: mostrar a senha hash do banco e a digitada
    echo "Hash no banco: " . $row['senha'] . "<br>";
    echo "Senha digitada: " . $senha . "<br>";

    // Verificar a senha usando password_verify
    if (password_verify($senha, $row['senha'])) {
        $_SESSION['account_loggedin'] = true;
        $_SESSION['usuario'] = $usuario;
        echo "Login bem-sucedido!";
        header('Location: perfil.php');
        exit;
    } else {
        echo "Senha incorreta!";
    }
    } else {
        echo "Usuário não encontrado!";
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

        <!-- Exibição de mensagens de erro -->
        <?php if (!empty($error)) : ?>
            <p style="color: red;"><?= $error ?></p>
        <?php endif; ?>

    </main>
</body>
</html>
