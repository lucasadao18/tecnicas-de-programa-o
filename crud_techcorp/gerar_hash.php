<?php
// gerar_hash.php
$senha_clara = '123456'; // SENHA DE TESTE
$hash_senha = password_hash($senha_clara, PASSWORD_DEFAULT);

echo "COPIE ESTE HASH INTEIRO E COLE NO BANCO DE DADOS: <br>";
echo "<strong>" . $hash_senha . "</strong>";

?>