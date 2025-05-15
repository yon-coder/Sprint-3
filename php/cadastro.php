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
</head>
<body>
    <header class="holder">
        <h1>Cadastro</h1>
        <nav>
            <a href="../index.html">Início</a>
        </nav>
    </header>
    <?php
    if ($conn->query($sql) === TRUE) {
    echo "Cadastro realizado com sucesso!";
    } else {
    echo "Erro ao cadastrar: " . $conn->error;
    }
    ?>
</html>
<?php
$conn->close();
?>