<?php
include 'conectar.php';
$nome = $_POST['nome'];
$usuario = $_POST['usuario'];
$idade = $_POST['idade'];
$peso = $_POST['peso'];
$altura = $_POST['altura'];
$objetivo = $_POST['objetivo'];
$sexo = $_POST['sexo'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

$sql = "INSERT INTO user (nome, usuario, idade, peso, altura, objetivo, sexo, senha) 
        VALUES ('$nome', '$usuario', '$idade', '$peso', '$altura', '$objetivo', '$sexo', '$senha')";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - BYB</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="icon" type="image/png" href="imgs/logoBYB.jpg">
</head>
<body>
    <header class="holder">
        <h1>Cadastro</h1>
        <nav>
            <a href="../index.html">Início</a>
        </nav>
    </header>
    <main class="conteudo">
        <?php
        if ($conn->query($sql) === TRUE) {
            echo '<div style="color:#38b000;font-size:1.3em;font-weight:bold;margin-bottom:16px;">Cadastro realizado com sucesso!</div>';
        } else {
            echo '<div style="color:#d7263d;font-size:1.1em;font-weight:bold;margin-bottom:16px;">Erro ao cadastrar: ' . $conn->error . '</div>';
        }
        ?>
        <a href="../html/login.html" class="botoes"><button>Ir para Login</button></a>
    </main>
        <footer class="holder" style="text-align:center;padding:20px 0;">
        <p style="font-size:var(--font-base);margin:0;">Site desenvolvido para pessoas em obesidade/sobre-peso, ou para quem deseja seguir uma vida mais saudável e ativa.</p>
        <p style="font-size:var(--font-base);">© 2025 BYB - Build Your Body. Todos os direitos reservados.</p>
</body>
</html>
<?php
$conn->close();
?>