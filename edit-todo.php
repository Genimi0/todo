<?php
require_once('db_connexion.php');
$getTodos = $pdo->prepare("SELECT * FROM todos WHERE idTodo = :id");
$updateTodo = $pdo->prepare("UPDATE todos SET done = :newTodoDone WHERE idTodo = :id");

$_GET['id'] = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';
if ($id) {
    $getTodos->bindValue(':id', $id);
    $getTodos->execute();
    $todo = $getTodos->fetch();

    if ($todo['done'] === 1) {
        $newTodoDone = 0;
    } else {
        $newTodoDone = 1;
    }
    $updateTodo->bindValue(':newTodoDone', $newTodoDone);
    $updateTodo->bindValue(':id', $id);
    $updateTodo->execute();
}

header('location: /');
