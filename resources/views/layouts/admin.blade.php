<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title') - Admin Panel</title>
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
  <div class="dashboard-container">
      <!-- Sidebar -->
      <aside class="sidebar">
          <div class="sidebar-header">
              <h2>Admin Panel</h2>
          </div>
          <ul class="menu">
              <li><a href="{{ route('dashboard') }}">🏠 Dashboard</a></li>
              <li><a href="{{ route('kereta.index') }}">🚆 Kereta</a></li>
              <li><a href="{{ route('stasiun.index') }}">🛤️ Stasiun</a></li>
              <li><a href="{{ route('user.index') }}">👤 User</a></li>
          </ul>
      </aside>

      <!-- Content -->
      <main class="content">
          <header class="content-header">
              <h1>@yield('page_title')</h1>
              <div class="user-info">
                  <span>Welcome, {{ Auth::user()->name ?? 'Admin' }}</span>
              </div>
          </header>

          <section>
              @yield('content')
          </section>
      </main>
  </div>
</body>
</html>
