<footer class="footer">
    <div class="container">

        <div class="footer-grid">

            <div class="footer-brand-col">
                <div class="footer-logo">
                    <span class="footer-logo-mark">N</span>
                    <span>NookNook</span>
                </div>
                <p class="footer-desc">
                    Platform penyewaan ruangan terpercaya untuk meeting, coworking, dan event.
                </p>

                <div class="footer-socials">
                    <a href="#" aria-label="Instagram" class="social-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="2" width="20" height="20" rx="5"/>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                        </svg>
                    </a>
                    <a href="#" aria-label="Twitter" class="social-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/>
                        </svg>
                    </a>
                    <a href="#" aria-label="WhatsApp" class="social-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="footer-col">
                <h4 class="footer-title">Produk</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li><a href="{{ route('ruangan.daftar') }}">Daftar Ruangan</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4 class="footer-title">Bantuan</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('syarat.ketentuan') }}">Syarat & Ketentuan</a></li>
                </ul>
            </div>

        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} NookNook. All rights reserved.</p>
        </div>

    </div>
</footer>