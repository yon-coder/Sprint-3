<?php
include 'conectar.php';
$usuario = $_POST['usuario'];
$senha = $_POST['senha'];

// $sql = "SELECT * FROM user WHERE usuario='$usuario'";
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
        <!-- <form action="login.php" method="POST">
            <input type="text" name="usuario" placeholder="Usuário" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form> -->
        <?php
            if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($senha, $row['senha'])) {
            // $_SESSION['usuario'] = $usuario;
            echo "Login bem-sucedido!";
            echo "<a href='perfil.php'> Acessar conta </a>";
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