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
                    <?php echo $this->Xformjp->create('Property',
                        array('class' => 'form-horizontal', 'type' => 'post',
                            'enctype'=>'multipart/form-data')); ?>
                    <?php for ($i=0; $i<20; $i++): ?>
                    <div class="form-group">
                        <label for="key_name<?php echo $i;?>"class="col-sm-2 control-label set">キー名▶</label>
                        <div class="col-sm-4">
                            <?php echo $this->Xformjp->input("Property.$i.key_name",
                                array('class' => 'form-control', 'id' => "key_name$i")); ?>
                            <p class="help-block">半角英数字で入力してください。</p>

                        </div>
                        <label for="label_name<?php echo $i;?>" class="col-sm-2 control-label set">ラベル名▶</label>
                        <div class="col-sm-4">
                            <?php echo $this->Xformjp->input("Property.$i.label_name",
                                array('class' => 'form-control', 'id' => "label_name$i")); ?>
                            <p class="help-block">日本語名を入力してください。</p>
                        </div>
                    </div>
                    <?php endfor; ?>

                    <div class="col-sm-offset-3 col-sm-9 form-submit">
                        <p>
                           <?php echo $this->Html->link('キャンセル', array('action' => 'index'),
                               array('class' => 'btn btn-default btn-lg')); ?>
                           <?php echo $this->Xformjp->button('設定する', array(
                                    'name' => 'submit_confirm',
                                    'type' => 'submit',
                                    'class' => 'btn btn-info btn-lg'
                                )); ?>
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