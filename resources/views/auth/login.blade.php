<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">

</head>
<body>
  <div class="container">
    <!-- Left Side (Login Form) -->
    <div class="login-section">
      <div class="login-box">
        <img src="https://www.google.com/url?sa=i&url=https%3A%2F%2Fid.pinterest.com%2Fpin%2Fdownload-logo-kai-kereta-api-indonesia-vektor-ai--772789617309640652%2F&psig=AOvVaw1JfkVTAyXZztFDFhoFHzjP&ust=1756473475198000&source=images&cd=vfe&opi=89978449&ved=0CBIQjRxqFwoTCKj1o97LrY8DFQAAAAAdAAAAABAE" alt="Logo" class="logo">
        <h2>Admin Login</h2>
        <p class="subtitle">Sign in to access the dashboard</p>

        {{-- Error Message --}}
        @if ($errors->any())
          <div class="error-message">
            {{ $errors->first() }}
          </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
          @csrf
          <label>Email</label>
          <input type="email" name="email" required>

          <label>Password</label>
          <input type="password" name="password" required>

          <button type="submit">Login</button>
        </form>

        <p class="footer">© 2025 Kereta Api Admin Portal</p>
      </div>
    </div>

    <!-- Right Side (Image / Banner) -->
    <div class="image-section">
      <div class="overlay"></div>
      <h1 class="tagline">Welcome to Admin Portal</h1>
    </div>
  </div>
</body>
</html>
