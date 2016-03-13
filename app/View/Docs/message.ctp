<?php $this->set('title_for_layout', 'メッセージ情報取得API'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3>API概要</h3>
                <p>Pushメッセージ情報を取得するためのAPI。特定（1件）のメッセージの詳細情報を取得する際に利用する。</p>
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
                            <th>リクエストメソッド</th>
                            <td>POST</td>
                        </tr>
                        <tr>
                            <th>エンドポイント</th>
                            <td>/message</td>
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
<!-- /.row（API概要） -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3>リクエストパラメーター</h3>
                <p>リクエストパラメーターを以下に示します。</p>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th colspan="2">
                                パラメーター
                            </th>
                            <th>
                                パラメーター名
                            </th>
                            <th>
                                必須
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
                            <td colspan="2">message_id</td>
                            <td>メッセージID</td>
                            <td>◯</td>
                            <td>数値</td>
                            <td>andPushサーバー側で生成されたメッセージID</td>
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
<!-- /.row（リクエストパラメーター） -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3>レスポンス</h3>
                <p>JSON形式のレスポンスデータを以下に示します。</p>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th colspan="4">
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
                            <td colspan="4">process_result</td>
                            <td>処理結果</td>
                            <td>数値</td>
                            <td>0：失敗<br />
                                1：成功
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7"><strong>成功時のみ</strong></td>
                        </tr>
                        <tr>
                            <td colspan="4">message_title</td>
                            <td>メッセージタイトル</td>
                            <td>文字列</td>
                            <td>サービス側で設定したメッセージタイトル</td>
                        </tr>
                        <tr>
                            <td colspan="4">message_body</td>
                            <td>メッセージ内容</td>
                            <td>文字列</td>
                            <td>サービス側で設定したメッセージ内容</td>
                        </tr>
                        <tr>
                            <td colspan="4">send_time</td>
                            <td>送信時間</td>
                            <td>日時</td>
                            <td>送信済の場合：実際の送信時間<br />
                                未送信の場合：予約した送信時間</td>
                        </tr>
                        <tr>
                            <td colspan="4">send_result</td>
                            <td>送信結果情報</td>
                            <td>オブジェクト</td>
                            <td>送信結果情報をオブジェクトとして返却</td>
                        </tr>
                        <tr>
                            <td rowspan="2"></td>
                            <td colspan="3">code</td>
                            <td>送信結果コード</td>
                            <td>数値</td>
                            <td>（成功）<br />
                                2000：送信完全成功<br />
                                2001：予約成功（未送信）<br />
                                2002：送信成功<br />
                                （失敗）<br />
                                1000：送信失敗（andPush側エラー）<br />
                                1001：予約失敗<br />
                                1002：送信失敗（APNS/GCM側エラー）
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">msg</td>
                            <td>送信結果メッセージ</td>
                            <td>文字列</td>
                            <td>送信結果の具体的な内容</td>
                        </tr>
                        <tr>
                            <td colspan="6"><strong>Push成功時のみ</strong></td>
                        </tr>
                        <tr>
                            <td colspan="4">device_result_list</td>
                            <td>端末結果リスト</td>
                            <td>配列</td>
                            <td>端末毎の結果を配列として返却<br />
                                ※エラー時でもレスポンス可能な場合は返却する</td>
                        </tr>
                        <tr>
                            <td rowspan="5"></td>
                            <td colspan="3"><i>index</i></td>
                            <td><i>添字</i></td>
                            <td>オブジェクト</td>
                            <td>各端末の情報をオブジェクトとして返却</td>
                        </tr>
                        <tr>
                            <td rowspan="4"></td>
                            <td colspan="2">push_target</td>
                            <td>Push対象</td>
                            <td>文字列</td>
                            <td>iPhoneの場合："ios"<br />
                                Androidの場合："android"
                            </td>
                        </tr>
                        <tr>
                            <td>push_token</td>
                            <td colspan="2">Pushトークン</td>
                            <td>文字列</td>
                            <td>iPhoneの場合：<i>device token</i><br />
                                Androidの場合：<i>registration id</i>
                            </td>
                        </tr>
                        <tr>
                            <td>user_id</td>
                            <td colspan="2">ユーザーID</td>
                            <td>文字列</td>
                            <td>未設定の場合はNULL</td>
                        </tr>
                        <tr>
                            <td>send_result_detail</td>
                            <td colspan="2">送信結果詳細</td>
                            <td>文字列</td>
                            <td>最新（前回）のPush送信結果を返却<br />
                                成功時："success"<br />
                                成功時（置換）："registration_id:"+ 新規registrationId<br />
                                失敗時："error:" + エラー原因<br />
                                失敗時（原因不明）："error"<br />
                                未送信：NULL</td>
                        </tr>
                        <tr>
                            <td colspan="7"><strong>失敗時のみ</strong></td>
                        </tr>
                        <tr>
                            <td colspan="4">error</td>
                            <td>エラー情報</td>
                            <td>オブジェクト</td>
                            <td>エラーの詳細情報をオブジェクトとして返却</td>
                        </tr>
                        <tr>
                            <td rowspan="2"></td>
                            <td colspan="3">code</td>
                            <td>エラーコード</td>
                            <td>数値</td>
                            <td>各エラーコードについては共通仕様を参照</td>
                        </tr>
                        <tr>
                            <td colspan="3">msg</td>
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
<!-- /.row（レスポンス） -->