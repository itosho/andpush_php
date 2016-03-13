<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="col-lg-12">
                    <?php echo $this->Xformjp->create('Message',
                        array('class' => 'form-horizontal', 'type' => 'get', 'url' => '#')); ?>
                    <?php
                    $result = "";
                    if ($item['send_result_code'] == '2000') $result = "送信成功";
                    if ($item['send_result_code'] == '2001') $result = "予約成功";
                    if ($item['send_result_code'] == '2002') $result = "送信成功";
                    if ($item['send_result_code'] == '1000') $result = "送信失敗";
                    if ($item['send_result_code'] == '1001') $result = "予約失敗";
                    if ($item['send_result_code'] == '1002') $result = "送信失敗";
                    ?>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">タイトル▶</label>
                        <div class="col-sm-9">
                            <p class='form-control-static'><?php echo $item['message_title']; ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">メッセージ内容▶</label>
                        <div class="col-sm-9">
                            <p class='form-control-static'><?php echo $item['message_body']; ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">送信時間▶</label>
                        <div class="col-sm-9">
                            <p class='form-control-static'><?php echo isset($item['send_time']) ? $item['send_time'] : $item['created']; ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">送信結果▶</label>
                        <div class="col-sm-9">
                            <p class='form-control-static'><?php echo $result; ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">送信端末数▶</label>
                        <div class="col-sm-9">
                            <p class='form-control-static'><?php echo $item['count']; ?></p>
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
                        <?php if ($item['send_result_code'] == '2001'): ?>
                            <?php
                            echo $this->Html->link('一覧へ戻る', array('action' => 'cms_index'),
                                array('class' => 'btn btn-default'));
                            echo $this->Html->link('編集する', './update/' . $item['id'],
                                array('class' => 'btn btn-info'));
                            echo $this->Html->link('削除する', './destroy/' . $item['id'],
                                array('class' => 'btn btn-danger'));
                            ?>
                        <?php else: ?>
                            <?php
                            echo $this->Html->link('一覧へ戻る', array('action' => 'cms_index'),
                                array('class' => 'btn btn-default'));
                            echo $this->Html->link('編集する', './update/' . $item['id'],
                                array('class' => 'btn btn-default', 'disabled'=>'disabled'));
                            echo $this->Html->link('削除する', './destroy/' . $item['id'],
                                array('class' => 'btn btn-danger'));
                            ?>
                        <?php endif; ?>
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