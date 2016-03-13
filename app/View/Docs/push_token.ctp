<?php $this->set('title_for_layout', 'Push通知API（トークン）'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3>API概要</h3>
                <p>Push（端末）トークンベースでPush通知を行うためのAPI。指定した端末にメッセージを送信したい際に利用する。</p>
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
                            <td>/push/token</td>
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
                            <th colspan="3">
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
                        <?php echo $this->element( 'docs/push_request' ); ?>
                        <tr>
                            <td colspan="3">device_list</td>
                            <td>端末リスト</td>
                            <td>◯</td>
                            <td>配列</td>
                            <td>Push対象端末の配列情報</td>
                        </tr>
                        <tr>
                            <td rowspan="4"></td>
                            <td colspan="2"><i>index</i></td>
                            <td><i>添字</i></td>
                            <td>◯</td>
                            <td>配列</td>
                            <td>各端末のPushトークン等の配列情報</td>
                        </tr>
                        <tr>
                            <td rowspan="3"></td>
                            <td>push_target</td>
                            <td>Push対象</td>
                            <td>◯</td>
                            <td>文字列</td>
                            <td>iPhoneの場合："ios"<br />
                                Androidの場合："android"
                            </td>
                        </tr>
                        <tr>
                            <td>push_token</td>
                            <td>Pushトークン</td>
                            <td>◯</td>
                            <td>文字列</td>
                            <td>iPhoneの場合：<i>device token</i><br />
                                Androidの場合：<i>registration id</i>
                            </td>
                        </tr>
                        <tr>
                            <td>user_id</td>
                            <td>ユーザーID</td>
                            <td>-</td>
                            <td>文字列</td>
                            <td>サービスサーバー側のユニークキー（255文字以内）</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <p>即時送信の場合は、"send_time"パラメーターそのものが不要です。</p>
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
                    <?php echo $this->element( 'docs/push_response' ); ?>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row（レスポンス） -->