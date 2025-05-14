<?php
    include "sql.php";
        
    $sql = "SELECT * FROM alunos";
    $resultado = $conexao->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Alunos</title>
    <link rel="icon" type="image/png" href="../images/icons/art.png">
    <link rel="stylesheet" href="../styles.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }
        
        th, td {
            padding: 0.75rem;
            text-align: left;
            border: 1px solid var(--extra3);
        }
        
        th {
            background-color: rgba(40, 40, 40, 0.5);
            color: var(--highlight);
            font-weight: 600;
        }
        
        tr:hover {
            background-color: rgba(40, 40, 40, 0.3);
        }
        
        .ação-link {
            display: inline-block;
            color: var(--extra1);
            text-decoration: none;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            transition: color 0.3s ease, background-color 0.3s ease;
        }
        
        .excluir {
            color: var(--accent1);
        }
        
        .editar {
            color: var(--accent2);
        }
        
        .ação-link:hover {
            background-color: rgba(40, 40, 40, 0.8);
            transform: scale(1.05);
        }
        
        .novo-link {
            display: inline-block;
            margin-top: 1rem;
            color: var(--highlight);
            text-decoration: none;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border: 1px solid var(--extra3);
            border-radius: 4px;
            transition: color 0.3s ease, background-color 0.3s ease, transform 0.3s ease;
        }
        
        .novo-link:hover {
            color: var(--hover);
            background-color: var(--extra2);
            transform: scale(1.05);
        }
        
        .vazio {
            color: var(--extra2);
            font-style: italic;
            text-align: center;
            padding: 2rem;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navegação">
            <ul>
                <li><a href="../index.html">🏡</a></li>
                <li><a href="../index.html">Cadastro</a></li>
                <li><a>Consulta</a></li>
            </ul>
        </nav>
    </header>

    <main class="conteúdo">
        <section class="principal">
            <h1>Consulta de Alunos</h1>
            <p>Lista de todos os alunos cadastrados no sistema</p>
        </section>

        <div class="introdução">
            <?php if ($resultado && $resultado->rowCount() > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Idade</th>
                            <th>Email</th>
                            <th colspan="2">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($linha = $resultado->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td><?php echo $linha["ra"]; ?></td>
                                <td><?php echo $linha["nome"]; ?></td>
                                <td><?php echo $linha["idade"]; ?></td>
                                <td><?php echo $linha["email"]; ?></td>
                                <td>
                                    <a href="change.php?ra=<?php echo $linha["ra"]; ?>" class="ação-link editar">Editar</a>
                                </td>
                                <td>
                                    <a href="delete.php?ra=<?php echo $linha["ra"]; ?>" class="ação-link excluir" 
                                       onclick="return confirm('Tem certeza que deseja excluir este aluno?')">Excluir</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="vazio">Nenhum aluno cadastrado</div>
            <?php endif; ?>
            
            <a href="../index.html" class="novo-link">Cadastrar Novo Aluno</a>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Gabriel. Todos os direitos reservados.</p>
    </footer>
</body>
</html>