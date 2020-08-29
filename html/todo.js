$(function() {
	'use strict';

	//入力フォームにフォーカスされる
	$('#new_todo').focus();

	//updateファンクション
	//#todosの中のupdate_todoクラスをクリックしたら発動
	$('#todos').on('click', '.update_todo', function() {
	//idを取得
		//update_todo(this)の親要素のliの(parents('li'))のdata属性のidを取得
		var id = $(this).parents('li').data('id');
	//ajaxを取得
		// _ファイルは裏側で走る普通とは違った処理というくらいの慣習
		//「$.post」は単にjQueryでこういう書き方をするもの
		//$.post( サーバーへのパス, 任意のデータ, （更新処理が終わった後の処理）)
		$.post('_ajax.php', {
			id: id,
			mode: 'update',
			token: $('#token').val()
			//コールバック関数
		}, function(res) { //更新処理が終わった後の処理。ここではチェックボックスを変更
			if (res.state === '1') {
				$('#todo_' + id).find('.todo_title').addClass('done');
			} else {
				$('#todo_' + id).find('.todo_title').removeClass('done');
			}
		})
	}); 

	//deleteファンクション
	$('#todos').on('click', '.delete_todo', function() {
		//idを取得
			var id = $(this).parents('li').data('id');

			if (confirm('are you sure ?')) {
			$.post('_ajax.php', {
				id: id,
				mode: 'delete',
				token: $('#token').val()
			}, function() { 
				$('#todo_' + id).fadeOut(800);
			});
		}
	});

	//cleateファンクション
	//フォームにタイトルを入力後、「送信」で実行
	$('#new_todo_form').on('submit', function() {
		//フォームに入力したタイトルをtitleに格納
		var title = $('#new_todo').val();
		//ajax処理
		$.post('_ajax.php', {
			title: title,
			mode: 'create',
			token: $('#token').val() 
		}, function(res) { 
		//li を追加
			//jQuery オブジェクトを作るときには変数名に $ をよく付ける
			//clone():要素のクローンを作成し、そのクローンを選択状態にする。
			var $li = $('#todo_template').clone();
			$li
				//attr(): 属性を操作することができる
				//第一引数のみで取得、第二引数で操作になる
				//ここではid をtodo_3 のような形でセット
				.attr('id', 'todo_' + res.id)
				//data(): data属性を操作することができる
				//第一引数のみで取得、第二引数で操作になる
				//ここではdata-id をres.id に変更
				.data('id', res.id)
				//find(): 指定要素が持つ全子孫要素から、指定条件式に合致するものを選択する。
				//ここではindex.php の.todo_title に、titleを記述する
				.find('.todo_title').text(title);
			//prepend: $li を#todos の先頭に挿入する
			$('#todos').prepend($li.fadeIn());
			//フォームをクリアし、フォーカスする
      $('#new_todo').val('').focus();
		});
		return false; //画面遷移を防ぐ
	});

});