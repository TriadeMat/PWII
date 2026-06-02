<?php

require_once 'conexao.php';

// ═══════════════════════════════════════════════
//  PASSO 1 — Responde sempre em JSON (sem HTML)
// ═══════════════════════════════════════════════
header('Content-Type: application/json; charset=utf-8');

// ═══════════════════════════════════════════════
//  PASSO 2 — Garante que veio de um formulário
// ═══════════════════════════════════════════════
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
http_response_code(405);
exit(json_encode(['sucesso' => false, 'erro' => 'Envie os dados via formulário (POST).']));
}

// ═══════════════════════════════════════════════
//  PASSO 3 — Lê os campos e valida
// ═══════════════════════════════════════════════
$nome       = trim($_POST['nome']      ?? '');
$email      = trim($_POST['email']     ?? '');
$senha      = trim($_POST['senha']     ?? '');
$confirmar  = trim($_POST['confirmar'] ?? '');

$erros = [];

if ($nome === '')         $erros[] = 'O campo "nome" não pode ficar vazio.';
if ($email === '')        $erros[] = 'O campo "e-mail" não pode ficar vazio.';
elseif(!filter_var($email, FILTER_VALIDATE_EMAIL))  $erros[] = 'O e-mail informado é inválido.';
if ($senha === '')         $erros[] = 'O campo "senha" não pode ficar vazio.';
elseif (strlen($senha) <6)  $erros[] = 'A senha deve ter pelo menos 6 caracteres.';     
if ($confirmar === '')         $erros[] = 'Por favor, confirme sua senha.';
elseif ($senha !== '' && $confirmar !== '' && $senha !== $confirmar) $erros[] = 'As senhas não coincidem.'; 

if($erros){
    http_response_code(422);
    exit(json_encode(['sucesso' => false, 'erros' => $erros]));
}

// ═══════════════════════════════════════════════
//  PASSO 4 — Conecta ao MySQL, cria banco e tabela
// ═══════════════════════════════════════════════
try {
$pdo->exec('CREATE DATABASE IF NOT EXISTS`'. DB_NAME .'`CHARECTER SET utf8mb4');
$pdo->exec('USE `' .DB_NAME . '`');

$pdo->exec('CREATE TABLE IF NOT EXISTS `usuarios`(
 id    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 nome   VARCHAR(200) NOT NULL,
 EMAIL  VARCHAR(200) NOT NULL UNIQUE,
 nome   VARCHAR(255) NOT NULL,
 criado_em DATETIME DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4');
 
// ═══════════════════════════════════════════════
//  PASSO 5 — Verifica se e-mail já está em uso
// ═══════════════════════════════════════════════
$stm = $pdo->prepara('SELECT id FROM `usuarios` WHERE
email = :email');
$stmt->execute([':email' => $email]);

if($stmt->fetch()){
    
}
}