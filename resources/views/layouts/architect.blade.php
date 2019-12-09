<!DOCTYPE html>
<html class="antialiased font-sans h-full">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-token" content="{{ Illuminate\Support\Facades\Auth::user()->api_token }}">

    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes" />

    <title>{{ config('architect.name') }}</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:200,200i,300,300i,400,400i,600,600i,800,800i,900,900i"
          rel="stylesheet">

    <link rel="stylesheet" href="{{ mix('architect.css', 'vendor/architect') }}">

    @foreach($assetManager->styles() as $name => $path)
        <link rel="stylesheet" href="/{{ config('architect.route') }}/api/assets/style/{!! $name !!}">
    @endforeach

    <!-- other styles -->
</head>
<body class="bg-5 min-h-full text-black">
<div id="architect">
    <div v-cloak class="flex min-h-screen">
        <!-- navigation -->
        <div class="navigation absolute z-30 hidden bg-highlight flex-none min-h-screen pt-15 w-56 max-w-225 sm:block sm:relative">
            <a href="/{{ config('architect.route') }}">
                <div class="absolute bg-blue-500 flex h-15 items-center left-0 pxx-6 right-0 text-center top-0">
                    Logo
                </div>
            </a>

            {!! $blueprintManager->renderForNavigation() !!}
        </div>

        <!-- main content -->
        <div class="architect-container">
            <div class="bg-primary flex h-15 items-center px-2 relative shadow z-20">
                <div class="flex-1">
                    <a class="font-bold mr-6 no-underline text-grey-800" href="{{ config('architect.main_site') }}">

                    </a>
                </div>
                <div class="hamburger md:hidden text-4xl cursor-pointer float-right">
                    <font-awesome-icon :icon="['fas', 'bars']"></font-awesome-icon>
                </div>
            </div>

            <div class="p-2 mx-auto">
                @yield('content')
            </div>
        </div>
    </div>

    <portal-target name="modal"></portal-target>
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

@foreach($assetManager->scripts() as $name => $path)
    <script src="/{{ config('architect.route') }}/api/assets/script/{!!  $name !!}"></script>
@endforeach

<script>
    Architect.build();
</script>
</body>
</html>