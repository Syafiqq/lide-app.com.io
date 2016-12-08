<?php
/**
 * This <lide-app.com.io> project created by :
 * Name         : syafiq
 * Date / Time  : 02 December 2016, 6:03 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
?>
<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>App Currency -Configuration-</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/normalize.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/main.css') ?>">
    <link href="<?php echo base_url('assets/frontend/bower_components/bootstrap/dist/css/bootstrap.min.css') ?>" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="<?php echo base_url('assets/frontend/js/html5shiv.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/frontend/js/respond.min.js') ?>"></script>
    <![endif]-->
    <link href="<?php echo base_url('assets/frontend/css/configuration/autoload.css') ?>" rel="stylesheet">
    <script src="<?php echo base_url('assets/frontend/js/modernizr-2.8.3.min.js') ?>"></script>
</head>
<body>
<form id="load_profile" action="<?php echo site_url('webservice/welm/loadProfile') ?>" method="post">
    <input id="load_profile_submit" type="submit" value="Load Profile">
</form>
<br>
<form id="do_process" action="<?php echo site_url('webservice/welm/doProcess') ?>" method="post">
    <input id="load_profile_submit" type="submit" value="Process">
</form>
<br>
<form id="save_data" action="<?php echo site_url('webservice/welm/saveData') ?>" method="post">
    <input id="load_profile_submit" type="submit" value="Save">
</form>
<br>
<form id="mark_profile" action="<?php echo site_url('webservice/welm/mark') ?>" method="post">
    <input id="load_profile_submit" type="submit" value="Mark As Active">
</form>
<br>
<form id="predict" action="<?php echo site_url('webservice/welm/predictAll') ?>" method="post">
    <input id="load_profile_submit" type="submit" value="Predict All">
</form>
<br>
<form id="simulate" action="<?php echo site_url('webservice/welm/simulate') ?>" method="post">
    <input id="load_profile_submit" type="submit" value="Simulate Cron Job">
</form>
<br>
<p id="accuracy">0.0</p>
<script src="<?php echo base_url('assets/frontend/bower_components/jquery/dist/jquery.min.js') ?>"></script>
<script src="<?php echo base_url('assets/frontend/bower_components/jquery-serialize-object/dist/jquery.serialize-object.min.js') ?>"></script>
<script src="<?php echo base_url('assets/frontend/js/plugins.js') ?>"></script>
<script src="<?php echo base_url('assets/frontend/js/main.js') ?>"></script>
<script src="<?php echo base_url('assets/frontend/bower_components/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
<script src="<?php echo base_url('assets/frontend/bower_components/moment/min/moment.min.js') ?>"></script>
<script src="<?php echo base_url('assets/frontend/bower_components/moment-timezone/builds/moment-timezone.min.js') ?>"></script>
<script type="text/javascript">
    (function ($)
    {
        $(function ()
        {
            $("form#load_profile").on('submit', function (event)
            {
                var form = $(this);
                event.preventDefault();
                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: {
                        name: 'usd_idr_se',
                        from: '2',
                        to: '1',
                        method: 'mape'
                    },
                    dataType: 'json',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8; X-Requested-With: XMLHttpRequest'
                })
                    .done(function (data)
                    {
                        console.log(data);
                    })
                    .fail(function ()
                    {
                        alert("error");
                    })
            });

            $("form#do_process").on('submit', function (event)
            {
                var form = $(this);
                event.preventDefault();
                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    dataType: 'json',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8; X-Requested-With: XMLHttpRequest'
                })
                    .done(function (data)
                    {
                        console.log(data);
                        $('p#accuracy').text(data['data']['accuracy']);
                    })
                    .fail(function ()
                    {
                        alert("error");
                    })
            });

            $("form#save_data").on('submit', function (event)
            {
                var form = $(this);
                event.preventDefault();
                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    dataType: 'json',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8; X-Requested-With: XMLHttpRequest'
                })
                    .done(function (data)
                    {
                        console.log(data);
                    })
                    .fail(function ()
                    {
                        alert("error");
                    })
            });

            $("form#mark_profile").on('submit', function (event)
            {
                var form = $(this);
                event.preventDefault();
                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    dataType: 'json',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8; X-Requested-With: XMLHttpRequest'
                })
                    .done(function (data)
                    {
                        console.log(data);
                    })
                    .fail(function ()
                    {
                        alert("error");
                    })
            });

            $("form#predict").on('submit', function (event)
            {
                var form = $(this);
                event.preventDefault();
                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    dataType: 'json',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8; X-Requested-With: XMLHttpRequest'
                })
                    .done(function (data)
                    {
                        console.log(data);
                    })
                    .fail(function ()
                    {
                        alert("error");
                    })
            });

            $("form#simulate").on('submit', function (event)
            {
                var form = $(this);
                event.preventDefault();
                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    dataType: 'json',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8; X-Requested-With: XMLHttpRequest'
                })
                    .done(function (data)
                    {
                        console.log(data);
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

