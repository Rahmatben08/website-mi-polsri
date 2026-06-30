<?php // includes/footer.php ?>
<footer class="footer" role="contentinfo">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4">
                <div class="footer-brand">
                    <div class="footer-logo">
                        <img src="<?= APP_URL ?>/assets/images/mi.png" alt="Logo MI" style="width:32px;height:32px;object-fit:contain;" onerror="this.outerHTML='<i class=\'fas fa-graduation-cap\' style=\'color:var(--clr-accent);font-size:1.5rem;\'></i>'">
                        <span>MI Polsri</span>
                    </div>
                    <p class="footer-desc">Jurusan Manajemen Informatika Politeknik Negeri Sriwijaya menghasilkan lulusan profesional yang kompeten di bidang teknologi informasi dan manajemen bisnis digital.</p>
                    <div class="social-links">
                        <?php if (APP_IG && APP_IG !== '#'): ?>
                        <a href="<?= e(APP_IG) ?>" target="_blank" rel="noopener" class="social-btn" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <?php endif; ?>
                        <?php if (APP_FB && APP_FB !== '#'): ?>
                        <a href="<?= e(APP_FB) ?>" target="_blank" rel="noopener" class="social-btn" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <?php endif; ?>
                        <?php if (APP_YT && APP_YT !== '#'): ?>
                        <a href="<?= e(APP_YT) ?>" target="_blank" rel="noopener" class="social-btn" title="YouTube"><i class="fab fa-youtube"></i></a>
                        <?php endif; ?>
                        <a href="https://manajemeninformatika.polsri.ac.id" target="_blank" rel="noopener" class="social-btn" title="Website Polsri"><i class="fas fa-globe"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <h6 class="footer-heading">Tautan Penting</h6>
                <div class="row">
                    <div class="col-6">
                        <ul class="footer-links">
                            <li><a href="<?= APP_URL ?>/index.php"><i class="fas fa-chevron-right"></i> Beranda</a></li>
                            <li><a href="<?= APP_URL ?>/about.php#sejarah"><i class="fas fa-chevron-right"></i> Sejarah</a></li>
                            <li><a href="<?= APP_URL ?>/about.php#visi-misi"><i class="fas fa-chevron-right"></i> Visi Misi</a></li>
                            <li><a href="<?= APP_URL ?>/dosen.php"><i class="fas fa-chevron-right"></i> Dosen</a></li>
                        </ul>
                    </div>
                    <div class="col-6">
                        <ul class="footer-links">
                            <li><a href="<?= APP_URL ?>/mahasiswa.php"><i class="fas fa-chevron-right"></i> Mahasiswa</a></li>
                            <li><a href="<?= APP_URL ?>/galeri.php"><i class="fas fa-chevron-right"></i> Galeri</a></li>
                            <li><a href="<?= APP_URL ?>/berita.php"><i class="fas fa-chevron-right"></i> Berita</a></li>
                            <li><a href="<?= APP_URL ?>/kontak.php"><i class="fas fa-chevron-right"></i> Kontak</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <h6 class="footer-heading">Hubungi Kami</h6>
                <ul class="footer-contact">
                    <li><i class="fas fa-map-marker-alt"></i><span><?= APP_ADDRESS ?></span></li>
                    <li><i class="fas fa-phone"></i><a href="tel:<?= APP_PHONE ?>"><?= APP_PHONE ?></a></li>
                    <li><i class="fas fa-envelope"></i><a href="mailto:<?= APP_EMAIL ?>"><?= APP_EMAIL ?></a></li>
                    <li><i class="fas fa-globe"></i><a href="https://manajemeninformatika.polsri.ac.id" target="_blank" rel="noopener">manajemeninformatika.polsri.ac.id</a></li>
                    <li><i class="fas fa-clock"></i><span>Senin – Jumat: 08.00 – 16.00 WIB</span></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <p>&copy; <?= date('Y') ?> <?= APP_FULL_NAME ?> — <?= APP_UNIVERSITY ?>. All rights reserved.</p>
                </div>
                <div class="col-md-5 text-md-end mt-2 mt-md-0">
                    <a href="<?= APP_URL ?>/admin/login.php" class="admin-link"><i class="fas fa-lock me-1"></i>Admin Panel</a>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12 text-center">
                    <p style="font-size:0.75rem; color:rgba(255,255,255,0.35); margin:0; letter-spacing:0.03em;">
                        <i class="fas fa-code" style="margin-right:5px; color:rgba(255,255,255,0.25);"></i>
                        Hak Cipta &copy; 2026 <strong style="color:rgba(255,255,255,0.55);">Ghali Rahmat B.B</strong> — Seluruh hak dilindungi.
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>

<button class="back-to-top" id="backToTop" title="Kembali ke atas" aria-label="Kembali ke atas">
    <i class="fas fa-arrow-up"></i>
</button>

<!-- Chatbot Widget -->
<button class="chat-widget-btn" id="chatWidgetBtn" title="Tanya MI-Bot" aria-label="Tanya MI-Bot">
    <i class="fas fa-comments"></i>
</button>

<div class="chat-widget-card" id="chatWidgetCard">
    <div class="chat-widget-header">
        <div style="display:flex;align-items:center;gap:10px;">
            <img src="<?= APP_URL ?>/assets/images/mi.png" alt="Logo MI" style="width:24px;height:24px;object-fit:contain;" onerror="this.outerHTML='<i class=\'fas fa-robot\'></i>'">
            <div>
                <div style="font-weight:700;font-size:0.85rem;line-height:1.2;">MI Assistant</div>
                <div style="font-size:0.65rem;opacity:0.8;">Online • Siap membantu</div>
            </div>
        </div>
        <button id="closeChatBtn" style="background:none;border:none;color:white;cursor:pointer;font-size:1rem;opacity:0.8;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.8">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <div class="chat-widget-body" id="chatWidgetBody">
        <div class="chat-msg chat-msg-bot">
            Halo! Selamat datang di Jurusan Manajemen Informatika POLSRI. Ada yang bisa saya bantu hari ini? Silakan pilih topik di bawah ini:
        </div>
        <div class="chat-options" id="chatOptionsContainer">
            <button class="chat-opt-btn" onclick="sendChatQuery('daftar')">🎓 Info Pendaftaran</button>
            <button class="chat-opt-btn" onclick="sendChatQuery('kontak')">📞 Kontak &amp; Lokasi</button>
            <button class="chat-opt-btn" onclick="sendChatQuery('akreditasi')">📜 Akreditasi &amp; Prodi</button>
            <button class="chat-opt-btn" onclick="sendChatQuery('lulus')">🎓 Syarat Kelulusan</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatBtn = document.getElementById('chatWidgetBtn');
    const chatCard = document.getElementById('chatWidgetCard');
    const closeBtn = document.getElementById('closeChatBtn');
    
    if (chatBtn && chatCard && closeBtn) {
        chatBtn.addEventListener('click', function() {
            chatCard.classList.toggle('open');
            const icon = chatBtn.querySelector('i');
            if (chatCard.classList.contains('open')) {
                icon.className = 'fas fa-chevron-down';
            } else {
                icon.className = 'fas fa-comments';
            }
        });
        
        closeBtn.addEventListener('click', function() {
            chatCard.classList.remove('open');
            chatBtn.querySelector('i').className = 'fas fa-comments';
        });
    }
});

const faqAnswers = {
    daftar: "Pendaftaran Mahasiswa Baru MI Polsri dilakukan secara online melalui jalur SNBP, SNBT, dan Jalur Mandiri melalui portal resmi Polsri (spmb.polsri.ac.id).",
    kontak: "Kami berlokasi di Kampus Polsri, Jalan Srijaya Negara, Bukit Besar, Palembang. Anda dapat menghubungi kami melalui email: mi@polsri.ac.id atau Telp: (0711) 353414.",
    akreditasi: "Manajemen Informatika Polsri memiliki Akreditasi B dan menyelenggarakan dua program studi unggulan: D3 Manajemen Informatika (Vokasi) & D4 Manajemen Informatika (Sarjana Terapan).",
    lulus: "Syarat kelulusan di antaranya adalah menyelesaikan seluruh SKS wajib (minimum 110 SKS untuk D3, 144 SKS untuk D4), lulus Ujian Laporan/Tugas Akhir, menyerahkan bukti Bebas Pustaka Jurusan, serta memiliki skor TOEIC/TOEFL sesuai standar Polsri."
};

function sendChatQuery(key) {
    const body = document.getElementById('chatWidgetBody');
    const options = document.getElementById('chatOptionsContainer');
    
    // Disable other buttons
    const optButtons = options.querySelectorAll('button');
    optButtons.forEach(btn => btn.disabled = true);
    
    // Add user message
    const userMsg = document.createElement('div');
    userMsg.className = 'chat-msg chat-msg-user';
    let label = '';
    if (key === 'daftar') label = '🎓 Info Pendaftaran';
    else if (key === 'kontak') label = '📞 Kontak & Lokasi';
    else if (key === 'akreditasi') label = '📜 Akreditasi & Prodi';
    else if (key === 'lulus') label = '🎓 Syarat Kelulusan';
    userMsg.textContent = label;
    body.appendChild(userMsg);
    
    // Scroll
    body.scrollTop = body.scrollHeight;
    
    // Add typing indicator
    const typing = document.createElement('div');
    typing.className = 'chat-typing';
    typing.innerHTML = '<span class="chat-dot"></span><span class="chat-dot"></span><span class="chat-dot"></span>';
    body.appendChild(typing);
    body.scrollTop = body.scrollHeight;
    
    setTimeout(() => {
        typing.remove();
        
        // Add bot answer
        const botMsg = document.createElement('div');
        botMsg.className = 'chat-msg chat-msg-bot';
        botMsg.textContent = faqAnswers[key];
        body.appendChild(botMsg);
        
        // Re-enable options and scroll
        optButtons.forEach(btn => btn.disabled = false);
        body.appendChild(options); // Move options to bottom
        body.scrollTop = body.scrollHeight;
    }, 1200);
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= APP_URL ?>/assets/js/main.js?v=2.0.1"></script>
</body>
</html>
