<?php $this->set('title_for_layout', '統計情報取得API（日次）'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3>API概要</h3>
                <p>日次ベースの統計情報を取得するためのAPI。</p>
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
                            <td>GET</td>
                        </tr>
                        <tr>
                            <th>エンドポイント</th>
                            <td>/statistic/daily</td>
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
                            <td colspan="2">from</td>
                            <td>取得対象日（From）</td>
                            <td>-</td>
                            <td>日付</td>
                            <td>取得対象日の開始日<br />
                            ※未設定の場合は当日の値が設定される</td>
                        </tr>
                        <tr>
                            <td colspan="2">to</td>
                            <td>取得対象日（To）</td>
                            <td>-</td>
                            <td>日付</td>
                            <td>取得対象日の終了日<br />
                            ※開始日から最大31日後まで指定可能<br />
                            ※未設定の場合はFromと同日の値が設定される</td>
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
                            <th colspan="3">
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
                            <td colspan="3">process_result</td>
                            <td>処理結果</td>
                            <td>数値</td>
                            <td>1：成功<br />
                                0：失敗
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7"><strong>成功時のみ</strong></td>
                        </tr>
                        <tr>
                            <td colspan="3">dailies</td>
                            <td>日次データ</td>
                            <td>オブジェクト</td>
                            <td>日次毎の統計情報をオブジェクトとして返却</td>
                        </tr>
                        <tr>
                            <td rowspan="14"></td>
                            <td colspan="2"><i>date</i></td>
                            <td>統計日</td>
                            <td>オブジェクト</td>
                            <td>当該日付の統計情報をオブジェクトとして返却</td>
                        </tr>
                        <tr>
                            <td rowspan="13"></td>
                            <td>count_device</td>
                            <td>登録端末数</td>
                            <td>数値</td>
                            <td>当該日付時点でのandPush側で管理している登録端末台数</td>
                        </tr>
                        <tr>
                            <td>count_push_message_06</td>
                            <td>Push送信回数<br />（0時-6時）</td>
                            <td>数値</td>
                            <td>当該日付の00:00〜05:59までにPushメッセージを送信した回数</td>
                        </tr>
                        <tr>
                            <td>count_push_device_06</td>
                            <td>Push送信端末台数<br />（0時-6時）</td>
                            <td>数値</td>
                            <td>当該日付の00:00〜05:59までにPushメッセージを送信した端末台数</td>
                        </tr>
                        <tr>
                            <td>count_open_06</td>
                            <td>開封数<br />（0時-6時）</td>
                            <td>数値</td>
                            <td>当該日付の00:00〜05:59までにPushメッセージを開封した回数</td>
                        </tr>
                        <tr>
                            <td>count_push_message_12</td>
                            <td>Push送信回数<br />（6時-12時）</td>
                            <td>数値</td>
                            <td>当該日付の06:00〜11:59までにPushメッセージを送信した回数</td>
                        </tr>
                        <tr>
                            <td>count_push_device_12</td>
                            <td>Push送信端末台数<br />（6時-12時）</td>
                            <td>数値</td>
                            <td>当該日付の06:00〜11:59までにPushメッセージを送信した端末台数</td>
                        </tr>
                        <tr>
                            <td>count_open_12</td>
                            <td>開封数<br />（6時-12時）</td>
                            <td>数値</td>
                            <td>当該日付の06:00〜11:59までにPushメッセージを開封した回数</td>
                        </tr>
                        <tr>
                            <td>count_push_message_18</td>
                            <td>Push送信回数<br />（12時-18時）</td>
                            <td>数値</td>
                            <td>当該日付の12:00〜17:59までにPushメッセージを送信した回数</td>
                        </tr>
                        <tr>
                            <td>count_push_device_18</td>
                            <td>Push送信端末台数<br />（12時-18時）</td>
                            <td>数値</td>
                            <td>当該日付の12:00〜17:59までにPushメッセージを送信した端末台数</td>
                        </tr>
                        <tr>
                            <td>count_open_18</td>
                            <td>開封数<br />（12時-18時）</td>
                            <td>数値</td>
                            <td>当該日付の12:00〜17:59までにPushメッセージを開封した回数</td>
                        </tr>
                        <tr>
                            <td>count_push_message_24</td>
                            <td>Push送信回数<br />（18時-24時）</td>
                            <td>数値</td>
                            <td>当該日付の18:00〜23:59までにPushメッセージを送信した回数</td>
                        </tr>
                        <tr>
                            <td>count_push_device_24</td>
                            <td>Push送信端末台数<br />（18時-24時）</td>
                            <td>数値</td>
                            <td>当該日付の18:00〜23:59までにPushメッセージを送信した端末台数</td>
                        </tr>
                        <tr>
                            <td>count_open_24</td>
                            <td>開封数<br />（18時-24時）</td>
                            <td>数値</td>
                            <td>当該日付の18:00〜23:59までにPushメッセージを開封した回数</td>
                        </tr>
                        <tr>
                            <td colspan="7"><strong>失敗時のみ</strong></td>
                        </tr>
                        <tr>
                            <td colspan="3">error</td>
                            <td>エラー情報</td>
                            <td>オブジェクト</td>
                            <td>エラーの詳細情報をオブジェクトとして返却</td>
                        </tr>
                        <tr>
                            <td rowspan="2"></td>
                            <td colspan="2">code</td>
                            <td>エラーコード</td>
                            <td>数値</td>
                            <td>各エラーコードについては共通仕様を参照</td>
                        </tr>
                        <tr>
                            <td colspan="2">msg</td>
                            <td>エラーメッセージ</td>
                            <td>文字列</td>
                            <td>エラーの具体的な内容</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <p>"date"レスポンスは"2015-08-01"のような日付型のデータが設定されます。</p>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row（レスポンス） -->