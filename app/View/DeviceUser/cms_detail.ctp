<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">端末情報詳細</div>
            <div class="panel-body">
                <div class="col-lg-12">
                    <?php echo $this->Xformjp->create('DeviceUser',
                        array('class' => 'form-horizontal', 'type' => 'get', 'url' => '#')); ?>
                    <?php
                    $os = "";
                    if ($item['push_target'] == "ios") {
                        $os = "iPhone";
                    }
                    if ($item['push_target'] == "android") {
                        $os = "Android";
                    }

                    $conditions = "";
                    if (strpos($item['last_send_result_detail'], 'success') !== false) {
                        $conditions = "問題なし";
                    }
                    if (strpos($item['last_send_result_detail'], 'error') !== false) {
                        $conditions = "問題あり";
                    }
                    if ($item['last_send_result_detail'] === null) {
                        $conditions = "未送信";
                    }

                    ?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">端末種別▶</label>

                        <div class="col-sm-9">
                            <p class='form-control-static'><?php echo $os; ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">トークン情報▶</label>

                        <div class="col-sm-9">
                            <p class='form-control-static'><?php echo $item['push_token']; ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">ユーザーID▶</label>

                        <div class="col-sm-9">
                            <p class='form-control-static'><?php echo $item['user_id']; ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">端末状態▶</label>

                        <div class="col-sm-9">
                            <p class='form-control-static'><?php echo $conditions; ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">更新時間▶</label>

                        <div class="col-sm-9">
                            <p class='form-control-static'><?php echo $item['modified']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-offset-3 col-sm-9 form-submit">
                    <p><?php
                        echo $this->Html->link('一覧へ戻る', array('action' => 'cms_index'),
                            array('class' => 'btn btn-default'));
                        echo $this->Html->link('編集する', './update/' . $item['id'],
                            array('class' => 'btn btn-default', 'disabled' => 'disabled'));
                        echo $this->Html->link('削除する', './destroy/' . $item['id'],
                            array('class' => 'btn btn-default', 'disabled' => 'disabled'));
                        ?>
                    </p>
                </div>
                <?php echo $this->Xformjp->end(); ?>
            </div>
        </div>
        <!-- /.panel-body -->
    </div>
    <!-- /.panel -->
</div>
<!-- /.col-lg-12 -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">送信履歴（最新10件）</div>
            <div class="panel-body">
                <?php if(isset($item['message_list']) && !empty($item['message_list'])): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>送信時間</th>
                            <th>送信結果</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($item['message_list'] as $message): ?>
                            <?php
                            $sendResult = "";
                            if (strpos($message['send_result_detail'], 'success') !== false) {
                                $sendResult = "成功";
                            }
                            if (strpos($message['send_result_detail'], 'error') !== false) {
                                $sendResult = "失敗";
                            }
                            if ($message['send_result_detail'] === null) {
                                $sendResult = "未送信";
                            }
                            ?>
                            <tr>
                                <td><?php echo $this->Html->link($message['message_id'],
                                        "/cms/message/detail/{$message['message_id']}"); ?></td>
                                <td><?php echo $message['modified']; ?></td>
                                <td><?php echo $sendResult; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
                <?php else: ?>
                    <p>送信履歴がありません。</p>
                <?php endif; ?>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">属性情報</div>
            <div class="panel-body">
                <?php if(isset($item['properties']) && !empty($item['properties'])): ?>
                <div class="col-lg-12">
                    <?php echo $this->Xformjp->create('DeviceUser',
                        array('class' => 'form-horizontal', 'type' => 'get', 'url' => '#')); ?>
                    <?php foreach ($item['properties'] as $key => $val): ?>
                    <div class="form-group">
                        <?php $lableName = $key;
                              $i = 0;
                        ?>
                        <?php foreach ($labels as $label): ?>
                            <?php
                                if ($label['key_name'] == $key) {
                                    $lableName = $label['label_name'];
                                    unset($label[$i]);
                                    break;
                                }
                                $i++;
                            ?>
                        <?php endforeach; ?>
                        <label class="col-sm-3 control-label"><?php echo $lableName; ?>▶</label>
                        <div class="col-sm-9">
                            <p class='form-control-static'><?php echo $val; ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php echo $this->Xformjp->end(); ?>
                <?php else: ?>
                    <p>属性情報がありません。</p>
                <?php endif; ?>
            </div>
        </div>
        <!-- /.panel-body -->
    </div>
    <!-- /.panel -->
</div>
<!-- /.col-lg-12 -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">他端末情報（同一ユーザーID）</div>
            <div class="panel-body">
                <?php if(isset($item['device_list']) && !empty($item['device_list'])): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>端末種別</th>
                            <th>ユーザーID</th>
                            <th>端末状態</th>
                            <th>更新時間</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($item['device_list'] as $device): ?>
                            <?php
                            $dOs = "";
                            if ($device['push_target'] == "ios") $dOs = "iPhone";
                            if ($device['push_target'] == "android") $dOs = "Android";

                            $dConditions = "";
                            if (strpos($device['last_send_result_detail'], 'success') !== false) $dConditions = "問題なし";
                            if (strpos($device['last_send_result_detail'], 'error') !== false) $dConditions = "問題あり";
                            if ($device['last_send_result_detail'] === null) $dConditions = "未送信";
                            ?>
                            <tr>
                                <td><?php echo $this->Html->link($device['id'],
                                        "detail/{$device['id']}"); ?></td>
                                <td><?php echo $dOs; ?></td>
                                <td><?php echo $device['user_id']; ?></td>
                                <td><?php echo $dConditions; ?></td>
                                <td><?php echo $device['modified']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
                <?php else: ?>
                    <p>他端末情報がありません。</p>
                <?php endif; ?>
            </div>
        </div>
        <!-- /.panel-body -->
    </div>
    <!-- /.panel -->
</div>
<!-- /.col-lg-12 -->

