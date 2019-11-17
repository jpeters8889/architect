<!DOCTYPE html>
<html class="antialiased font-sans h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('architect.name') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,800,800i,900,900i" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('architect.css', 'vendor/architect') }}">

</head>
<body class="bg-5 h-full text-black">
<div class="h-full">
    <div class="px-12 py-12 mx-auto">
        <div class="mx-auto max-w-sm py-8 text-center text-highlight">
            Logo
        </div>

        <form class="bg-primary p-8 max-w-md mx-auto rounded-lg shadow" method="post" action="./login">
            @csrf

            <h2 class="font-normal mb-6 text-2xl text-center text-2">Please login</h2>

            <div class="mb-6">
                <label class="block font-bold mb-2" for="email">Email</label>
                <input class="form-control form-control-input w-full {{ $errors->has('email') ? 'form-control-error' : '' }}"
                       id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="mb-6">
                <label class="block font-bold mb-2" for="password">Password</label>
                <input class="form-control form-control-input w-full {{ $errors->has('password') ? 'form-control-error' : '' }}"
                       id="password" type="password" name="password" required>
            </div>

            @if ($errors->any())
                <p class="text-center font-semibold text-negative my-3">
                    There was an error logging you in...
                </p>
            @endif

            <button class="w-full button button-default button-white transition-bg hover:bg-positive hover:text-white" type="submit">
                Login
            </button>
        </form>
    </div>
</div>
</body>
</html>
