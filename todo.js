$(function() {
  "use strict";
/*
チェックボックスをクリックした時に処理を行う
*/

  $("#new_todo").focus();     //ページを読み込んだ時にフォーカスを当てる

  // update
  $("#todos").on("click", ".update_todo", function() {
    //update-todoをクリックした部分のtodoのidを取得

    //.update_todoの親要素（li#todo_）のliのdata属性のidを取得
    var id = $(this)
      .parents("li")
      .data("id");

      // ajax処理
    $.post(
      "_ajax.php",
      {
        id: id,                   //id を渡す
        mode: "update",           //処理の種類を'mode'で渡す (update 更新)
        token: $("#token").val()  //#token要素のvalueを与える（tokenのあたい）
      },
      function(res) {
        //resというオブジェクトが返ってきて、更新したtodoの状態をいれる

      //更新した後のstateが1だったら、#todoに対してdoneをつける
        if (res.state === "1") {
          $("#todo_" + id)
            .find(".todo_title")
            .addClass("done");
        } else {
          $("#todo_" + id)
            .find(".todo_title")
            .removeClass("done");
        }
      }
    );
  });

  // delete
  $("#todos").on("click", ".delete_todo", function() {
    // idを取得
    var id = $(this)
      .parents("li")
      .data("id");

      // ajax処理
      //消すかどうかを確認する confirm    if (confirm("このタスクを完了していいですか？")) {
      $.post(
        "_ajax.php",
        {
          id: id,
          mode: "delete",       //modeをdeleteで渡す
          token: $("#token").val()
        },
        function() {      //終わった後の処理、id="todo_30"とかを消してあげるだけでいい
          $("#todo_" + id).slideUp(100);
        }
      );
    // }    //confirm
  });

  // create
  $("#new_todo_form").on("submit", function() {   //formがsubmitされた時

    // titleを取得
    var title = $("#new_todo").val();
    // ajax処理
    $.post(
      "_ajax.php",
      {
        title: title,     //titleを取得
        mode: "create",
        token: $("#token").val()
      },
      function(res) {

        // liを追加
        var $li = $("#todo_template").clone();
        $li
          .attr("id", "todo_" + res.id)   //id
          .data("id", res.id)             //data属性
          .find(".todo_title")            //_todo-titleクラスの中身にtitleを挿入
          .text(title);
        $("#todos").prepend($li.fadeIn());  //todosの一番上にfadeinしながら追加
        $("#new_todo")
          .val("")
          .focus();       //値をからにしてフォーカスを当てる
      }
    );
    return false; //submit時の画面の遷移を防ぐ
  });
});
