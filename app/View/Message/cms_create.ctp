<div class="row">
    <div class="col-lg-12">
        <?php if (isset($errMsgList) && is_array($errMsgList)) {
            echo '<div class="alert alert-danger">';
            foreach ($errMsgList as $errMsg) {
                echo $errMsg;
                echo '</br>';
            }
            echo '</div>';
        }
        ?>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="col-lg-12">
                    <?php echo $this->Xformjp->create('Message',
                        array('class' => 'form-horizontal', 'type' => 'post',
                            'enctype'=>'multipart/form-data')); ?>
                    <div class="form-group">
                        <label for="message_title" class="col-sm-2 control-label">タイトル▶</label>
                        <div class="col-sm-10">
                            <?php echo $this->Xformjp->input('Message.message_title',
                                array('class' => 'form-control', 'id' => 'message_title')); ?>
                            <?php if (!$this->Xformjp->checkConfirmScreen()) { ?>
                                <p class="help-block">Pushメッセージのタイトルです。30文字以内で入力してください。
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message_body" class="col-sm-2 control-label"><span class="required">*</span>メッセージ内容▶</label>
                        <div class="col-sm-10">
                            <?php echo $this->Xformjp->input('Message.message_body',
                                array('class' => 'form-control', 'id' => 'message_body', 'type'=> 'textarea')); ?>
                            <?php if (!$this->Xformjp->checkConfirmScreen()) { ?>
                                <p class="help-block">Pushメッセージの内容です。140文字以内で入力してください。</p>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><span class="required">*</span>送信端末▶</label>
                        <div class="col-sm-10">
                            <?php if (!$this->Xformjp->checkConfirmScreen()) { ?>
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">
                                    Pushメッセージ送信端末設定
                                </button>
                                <span id="device-count" class="text-danger">&nbsp;設定状況：<?php echo $deviceCount; ?>台</span>
                            <?php } else { ?>
                                <p class="form-control-static text-danger"><?php echo $deviceCount; ?>台</p>
                            <?php } ?>
                            <?php if (!$this->Xformjp->checkConfirmScreen()) { ?>
                                <p class="help-block">デフォルトでは、全端末に通知されます。</p>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label"><span class="required">*</span>送信方法▶</label>
                        <div class="col-sm-10">
                            <?php echo $this -> Xformjp -> radio('Message.send_type', array('1' => '予約送信', '0' => '即時送信'), array('legend' => false, 'label' => false, 'default' => 1)); ?>
                        </div>
                    </div>

                    <?php if ((!$this->Xformjp->checkConfirmScreen()) || $this->data['Message']['send_type'] == "1") { ?>
                    <div class="form-group option">
                        <label for="send_time" class="col-sm-2 control-label"><span class="required">*</span>送信日時▶</label>
                        <div class="col-sm-10">
                            <?php echo $this -> Xformjp -> dateTime('Message.send_time', 'YMD', '24',
                                array('class' => 'select-date', 'id' => 'send_time', 'monthNames' => false, 'empty' => false,
                                    'maxYear' => 2020, 'minYear' => 2015, 'orderYear' => 'asc', 'minuteInterval' => 10)); ?>
                            <?php if (!$this->Xformjp->checkConfirmScreen()) { ?>
                                <p class="help-block">10分単位で指定が可能です。</p>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="col-sm-offset-3 col-sm-9 form-submit">
                        <p>
                            <?php if ($this->Xformjp->checkConfirmScreen()) { ?>
                                <?php echo $this->Formhidden->hiddenVars(); ?>
                                <input type="hidden" name="back" id="back"/>
                                <input type="hidden" name="mode" id="mode" value="2"/>
                                <?php echo $this->Xformjp->button('修正する', array(
                                    'name' => 'submit_back',
                                    'onclick' => "javascript:document.getElementById('back').value = '1';",
                                    'type' => 'submit',
                                    'class' => 'btn btn-default btn-lg'
                                ));
                                echo $this->Xformjp->button('登録する',
                                    array('name' => 'submit_ok', 'type' => 'submit', 'class' => 'btn btn-info btn-lg'));
                                ?>
                            <?php } else { ?>
                                <input type="hidden" name="mode" id="mode" value="1">
                                <?php echo $this->Html->link('キャンセル', array('action' => 'index'),
                                    array('class' => 'btn btn-default btn-lg')); ?>
                                <?php echo $this->Xformjp->button('確認画面へ', array(
                                    'name' => 'submit_confirm',
                                    'type' => 'submit',
                                    'class' => 'btn btn-info btn-lg'
                                )); ?>
                            <?php } ?>
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
</div>
<!-- /.row -->
<!-- modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Pushメッセージ送信端末設定</h4>
            </div>
            <div class="modal-body">
                <?php echo $this->Xformjp->create('Message',
                    array(
                        'id' => 'search',
                        'class' => 'form-horizontal',
                        'type' => 'post',
                        'enctype' => 'multipart/form-data'
                    )); ?>
                    <?php foreach ($propertyLabels as $label): ?>
                        <div class="form-group">
                            <label for="<?php echo $label['label_name']; ?>" class="col-sm-2 control-label"><?php echo $label['label_name']; ?>▶</label>
                            <div class="col-sm-10">
                                <?php echo $this->Xformjp->input($label['key_name'],
                                    array('class' => 'form-control', 'id' => $label['label_name'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php echo $this->Xformjp->button('<i class="fa fa-search"></i> 検索する', array(
                    'id' => 'submit_search',
                    'name' => 'submit_confirm',
                    'type' => 'submit',
                    'class' => 'btn btn-warning btn-lg btn-block'
                )); ?>
                <?php echo $this->Xformjp->end(); ?>
                <br />
                <div class="alert alert-warning">
                    Pushメッセージ送信端末台数：<?php echo $deviceCount; ?>台
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
                <button id="set_segment" type="button" class="btn btn-primary">設定する</button>
            </div>
        </div>
    </div>
</div>
<script>
    $('#search').submit(function(event) {
        // HTMLでの送信をキャンセル
        event.preventDefault();

        // 操作対象のフォーム要素を取得
        var $form = $(this);

        // 送信
        $.ajax({
            'type': 'post',
            'dataType': 'json',
            'url': '/cms/message/async_search_segment',
            'data': $form.serialize(),
            'success': function(data) {
                if(data.result == 1) {
                    // 成功時の処理。無事Userからリストを取得する。
                    $(".modal-body .alert").text("Pushメッセージ送信端末台数：" + data.count + "台");

                } else {
                    alert(data.error_msg_list[0]);
                }
            },
            'error': function() {
                alert("予期せぬエラーが発生しました。");
            }
        });
    });

    $('#set_segment').click(function(event) {
        // HTMLでの送信をキャンセル
        event.preventDefault();

        // 送信
        $.ajax({
            'type': 'get',
            'dataType': 'json',
            'url': '/cms/message/async_set_segment',
            'success': function(data) {
                if(data.result == 1) {
                    $("#device-count").text(" 設定状況：" + data.count + "台");
                    $('#myModal').modal('hide');

                } else {
                    alert(data.error_msg);
                }
            },
            'error': function() {
                alert("予期せぬエラーが発生しました。");
            }
        });
    });
</script>