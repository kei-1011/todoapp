<?php

session_start();
//セッションスタート

require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/Todo.php');

// get todos
$todoApp = new \MyApp\Todo();
$todos = $todoApp->getAll();

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <title>Todo List</title>
  <link rel="stylesheet" href="./css/style.css">

</head>

<body>
  <h1 class="title">Todo List</h1>
  <div id="container" class="container">

      <form action="" id="new_todo_form" class="mb-3">
        <div class="form-group">
          <input type="text" id="new_todo" placeholder="what to do">
          <input type="submit" class="button" value="追加">
        </div>
      </form>
    <div class="todo-list">

      <div class="folder">
        <ul>
          <li>フォルダ</li>
          <li><a href="#" class="folder-btn">todos</a></li>
        </ul>
      </div>

      <ul id="todos" class="todos">
        <?php foreach ($todos as $todo) : ?>
          <li class="mb-3" id="todo_<?= h($todo->id); ?>" data-id="<?= h($todo->id); ?>">
            <input type="checkbox" class="update_todo form-check-input" <?php if ($todo->state === '1') { echo 'checked'; } ?>>
            <span class="todo_title <?php if ($todo->state === '1') { echo 'done'; } ?>"><?= h($todo->title); ?></span>
            <button type="button" class="btn btn-success delete_todo">削除</button>
            <span class="elapsed_time"></span>
          </li>
        <?php endforeach; ?>

        <li id="todo_template" data-id="" class="mb-3">
          <input type="checkbox" class="update_todo form-check-input">
          <span class="todo_title"></span>
          <span class="elapsed_time"></span>
          <button type="button" class="btn btn-success delete_todo">削除</button>
        </li>
      </ul>

    </div><!--todo-list-->
  </div><!--container-->

  <input type="hidden" id="token" value="<?= h($_SESSION['token']); ?>">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="./js/todo.js"></script>
  <script src="./js/index.js"></script>
</body>

</html>
