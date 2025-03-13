<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, viewport-fit=cover">

    <title>Sprint Pay</title>

    <!-- Favicon and Touch Icons  -->
    <link rel="shortcut icon" href="{{url('')}}/public/assets/images/logo.png" />
    <link rel="apple-touch-icon-precomposed" href="{{url('')}}/public/assets/images/logo.png" />
    <!-- Font -->
    <link rel="stylesheet" href="{{url('')}}/public/assets/fonts/fonts.css" />
    <!-- Icons -->
    <link rel="stylesheet" href="{{url('')}}/public/assets/fonts/icons-alipay.css">
    <link rel="stylesheet" href="{{url('')}}/public/assets/styles/bootstrap.css">
    <link rel="stylesheet"type="text/css" href="{{url('')}}/public/assets/styles/styles.css"/>
    <link rel="apple-touch-icon" sizes="192x192" href="app/icons/icon-192x192.png">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

    <!-- PWA  -->
    <meta name="theme-color" content="#6777ef"/>
    <link rel="apple-touch-icon" href="{{ url('')}}/public/assets/images/logo.png">
    <link rel="manifest" href="{{url('')}}/public/manifest.json">


    <script src="https://cdn.jsdelivr.net/npm/pulltorefreshjs"></script>

    <script>
        PullToRefresh.init({
            mainElement: "body",
            onRefresh: function() {
                location.reload();
            }
        });
    </script>


    <style>
        .custom-dropdown {
            position: relative;
            width: 100%;
        }

        .dropdown-btn {
            width: 100%;
            padding: 10px;
            background: rgb(243,245,255);
            border-radius: 10px;
            border: 1px solid #ccc;
            text-align: left;
            cursor: pointer;
        }

        .dropdown-content {
            position: absolute;
            width: 100%;
            background: white;
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: none;
            z-index: 1000;
        }

        .dropdown-content input {
            width: 100%;
            padding: 8px;
            border: none;
            border-bottom: 1px solid #ccc;
        }

        .dropdown-content ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .dropdown-content li {
            padding: 10px;
            cursor: pointer;
        }

        .dropdown-content li:hover {
            background: #f1f1f1;
        }
    </style>




</head>
<body>





<!-- preloade -->
<div class="preload preload-container">
    <div class="preload-logo">
        <div class="spinner"></div>
    </div>
</div>
<!-- /preload -->



@yield('content')

@include('layouts.sidebar')




<script type="text/javascript" src="{{url('')}}/public/assets/javascript/jquery.min.js"></script>
<script type="text/javascript" src="{{url('')}}/public/assets/javascript/bootstrap.min.js"></script>
<script type="text/javascript" src="{{url('')}}/public/assets/javascript/main.js"></script>
<script type="text/javascript" src="{{url('')}}/public/assets/javascript/init.js"></script>
<script type="text/javascript" src="{{url('')}}/public/assets/javascript/password-addon.js"></script>
<script type="text/javascript" src="{{url('')}}/public/assets/javascript/swiper-bundle.min.js"></script>
<script type="text/javascript" src="{{url('')}}/public/assets/javascript/swiper.js"></script>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script src="{{url('')}}/public/sw.js"></script>
<script>
    if ("serviceWorker" in navigator) {
        // Register a service worker hosted at the root of the
        // site using the default scope.
        navigator.serviceWorker.register("sw.js").then(
            (registration) => {
                console.log("Service worker registration succeeded:", registration);
            },
            (error) => {
                console.error(`Service worker registration failed: ${error}`);
            },
        );
    } else {
        console.error("Service workers are not supported.");
    }
</script>

</body>
</html>
