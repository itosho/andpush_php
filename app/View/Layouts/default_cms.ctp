<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>andPush Content Management System</title>
    <!-- Core CSS - Include with every page -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

    <!-- Page-Level Plugin CSS - Tables -->
    <link href="/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">

    <!-- SB Admin CSS - Include with every page -->
    <link href="/css/sb-admin.css" rel="stylesheet">
    <!-- Admin CSS - Include with every page -->
    <link href="/css/admin.css" rel="stylesheet">
    <script src="/js/jquery-1.10.2.js"></script>
    <script type="text/javascript" src="//www.google.com/jsapi"></script>
</head>

<body>

<div id="wrapper">

    <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <h1>
                <a class="navbar-brand" href="/cms">&nbsp;<i class="fa fa-paper-plane-o fa-lg"></i>&nbsp;andPush Content Management System</a>
            </h1>
        </div>
        <!-- /.navbar-header -->

        <ul class="nav navbar-top-links navbar-right">

            <!-- /.dropdown -->
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <?php echo $this->Session->read('service_code') . "：" . $this->Session->read('service_name') . " "; ?>
                    <i class="fa fa-caret-down"></i> </a>
                <ul class="dropdown-menu dropdown-user">
                    <li>
                        <a href="/cms/logout"><i class="fa fa-sign-out fa-fw"></i>ログアウト</a>
                    </li>
                </ul>
                <!-- /.dropdown-user -->
            </li>
            <!-- /.dropdown -->
        </ul>
        <!-- /.navbar-top-links -->

        <div class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="side-menu">
                    <li>
                        <a <?php echo $this->name == 'Message' ? "class='active'": ""; ?> href="/cms/message">
                            <i class="fa fa-comment fa-fw"></i> メッセージ管理</a>
                    </li>
                    <li>
                        <a <?php echo $this->name == 'Device' ? "class='active'": ""; ?> href="/cms/device_user">
                            <i class="fa fa-mobile fa-fw"></i> 端末ユーザー管理</a>
                    </li>
                    <li>
                        <a <?php echo $this->name == 'Service' ? "class='active'": ""; ?> href="/cms/service">
                            <i class="fa fa-wrench fa-fw"></i> サービス管理</a>
                    </li>
                    <li>
                        <a <?php echo $this->name == 'Property' ? "class='active'": ""; ?> href="/cms/property">
                            <i class="fa fa-user fa-fw"></i> 属性情報マスタ管理</a>
                    </li>
                    <li>
                        <a <?php echo $this->name == 'Statistic' ? "class='active'": ""; ?> href="/cms/statistic">
                            <i class="fa fa-bar-chart fa-fw"></i> 統計情報管理</a>
                    </li>
                    <li>
                        <br />
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-book fa-fw"></i> マニュアル</a>
                    </li>
                </ul>
                <?php // var_dump($this->request); ?>
                <!-- /#side-menu -->
            </div>
            <!-- /.sidebar-collapse -->
        </div>
        <!-- /.navbar-static-side -->
    </nav>
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <?php echo $this->Session->flash(); ?>
                <h2 class="page-header"><?php echo $title_for_layout; ?></h2>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <?php echo $this->fetch('content'); ?>

    </div>
    <!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->
<div id="footer">
    <div class="container">
        <p class="text-muted pull-right">
            <small>Copyright © 2016 itosho All Rights Reserved.</small>
        </p>
    </div>
</div>
<!-- Core Scripts - Include with every page -->
<script src="/js/bootstrap.min.js"></script>
<script src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>

<!-- Page-Level Plugin Scripts - Tables -->
<script src="/js/plugins/dataTables/jquery.dataTables.js"></script>
<script src="/js/plugins/dataTables/dataTables.bootstrap.js"></script>

<!-- SB Admin Scripts - Include with every page -->
<script src="/js/sb-admin.js"></script>
<!-- Admin Scripts - Include with every page -->
<script src="/js/admin.js"></script>

<!-- Page-Level Demo Scripts - Tables - Use for reference -->
<script>
    $(document).ready(function () {
        $('#dataTables-example').dataTable();
    });
</script>

<?php echo $this->fetch('scriptBottom'); ?>
</body>

</html>
