<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="col-lg-12">
                    <?php echo $this->Xformjp->create('Service',
                        array('class' => 'form-horizontal', 'type' => 'get', 'url' => '#')); ?>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">サービス名▶</label>
                        <div class="col-sm-9">
                            <p class='form-control-static'><?php echo $item['service_name']; ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">サービスコード▶</label>
                        <div class="col-sm-9">
                            <p class='form-control-static'><?php echo $item['service_code']; ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">認証キー▶</label>
                        <div class="col-sm-9">
                            <p class='form-control-static'><?php echo "********"; ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">APNS証明書ファイル▶</label>
                        <div class="col-sm-9">
                            <?php if ($item['ios_cert_file'] != ''): ?>
                                <p class='form-control-static'><?php echo $this->Html->link('証明書ダウンロード', array('action' => 'download_cert')); ?></p>
                            <?php else: ?>
                                <p class='form-control-static'>APNS証明書ファイルは未設定です。</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">GCM APIキー▶</label>
                        <div class="col-sm-9">
                            <?php if ($item['android_api_key'] != ''): ?>
                                <p class='form-control-static'><?php echo $item['android_api_key']; ?></p>
                            <?php else: ?>
                                <p class='form-control-static'>GCM APIキーは未設定です。</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">管理者名▶</label>
                        <div class="col-sm-9">
                            <p class='form-control-static'><?php echo $item['contact_name']; ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">管理者メールアドレス▶</label>
                        <div class="col-sm-9">
                            <p class='form-control-static'><?php echo $item['contact_email']; ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">登録日時▶</label>
                        <div class="col-sm-9">
                            <p class='form-control-static'><?php echo $item['created']; ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">更新日時▶</label>
                        <div class="col-sm-9">
                            <p class='form-control-static'><?php echo $item['modified']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-offset-3 col-sm-9 form-submit">
                    <p>
                        <?php
                            echo $this->Html->link('編集する', './update', array('class' => 'btn btn-info'));
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
</div>
<!-- /.row -->