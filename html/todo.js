$(function() {
	'use strict';

	//updateファンクション
	//#todosの中のupdate_todoクラスをクリックしたら発動
	$('#todos').on('click', '.update_todo', function() {
	//idを取得
		//update_todo(this)の親要素のliの(parents('li'))のdata属性のidを取得
		var id = $(this).parents('li').data('id');
	//ajaxを取得
		// _ファイルは裏側で走る普通とは違った処理というくらいの慣習
		$.post('_ajax.php', {
			id: id,
			mode: 'update',
			token: $('#token').val()
		}, function(res) { //更新処理が終わった後の処理。ここではチェックボックスを変更
			if (res.state === '1') {
				$('#todo_' + id).find('.todo_title').addClass('done');
			} else {
				$('#todo_' + id).find('.todo_title').removeClass('done');
			}
		})
	})
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
});