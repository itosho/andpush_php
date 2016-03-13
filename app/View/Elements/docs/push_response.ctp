<table class="table table-bordered">
    <thead>
    <tr>
        <th colspan="2">
            レスポンス
        </th>
        <th>
            レスポンス名
        </th>
        <th>
            形式
        </th>
        <th>
            説明
        </th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan="2">process_result</td>
        <td>処理結果</td>
        <td>数値</td>
        <td>0：失敗<br />
            1：成功
        </td>
    </tr>
    <tr>
        <td colspan="5"><strong>成功時のみ</strong></td>
    </tr>
    <tr>
        <td colspan="2">send_result</td>
        <td>送信結果情報</td>
        <td>オブジェクト</td>
        <td>送信結果情報をオブジェクトとして返却</td>
    </tr>
    <tr>
        <td rowspan="2"></td>
        <td>code</td>
        <td>送信結果コード</td>
        <td>数値</td>
        <td>2000：送信完全成功<br />
            2001：予約成功（未送信）<br />
            2002：送信成功<br />
            ※失敗時の場合はエラーコードを参照
        </td>
    </tr>
    <tr>
        <td>msg</td>
        <td>送信結果メッセージ</td>
        <td>文字列</td>
        <td>成功の具体的な内容</td>
    </tr>
    <tr>
        <td colspan="2">message_id</td>
        <td>メッセージID</td>
        <td>数値</td>
        <td>andPushサーバー側で生成されたメッセージID<br />
            ※エラー時でもレスポンス可能な場合は返却する</td>
    </tr>
    <?php echo $this->element( 'docs/error_response' ); ?>
    </tbody>
</table>