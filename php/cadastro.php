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
    <link rel=icon type="image/png" href="../imgs/green_pokeball_by_jormxdos_dfgb82o-fullview.png">
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
</body>
</html>
<?php
$conn->close();
?>