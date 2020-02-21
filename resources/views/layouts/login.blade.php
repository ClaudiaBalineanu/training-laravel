<!--  snippet code taken from app.blade.php for logout, doesn't work without form and onclick  -->
<a href="{{ route('logout') }}"
   onclick="event.preventDefault();
       document.getElementById('logout-form').submit();">
    {{ __('Logout') }}
</a>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
