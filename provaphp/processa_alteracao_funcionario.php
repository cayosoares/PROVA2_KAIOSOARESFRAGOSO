<?php
session_start();
require 'conexao.php';

// Verifica se o usuário tem permissão de administrador
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado!'); window.location.href='principal.php';</script>";
    exit();
}

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_funcionario'] ?? '';
    $nome = $_POST['nome_funcionario'] ?? '';
    $endereco = $_POST['endereco'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $email = $_POST['email'] ?? '';

    // Validação básica
    if (empty($id) || empty($nome) || empty($endereco) || empty($telefone) || empty($email)) {
        echo "<script>alert('Todos os campos obrigatórios devem ser preenchidos!'); history.back();</script>";
        exit();
    }

    try {
        // Atualiza os dados do funcionário
        $sql = "UPDATE funcionario 
                SET nome_funcionario = :nome, endereco = :endereco, telefone = :telefone, email = :email 
                WHERE id_funcionario = :id";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':email', $email);

        $stmt->execute();

        echo "<script>alert('Funcionário atualizado com sucesso!'); window.location.href='buscar_funcionario.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Erro ao atualizar: " . $e->getMessage() . "'); history.back();</script>";
    }
} else {
    echo "<script>alert('Requisição inválida!'); window.location.href='principal.php';</script>";
    exit();
}
?>
