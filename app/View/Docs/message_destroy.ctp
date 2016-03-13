<?php $this->set('title_for_layout', 'メッセージ情報削除API'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3>API概要</h3>
                <p>Pushメッセージ情報を削除するためのAPI。不要なメッセージを削除する際に利用する。</p>
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
                            <td>/message/destroy</td>
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
                            <td colspan="2">delete_type</td>
                            <td>削除タイプ</td>
                            <td>文字列</td>
                            <td>未送信メッセージ削除時："unsent"<br />
                                送信済メッセージ削除時："sent"
                            </td>
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