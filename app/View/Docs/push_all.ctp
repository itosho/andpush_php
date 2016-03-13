<?php $this->set('title_for_layout', 'Push通知API（全端末）'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3>API概要</h3>
                <p>全端末にPush通知を行うためのAPI。andPushで管理している全端末にメッセージを送信したい際に利用する。</p>
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
                            <td>/push/all</td>
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