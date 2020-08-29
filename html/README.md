## MEMO
### ajax.php
ページの表示は index.php が起点、Todo のチェックは ajax.php 

### 処理の流れ
* update
1. チェックボックスをクリックしたら todo.js のアップデートfunction が発動。
2. _ajax.php で postメソッドを着火
3. Todo.php の postメソッドを発動、DBが更新される

* CSRF 対策
1. index.php と _ajax.php で Session を使う
2. Todo.php で _createToken メソッドを作成
3. Session の Token をフォームから送信したいので index.php に埋め込む
4. todo.js で token を渡す
5. Todo.php で token のチェック （_validateToken メソッド）