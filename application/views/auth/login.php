<?php
/**
 * This <lide-app.com.io> project created by :
 * Name         : syafiq
 * Date / Time  : 25 November 2016, 7:00 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

?>
<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Login</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/normalize.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/main.css') ?>">
    <link href="<?php echo base_url('assets/frontend/bower_components/bootstrap/dist/css/bootstrap.min.css') ?>" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="<?php echo base_url('assets/frontend/js/html5shiv.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/frontend/js/respond.min.js') ?>"></script>
    <![endif]-->

    <link href="<?php echo base_url('assets/frontend/css/auth/login.css') ?>" rel="stylesheet">

    <script src="<?php echo base_url('assets/frontend/js/modernizr-2.8.3.min.js') ?>"></script>
</head>
<body>
<div class="container" style="margin-top:40px">
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form role="form" id="login" action="<?php echo site_url('ws/auth/login') ?>" method="post">
                        <fieldset>
                            <div class="row">
                                <div class="center-block">
                                    <img class="profile-img"
                                         src="<?php echo base_url('assets/frontend/img/auth/login/logo.png') ?>" alt="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-10  col-md-offset-1 ">
                                    <div class="form-group">
                                        <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="glyphicon glyphicon-briefcase"></i>
                                                    </span>
                                            <select class="form-control" title="titel" name="user[role]">
                                                <option>User</option>
                                                <option>Administrator</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-envelope"></i>
                                                </span>
                                            <input class="form-control" placeholder="Email@host.com" name="user[email]" type="email" autofocus>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-lock"></i>
                                                </span>
                                            <input class="form-control" placeholder="Password" name="user[password]" type="password" value="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-lg btn-primary btn-block" value="Sign in">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <!--<div class="panel-footer ">
                    Don't have an account!
                    <a href="#" onClick=""> Sign Up Here</a>
                </div>-->
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('assets/frontend/bower_components/jquery/dist/jquery.min.js') ?>"></script>
<script src="<?php echo base_url('assets/frontend/bower_components/jquery-serialize-object/dist/jquery.serialize-object.min.js') ?>"></script>
<script src="<?php echo base_url('assets/frontend/js/plugins.js') ?>"></script>
<script src="<?php echo base_url('assets/frontend/js/main.js') ?>"></script>
<script src="<?php echo base_url('assets/frontend/bower_components/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
<script type="text/javascript">
    (function ($)
    {
        $(function ()
        {
            /*
             * Run right away
             * */
            $("#login").on('submit', function (event)
            {
                var form = $(this);
                event.preventDefault();
                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serializeObject(),
                    dataType: 'json',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8; X-Requested-With: XMLHttpRequest'
                })
                    .done(function (data)
                    {
                        console.log(data);
                        alert(JSON.stringify(data));

                    })
                    .fail(function ()
                    {
                        alert("error");
                    })
            });
        });

        /*
         * Run right away
         * */
    })(jQuery);
</script>
</body>
</html>