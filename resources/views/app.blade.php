@php
$config = [
    'appName' => config('app.name'),
    'locale' => $locale = app()->getLocale(),
    'locales' => config('app.locales'),
];

$polyfills = [
    'Promise',
    'Object.assign',
    'Object.values',
    'Array.prototype.find',
    'Array.prototype.findIndex',
    'Array.prototype.includes',
    'String.prototype.includes',
    'String.prototype.startsWith',
    'String.prototype.endsWith',
];
@endphp

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }}</title>
        <link type="text/css" rel="stylesheet" href="https://unpkg.com/bootstrap/dist/css/bootstrap.min.css"/>
        <link type="text/css" rel="stylesheet" href="https://unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.css"/>
    </head>

    <body>
      <div id="app"></div>

      {{-- Global configuration object --}}
      <script>window.config = @json($config);</script>
      {{-- Polyfill JS features via polyfill.io --}}
      <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features={{ implode(',', $polyfills) }}"></script>

      {{-- Load the application scripts --}}
      @if (app()->isLocal())
      <script src="{{ mix('js/app.js') }}"></script>
      @else
      <script src="{{ mix('js/manifest.js') }}"></script>
      <script src="{{ mix('js/vendor.js') }}"></script>
      <script src="{{ mix('js/app.js') }}"></script>
      @endif
      <script src="https://unpkg.com/vue/dist/vue.min.js"></script>
      <script src="https://unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.js"></script>
    </body>
</html>
