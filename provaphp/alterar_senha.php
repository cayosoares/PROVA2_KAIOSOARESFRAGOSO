<?php
session_start();
require_once 'conexao.php';

// Verifica se está logado
if (!isset($_SESSION['id_usuario'])) {
    echo "<script>alert('Acesso negado!');window.location.href='login.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id_usuario = $_SESSION['id_usuario'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    if ($nova_senha !== $confirmar_senha) {
        echo "<script>alert('As senhas não coincidem!');</script>";
    } elseif (strlen($nova_senha) < 8) {
        echo "<script>alert('A senha deve ter pelo menos 8 caracteres!');</script>";
    } elseif ($nova_senha === "temp123") {
        echo "<script>alert('Escolha uma senha diferente da temporária!');</script>";
    } else {
        $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        $sql = "UPDATE usuario SET senha = :senha, senha_temporaria = FALSE WHERE id_usuario = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':senha', $senha_hash);
        $stmt->bindParam(':id', $id_usuario);

        if ($stmt->execute()) {
            session_destroy();
            echo "<script>alert('Senha alterada com sucesso! Faça login novamente.');window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Erro ao alterar a senha!');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Senha</title>
    <link rel="stylesheet" href="css/senha.css">
</head>
<body>
    <h4> Kaio Eduardo Soares Fragoso </h4>
    <div class="container">
        <h2>Alterar Senha</h2>
        <p>Olá, <strong><?php echo $_SESSION['usuario']; ?></strong>. Digite sua nova senha:</p>

        <form action="alterar_senha.php" method="POST">
            <div class="form-group">
                <label for="nova_senha">Nova Senha:</label>
                <input type="password" id="nova_senha" name="nova_senha" required>
            </div>

            <div class="form-group">
                <label for="confirmar_senha">Confirmar Senha:</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" required>
            </div>

            <div class="form-group checkbox">
                <input type="checkbox" id="mostrar_senha" onclick="mostrarSenha()">
                <label for="mostrar_senha">Mostrar Senha</label>
            </div>

            <button type="submit">Salvar nova Senha</button>
        </form>
    </div>

    <script>
        function mostrarSenha() {
            const senha1 = document.getElementById("nova_senha");
            const senha2 = document.getElementById("confirmar_senha");
            const tipo = senha1.type === "password" ? "text" : "password";
            senha1.type = tipo;
            senha2.type = tipo;
        }
    </script>
</body>
</html>

