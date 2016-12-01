<?php
/**
 * This <lide-app.com.io> project created by :
 * Name         : syafiq
 * Date / Time  : 25 November 2016, 9:45 AM.
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

    <script src="<?php echo base_url('assets/frontend/js/modernizr-2.8.3.min.js') ?>"></script>
</head>
<body>
<form id="destroyss" action="<?php echo site_url('AppForecast/AppCurrency/wsdestroysess') ?>" method="post">
    <table>
        <tbody>
        <tr>
            <td colspan="2">Destroy Session</td>
        </tr>
        <tr>
            <td colspan="2" align="right">
                <input id="submit" type="submit" value="Submit">
            </td>
        </tr>
        </tbody>
    </table>
</form>
<br>
<br>
<br>
<br>
<form id="workon" action="<?php echo site_url('AppForecast/AppCurrency/wssetworkon') ?>" method="post">
    <table>
        <tbody>
        <tr>
            <td colspan="2"> WorkOn :</td>
        </tr>
        <tr>
            <td>From</td>
            <td>
                <select id="s_workon_from" style="width: 200px" name="from" title="from">
                </select>
            </td>
        </tr>
        <tr>
            <td>To</td>
            <td>
                <select id="s_workon_to" style="width: 200px" name="to" title="to">
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="right">
                <input id="submit" type="submit" value="Submit">
            </td>
        </tr>
        </tbody>
    </table>
</form>
<br>
<br>
<br>
<form id="loaddata" action="<?php echo site_url('AppForecast/AppCurrency/wsloaddata') ?>" method="post">
    <table>
        <tbody>
        <tr>
            <td colspan="2"> Generate Data :</td>
        </tr>
        <tr>
            <td>Maximum Data</td>
            <td>
                <span id="s_loaddata_maximum"></span>
            </td>
        </tr>
        <tr>
            <td>Put Data</td>
            <td>
                <input name="total" required title="Put Data">
            </td>
        </tr>
        <tr>
            <td>Feature</td>
            <td>
                <input name="feature" required title="Feature">
            </td>
        </tr>
        <tr>
            <td colspan="2" align="right">
                <input id="submit" type="submit" value="Submit">
            </td>
        </tr>
        </tbody>
    </table>
</form>

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
            /*/!*
             * Run right away
             * *!/
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
             });*/
            $('form#destroyss').on('submit', function (event)
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
                        alert('error');
                    })
            });

            $('form#workon').ready(function ()
            {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo site_url('AppForecast/AppCurrency/wsgetsupportedcurrency') ?>',
                    dataType: 'json',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8; X-Requested-With: XMLHttpRequest'
                })
                    .done(function (data)
                    {
                        for (var c_c = -1, cs_c = data['data']['currency'].length; ++c_c < cs_c;)
                        {
                            var cur = data['data']['currency'][c_c];
                            $('select#s_workon_from').append($('<option>', {
                                value: cur['id'],
                                text: cur['code'] + ' (' + cur['name'] + ') '
                            }));
                            $('select#s_workon_to').append($('<option>', {
                                value: cur['id'],
                                text: cur['code'] + ' (' + cur['name'] + ') '
                            }));
                        }
                        console.log(data);
                    })
                    .fail(function ()
                    {
                        alert("error");
                    })
            });

            $('form#workon').on('submit', function (event)
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
                        $('span#s_loaddata_maximum').text(data['data']['total']);
                        console.log(data);
                    })
                    .fail(function ()
                    {
                        alert("error");
                    })
            });

            $('form#loaddata').on('submit', function (event)
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
