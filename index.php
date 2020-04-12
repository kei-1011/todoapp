<?php

session_start();
//セッションスタート

require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/Todo.php');

// get todos
$todoApp = new \MyApp\Todo();
$todos = $todoApp->getAll();
$done_todos = $todoApp->getTrash();

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <title>Todo List</title>
  <link rel="stylesheet" href="./css/style.css">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">

</head>

<body>
  <h1 class="title">Todo List</h1>
  <div id="container" class="container">

      <form action="" id="new_todo_form" class="todo-form">
        <div class="form-group">
          <input type="text" id="new_todo" class="new_todo" placeholder="what to do" autocomplete="off">
          <input type="submit" class="button" value="追加">
        </div>
      </form>
    <div class="todo-list">

      <div class="folder">
        <!-- <ul>
          <li>フォルダ</li>
          <li><a href="#" class="folder-btn">todos</a></li>
        </ul> -->
      </div>

      <ul id="todos" class="todos">
        <?php foreach ($todos as $todo) : ?>
          <li class="mb-3 todo-item" id="todo_<?= h($todo->id); ?>" data-id="<?= h($todo->id); ?>" data-title="<?= h($todo->title); ?>">
            <input type="checkbox" class="update_todo form-check-input" <?php if ($todo->state === '1') { echo 'checked'; } ?>>
            <span class="todo_title <?php if ($todo->state === '1') { echo 'done'; } ?>"><?= h($todo->title); ?></span>
            <button type="button" class="btn btn-success delete_todo">削除</button>
            <!-- <span class="elapsed_time"></span> -->
          </li>
        <?php endforeach; ?>

        <li id="todo_template" data-id="" class="mb-3 todo-item">
          <input type="checkbox" class="update_todo form-check-input">
          <span class="todo_title"></span>
          <!-- <span class="elapsed_time"></span> -->
          <button type="button" class="btn btn-success delete_todo">削除</button>
        </li>
      </ul>
    </div><!--todo-list-->
  </div><!--container-->

  <div class="done-wrap">
    <div class="done_list">
      <ul class="done_todos">
      <?php foreach ($done_todos as $done_todo) : ?>
        <li id="done_<?= h($done_todo->id); ?>" class="done_todo" data-id="<?php echo $done_todo->id; ?>" data-title="<?php echo $done_todo->title; ?>">
          <i class="fas fa-check-circle todo-back"></i>
          <span class="todo_title"><?php echo $done_todo->title; ?></span>
          <!-- <span class="elapsed_time"><?php echo $done_todo->date_time;?></span> -->
        </li>
        <?php endforeach; ?>

        <li id="done_template" data-id="" data-title="" class="done_todo">
        <i class="fas fa-check-circle todo-back"></i>
          <span class="todo_title"></span>
        </li>
      </ul>
    </div>
  </div>

  <input type="hidden" id="token" value="<?= h($_SESSION['token']); ?>">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="./js/todo.js"></script>
  <script src="./js/index.js"></script>
</body>

</html>
