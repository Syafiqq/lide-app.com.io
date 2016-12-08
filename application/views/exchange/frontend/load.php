<?php
/**
 * This <lide-app.com.io> project created by :
 * Name         : syafiq
 * Date / Time  : 02 December 2016, 3:19 AM.
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
    <link href="<?php echo base_url('assets/frontend/css/currency/load.css') ?>" rel="stylesheet">
    <script src="<?php echo base_url('assets/frontend/js/modernizr-2.8.3.min.js') ?>"></script>
</head>
<body>
<form id="workon" action="<?php echo site_url('webservice/wexchange/store') ?>" method="post">
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
            <td>Date From</td>
            <td>
                <input type="text" placeholder="MMMM-YY-DD" id="s_workon_from" style="width: 200px" name="d_from" title="date" value="2012-01-02">
            </td>
        </tr>
        <tr>
            <td>Date To</td>
            <td>
                <input type="text" placeholder="MMMM-YY-DD" id="s_workon_to" style="width: 200px" name="d_to" title="date" value="">
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

<div id="log">
</div>

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
            var currency = {};

            $('input#s_workon_to').ready(function ()
            {
                $('input#s_workon_to').val(moment().format('YYYY-MM-DD'));
            });

            function storeExchange(date, maxDate, from, to)
            {
                formattedDate = date.format("YYYY-MM-DD");
                $.ajax({
                    type: 'GET',
                    url: 'http://api.fixer.io/' + formattedDate + '?symbols=' + currency[to].toUpperCase() + '&base=' + currency[from].toUpperCase(),
                    dataType: 'json',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8; X-Requested-With: XMLHttpRequest'
                })
                    .done(function (data)
                    {
                        $("div#log").append('<span>Successfully retrieve from [ ' + currency[from].toUpperCase() + ' ] to [ ' + currency[to].toUpperCase() + ' ] with request time [ ' + formattedDate + ' ] and server time [ ' + data['date'] + ' ]</span><br>');
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo site_url('webservice/wexchange/store') ?>',
                            data: {
                                base: from,
                                to: to,
                                date: formattedDate,
                                value: data['rates'][currency[to].toUpperCase()]
                            },
                            dataType: 'json',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8; X-Requested-With: XMLHttpRequest'
                        })
                            .done(function ()
                            {
                                $("div#log").append('<span>Successfully store&nbsp;&nbsp;&nbsp; from [ ' + currency[from].toUpperCase() + ' ] to [ ' + currency[to].toUpperCase() + ' ] with store&nbsp;&nbsp; time [ ' + formattedDate + ' ]</span><br>');
                                if (!date.isSame(maxDate, 'day'))
                                {
                                    storeExchange(date.add(1, 'days'), maxDate, from, to);
                                }
                            })
                            .fail(function ()
                            {
                                alert("error");
                            })
                    })
                    .fail(function ()
                    {
                        alert("error");
                    })
            }

            $('form#workon').ready(function ()
            {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo site_url('webservice/wcurrency/load') ?>',
                    dataType: 'json',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8; X-Requested-With: XMLHttpRequest'
                })
                    .done(function (data)
                    {
                        for (var c_c = -1, cs_c = data['data']['currency'].length; ++c_c < cs_c;)
                        {
                            var cur = data['data']['currency'][c_c];
                            currency[cur['id']] = cur['code'];
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
                form = form.serializeObject();
                event.preventDefault();
                console.log();
                storeExchange(moment(form['d_from']), moment(form['d_to']), form['from'], form['to']);
            });
        });

        /*
         * Run right away
         * */
    })(jQuery);
</script>
</body>
</html>
