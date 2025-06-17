<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado e tem perfil de administrador (supondo que perfil 1 seja admin)
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] != 1) {
    echo "Acesso negado";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_funcionario = $_POST['nome_funcionario'] ?? '';
    $endereco = $_POST['endereco'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $email = $_POST['email'] ?? '';

    // Verifica se os campos obrigatórios estão preenchidos
    if (empty($nome_funcionario) || empty($endereco) || empty($telefone) || empty($email)) {
        echo "<script>alert('Todos os campos são obrigatórios.');</script>";
        exit;
    }

    // Insere os dados no banco (sem o id_funcionario, que deve ser AUTO_INCREMENT)
    $sql = "INSERT INTO funcionario (nome_funcionario, endereco, telefone, email) 
            VALUES (:nome_funcionario, :endereco, :telefone, :email)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome_funcionario', $nome_funcionario);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':email', $email);

    if ($stmt->execute()) {
        echo "<script>alert('Funcionário cadastrado com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar funcionário!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Funcionário</title>
    <link rel="stylesheet" href="css/cadastro.css">
</head>
<body>
        <h4> Kaio Eduardo Soares Fragoso </h4>
    <h2>Cadastrar Funcionário</h2>
    <form action="cadastro_funcionario.php" method="POST">
        <label for="nome_funcionario">Nome:</label><br>
        <input type="text" id="nome_funcionario" name="nome_funcionario" required><br><br>

        <label for="endereco">Endereço:</label><br>
        <input type="text" id="endereco" name="endereco" required><br><br>

        <label for="telefone">Telefone:</label><br>
        <input type="tel" id="telefone" name="telefone" required><br><br>

        <label for="email">E-mail:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <button type="submit">Cadastrar</button>
        <button type="reset">Cancelar</button>
    </form>
    <br>
    <a href="principal.php">Voltar</a>
</body>
</html>
