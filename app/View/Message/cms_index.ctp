<div class="row">
    <div class="col-lg-12">
        <div class="panel">
            <a href="/cms/message/create" class="btn btn-outline btn-warning btn-lg btn-block">新規登録</a>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>タイトル</th>
                            <th>Pushメッセージ内容</th>
                            <th>送信時間</th>
                            <th>送信結果</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($list as $item): ?>
                            <?php
                                $result = "";
                                if ($item['send_result_code'] == '2000') $result = "送信成功";
                                if ($item['send_result_code'] == '2001') $result = "予約成功";
                                if ($item['send_result_code'] == '2002') $result = "送信成功";
                                if ($item['send_result_code'] == '1000') $result = "送信失敗";
                                if ($item['send_result_code'] == '1001') $result = "予約失敗";
                                if ($item['send_result_code'] == '1002') $result = "送信失敗";
                            ?>
                            <tr>
                                <td><?php echo $this->Html->link($item['id'],
                                        "detail/{$item['id']}"); ?></td>
                                <td><?php echo $item['message_title']; ?></td>
                                <td><?php echo $this->Text->truncate($item['message_body'], 30); ?></td>
                                <td><?php echo isset($item['send_time']) ? $item['send_time'] : $item['created']; ?></td>
                                <td><?php echo $result; ?></td>
                                <td>
                                    <?php if ($item['send_result_code'] == '2001'): ?>
                                        <?php echo $this->Html->link("編集", "update/{$item['id']}",
                                            array('class' => 'btn btn-outline btn-info btn-sm')); ?>
                                        <?php $destoryLabel = "取消"; ?>
                                    <?php else: ?>
                                        <?php echo $this->Html->link("編集", "update/{$item['id']}",
                                            array(
                                                'class' => 'btn btn-outline btn-default btn-sm',
                                                'disabled' => 'disabled'
                                            )); ?>
                                        <?php $destoryLabel = "削除"; ?>
                                    <?php endif; ?>

                                    <?php echo $this->Html->link($destoryLabel, "destroy/{$item['id']}",
                                            array('class' => 'btn btn-outline btn-danger btn-sm')); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="dataTables_info" id="dataTables-example_info" role="alert" aria-live="polite">
                            <?php echo $this->Paginator->counter(array('format' => '%count%件中 %start%〜%end%件')); ?>
                        </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="dataTables_paginate paging_simple_numbers" id="dataTables-example_paginate">
                                <ul class="pagination">
                                    <?php echo $this->Paginator->prev(__('前へ'), array('tag' => 'li'), null,
                                        array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a')); ?>
                                    <?php echo $this->Paginator->numbers(array(
                                        'separator' => '',
                                        'currentTag' => 'a',
                                        'currentClass' => 'active',
                                        'tag' => 'li',
                                        'first' => 1,
                                        'ellipsis' => '<li class="disabled"><a>...</a></li>'
                                    )); ?>
                                    <?php echo $this->Paginator->next(__('次へ'),
                                        array('tag' => 'li', 'currentClass' => 'disabled'), null,
                                        array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a')); ?>
                                </ul>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>