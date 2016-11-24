<?php
/**
 * This <lide-app.com.io> project created by :
 * Name         : syafiq
 * Date / Time  : 11 November 2016, 8:14 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
?>
<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/normalize.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/main.css') ?>">
    <script src="<?php echo base_url('assets/frontend/js/modernizr-2.8.3.min.js') ?>"></script>
</head>
<body>
<h1>HelloWorld</h1>
<form id="login" action="<?php echo site_url('ws/auth/login') ?>" method="post">
    <input type="email" name="email" value="Syafiq.rezpector@gmail.com" required title="Email">
    <input type="password" name="password" value="Muhammad_Syafiq" required title="Password">
    <input id="submit" type="submit" value="Submit">
</form>
<script src="<?php echo base_url('assets/frontend/bower_components/jquery/dist/jquery.min.js') ?>"></script>
<script src="<?php echo base_url('assets/frontend/bower_components/jquery-serialize-object/dist/jquery.serialize-object.min.js') ?>"></script>
<script src="<?php echo base_url('assets/frontend/js/plugins.js') ?>"></script>
<script src="<?php echo base_url('assets/frontend/js/main.js') ?>"></script>
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
                console.log(form.serializeObject());
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