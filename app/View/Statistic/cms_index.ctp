<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                メッセージ送信 / 開封状況
            </div>
            <div class="panel-body">
                <div class="col-lg-12">
                    <?php echo $this->Xformjp->create('Statistic',
                        array(
                            'class' => 'form-horizontal',
                            'type' => 'get',
                            'enctype' => 'multipart/form-data'
                        )); ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">対象期間▶</label>

                        <div class="col-sm-7">
                            <?php echo $this->Xformjp->input('from_year', $this->SelectOption->yearArray('from_year')) ?>&nbsp;年
                            <?php echo $this->Xformjp->input('from_month', $this->SelectOption->MonthArray('from_month')) ?>&nbsp;月
                            <?php echo $this->Xformjp->input('from_day', $this->SelectOption->DayArray('from_day')) ?>&nbsp;日
                            〜
                            <?php echo $this->Xformjp->input('to_year', $this->SelectOption->yearArray('to_year')) ?>&nbsp;年
                            <?php echo $this->Xformjp->input('to_month', $this->SelectOption->MonthArray('to_month')) ?>&nbsp;月
                            <?php echo $this->Xformjp->input('to_day', $this->SelectOption->DayArray('to_day')) ?>&nbsp;日
                        </div>
                        <div class="col-sm-3">
                                <?php echo $this->Xformjp->button('<i class="fa fa-search"></i> 検索する', array(
                                    'id' => 'submit_daily',
                                    'name' => 'submit_confirm',
                                    'type' => 'submit',
                                    'class' => 'btn btn-primary'
                                )); ?>
                        </div>

                    </div>
                    <?php echo $this->Xformjp->end(); ?>
                </div>
                <div class="col-lg-12">
                    <div style="margin:0; padding:0; width:auto; height:420px;" id="graphField">読み込み中…
                    </div>
                    <script type="text/javascript">
                        google.load('visualization', '1.0', {'packages': ['corechart']});
                        google.setOnLoadCallback(function () {
                            var data = google.visualization.arrayToDataTable([
                                ['時間', '送信端末数', '開封数'],
                                <?php foreach($datum as $data): ?>
                                [
                                    '<?php echo date('m/d', strtotime($data['statistic_date'])); ?>',
                                    <?php echo $data['count_push_device_06'] ?>,
                                    <?php echo $data['count_open_06'] ?>
                                ],
                                [
                                    '',
                                    <?php echo $data['count_push_device_12'] ?>,
                                    <?php echo $data['count_open_12'] ?>

                                ],
                                [
                                    '',
                                    <?php echo $data['count_push_device_18'] ?>,
                                    <?php echo $data['count_open_18'] ?>

                                ],
                                [
                                    '',
                                    <?php echo $data['count_push_device_24'] ?>,
                                    <?php echo $data['count_open_24'] ?>

                                ],
                                <?php endforeach ?>
                            ]);

                            var options = {
                                chartArea: {'width': '95%', 'height': '75%', 'left': 55},
                                hAxis: {title: '時間', titleTextStyle: {italic: false}},
                                vAxis: {title: '数', titleTextStyle: {italic: false}},
                                crosshair: {trigger: 'both'},
                                legend: {position: 'top'},
                                colors:['#ef9a9a','#81d4fa']
                            };
                            var chart = new google.visualization.LineChart(document.getElementById('graphField'));
                            chart.draw(data, options);
                        });
                    </script>
                    <script>
                        $('#StatisticCmsIndexForm').submit(function(event) {
                            // HTMLでの送信をキャンセル
                            event.preventDefault();

                            // 操作対象のフォーム要素を取得
                            var $form = $(this);

                            // 送信
                            $.ajax({
                                'type': 'get',
                                'dataType': 'json',
                                'url': '/cms/statistic/async_daily',
                                'data': $form.serialize(),
                                'success': function(data) {
                                    var list = null;
                                    if(data.result == 1) {
                                        // 成功時の処理。無事Userからリストを取得する。
                                        list = data.datum;

                                        var datum = [];
                                        var i;
                                        var dateList;
                                        var statisticDate;

                                        datum.push(['時間', '送信端末数', '開封数']);

                                        for (i = 0; i < list.length; i++) {

                                            dateList = list[i]['statistic_date'].split('-');
                                            statisticDate = dateList[1] + '/' + dateList[2];

                                            datum.push(
                                                    [statisticDate,
                                                    parseInt(list[i]['count_push_device_06'], 10),
                                                    parseInt(list[i]['count_open_06'], 10)]
                                            );
                                            datum.push(
                                                        ['',
                                                        parseInt(list[i]['count_push_device_12'], 10),
                                                    parseInt(list[i]['count_open_12'], 10)]);
                                            datum.push(
                                                ['',
                                                    parseInt(list[i]['count_push_device_18'], 10),
                                                    parseInt(list[i]['count_open_18'], 10)]);
                                            datum.push(
                                                ['',
                                                    parseInt(list[i]['count_push_device_24'], 10),
                                                    parseInt(list[i]['count_open_24'], 10)]);
                                        }

                                        var options = {
                                            chartArea: {'width': '95%', 'height': '75%', 'left': 55},
                                            hAxis: {title: '時間', titleTextStyle: {italic: false}},
                                            vAxis: {title: '数', titleTextStyle: {italic: false}},
                                            crosshair: {trigger: 'both'},
                                            legend: {position: 'top'},
                                            colors:['#ef9a9a','#81d4fa']
                                        };

                                        var data = google.visualization.arrayToDataTable(datum);

                                        var chart = new google.visualization.LineChart(document.getElementById('graphField'));
                                        chart.draw(data, options);

                                    } else {
                                        // 失敗時の処理。失敗したことを伝える。
                                        alert(data.err_msg);
                                    }
                                },
                                'error': function() {
                                    // アクション側でExceptionが投げられた場合はここに来る。
                                    // エラーをここで処理したい場合はExceptionを投げても良い
                                }
                            });
                        });
                    </script>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>