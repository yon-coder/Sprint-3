<?php
// Configura a duração da sessão para 30 dias
session_set_cookie_params(30 * 24 * 60 * 60);
session_start();

include 'conectar.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$usuario = mysqli_real_escape_string($conn, $_SESSION['usuario']);
$sql = "SELECT * FROM user WHERE usuario='$usuario'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Perfil - BYB</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    <header class="holder">
        <h1>Perfil do Usuário</h1>
        <nav>
            <a href="dieta.php">Dieta</a>
            <a href="html/treinos.html">Treinos</a>
            <a href="html/progresso.html">Progresso</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>

    <main class="conteudo">
        <h2>Seus Dados:</h2>
        <div id="dados-perfil">
            <?php
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo "<p>Nome: " . htmlspecialchars($row['nome']) . "</p>";
                echo "<p>Idade: " . htmlspecialchars($row['idade']) . "</p>";
                echo "<p>Peso: " . htmlspecialchars($row['peso']) . " kg</p>";
                echo "<p>Altura: " . htmlspecialchars($row['altura']) . " m</p>";
                echo "<p>Objetivo: " . htmlspecialchars($row['objetivo']) . "</p>";
                echo "<p>Sexo: " . htmlspecialchars($row['sexo']) . "</p>";
            } else {
                echo "<p>Erro ao carregar perfil.</p>";
            }
            ?>
        </div>
    </main>
</body>
</html>
<?php
$conn->close();
?>
