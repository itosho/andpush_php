<?php $this->set('title_for_layout', '共通仕様'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3>通信方式</h3>
                <p>通信方式を以下に示します。</p>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>
                                項目
                            </th>
                            <th>
                                内容
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th>通信プロトコル</th>
                            <td>HTTPS</td>
                        </tr>
                        <tr>
                            <th>URL（仮）</th>
                            <td>プロダクション環境：https://www.andpush.jp/v2/xxx<br/>
                                サンドボックス環境：https://sandbox.andpush.jp/v2/xxx
                            </td>
                        </tr>
                        <tr>
                            <th>リクエストメソッド</th>
                            <td>GET / POST</td>
                        </tr>
                        <tr>
                            <th>エンコーディング</th>
                            <td>UTF-8</td>
                        </tr>
                        <tr>
                            <th>リクエスト（POST）</th>
                            <td>x-www-form-urlencoded形式</td>
                        </tr>
                        <tr>
                            <th>レスポンス</th>
                            <td>JSONフォーマット形式</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row（パラメーターフォーマット） -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3>認証仕様</h3>
                <p>各APIは連携サービス（リクエスト元）確認のために拡張ヘッダーを利用します。<br/>
                    API利用時、事前に払い出されたコード及びキー情報を以下の拡張ヘッダーの仕様に沿って設定してください。
                </p>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>
                                ヘッダーフィールド
                            </th>
                            <th>
                                値
                            </th>
                            <th>
                                備考
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>X-Push-Code</td>
                            <td>CMSで払い出されたサービスコード</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>X-Push-Key</td>
                            <td>CMSで払い出されたサービスキー</td>
                            <td>-</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <p>サービスコードとサービスキーの払い出し方法は別途<a href="#">オンラインマニュアル</a>をご参照ください。</p>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row（認証仕様） -->
<!-- /.row（通信方式） -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3>パラメーターフォーマット</h3>
                <p>リクエストやレスポンスデータのパラメーターフォーマットを以下に示します。</p>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>
                                型
                            </th>
                            <th>
                                フォーマット
                            </th>
                            <th>
                                例
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="3"><strong>バリューフォーマット</strong></td>
                        </tr>
                        <tr>
                            <td>日時</td>
                            <td>yyyy-mm-dd hh:mm:ss</td>
                            <td>2015-05-01 15:30:00</td>
                        </tr>
                        <tr>
                            <td>日付</td>
                            <td>yyyy-mm-dd</td>
                            <td>2015-05-01</td>
                        </tr>
                        <tr>
                            <td>時刻</td>
                            <td>hh:mm:ss</td>
                            <td>15:30:00</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row（パラメーターフォーマット） -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3>レスポンス仕様</h3>
                <p>APIのリクエスト結果として、適切なHTTPステータスコードと処理結果をまとめたJSON形式のレスポンスデータを返却します。</p>
                <hr />
                <h4>HTTPステータスコード仕様</h4>
                <p>返却される各HTTPステータスコードの仕様（意味）は以下になります。</p>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>
                                エラー内容
                            </th>
                            <th>
                                ステータスコード
                            </th>
                            <th>
                                説明
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="3"><strong>成功</strong></td>
                        </tr>
                        <tr>
                            <td>リクエスト成功</td>
                            <td>200</td>
                            <td>APIが正常に終了した場合<br />
                            ※APIの処理自体は完了したものの、APNSやGCMサーバー等の問題にあり、<br />
                                Push通知が正常に実行されなかった場合も200を返します。<br />
                                その場合は、後述の"process_result"を確認してください。</td>
                        </tr>
                        <tr>
                            <td colspan="3"><strong>エラー（クライアント系）</strong></td>
                        </tr>
                        <tr>
                            <td>パラメーターエラー</td>
                            <td>400</td>
                            <td>リクエストパラメーターが不正の場合</td>
                        </tr>
                        <tr>
                            <td>認証エラー</td>
                            <td>401</td>
                            <td>拡張ヘッダーの認証情報が不正の場合</td>
                        </tr>
                        <tr>
                            <td>アクセスエラー</td>
                            <td>403</td>
                            <td>アクセス禁止サーバーからリクエストされた場合</td>
                        </tr>
                        <tr>
                            <td>存在エラー</td>
                            <td>404</td>
                            <td>存在しないAPIや存在しないデータがリクエストされた場合</td>
                        </tr>
                        <tr>
                            <td colspan="3"><strong>エラー（サーバー系）</strong></td>
                        </tr>
                        <tr>
                            <td>サーバーエラー</td>
                            <td>500</td>
                            <td>サーバー側で予期せぬエラーが発生した場合</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <h4>レスポンスデータ（JSON）共通仕様</h4>
                <p>各APIの共通レスポンスの場合を以下に示します。</p>
                <div class="table-responsive">
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
                            <td>1：成功<br />
                                0：失敗
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5"><strong>失敗時のみ</strong></td>
                        </tr>
                        <tr>
                            <td colspan="2">error</td>
                            <td>エラー情報</td>
                            <td>オブジェクト</td>
                            <td>エラーの詳細情報をオブジェクトとして返却</td>
                        </tr>
                        <tr>
                            <td rowspan="2"></td>
                            <td>code</td>
                            <td>エラーコード</td>
                            <td>数値</td>
                            <td>各エラーコードについては共通仕様を参照</td>
                        </tr>
                        <tr>
                            <td>msg</td>
                            <td>エラーメッセージ</td>
                            <td>文字列</td>
                            <td>エラーの具体的な内容</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row（レスポンス仕様） -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3>コード一覧</h3>
                <p>Push送信結果コード及びエラーコードの一覧を以下に示します。</p>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>
                                コード
                            </th>
                            <th>
                                メッセージ
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="2"><strong>Push処理結果コード（エラー）</strong></td>
                        </tr>
                        <tr>
                            <td>1000</td>
                            <td>Pushメッセージ送信処理が失敗しました。<br />
                                ※andPushサーバー側の問題によるPush送信失敗</td>
                        </tr>
                        <tr>
                            <td>1001</td>
                            <td>Pushメッセージ送信予約処理が失敗しました。</td>
                        </tr>
                        <tr>
                            <td>1002</td>
                            <td>APNS/GCMサーバーでエラーが発生しました。<br />
                                APNS/GCMサーバー側の問題によるPush送信失敗</td>
                        </tr>
                        <tr>
                            <td colspan="2"><strong>Push処理結果コード（成功）</strong></td>
                        </tr>
                        <tr>
                            <td>2000</td>
                            <td>Pushメッセージ送信処理が全て成功しました。</td>
                        </tr>
                        <tr>
                            <td>2001</td>
                            <td>Pushメッセージ送信予約処理が成功しました。</td>
                        </tr>
                        <tr>
                            <td>2002</td>
                            <td>Pushメッセージ送信処理が成功しました。<br />
                            ※ただし、アンインストールなどの要因により全端末への送信が成功したわけではない。</td>
                        </tr>
                        <tr>
                            <td colspan="2"><strong>エラーコード</strong></td>
                        </tr>
                        <tr>
                            <td>4000</td>
                            <td>リクエストに問題があります。<br />
                                ※クライアント側の何らかの問題によるエラー</td>
                        </tr>
                        <tr>
                            <td>4001</td>
                            <td>認証情報が正しくありません。</td>
                        </tr>
                        <tr>
                            <td>4002</td>
                            <td>リクエストパラメーターが不正です。</td>
                        </tr>
                        <tr>
                            <td>4003</td>
                            <td>リクエストメソッドが不正です。</td>
                        </tr>
                        <tr>
                            <td>4004</td>
                            <td>指定されたデータが存在しません。</td>
                        </tr>
                        <tr>
                            <td>4005</td>
                            <td>指定された条件ではPush対象端末が存在しません。</td>
                        </tr>
                        <tr>
                            <td>5000</td>
                            <td>システムエラーが発生しました。<br />
                                ※サーバー側の何らかの問題によるエラー</td>
                        </tr>
                        <tr>
                            <td>5001</td>
                            <td>データベースへの保存に失敗しました。</td>
                        </tr>
                        <tr>
                            <td>5002</td>
                            <td>データベースからの削除に失敗しました。</td>
                        </tr>
                        <tr>
                            <td>9000</td>
                            <td>予期せぬエラーが発生しました。管理者へ問い合わせください。</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row（コード一覧） -->