<?php $this->set('title_for_layout', '端末情報取得API'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3>API概要</h3>
                <p>端末情報を取得するためのAPI。特定（1件）の端末の詳細情報を取得する際に利用する。</p>
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
                            <td>/device</td>
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
                            <td colspan="2">push_target</td>
                            <td>Push対象</td>
                            <td>◯</td>
                            <td>文字列</td>
                            <td>"ios" or "android"</td>
                        </tr>
                        <tr>
                            <td colspan="2">push_token</td>
                            <td>Pushトークン</td>
                            <td>◯</td>
                            <td>文字列</td>
                            <td>iPhoneの場合：<i>device token</i><br />
                                Androidの場合：<i>registration id</i>
                            </td>
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
                            <td colspan="5"><strong>成功時のみ</strong></td>
                        </tr>
                        <tr>
                            <td colspan="2">device_status</td>
                            <td>端末状況</td>
                            <td>文字列</td>
                            <td>最新（前回）のPush送信結果を返却<br />
                                成功時："success"<br />
                                成功時（要置換）："registration_id:"+ 新規registrationId<br />
                                失敗時："error:" + エラー原因<br />
                                失敗時（原因不明）："error"<br />
                                未送信：NULL</td>
                        </tr>
                        <tr>
                            <td colspan="2">user_id</td>
                            <td>ユーザーID</td>
                            <td>文字列</td>
                            <td>未設定の場合：NULL</td>
                        </tr>
                        <tr>
                            <td colspan="2">properties</td>
                            <td>属性情報</td>
                            <td>オブジェクト</td>
                            <td>ユーザー属性情報をオブジェクトとして返却（最大20個）<br />
                                未設定の場合：NULL
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><i>key</i></td>
                            <td>サービス側で設定したキー</td>
                            <td>文字列</td>
                            <td>サービス側で設定した値</td>
                        </tr>
                        <?php echo $this->element( 'docs/error_response' ); ?>
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