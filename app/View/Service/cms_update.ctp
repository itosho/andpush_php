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
                    <?php echo $this->Xformjp->create('Service',
                        array(
                            'class' => 'form-horizontal',
                            'type' => 'post',
                            'enctype' => 'multipart/form-data'
                        )); ?>
                    <div class="form-group">
                        <label for="service_name" class="col-sm-2 control-label"><span
                                class="required">*</span>サービス名▶</label>

                        <div class="col-sm-10">
                            <?php echo $this->Xformjp->input('Service.service_name',
                                array('class' => 'form-control', 'id' => 'service_name')); ?>
                            <?php if (!$this->Xformjp->checkConfirmScreen()): ?>
                                <p class="help-block">30文字以内で入力してください。</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="service_code" class="col-sm-2 control-label"><span class="required">*</span>サービスコード▶</label>

                        <div class="col-sm-10">
                            <?php echo $this->Xformjp->input('Service.service_code',
                                array('class' => 'form-control', 'id' => 'service_code')); ?>
                            <?php if (!$this->Xformjp->checkConfirmScreen()): ?>
                                <p class="help-block">半角英数字20文字以内で入力してください。</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password_text" class="col-sm-2 control-label">認証キー▶</label>

                        <div class="col-sm-10">
                            <?php echo $this->Xformjp->password('Service.password_text',
                                array('class' => 'form-control', 'id' => 'password_text')); ?>
                            <?php if (!$this->Xformjp->checkConfirmScreen()) { ?>
                                <p class="help-block">半角英数字8文字以上で入力してください。<br/>
                                    認証キーを変更しない場合は入力不要です。</p>
                            <?php } else { ?>
                                <?php echo empty($this->data['Service']['password_text']) ? "<p class='form-control-static'>更新なし</p>" : "<p class='form-control-static'>********</p>"; ?>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password_confirm" class="col-sm-2 control-label">認証キー確認▶</label>

                        <div class="col-sm-10">
                            <?php echo $this->Xformjp->password('Service.password_confirm',
                                array('class' => 'form-control', 'id' => 'password_confirm')); ?>
                            <?php if (!$this->Xformjp->checkConfirmScreen()) { ?>
                                <p class="help-block">確認のため、もう一度入力してください。<br/>
                                    認証キーを変更しない場合は入力不要です。</p>
                            <?php } else { ?>
                                <?php echo empty($this->data['Service']['password_confirm']) ? "<p class='form-control-static'>更新なし</p>" : "<p class='form-control-static'>********</p>"; ?>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">証明書▶</label>

                        <div class="col-sm-10">
                            <?php echo $this->Xformjp->input('certification',
                                array(
                                    'type' => 'file',
                                    'style' => 'display:none;',
                                    'class' => 'file',
                                    'id' => 'certification'
                                )); ?>
                            <?php if (!$this->Xformjp->checkConfirmScreen()) { ?>
                                <div class="input-group">
                                    <?php echo $this->Xformjp->input('path_certification',
                                        array(
                                            'class' => 'form-control alias-input',
                                            'id' => 'input-certification',
                                            'readonly' => true
                                        )); ?>
                                    <span class="input-group-btn">
                                        <button id="button-certification" class="btn btn-default alias-button"
                                            type="button">ファイル選択
                                        </button>
                                        <?php /*
                                        <button id="button-certification-clear" class="btn btn-danger clear-button"
                                                type="button">クリア
                                        </button>
                                        */ ?>
                                    </span>
                                </div>
                            <?php } else { ?>
                                <?php echo $this->Xformjp->input('path_certification',
                                    array(
                                        'class' => 'form-control alias-input',
                                        'id' => 'input-certification',
                                        'readonly' => true
                                    )); ?>
                                <p class='form-control-static'>
                                <?php echo empty($this->data['Service']['path_certification']) ? '更新なし' : $this->data['Service']['path_certification']; ?>
                                </p>
                            <?php } ?>
                            <?php if (!$this->Xformjp->checkConfirmScreen()) { ?>
                                <p class="help-block">.pemファイルを選択してください。変更しない場合は更新不要です。</p>
                            <?php } ?>
                            <?php echo $this->Xformjp->input('certification_file', array('type' => 'hidden')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="android_api_key" class="col-sm-2 control-label">GCM APIキー▶</label>

                        <div class="col-sm-10">
                            <?php echo $this->Xformjp->input('Service.android_api_key',
                                array('class' => 'form-control', 'id' => 'android_api_key')); ?>
                            <?php if (!$this->Xformjp->checkConfirmScreen()) { ?>
                                <p class="help-block"></p>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="contact_name" class="col-sm-2 control-label"><span
                                class="required">*</span>管理者名▶</label>

                        <div class="col-sm-10">
                            <?php echo $this->Xformjp->input('Service.contact_name',
                                array('class' => 'form-control', 'id' => 'contact_name')); ?>
                            <?php if (!$this->Xformjp->checkConfirmScreen()) { ?>
                                <p class="help-block">20文字以内で入力してください。</p>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="contact_email" class="col-sm-2 control-label"><span class="required">*</span>メールアドレス▶</label>

                        <div class="col-sm-10">
                            <?php echo $this->Xformjp->input('Service.contact_email',
                                array('class' => 'form-control', 'id' => 'contact_email')); ?>
                            <?php if (!$this->Xformjp->checkConfirmScreen()) { ?>
                                <p class="help-block">メールアドレス形式で入力してください。</p>
                            <?php } ?>
                        </div>
                    </div>

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