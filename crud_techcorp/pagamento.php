<?php

include 'conexao.php';

// Verifica se o ID do funcionário foi passado
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}
$funcionario_id = (int)$_GET['id'];

$sql_func = "SELECT nome FROM funcionarios WHERE id = ?";
$stmt_func = $conexao->prepare($sql_func);
$stmt_func->bind_param("i", $funcionario_id);
$stmt_func->execute();
$resultado_func = $stmt_func->get_result();

if ($resultado_func->num_rows === 0) {
    echo "Funcionário não encontrado.";
    $stmt_func->close();
    $conexao->close();
    exit();
}
$funcionario = $resultado_func->fetch_assoc();
$nome_funcionario = htmlspecialchars($funcionario['nome']);
$stmt_func->close();


$sql_pagamentos = "
    SELECT id, total_liquido, mes_referencia, salario_base, descontos, beneficios
    FROM pagamentos 
    WHERE funcionario_id = ? 
    ORDER BY mes_referencia DESC
";
$stmt_pag = $conexao->prepare($sql_pagamentos);
$stmt_pag->bind_param("i", $funcionario_id);
$stmt_pag->execute();
$historico_pagamentos = $stmt_pag->get_result();
$stmt_pag->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pagamentos - <?php echo $nome_funcionario; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-warning">💸 Histórico de Pagamentos de <?php echo $nome_funcionario; ?></h2>

        <?php 
        if (isset($_GET['msg']) && $_GET['msg'] == 'sucesso_pagamento') {
            echo '<div class="alert alert-success">Pagamento registrado com sucesso!</div>';
        }
        ?>

        <a href="adicionar_pagamento.php?funcionario_id=<?php echo $funcionario_id; ?>" 
           class="btn btn-warning mb-3">➕ Registrar Novo Pagamento</a>

        <table class="table table-striped table-hover border">
            <thead class="table-dark">
                <tr>
                    <th>ID Pag.</th>
                    <th>Ref. Mês</th>
                    <th>Base</th>
                    <th>Descontos</th>
                    <th>Benefícios</th>
                    <th>Total Líquido</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($historico_pagamentos->num_rows > 0) {
                    while($pagamento = $historico_pagamentos->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $pagamento["id"] . "</td>";
                        
                        // Usando mes_referencia
                        echo "<td>" . date('d/m/Y', strtotime($pagamento["mes_referencia"])) . "</td>"; 
                        
                        // Valores formatados
                        echo "<td>R$ " . number_format($pagamento["salario_base"], 2, ',', '.') . "</td>";
                        echo "<td class='text-danger'>R$ " . number_format($pagamento["descontos"], 2, ',', '.') . "</td>";
                        echo "<td class='text-success'>R$ " . number_format($pagamento["beneficios"], 2, ',', '.') . "</td>";
                        
                        // Usando total_liquido
                        echo "<td><strong>R$ " . number_format($pagamento["total_liquido"], 2, ',', '.') . "</strong></td>";
                        
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>Nenhum pagamento registrado ainda.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        
        <a href="index.php" class="btn btn-secondary mt-3">Voltar para a Lista de Funcionários</a>
    </div>
</body>
</html>
<?php 
