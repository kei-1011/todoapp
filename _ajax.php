<?php

session_start();

require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/Todo.php');

$todoApp = new \MyApp\Todo();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    //$todoAppのpostメソッドで呼び出す
    //結果を配列で返したいのでresで受け取る

    $res = $todoApp->post();

    //JSで扱いやすいようにjson形式にする
    header('Content-Type: application/json');
    echo json_encode($res);   // resで返ってきた配列をjsonで出力する
    exit;
  } catch (Exception $e) {    //エラーメッセージの表示
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    echo $e->getMessage();
    exit;
  }
}
