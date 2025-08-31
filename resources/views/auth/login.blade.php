<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <style>
    .password-container {
      position: relative;
      width: 100%;
    }

    .password-container input {
      width: 100%;
      padding-right: 40px; /* kasih space buat icon */
    }

    .toggle-password {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      font-size: 18px;
      color: #555;
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Left Side (Login Form) -->
    <div class="login-section">
      <div class="login-box">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/39/Logo_PT_Kereta_Api_Indonesia_%28Persero%29.svg/2560px-Logo_PT_Kereta_Api_Indonesia_%28Persero%29.svg.png" alt="Logo" class="logo">
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
          <div class="password-container">
            <input type="password" id="password" name="password" required>
            <span class="toggle-password" onclick="togglePassword()">👁️</span>
          </div>

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

  <script>
    function togglePassword() {
      const passwordInput = document.getElementById("password");
      const toggleIcon = document.querySelector(".toggle-password");
      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleIcon.textContent = "🙈"; // icon berubah
      } else {
        passwordInput.type = "password";
        toggleIcon.textContent = "👁️";
      }
    }
  </script>
</body>
</html>
