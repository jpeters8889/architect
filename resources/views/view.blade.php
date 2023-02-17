<!DOCTYPE html>
<html class="antialiased font-sans h-full">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @auth
        <meta name="api-token" content="{{ Illuminate\Support\Facades\Auth::user()->api_token }}">
    @endauth

    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes"/>

    <title>{{ config('architect.name') }}</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:200,200i,300,300i,400,400i,600,600i,800,800i,900,900i"
          rel="stylesheet">

    <link rel="stylesheet" href="{{ mix('architect.css', 'vendor/architect') }}">

    @auth
        @foreach($assetManager->styles() as $name => $path)
            <link rel="stylesheet" href="/{{ config('architect.route') }}/api/assets/style/{!! $name !!}">
        @endforeach
    @endauth

<!-- other styles -->
</head>
<body class="font-sans bg-gray-200 min-h-full text-black">
<div id="architect">
    <architect-app></architect-app>
</div>

<script>
    window.config = @json(JPeters\Architect\Architect::coreJavascript());
</script>

<script src="{{ mix('manifest.js', 'vendor/architect') }}"></script>
<script src="{{ mix('vendor.js', 'vendor/architect') }}"></script>
<script src="{{ mix('architect.js', 'vendor/architect') }}"></script>

<script>
    window.Architect = new architectBootstrapper(config);
</script>

@auth
    @foreach($assetManager->scripts() as $name => $path)
        <script src="/{{ config('architect.route') }}/api/assets/script/{!!  $name !!}"></script>
    @endforeach
@endauth
<script>
    Architect.build();
</script>
</body>
</html>
