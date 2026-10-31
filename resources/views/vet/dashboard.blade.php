<!DOCTYPE html>
<html>
<head>
  <title>Vet Dashboard</title>
</head>
<body>
  <h1>Welcome Dr. {{ auth()->user()->first_name }}! 🩺</h1>
  <p>You are logged in as Veterinarian.</p>
  <a href="{{ route('logout') }}"
     onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
     Logout
  </a>
  <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">
    @csrf
  </form>
</body>
</html>