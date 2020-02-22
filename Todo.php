<?php

/*
セキュリティを高めるための　CSRF対策
Token発行してSessionに格納し、
フォームからもToken を発行して送信する

セッションの中のTokenとフォームから送られたTokenが同じかどうかをチェックすることで対策を施す
*/


namespace MyApp;

class Todo
{
  private $_db;

  public function __construct()
  {
    $this->_createToken();

    try {
      $this->_db = new \PDO(DSN, DB_USERNAME, DB_PASSWORD);
      $this->_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
      echo $e->getMessage();
      exit;
    }
  }

  //Tokenがセットされていなければ、tokenを作成する
  private function _createToken()
  {
    if (!isset($_SESSION['token'])) {
      $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));//32桁の推測されにくい文字
    }
  }

  public function getAll()
  {
    $stmt = $this->_db->query("select * from todos order by id desc");
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
    /*
    fetchAllでオブジェクト形式で結果を返す
     */

  }

  public function post()
  {
    $this->_validateToken();

    if (!isset($_POST['mode'])) {    //modeが渡ってきてない場合
      throw new \Exception('mode not set!');//例外処理
    }

    switch ($_POST['mode']) {       //modeが渡ってきたら、その内容に応じて処理を振り分ける

      //privateメソッドで作る
      //返り値は配列になるので、returnする

      case 'update':
        return $this->_update();
      case 'create':
        return $this->_create();
      case 'delete':
        return $this->_delete();
    }
  }

  private function _validateToken()
  {
    if (
      !isset($_SESSION['token']) ||
      !isset($_POST['token']) ||
      $_SESSION['token'] !== $_POST['token']
    ) {
      throw new \Exception('invalid token!');
    }
  }


  //編集、更新
  private function _update()
  {
    if (!isset($_POST['id'])) {
      throw new \Exception('[update] id not set!');
    }
    /*
    渡ってきたidを元に、DBの更新
    stateが0の時は1に、１の時は０にしたい
    →stateに１を足して2で割った余りで更新すればいい

    idを%dにして、$_POSTで渡ってきたIDをいれる
    */

    //同時にたくさんアクセスされた時、IDがずれるのを防ぐ

    $this->_db->beginTransaction();

    $sql = sprintf("update todos set state = (state + 1) %% 2 where id = %d", $_POST['id']);
    $stmt = $this->_db->prepare($sql);
    $stmt->execute();

    //更新されたstateを返す
    $sql = sprintf("select state from todos where id = %d", $_POST['id']);
    $stmt = $this->_db->query($sql);
    $state = $stmt->fetchColumn();

    $this->_db->commit();       //トランザクションのコミット


    //配列で返す
    return [
      'state' => $state
    ];
  }


  //追加
  private function _create()
  {
    if (!isset($_POST['title']) || $_POST['title'] === '') {    //titleが空だった場合
      throw new \Exception('[create] title not set!');    //errorを返す
    }

    $sql = "insert into todos (title) values (:title)";   //プレースホルダー？文字列だから？
    $stmt = $this->_db->prepare($sql);
    $stmt->execute([':title' => $_POST['title']]);

    //返す値挿入されたデータのid
    return [
      'id' => $this->_db->lastInsertId()
    ];
  }


  //削除
  private function _delete()
  {
    if (!isset($_POST['id'])) {
      throw new \Exception('[delete] id not set!');
    }

    $sql = sprintf("delete from todos where id = %d", $_POST['id']);
    $stmt = $this->_db->prepare($sql);
    $stmt->execute();

    return [];
  }
}
