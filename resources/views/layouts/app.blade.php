<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, viewport-fit=cover">

    <title>index</title>

    <!-- Favicon and Touch Icons  -->
    <link rel="shortcut icon" href="{{url('')}}/public/assets/images/logo.png" />
    <link rel="apple-touch-icon-precomposed" href="{{url('')}}/public/assets/images/logo.png" />
    <!-- Font -->
    <link rel="stylesheet" href="{{url('')}}/public/assets/fonts/fonts.css" />
    <!-- Icons -->
    <link rel="stylesheet" href="{{url('')}}/public/assets/fonts/icons-alipay.css">
    <link rel="stylesheet" href="{{url('')}}/public/assets/styles/bootstrap.css">
    <link rel="stylesheet"type="text/css" href="{{url('')}}/public/assets/styles/styles.css"/>
    <link rel="manifest" href="_manifest.json" data-pwa-version="set_in_manifest_and_pwa_js">
    <link rel="apple-touch-icon" sizes="192x192" href="app/icons/icon-192x192.png">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />


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



</body>
</html>
