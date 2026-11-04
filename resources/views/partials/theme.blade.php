{{-- ANTI-FLICKER + LOAD THEME CSS --}}
<script>
    (function() {
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
    })();
</script>

<link rel="stylesheet" href="{{ asset('css/theme.css') }}">