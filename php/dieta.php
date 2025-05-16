<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Escolha sua dieta</title>
    <link rel="stylesheet" href="../css/estilo.css" >
</head>
<body>
    <header class="holder">
        <h1>Dieta</h1>
        <nav>
            <a href="perfil.php">Perfil</a>
            <a href="html/treinos.html">Treinos</a>
            <a href="html/progresso.html">Progresso</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>
    <h1>Escolha sua dieta</h1>
    <form action="salvar_dieta.php" method="post">
    <label>Escolha sua dieta:</label>
    <select name="dieta">
        <option value="1">Sem frutos do mar</option>
        <option value="2">Sem carne vermelha</option>
        <option value="3">Tudo incluso</option>
    </select>
    <button type="submit">Salvar Dieta</button>
</form>

</body>
</html>

