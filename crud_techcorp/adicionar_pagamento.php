<?php

include 'conexao.php';

if (!isset($_GET['funcionario_id']) || !is_numeric($_GET['funcionario_id'])) {
    header("Location: index.php");
    exit();
}
$funcionario_id = (int)$_GET['funcionario_id'];

$nome_funcionario = "Funcionário";
$erro = "";

$sql_func = "SELECT nome FROM funcionarios WHERE id = ?";
$stmt_func = $conexao->prepare($sql_func);
$stmt_func->bind_param("i", $funcionario_id);
$stmt_func->execute();
$resultado_func = $stmt_func->get_result();
if ($resultado_func->num_rows > 0) {
    $funcionario = $resultado_func->fetch_assoc();
    $nome_funcionario = htmlspecialchars($funcionario['nome']);
}
$stmt_func->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $salario_base = str_replace(',', '.', $conexao->real_escape_string($_POST['salario_base']));
    $descontos = str_replace(',', '.', $conexao->real_escape_string($_POST['descontos']));
    $beneficios = str_replace(',', '.', $conexao->real_escape_string($_POST['beneficios']));
    $mes_referencia = $conexao->real_escape_string($_POST['mes_referencia']);
    $observacao = $conexao->real_escape_string($_POST['observacao']);
    
    if (empty($salario_base) || empty($mes_referencia) || !is_numeric($salario_base)) {
        $erro = "Salário Base e Mês de Referência são obrigatórios.";
    } else {
        
        $total_liquido = $salario_base + $beneficios - $descontos; 

        $sql = "INSERT INTO pagamentos (funcionario_id, salario_base, descontos, beneficios, total_liquido, mes_referencia, observacao) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        
        $stmt->bind_param("iddddss", $funcionario_id, $salario_base, $descontos, $beneficios, $total_liquido, $mes_referencia, $observacao); 

        if ($stmt->execute()) {
            header("Location: pagamento.php?id=" . $funcionario_id . "&msg=sucesso_pagamento"); 
            exit();
        } else {
            $erro = "Erro ao registrar pagamento: " . $conexao->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Registrar Pagamento - TechCorp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-warning">💰 Registrar Pagamento para <?php echo $nome_funcionario; ?></h2>
        
        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?php echo $erro; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="adicionar_pagamento.php?funcionario_id=<?php echo $funcionario_id; ?>">
            
            <div class="mb-3">
                <label for="mes_referencia" class="form-label">Mês de Referência</label>
                <input type="date" class="form-control" id="mes_referencia" name="mes_referencia" required>
            </div>
            
            <div class="mb-3">
                <label for="salario_base" class="form-label">1. Salário Base (R$)</label>
                <input type="number" step="0.01" class="form-control" id="salario_base" name="salario_base" required>
            </div>
            
            <div class="mb-3">
                <label for="beneficios" class="form-label">2. Benefícios/Bônus (+)</label>
                <input type="number" step="0.01" class="form-control" id="beneficios" name="beneficios" value="0.00">
            </div>
            
            <div class="mb-3">
                <label for="descontos" class="form-label">3. Descontos (-)</label>
                <input type="number" step="0.01" class="form-control" id="descontos" name="descontos" value="0.00">
            </div>
            
            <div class="mb-3">
                <label for="observacao" class="form-label">Observação (Bônus, Falta, etc.)</label>
                <textarea class="form-control" id="observacao" name="observacao" rows="3"></textarea>
            </div>

            
            <button type="submit" class="btn btn-warning text-dark">Registrar Pagamento</button>
            <a href="pagamento.php?id=<?php echo $funcionario_id; ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
<?php 
$conexao->close();
?>