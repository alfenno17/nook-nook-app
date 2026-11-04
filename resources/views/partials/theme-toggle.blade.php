<button id="themeToggle" class="theme-toggle" title="Ganti tema">
    <svg id="iconSun" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" style="display:none;">
        <circle cx="12" cy="12" r="5"/>
        <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
    </svg>
    <svg id="iconMoon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" style="display:none;">
        <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
    </svg>
    <span id="themeLabel">Gelap</span>
</button>

<script>
    (function() {
        const toggle = document.getElementById('themeToggle');
        const iconSun = document.getElementById('iconSun');
        const iconMoon = document.getElementById('iconMoon');
        const themeLabel = document.getElementById('themeLabel');

        function applyTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
            updateIcon(theme);
        }

        function updateIcon(theme) {
            if (theme === 'dark') {
                iconSun.style.display = 'block';
                iconMoon.style.display = 'none';
                if (themeLabel) themeLabel.textContent = 'Terang';
            } else {
                iconSun.style.display = 'none';
                iconMoon.style.display = 'block';
                if (themeLabel) themeLabel.textContent = 'Gelap';
            }
        }

        const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
        updateIcon(currentTheme);

        if (toggle) {
            toggle.addEventListener('click', function() {
                const now = document.documentElement.getAttribute('data-theme');
                const next = now === 'dark' ? 'light' : 'dark';
                applyTheme(next);
            });
        }
    })();
</script>