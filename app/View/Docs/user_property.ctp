<?php $this->set('title_for_layout', 'ユーザー属性情報登録API'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3>API概要</h3>
                <p>ユーザー属性情報を登録するためのAPI。サービス側で用意している属性情報を自由に登録出来る。<br />
                    基本的にはアプリ側からリクエストされるが、サービスサーバー側から登録することも可能。</p>
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
                            <td>/user/property</td>
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
                            <td colspan="2">user_id</td>
                            <td>ユーザーID</td>
                            <td>◯</td>
                            <td>文字列</td>
                            <td>サービスサーバー側のユニークキー（255文字以内）</td>
                        </tr>
                        <tr>
                            <td colspan="2">properties</td>
                            <td>属性情報</td>
                            <td>◯</td>
                            <td>配列</td>
                            <td>属性情報の配列情報（最大20個）<br />
                            サービスサーバー側で任意のキーと値を設定可能</td>
                        </tr>
                        <tr>
                            <td rowspan="1"></td>
                            <td><i>key</i></td>
                            <td>任意のキー</td>
                            <td>◯</td>
                            <td>文字列</td>
                            <td>任意の値（255文字以内）</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <p>各キー名は半角英数字のみ利用可能です。</p>
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
                            <td colspan="2">save_type</td>
                            <td>登録タイプ</td>
                            <td>文字列</td>
                            <td>新規登録時："create"<br />
                                更新時："update"
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