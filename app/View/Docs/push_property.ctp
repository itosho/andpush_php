<?php $this->set('title_for_layout', 'Push通知API（属性情報）'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3>API概要</h3>
                <p>属性情報ベースでPush通知を行うためのAPI。メッセージをターゲティング配信したい際に利用する。</p>
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
                            <td>/push/property</td>
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
                            <td colspan="3">conditions</td>
                            <td>条件</td>
                            <td>◯</td>
                            <td>配列</td>
                            <td>Push対象の属性情報条件の配列情報（最大20個）</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="2"><i>key</i></td>
                            <td>任意のキー</td>
                            <td>◯</td>
                            <td>文字列</td>
                            <td>任意の値を設定可能</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <p>即時送信の場合は、"send_time"パラメーターそのものが不要です。</p>
                <p>キーは半角英数字のみ利用可能です。</p>
                <p>条件が複数されている場合は、AND条件になります。また、範囲指定などには現在対応していません。</p>
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