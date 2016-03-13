<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>端末種別</th>
                            <th>ユーザーID</th>
                            <th>端末状態</th>
                            <th>更新時間</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($list as $item): ?>
                            <?php
                            $os = "";
                            if ($item['push_target'] == "ios") $os = "iPhone";
                            if ($item['push_target'] == "android") $os = "Android";

                            $conditions = "";
                            if (strpos($item['last_send_result_detail'], 'success') !== false) $conditions = "問題なし";
                            if (strpos($item['last_send_result_detail'], 'error') !== false) $conditions = "問題あり";
                            if ($item['last_send_result_detail'] === null) $conditions = "未送信";
                            ?>
                            <tr>
                                <td><?php echo $this->Html->link($item['id'],
                                        "detail/{$item['id']}"); ?></td>
                                <td><?php echo $os; ?></td>
                                <td><?php echo $item['user_id']; ?></td>
                                <td><?php echo $conditions; ?></td>
                                <td><?php echo $item['modified']; ?></td>
                                <td>
                                    <?php echo $this->Html->link("編集", "update/{$item['id']}",
                                            array(
                                                'class' => 'btn btn-outline btn-default btn-sm',
                                                'disabled' => 'disabled'
                                            )); ?>
                                    <?php echo $this->Html->link("削除", "destroy/{$item['id']}",
                                        array(
                                            'class' => 'btn btn-outline btn-default btn-sm',
                                            'disabled' => 'disabled'
                                            )); ?>
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