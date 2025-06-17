<?php
session_start();
require 'conexao.php';

// Verifica se o usuário tem permissão de ADM
if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado!'); window.location.href='principal.php';</script>";
    exit();
}

// Inicializa variável
$funcionario = null;

// Se o formulário for enviado, busca o funcionário pelo ID ou nome
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['busca_funcionario'])) {
        $busca = trim($_POST['busca_funcionario']);

        if (is_numeric($busca)) {
            $sql = "SELECT * FROM funcionario WHERE id_funcionario = :busca";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
        } else {
            $sql = "SELECT * FROM funcionario WHERE nome_funcionario LIKE :busca_nome";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
        }

        $stmt->execute();
        $funcionario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$funcionario) {
            echo "<script>alert('Funcionário não encontrado!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Alterar Funcionário</title>
    <link rel="stylesheet" href="css/alterar.css">
</head>
<body>
    <h4> Kaio Eduardo Soares Fragoso </h4>
    <h2>Alterar Funcionário</h2>

    <!-- Formulário de busca -->
    <form action="alterar_funcionario.php" method="POST">
        <label for="busca_funcionario">Digite o ID ou Nome do funcionário:</label>
        <input type="text" id="busca_funcionario" name="busca_funcionario" required>
        <button type="submit">Buscar</button>
    </form>

    <?php if ($funcionario): ?>
        <!-- Formulário de alteração -->
        <form action="processa_alteracao_funcionario.php" method="POST">
            <input type="hidden" name="id_funcionario" value="<?= htmlspecialchars($funcionario['id_funcionario']) ?>">

            <label for="nome_funcionario">Nome:</label><br>
            <input type="text" id="nome_funcionario" name="nome_funcionario" value="<?= htmlspecialchars($funcionario['nome_funcionario']) ?>" required><br><br>

            <label for="endereco">Endereço:</label><br>
            <input type="text" id="endereco" name="endereco" value="<?= htmlspecialchars($funcionario['endereco']) ?>" required><br><br>

            <label for="telefone">Telefone:</label><br>
            <input type="tel" id="telefone" name="telefone" value="<?= htmlspecialchars($funcionario['telefone']) ?>" required><br><br>

            <label for="email">E-mail:</label><br>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($funcionario['email']) ?>" required><br><br>

            <!-- Senha nova (opcional) -->
            <?php if ($_SESSION['perfil'] == 1): ?>
                <label for="nova_senha">Nova Senha:</label><br>
                <input type="password" id="nova_senha" name="nova_senha"><br><br>
            <?php endif; ?>

            <button type="submit">Salvar Alterações</button>
            <button type="reset">Cancelar</button>
        </form>
    <?php endif; ?>

    <br>
    <a href="principal.php">Voltar</a>
</body>
</html>
