<?php
require_once('db_connexion.php');
$deleteTodo = $pdo->prepare("DELETE FROM todos WHERE idTodo = :id");

$_GET['id'] = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';
if ($id) {
    $deleteTodo->bindValue(':id', $id);
    $deleteTodo->execute();
}
header('location: /');