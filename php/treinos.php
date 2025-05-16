<?php
session_start();
include 'conectar.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$usuario = mysqli_real_escape_string($conn, $_SESSION['usuario']);
$sql = "SELECT objetivo, sexo FROM user WHERE usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
    echo "Erro ao carregar dados do usuário.";
    exit();
}

$row = $result->fetch_assoc();
$objetivo = $row['objetivo'];
$sexo = strtolower($row['sexo']);

$treinos = [
    'Perna' => [
        ['exercicio' => 'Agachamento Livre', 'series' => '4x12', 'dica' => 'Mantenha a carga constante para focar na execução.'],
        ['exercicio' => 'Leg Press', 'series' => '4x10', 'dica' => 'Progressão de carga a cada série.'],
        ['exercicio' => 'Cadeira Extensora', 'series' => '3x15', 'dica' => 'Concentre-se na contração máxima.'],
        ['exercicio' => 'Levantamento Terra', 'series' => '4x8', 'dica' => 'Aumente a carga gradativamente.'],
        ['exercicio' => 'Afundo com Halteres', 'series' => '3x12', 'dica' => 'Priorize o equilíbrio e a amplitude.'],
        ['exercicio' => 'Stiff', 'series' => '4x10', 'dica' => 'Foque na descida controlada.'],
        ['exercicio' => 'Leg Curl', 'series' => '3x12', 'dica' => 'Mantenha a carga moderada para evitar lesões.'],
        ['exercicio' => 'Cadeira Adutora', 'series' => '3x15', 'dica' => 'Movimento lento e controlado.'],
        ['exercicio' => 'Panturrilha no Leg Press', 'series' => '4x15', 'dica' => 'Faça o movimento completo para melhor ativação.'],
    ],
    'Costas/Antebraço/Bíceps' => [
        ['exercicio' => 'Puxada na Barra Fixa', 'series' => '3x8', 'dica' => 'Foque na execução correta.'],
        ['exercicio' => 'Remada Curvada', 'series' => '4x10', 'dica' => 'Mantenha a postura reta durante o movimento.'],
        ['exercicio' => 'Pulley Frente', 'series' => '3x12', 'dica' => 'Progressão leve a cada série.'],
        ['exercicio' => 'Rosca Direta', 'series' => '3x15', 'dica' => 'Use carga moderada para manter o controle.'],
        ['exercicio' => 'Rosca Martelo', 'series' => '3x12', 'dica' => 'Evite balanço do corpo.'],
        ['exercicio' => 'Rosca Inversa', 'series' => '3x12', 'dica' => 'Controle a descida para maior eficiência.'],
        ['exercicio' => 'Rosca Concentrada', 'series' => '3x10', 'dica' => 'Use carga leve para manter a forma.'],
    ],
    'Peito/Ombro/Tríceps' => [
        ['exercicio' => 'Supino Reto', 'series' => '4x8', 'dica' => 'Mantenha a carga estável em todas as séries.'],
        ['exercicio' => 'Supino Inclinado', 'series' => '3x10', 'dica' => 'Progrida na última série se sentir conforto.'],
        ['exercicio' => 'Desenvolvimento com Halteres', 'series' => '4x12', 'dica' => 'Amplitude total para melhor ativação.'],
        ['exercicio' => 'Crucifixo Inclinado', 'series' => '3x15', 'dica' => 'A carga deve permitir um movimento amplo.'],
        ['exercicio' => 'Fly Pec Deck', 'series' => '3x12', 'dica' => 'Movimento lento e controlado.'],
        ['exercicio' => 'Elevação Lateral', 'series' => '3x12', 'dica' => 'Evite movimentos bruscos.'],
        ['exercicio' => 'Desenvolvimento Militar', 'series' => '4x8', 'dica' => 'Aumente a carga nas últimas séries.'],
        ['exercicio' => 'Flexão de Braço', 'series' => '3x15', 'dica' => 'Foque na postura correta.'],
        ['exercicio' => 'Tríceps Pulley', 'series' => '3x12', 'dica' => 'Movimento completo para máximo estímulo.'],
        ['exercicio' => 'Tríceps Testa', 'series' => '3x10', 'dica' => 'A carga deve ser leve para proteger as articulações.'],
    ],
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Treinos Personalizados - BYB</title>
    <link rel="stylesheet" href="../css/estilo.css" />
    <style>
        .treino-titulo {
            cursor: pointer;
            background-color: #007bff;
            color: white;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            user-select: none;
        }
        .treino-conteudo {
            display: none;
            margin: 5px 0;
            padding: 10px;
            border: 1px solid #007bff;
            border-radius: 5px;
            background-color: #f0f8ff;
        }
        .exercicio {
            margin-bottom: 5px;
        }
        .dica {
            color: #555;
        }
    </style>
</head>
<body>
    <header class="holder">
        <h1>Treinos Personalizados</h1>
        <nav>
            <a href="dieta.php">Dieta</a>
            <a href="perfil.php">Perfil</a>
            <a href="progresso.php">Progresso</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>

    <main class="conteudo">
        <h2>Objetivo: <b><?php echo ucfirst($objetivo); ?></b></h2>

        <?php foreach ($treinos as $grupo => $exercicios): ?>
            <div class="treino-titulo" onclick="toggleConteudo(this)">
                <?php echo $grupo; ?>
            </div>
            <div class="treino-conteudo">
                <?php foreach ($exercicios as $ex): ?>
                    <div class="exercicio">
                        <b><?php echo $ex['exercicio']; ?> (<?php echo $ex['series']; ?>)</b> 
                        <p class="dica"><?php echo $ex['dica']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </main>

    <script>
        function toggleConteudo(element) {
            const conteudo = element.nextElementSibling;
            conteudo.style.display = (conteudo.style.display === "block") ? "none" : "block";
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
