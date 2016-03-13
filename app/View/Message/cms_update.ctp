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
                            <p class="help-block">Pushメッセージのタイトルです。30文字以内で入力してください。</p>
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
                                        'minYear' => 2015, 'maxYear' => 2020, 'minuteInterval' => 10)); ?>
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