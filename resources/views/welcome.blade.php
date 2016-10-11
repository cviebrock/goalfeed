<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Goalfeed.ca - Trigger Philips Hue lights when your favourite NHL team scores</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Lato|Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
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
            .logo{
                width: 15%;
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

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    <a href="{{ url('/login') }}">Login</a>
                    <a href="{{ url('/register') }}">Register</a>
                </div>
            @endif

            <div class="content">
                <img src="./light-logo.png" class="logo">
                <div class="title m-b-md">
                    Goalfeed.ca
                </div>

                <div class="description">
                    <p>Goalfeed provides nearly instant notifications every time a goal is scored in the NHL.</p>
                    <p>Currently, we offer an extension for Google Chrome to trigger a goal-light light sequence for your Philips Hue lights.</p>
                    <p><a href="https://chrome.google.com/webstore/detail/chrome-goalfeed-for-phili/fgcbighghabceookojahkojodedbgfoc?hl=en-US&gl=CA&authuser=0">Check us out in the Chrome Store!</a></p>
                </div>
            </div>
        </div>
    </body>
</html>
