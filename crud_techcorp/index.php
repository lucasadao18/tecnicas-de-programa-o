<?php
// ======================================================================
// 1. LÓGICA PHP (DEVE ESTAR NO TOPO)
// ======================================================================

session_start();

include 'conexao.php';

// --- Lógica de Busca e Filtro ---
$termo_busca = '';
$clausula_where = '';

if (isset($_GET['busca']) && !empty($_GET['busca'])) {
    $termo_busca = $conexao->real_escape_string($_GET['busca']);
    $clausula_where = " WHERE f.nome LIKE '%$termo_busca%' OR s.nome LIKE '%$termo_busca%' ";
}

$sql = "
    SELECT f.id, f.nome, f.salario, f.contratacao, s.nome as nome_setor 
    FROM funcionarios f
    LEFT JOIN setores s ON f.setor_id = s.id 
    $clausula_where 
    ORDER BY f.nome ASC
";
$resultado = $conexao->query($sql);

$conexao->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard RH - TechCorp Solutions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4 text-primary">📋 Funcionários - TechCorp Solutions</h1>

        <?php 
        if (isset($_GET['msg'])) {
            $msg = $_GET['msg'];
            if ($msg == 'sucesso_cadastro') {
                echo '<div class="alert alert-success">Funcionário cadastrado com sucesso!</div>';
            } elseif ($msg == 'sucesso_edicao') {
                echo '<div class="alert alert-success">Dados do funcionário atualizados!</div>';
            } elseif ($msg == 'sucesso_exclusao') {
                echo '<div class="alert alert-warning">Funcionário excluído do sistema.</div>';
            }
        }
        ?>

        <form method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="busca" class="form-control" placeholder="Buscar por Nome ou Setor..." 
                       value="<?php echo htmlspecialchars($termo_busca); ?>">
                <button type="submit" class="btn btn-outline-primary">Buscar</button>
                <a href="index.php" class="btn btn-outline-secondary">Limpar Busca</a>
            </div>
        </form>

        <div class="d-flex justify-content-between mb-3">
            <a href="adicionar.php" class="btn btn-success">➕ Adicionar Novo Funcionário</a>
            <a href="logout.php" class="btn btn-danger">🔒 Sair (Logout)</a>
        </div>
        
        <table class="table table-striped table-hover border">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Setor</th>
                    <th>Salário</th>
                    <th>Contratação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($resultado->num_rows > 0) {
                    while($linha = $resultado->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $linha["id"] . "</td>";
                        echo "<td>" . $linha["nome"] . "</td>";
            
                        echo "<td>" . ($linha["nome_setor"] ? htmlspecialchars($linha["nome_setor"]) : 'N/A') . "</td>"; 
                        
                        echo "<td>" . "R$ " . number_format($linha["salario"], 2, ',', '.') . "</td>";
                        
                        echo "<td>" . date('d/m/Y', strtotime($linha["contratacao"])) . "</td>"; 
                        
                        echo "<td>";

                        echo "<a href='pagamento.php?id=" . $linha["id"] . "' class='btn btn-sm btn-warning me-2 text-dark'>Pagamentos</a>"; 
                        echo "<a href='editar.php?id=" . $linha["id"] . "' class='btn btn-sm btn-info me-2 text-white'>Editar</a>";
                        echo "<a href='deletar.php?id=" . $linha["id"] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Tem certeza que deseja excluir o funcionário " . $linha["nome"] . "?\")'>Excluir</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>Nenhum funcionário encontrado.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>