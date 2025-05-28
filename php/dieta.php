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
    <title>Dieta - BYB</title>
    <link rel="stylesheet" href="../css/estilo.css" >
    <link rel="icon" type="image/png" href="../imgs/logoBYB.jpg">
</head>
<body>
    <header class="holder">
        <h1>Dieta</h1>
        <nav>
            <a href="perfil.php">Perfil</a>
            <a href="treinos.php">Treinos</a>
            <a href="progresso.php">Progresso</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>
    <main class="conteudo" style="max-width:var(--container-max-width);padding:var(--container-padding);">
        <form action="salvar_dieta.php" method="post" style="max-width:var(--container-max-width);margin:0 auto;display:flex;flex-direction:column;gap:10px;">
            <h2 style="font-size:1.5em;letter-spacing:1px;margin-bottom:18px;text-shadow:0 2px 8px #fbc2eb99;">Escolha sua dieta:</h2>
            <select name="dieta" required>
                <option value="1">Sem frutos do mar</option>
                <option value="2">Sem carne vermelha</option>
                <option value="3">Tudo incluso</option>
            </select>
            <button type="submit">Ver Dieta</button>
        </form>
    </main>
        <footer class="holder" style="text-align:center;padding:20px 0;">
        <p style="font-size:var(--font-base);margin:0;">Site desenvolvido para pessoas em obesidade/sobre-peso, ou para quem deseja seguir uma vida mais saudável e ativa.</p>
        <p style="font-size:var(--font-base);">© 2025 BYB - Build Your Body. Todos os direitos reservados.</p>


</body>
</html>

