<!DOCTYPE html>
<html class="antialiased font-sans h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('architect.name') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,800,800i,900,900i"
          rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('architect.css', 'vendor/architect') }}">

</head>
<body class="bg-highlight h-full text-black h-full flex justify-center items-center">
<div class="bg-8 max-w-xs mx-auto rounded-lg shadow mx-auto w-full border-primary border-t-4">
    <div class="mx-auto text-center text-highlight">
        Logo
    </div>

    <form class="p-8" method="post" action="./login">
        @csrf

        <h2 class="font-normal mb-6 text-2xl text-center text-2">Please login</h2>

        <div class="mb-2">
            <input class="form-control form-control-input w-full {{ $errors->has('email') ? 'form-control-error' : '' }}"
                   placeholder="Email" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="mb-6">
            <input class="form-control form-control-input w-full {{ $errors->has('password') ? 'form-control-error' : '' }}"
                   placeholder="Password" id="password" type="password" name="password" required>
        </div>

        @if ($errors->any())
            <p class="text-center font-semibold text-negative my-3">
                There was an error logging you in...
            </p>
        @endif

        <jp-ui-button class="w-full" theme="positive">Login</jp-ui-button>
    </form>
</div>
</body>
</html>
