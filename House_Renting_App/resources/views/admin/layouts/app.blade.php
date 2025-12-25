<!doctype html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Admin')</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

  <link rel="stylesheet"
      href="{{ asset('admin/assets/css/admin.css') }}?v={{ filemtime(public_path('admin/assets/css/admin.css')) }}">

  @stack('styles')
</head>
<body class="@yield('body_class')">
  @yield('content')

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('admin/assets/js/admin.js') }}?v={{ filemtime(public_path('admin/assets/js/admin.js')) }}"></script>

  @stack('scripts')
</body>
</html>
