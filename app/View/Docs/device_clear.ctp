<?php $this->set('title_for_layout', '端末情報クリアAPI'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3>API概要</h3>
                <p>端末情報をクリア（削除）するためのAPI。不要な端末データを削除する際に利用する。</p>
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
                            <td>/device/clear</td>
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
                            <td colspan="2">user_device_count</td>
                            <td>ユーザー設定端末数</td>
                            <td>数値</td>
                            <td>削除された端末に紐付いたユーザーIDが設定されている端末数</td>
                        </tr>
                        <tr>
                            <td colspan="2">user_id</td>
                            <td>ユーザーID</td>
                            <td>文字列</td>
                            <td>削除された端末に紐付いたユーザーID<br />
                                ユーザー設定端末数が1件以上の場合に返却</td>
                        </tr>
                        <?php echo $this->element( 'docs/error_response' ); ?>
                        </tbody>
                    </table>
                </div>
                <p>指定した端末情報に紐づくユーザー情報も削除されます。<br />
                    ただし、ユーザーIDが複数端末に設定されている場合（ユーザー設定端末数が2件以上の場合）、ユーザー情報は削除されません。</p>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row（レスポンス） -->