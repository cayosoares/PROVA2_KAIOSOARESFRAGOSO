<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário tem permissão (perfil 1 = admin, 2 = gerente, por exemplo)
if (!isset($_SESSION['perfil']) || ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2)) {
    echo "<script>alert('ACESSO NEGADO');window.location.href='principal.php';</script>";
    exit();
}

$funcionarios = []; // Inicializa variável

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['busca'])) {
    $busca = trim($_POST['busca']);

    if (is_numeric($busca)) {
        $sql = "SELECT * FROM funcionario WHERE id_funcionario = :busca ORDER BY nome_funcionario ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM funcionario WHERE nome_funcionario LIKE :busca_nome ORDER BY nome_funcionario ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
    }
} else {
    $sql = "SELECT * FROM funcionario ORDER BY nome_funcionario ASC";
    $stmt = $pdo->prepare($sql);
}

$stmt->execute();
$funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Buscar Funcionário</title>
    <link rel="stylesheet" href="css/buscar.css">
</head>
<body>
    <h4> Kaio Eduardo Soares Fragoso </h4>
    <h2>Lista de Funcionários</h2>

    <form action="buscar_funcionario.php" method="POST">
        <label for="busca">Digite o ID ou nome (opcional):</label>
        <input type="text" id="busca" name="busca">
        <button type="submit">Pesquisar</button>
    </form>

    <?php if (!empty($funcionarios)): ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Endereço</th>
                <th>Telefone</th>
                <th>Email</th>
            </tr>
            <?php foreach ($funcionarios as $funcionario): ?>
                <tr>
                    <td><?= htmlspecialchars($funcionario['id_funcionario']); ?></td>
                    <td><?= htmlspecialchars($funcionario['nome_funcionario']); ?></td>
                    <td><?= htmlspecialchars($funcionario['endereco']); ?></td>
                    <td><?= htmlspecialchars($funcionario['telefone']); ?></td>
                    <td><?= htmlspecialchars($funcionario['email']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Nenhum funcionário encontrado.</p>
    <?php endif; ?>

    <br><a href="principal.php">Voltar</a>
</body>
</html>
