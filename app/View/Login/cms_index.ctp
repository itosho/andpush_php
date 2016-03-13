<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>andPush CMS</title>

    <!-- Core CSS - Include with every page -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- SB Admin CSS - Include with every page -->
    <link href="/css/sb-admin.css" rel="stylesheet">
    <!-- Admin CSS - Include with every page -->
    <link href="/css/login-admin.css" rel="stylesheet">

</head>

<body>

<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">andPush CMS</h3>
                </div>
                <div class="panel-body">
                    <?php echo $this -> Session -> flash(); ?>
                    <form role="form" method="post" action="/cms/login">
                        <fieldset>
                            <div class="form-group">
                                <input name="data[AuthApi][service_code]" type="text" class="form-control" placeholder="ID" autofocus>
                            </div>
                            <div class="form-group">
                                <input name="data[AuthApi][auth_key]" type="password" class="form-control" placeholder="パスワード">
                            </div>
                            <!-- Change this to a button or input when using this as a form -->
                            <button type="submit" class="btn btn-lg btn-warning btn-block">
                                ログイン</button>
                        </fieldset>
                    </form>
                </div>
            </div>
            <p class="text-muted pull-center"><small>Copyright © 2015 itosho All Rights Reserved.</small></p>
        </div>
    </div>
</div>

<!-- Core Scripts - Include with every page -->
<script src="/js/jquery-1.10.2.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>

<!-- SB Admin Scripts - Include with every page -->
<script src="/js/sb-admin.js"></script>

</body>

</html>
