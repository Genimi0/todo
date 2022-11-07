<?php
require_once('db_connexion.php');
$addTodo = $pdo->prepare("INSERT INTO todos VALUES (DEFAULT, :name, 0)");
$getAllTodo = $pdo->prepare("SELECT * FROM todos");

const ERROR_REQUIRED = "Veuillez renseigner une todo";
const ERROR_TOO_SHORT = "Veuillez saisir au moins 5 caractères";
$error = '';
$todo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $todo = $_POST['todo'] ?? '';

    if (!$todo) {
        $error = ERROR_REQUIRED;
    } elseif (mb_strlen($todo) <= 5) {
        $error = ERROR_TOO_SHORT;
    };

    if (!$error) {
        $name = filter_var($todo, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $addTodo->bindValue(':name', $name);
        $addTodo->execute();
    }
};
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once('includes/head.php'); ?>
    <title>Projet Todo</title>
</head>

<body class='d-flex flex-column'>
    <?php require_once('includes/header.php'); ?>

    <main class='d-flex justify-center align-center'>
        <div class="todo-container">
            <h1 class='text-center'>Ma Todo</h1>
            <form class="todo-form d-flex align-center" action="/" method='POST'>
                <input value="<?= $todo; ?>" type="text" name="todo" id="todo">
                <button type="submit" class='btn btn-primary'>Ajouter</button>
            </form>
            <?php if ($error) : ?>
                <p class='text-danger'><?= $error; ?></p>
            <?php endif; ?>
            <ul class="todo-list">
                <?php
                $getAllTodo->execute();
                $allTodos = $getAllTodo->fetchAll();
                foreach ($allTodos as $t) {
                    $class = '';
                    if ($t['done'] === 1) {
                        $class .= 'line';
                        $buttonText = "Annuler";
                    } else {
                        $buttonText = "Valider";
                    }
                    echo
                    "<li class='todo-item d-flex align-center'>
                            <span class='todo-name $class'>$t[name]</span>
                            <a href='edit-todo.php?id=$t[idTodo]'<button class='btn btn-primary btn-small'>$buttonText</button></a>
                            <a href='delete-todo.php?id=$t[idTodo]'<button class='btn btn-danger btn-small'>Supprimer</button></a>
                        </li>";
                }
                ?>
            </ul>
        </div>
    </main>

    <?php require_once('includes/footer.php'); ?>

    <script src='assets/js/index.js'></script>
</body>

</html>