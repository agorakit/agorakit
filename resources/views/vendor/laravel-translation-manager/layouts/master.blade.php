<!DOCTYPE html>
<html lang="<?= $currentLocale ?>">
<?php use Vsch\TranslationManager\ManagerServiceProvider; $public_prefix = ManagerServiceProvider::PUBLIC_PREFIX; ?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Vladimir Schneider">
    <link rel="icon" href="@asset('/images/favicon.png')">
    <meta name="description" content="<?=noEditTrans('laravel-translation-manager::messages.translation-manager')?>">
    <meta name="csrf-token" content="<?= csrf_token() ?>"/>
    {{--<!-- Bootstrap core CSS -->--}}
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
    <link href="<?= $public_prefix ?>laravel-translation-manager/css/translations.css" rel="stylesheet">
    @yield('head')
    {{--<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->--}}
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body role="document">
<noscript>This site does not work without JavaScript</noscript>

@if(inPlaceEditing())
    <div class="top-spacer" style="min-height: 50px"></div>
@endif

<div id="main" class="container-fluid main theme-showcase" role="main">
    @yield('content')
</div>
{{--<!--================================================== -->--}}
{{--<!-- Placed at the end of the document so the pages load faster -->--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/3.0.3/handlebars.min.js"></script>--}}
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script src="<?= $public_prefix ?>laravel-translation-manager/js/rails.min.js"></script>
<script src="<?= $public_prefix ?>laravel-translation-manager/js/inflection.js"></script>
<script src="<?= $public_prefix ?>laravel-translation-manager/js/translations.js"></script>
@yield('body-bottom')
</body>
</html>
