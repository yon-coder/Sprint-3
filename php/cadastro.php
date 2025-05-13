<?php
include 'conectar.php';

$nome = $_POST['nome'];
$usuario = $_POST['usuario'];
$idade = $_POST['idade'];
$peso = $_POST['peso'];
$altura = $_POST['altura'];
$objetivo = $_POST['objetivo'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (nome, usuario, idade, peso, altura, objetivo, senha) 
        VALUES ('$nome', '$usuario', '$idade', '$peso', '$altura', '$objetivo', '$senha')";

if ($conn->query($sql) === TRUE) {
    echo "Cadastro realizado com sucesso!";
} else {
    echo "Erro ao cadastrar: " . $conn->error;
}

$conn->close();
?>
