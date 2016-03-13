<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>andPush Rest APIs Documentation</title>
    <!-- Core CSS - Include with every page -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/font-awesome/css/font-awesome.min.css">
    <!-- Page-Level Plugin CSS - Tables -->
    <link href="/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
    <!-- SB Admin CSS - Include with every page -->
    <link href="/css/sb-admin.css" rel="stylesheet">
    <!-- Admin CSS - Include with every page -->
    <link href="/css/admin.css" rel="stylesheet">
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
            <h1><a class="navbar-brand" href="/docs">&nbsp;<i class="fa fa-paper-plane-o fa-lg"></i>&nbsp;andPush Rest APIs
                Documentation</a></h1>
        </div>
        <!-- /.navbar-header -->
        <ul class="nav navbar-top-links navbar-right">
            <!-- /.dropdown -->
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    Last Updated ： 2015.08.01, Version 2.0.0
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li>
                        <a href="#">更新履歴はありません。</a>
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
                    <li <?php echo (strpos($this->request->url, 'docs/push') !== false) ? "class='active'" : ""; ?> >
                        <a href="#"><i class="fa fa-paper-plane fa-fw"></i>&nbsp;push APIs<span
                                class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a <?php echo $this->request->url == 'docs/push_token' ? "class='active'" : ""; ?>
                                    href="/docs/push_token">POST /push/token</a>
                            </li>
                            <li>
                                <a <?php echo $this->request->url == 'docs/push_user' ? "class='active'" : ""; ?>
                                    href="/docs/push_user">POST /push/user</a>
                            </li>
                            <li>
                                <a <?php echo $this->request->url == 'docs/push_property' ? "class='active'" : ""; ?>
                                    href="/docs/push_property">POST /push/property</a>
                            </li>
                            <li>
                                <a <?php echo $this->request->url == 'docs/push_all' ? "class='active'" : ""; ?>
                                    href="/docs/push_all">POST /push/all</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <li <?php echo (strpos($this->request->url, 'docs/message') !== false) ? "class='active'" : ""; ?> >
                        <a href="#"><i class="fa fa-comment fa-fw"></i>&nbsp;message APIs<span
                                class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a <?php echo $this->request->url == 'docs/message_destroy' ? "class='active'" : ""; ?>
                                    href="/docs/message_destroy">POST /message/destroy</a>
                            </li>
                            <li>
                                <a <?php echo $this->request->url == 'docs/message' ? "class='active'" : ""; ?>
                                    href="/docs/message">POST /message</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <li <?php echo (strpos($this->request->url, 'docs/device') !== false) ? "class='active'" : ""; ?> >
                        <a href="#"><i class="fa fa-mobile fa-fw"></i>&nbsp;device APIs<span
                                class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a <?php echo $this->request->url == 'docs/device_entry' ? "class='active'" : ""; ?>
                                    href="/docs/device_entry">POST /device/entry</a>
                            </li>
                            <li>
                                <a <?php echo $this->request->url == 'docs/device_open' ? "class='active'" : ""; ?>
                                    href="/docs/device_open">POST /device/open</a>
                            </li>
                            <li>
                                <a <?php echo $this->request->url == 'docs/device_clear' ? "class='active'" : ""; ?>
                                    href="/docs/device_clear">POST /device/clear</a>
                            </li>
                            <li>
                                <a <?php echo $this->request->url == 'docs/device' ? "class='active'" : ""; ?>
                                    href="/docs/device">POST /device</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <li <?php echo (strpos($this->request->url, 'docs/user') !== false) ? "class='active'" : ""; ?> >
                        <a href="#"><i class="fa fa-user fa-fw"></i>&nbsp;user APIs<span
                                class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a <?php echo $this->request->url == 'docs/user_property' ? "class='active'" : ""; ?>
                                    href="/docs/user_property">POST /user/property</a>
                            </li>
                            <li>
                                <a <?php echo $this->request->url == 'docs/user_destroy' ? "class='active'" : ""; ?>
                                    href="/docs/user_destroy">POST /user/destroy</a>
                            </li>
                            <li>
                                <a <?php echo $this->request->url == 'docs/user' ? "class='active'" : ""; ?>
                                    href="/docs/user">POST /user</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <li <?php echo (strpos($this->request->url,
                            'docs/statistic') !== false) ? "class='active'" : ""; ?> >
                        <a href="#"><i class="fa fa-bar-chart fa-fw"></i>&nbsp;statistic APIs<span
                                class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a <?php echo $this->request->url == 'docs/statistic_daily' ? "class='active'" : ""; ?>
                                    href="/docs/statistic_daily">GET /statistic/daily</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
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
<script src="/js/jquery-1.10.2.js"></script>
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
