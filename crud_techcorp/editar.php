<?php
include 'conexao.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}
$id = (int)$_GET['id'];

$sql_setores = "SELECT id, nome FROM setores ORDER BY nome ASC";
$setores_disponiveis = $conexao->query($sql_setores);

$sql_select = "SELECT nome, setor_id, salario, contratacao FROM funcionarios WHERE id = ?";
$stmt_select = $conexao->prepare($sql_select);
$stmt_select->bind_param("i", $id);
$stmt_select->execute();
$resultado = $stmt_select->get_result();

if ($resultado->num_rows === 0) {
    header("Location: index.php");
    exit();
}
$funcionario = $resultado->fetch_assoc();
$stmt_select->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nome = $conexao->real_escape_string($_POST['nome']);
    $setor_id = $conexao->real_escape_string($_POST['setor_id']);
    $salario = str_replace(',', '.', $conexao->real_escape_string($_POST['salario']));
    $contratacao = $conexao->real_escape_string($_POST['contratacao']);

    $sql_update = "UPDATE funcionarios SET nome = ?, setor_id = ?, salario = ?, contratacao = ? WHERE id = ?";
    $stmt_update = $conexao->prepare($sql_update);
    
    $stmt_update->bind_param("sidsi", $nome, $setor_id, $salario, $contratacao, $id);

    if ($stmt_update->execute()) {
        header("Location: index.php?msg=sucesso_edicao");
        exit();
    } else {
        $erro = "Erro ao atualizar: " . $conexao->error;
    }
    $stmt_update->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Funcionário - TechCorp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-info">✏️ Editar Dados do Funcionário</h2>
        
        <?php if (isset($erro)): ?>
            <div class="alert alert-danger"><?php echo $erro; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="editar.php?id=<?php echo $id; ?>">
            
            <div class="mb-3">
                <label for="nome" class="form-label">Nome Completo</label>
                <input type="text" class="form-control" id="nome" name="nome" 
                       value="<?php echo htmlspecialchars($funcionario['nome']); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="setor_id" class="form-label">Setor</label>
                <select class="form-select" id="setor_id" name="setor_id" required>
                    <option value="">Selecione o Setor</option>
                    <?php 
                    while($setor = $setores_disponiveis->fetch_assoc()): 
                        // Verifica se este é o setor atual do funcionário para marcá-lo como 'selected'
                        $selected = ($setor['id'] == $funcionario['setor_id']) ? 'selected' : '';
                    ?>
                        <option value="<?php echo $setor['id']; ?>" <?php echo $selected; ?>>
                            <?php echo htmlspecialchars($setor['nome']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="salario" class="form-label">Salário (R$)</label>
                <input type="number" step="0.01" class="form-control" id="salario" name="salario" 
                       value="<?php echo htmlspecialchars($funcionario['salario']); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="contratacao" class="form-label">Data de Contratação</label>
                <input type="date" class="form-control" id="contratacao" name="contratacao" 
                       value="<?php echo htmlspecialchars($funcionario['contratacao']); ?>" required>
            </div>
            
            <button type="submit" class="btn btn-info text-white">Salvar Alterações</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
<?php 
if (isset($conexao) && !$conexao->connect_error) {
}
?>