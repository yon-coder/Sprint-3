<?php
// Iniciar sessão
session_start();

// Conectar ao banco de dados
include 'conectar.php';

$usuario = $_POST['username'];  // Alterado para username
$senha = $_POST['password'];  // Alterado para password

// Verificar se o usuário já está logado
if (isset($_SESSION['account_loggedin'])) {
    header('Location: home.php');
    exit;
}

// Consultar o banco de dados
$sql = "SELECT * FROM user WHERE usuario='$usuario'";
$result = $conn->query($sql);

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
            <label for="username">Usuário</label>
            <input type="text" name="username" id="username" placeholder="Usuário" required>
            
            <label for="password">Senha</label>
            <input type="password" name="password" id="password" placeholder="Senha" required>
            
            <button type="submit">Entrar</button>
        </form>

        <?php
            // Verificar se a consulta retornou resultados
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                // Verificar a senha usando password_verify
                if (password_verify($senha, $row['senha'])) {
                    // Definir a sessão indicando que o usuário está logado
                    $_SESSION['account_loggedin'] = true;
                    $_SESSION['username'] = $usuario;

                    // Redirecionar para a página inicial ou perfil
                    header('Location: home.php');
                    exit;
                } else {
                    echo "Senha incorreta!";
                }
            } else {
                echo "Usuário não encontrado!";
            }
        ?>

    </main>
</body>
</html>

<?php
$conn->close();
?>
