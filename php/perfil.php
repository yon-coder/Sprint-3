<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Perfil - BYB</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    <div class="holder">
        <h1>Perfil do Usuário</h1>
        <nav>
            <a href="index.html">Início</a>
            <a href="dieta.html">Dieta</a>
            <a href="treinos.html">Treinos</a>
            <a href="progresso.html">Progresso</a>
        </nav>
    </div>

    <div class="conteudo">
        <h2>Seus Dados:</h2>
        <div id="dados-perfil">
            <?php

                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                include 'conectar.php';

                if (isset($_SESSION['usuario'])) {
                    $usuario = mysqli_real_escape_string($conn, $_SESSION['usuario']);

                    $query = "SELECT * FROM user WHERE usuario='$usuario'";
                    $result = mysqli_query($conn, $query);

                    if ($result && mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        echo "<p>Nome: " . htmlspecialchars($row['nome']) . "</p>";
                        echo "<p>Idade: " . htmlspecialchars($row['idade']) . "</p>";
                        echo "<p>Peso: " . htmlspecialchars($row['peso']) . " kg</p>";
                        echo "<p>Altura: " . htmlspecialchars($row['altura']) . " m</p>";
                        echo "<p>Objetivo: " . htmlspecialchars($row['objetivo']) . "</p>";
                    } else {
                        echo "<p>Perfil não encontrado ou erro ao carregar dados.</p>";
                    }
                } else {
                    echo "<p>Usuário não logado.</p>";
                }
            ?>
        </div>
    </div>
</body>
</html>
