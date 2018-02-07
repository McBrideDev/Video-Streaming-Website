<!DOCTYPE html>
<html>
    <head>
        <title>Request Not Found</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
                background-color: #191a1c
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 72px;
                margin-bottom: 20px;
            }
            .contact_text{
                font-size: 25px

            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">Request Not Found</div>
                <div class="contact_text">Contact US</div>
               <div class="contact_text">{{$config->site_email}}</div>
               <div class="contact_text">Tel</div>
               <div class="contact_text">{{$config->site_phone}}</div>
            </div>
        </div>
    </body>
</html>
