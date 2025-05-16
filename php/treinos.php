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

function ajustarCargaReps($cargaBase, $repsBase, $sexo, $objetivo) {
    if ($sexo === 'feminino') {
        $cargaBase = round($cargaBase * 0.7);
        $repsBase = round($repsBase * 1.2);
    }

    switch ($objetivo) {
        case 'ganhar':
            $carga = $cargaBase;
            $reps = $repsBase;
            break;
        case 'perder':
            $carga = round($cargaBase * 0.7);
            $reps = round($repsBase * 1.5);
            break;
        case 'manter':
            $carga = round($cargaBase * 0.85);
            $reps = round($repsBase * 1.1);
            break;
        default:
            $carga = $cargaBase;
            $reps = $repsBase;
    }
    return ['carga' => $carga, 'reps' => $reps];
}

$treinos = [
    'Perna' => [
        ['exercicio' => 'Agachamento Livre', 'carga' => 80, 'reps' => 8],
        ['exercicio' => 'Leg Press', 'carga' => 100, 'reps' => 10],
        ['exercicio' => 'Cadeira Extensora', 'carga' => 50, 'reps' => 12],
        ['exercicio' => 'Levantamento Terra', 'carga' => 90, 'reps' => 8],
        ['exercicio' => 'Afundo com Halteres', 'carga' => 30, 'reps' => 10],
        ['exercicio' => 'Stiff', 'carga' => 70, 'reps' => 10],
    ],
    'Costas/Antebraço/Bíceps' => [
        ['exercicio' => 'Puxada na Barra Fixa', 'carga' => 0, 'reps' => 8],
        ['exercicio' => 'Remada Curvada', 'carga' => 60, 'reps' => 10],
        ['exercicio' => 'Rosca Direta', 'carga' => 25, 'reps' => 12],
        ['exercicio' => 'Pulley Frente', 'carga' => 40, 'reps' => 10],
        ['exercicio' => 'Rosca Martelo', 'carga' => 20, 'reps' => 12],
        ['exercicio' => 'Rosca Inversa', 'carga' => 15, 'reps' => 12],
    ],
    'Peito/Ombro/Tríceps' => [
        ['exercicio' => 'Supino Reto', 'carga' => 70, 'reps' => 8],
        ['exercicio' => 'Desenvolvimento com Halteres', 'carga' => 20, 'reps' => 10],
        ['exercicio' => 'Tríceps Pulley', 'carga' => 30, 'reps' => 12],
        ['exercicio' => 'Crucifixo Inclinado', 'carga' => 25, 'reps' => 10],
        ['exercicio' => 'Fly Pec Deck', 'carga' => 40, 'reps' => 12],
        ['exercicio' => 'Flexão de Braço', 'carga' => 0, 'reps' => 15],
        ['exercicio' => 'Elevação Lateral', 'carga' => 15, 'reps' => 12],
        ['exercicio' => 'Desenvolvimento Militar', 'carga' => 50, 'reps' => 8],
        ['exercicio' => 'Tríceps Testa', 'carga' => 20, 'reps' => 12],
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
            margin-top: 10px;
            border-radius: 5px;
            user-select: none;
        }
        .treino-conteudo {
            display: none;
            margin-top: 5px;
            padding: 10px;
            border: 1px solid #007bff;
            border-radius: 5px;
            background-color: #f0f8ff;
        }
        .exercicio {
            margin-bottom: 8px;
        }
        .exercicio b {
            display: inline-block;
            width: 200px;
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
        <p>Sexo: <b><?php echo ucfirst($sexo); ?></b></p>

        <?php foreach ($treinos as $grupo => $exercicios): ?>
            <div class="treino-bloco">
                <div class="treino-titulo" onclick="toggleConteudo(this)">
                    <?php echo $grupo; ?>
                </div>
                <div class="treino-conteudo">
                    <?php foreach ($exercicios as $ex): 
                        $ajuste = ajustarCargaReps($ex['carga'], $ex['reps'], $sexo, $objetivo);
                        $cargaExib = $ajuste['carga'] > 0 ? $ajuste['carga'] . " kg" : "Peso corporal";
                    ?>
                        <div class="exercicio">
                            <b><?php echo $ex['exercicio']; ?></b> — <?php echo $ajuste['reps']; ?> repetições, carga: <?php echo $cargaExib; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
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
