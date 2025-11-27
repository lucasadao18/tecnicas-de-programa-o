<?php
include 'conexao.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int)$_GET['id'];

$sql = "DELETE FROM funcionarios WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: index.php?msg=sucesso_exclusao");
    exit();
} else {
    echo "Erro ao excluir funcionário: " . $conexao->error;
}

$stmt->close();
$conexao->close();
?>