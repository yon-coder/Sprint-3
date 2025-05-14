<?php
// 1. Iniciar a sessão SEMPRE no início do script
session_start();

include 'conectar.php'; // Assume que $conn é estabelecido aqui (usando mysqli ou PDO)

// Inicializar uma variável para mensagens de status
$mensagem_status = "";

// Verificar se o formulário foi submetido via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['usuario']) && isset($_POST['senha'])) {
        $usuario_submetido = $_POST['usuario'];
        $senha_submetida = $_POST['senha'];

        // 2. DESCOMENTAR E PROTEGER A QUERY SQL (EXEMPLO COM MYSQLI PREPARED STATEMENTS)
        // Adapte se estiver usando PDO
        $sql = "SELECT id, usuario, senha FROM user WHERE usuario = ?"; // Selecione o ID também, pode ser útil
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $usuario_submetido);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                // Verificar a senha usando password_verify
                if (password_verify($senha_submetida, $row['senha'])) {
                    // Senha correta - Login bem-sucedido
                    
                    // 3. Preencher a sessão
                    $_SESSION['usuario_id'] = $row['id_usuario']; // Guardar o ID do usuário é uma boa prática
                    $_SESSION['usuario_nome'] = $row['usuario']; // Guardar o nome de usuário

                    // 4. Redirecionar para perfil.php
                    header("Location: perfil.php");
                    exit; // Crucial para parar a execução do script e garantir o redirecionamento

                } else {
                    $mensagem_status = "Senha incorreta!";
                }
            } else {
                $mensagem_status = "Usuário não encontrado!";
            }
            $stmt->close();
        } else {
            // Erro na preparação da query
            // Em um ambiente de produção, você registraria esse erro em vez de exibi-lo diretamente
            error_log("Erro ao preparar a query: " . $conn->error);
            $mensagem_status = "Ocorreu um erro no sistema. Tente novamente.";
        }
    } else {
        $mensagem_status = "Usuário e senha são obrigatórios.";
    }
}
// Se não for POST ou se o login falhar, o restante da página (HTML) será exibido.
// A conexão só deve ser fechada aqui se não houver redirecionamento.
// Se houver redirecionamento, o exit; já encerrou o script.
// No entanto, é seguro fechar aqui se o script continuar.
if (isset($conn)) {
    $conn->close();
}

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
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div>
                <label for="usuario">Usuário:</label>
                <input type="text" id="usuario" name="usuario" placeholder="Usuário" required value="<?php echo isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : ''; ?>">
            </div>
            <div>
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" placeholder="Senha" required>
            </div>
            <div>
                <button type="submit">Entrar</button>
            </div>
        </form>

        <?php
        // Exibir mensagem de status se houver alguma
        if (!empty($mensagem_status)) {
            echo "<p style='color:red;'>" . htmlspecialchars($mensagem_status) . "</p>";
            // Você pode adicionar um link para tentar novamente, se desejar
            // echo "<p><a href='pagina_de_login.php'>Tentar novamente</a></p>";
        }
        ?>
        
    </main>
</body>
</html>