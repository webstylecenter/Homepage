{!! $isAdmin = true !!}<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@section('title')Feednews.me@endsection</title>
    @section('stylesheet')
        <link rel="stylesheet" href="/css/app.css">
    @endsection
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="Description" content="Read the news as it happens from different sources, watch YouTube and manage your todo's all in one place! Join us now on your Desktop, Tablet and phone">
    <meta name="keywords" content="online rss reader, feed, news">
    <meta name="theme-color" content="#f5f5f5">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" crossorigin="anonymous">
    @section('header')
    @endsection
</head>
<body class="{% if bodyClass is defined %}{{ $bodyClass ?? '' }}{% endif %}" data-refresh-date="{{ "now"|date('Y-m-d H:i:s') }}">

@section('body')
@endsection

@include('modals/dialog')
@section('javascripts')
    <script src="/js/vendor.js"></script>
    <script src="/js/app.js"></script>
@endsection
</body>
</html>
