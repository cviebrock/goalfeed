<?php
$showJumbo = isset($jumbotron) ? true : false;
?>
        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <script>
        window.Laravel =; <?php echo json_encode([
                'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    {{--<style>
        html, body {
            background-color: #CC0000;
            color: #636b6f;
            font-family: 'Raleway';
            color: #fff;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }
        .description {
            color: #fff;
            padding: 0 25px;
            font-size: 1.25em;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            font-family: 'Lato';
            /*text-transform: uppercase;*/
        }
        .description a {
            color: #fff;
            padding: 0 25px;
            font-size: 1.25em;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: underline;
            font-family: 'Lato';

        }


        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }
        .header{
            background: black;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>--}}
    <title>Goalfeed.ca - Trigger Philips Hue lights when your favourite NHL team scores</title>
    <!-- Custom styles for this template -->
    <link href="css/jumbotron.css" rel="stylesheet">
    <link href="css/app.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <![endif]-->
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                    m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-85601117-1', 'auto');
        ga('send', 'pageview');

    </script>
</head>

<body>
@include('partials.header.header')

@if ($showJumbo)
    <div class="jumbotron">
        <div class="container">
            @yield('jumbotron')
        </div>
    </div>
@endif


<div class="container">
    @yield('content')
    <hr>

    <footer>
        <p>&copy; 2016 Goalfeed.ca</p>
    </footer>
</div> <!-- /container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="https://code.jquery.com/jquery-3.1.1.js"><\/script>')</script>
<script src="https://code.jquery.com/jquery-3.1.1.js"></script>

</body>
</html>
