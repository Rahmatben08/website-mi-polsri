<script>
(function() {
    const savedTheme = localStorage.getItem('admin-theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
})();
</script>
<aside class="admin-sidebar" id="adminSidebar">
    <div class="admin-sidebar-brand">
        <div class="logo-icon">
            <img src="../assets/images/mi.png?v=1" alt="Logo" style="width:26px;height:26px;object-fit:contain;" onerror="this.outerHTML='<i class=\'fas fa-microchip\' style=\'color:white;font-size:1.1rem;\'></i>'">
        </div>
        <h5>MI Polsri Admin</h5>
        <?php if(isset($_SESSION['admin_user'])): ?>
        <small>Halo, <?= e($_SESSION['admin_user']) ?> 👋</small>
        <?php endif; ?>
    </div>
    <nav style="flex:1;">
        <a href="<?= APP_URL ?>/admin/dashboard.php"    class="admin-nav-link <?= isActive('dashboard.php') ?>"><i class="fas fa-th-large"></i> Dashboard</a>
        <a href="<?= APP_URL ?>/admin/berita.php"        class="admin-nav-link <?= isActive('berita.php') ?>"><i class="fas fa-newspaper"></i> Kelola Berita</a>
        <a href="<?= APP_URL ?>/admin/pengumuman.php"    class="admin-nav-link <?= isActive('pengumuman.php') ?>"><i class="fas fa-bullhorn"></i> Kelola Pengumuman</a>
        <a href="<?= APP_URL ?>/admin/dokumen.php"       class="admin-nav-link <?= isActive('dokumen.php') ?>"><i class="fas fa-file-alt"></i> Kelola Dokumen</a>
        <a href="<?= APP_URL ?>/admin/dosen.php"         class="admin-nav-link <?= isActive('dosen.php') ?>"><i class="fas fa-chalkboard-teacher"></i> Kelola Dosen</a>
        <a href="<?= APP_URL ?>/admin/galeri.php"        class="admin-nav-link <?= isActive('galeri.php') ?>"><i class="fas fa-images"></i> Kelola Galeri</a>
        <a href="<?= APP_URL ?>/admin/prestasi.php"      class="admin-nav-link <?= isActive('prestasi.php') ?>"><i class="fas fa-trophy"></i> Kelola Prestasi</a>
        <a href="<?= APP_URL ?>/admin/pesan.php"         class="admin-nav-link <?= isActive('pesan.php') ?>"><i class="fas fa-envelope"></i> Pesan Kontak</a>
        <a href="<?= APP_URL ?>/admin/pengaturan.php"    class="admin-nav-link <?= isActive('pengaturan.php') ?>"><i class="fas fa-cog"></i> Pengaturan</a>
        <div style="height:1px;background:#1E293B;margin:16px 24px;"></div>
        <a href="<?= APP_URL ?>/index.php" target="_blank" class="admin-nav-link"><i class="fas fa-external-link-alt"></i> Lihat Website</a>
        <a href="<?= APP_URL ?>/admin/logout.php" class="admin-nav-link" style="color:#F87171;"><i class="fas fa-sign-out-alt"></i> Keluar</a>
    </nav>
    <div style="padding:16px 24px; border-top:1px solid #1E293B; margin-top:auto;">
        <p style="font-size:0.65rem; color:#334155; text-align:center; margin:0; line-height:1.6; letter-spacing:0.02em;">
            <i class="fas fa-code" style="margin-right:4px; color:#475569;"></i>
            Hak Cipta &copy; 2026<br>
            <strong style="color:#475569;">Ghali Rahmat B.B</strong>
        </p>
    </div>
</aside>

<!-- Container Toast Notifications -->
<div class="toast-container-custom" id="toastContainerCustom"></div>

<script>
// Helper showToast untuk notifikasi melayang
function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainerCustom');
    if (!container) return;
    
    const toast = document.createElement('div');
    // Map 'danger' to 'danger' or fallback
    const toastType = type === 'error' ? 'danger' : type;
    toast.className = `toast-custom toast-custom-${toastType}`;
    
    const iconClass = toastType === 'success' ? 'fas fa-check' : 'fas fa-exclamation';
    const titleText = toastType === 'success' ? 'Sukses' : 'Pemberitahuan';
    
    toast.innerHTML = `
        <div class="toast-custom-icon"><i class="${iconClass}"></i></div>
        <div class="toast-custom-content">
            <div class="toast-custom-title">${titleText}</div>
            <div class="toast-custom-message">${message}</div>
        </div>
        <button class="toast-custom-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
    `;
    
    container.appendChild(toast);
    
    // Force reflow and add 'show' class for animation
    setTimeout(() => {
        toast.classList.add('show');
    }, 10);
    
    // Auto remove after 4.5 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            toast.remove();
        }, 400);
    }, 4500);
}

// Sidebar toggle handler untuk layar responsif
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('adminSidebar');
    if (toggle && sidebar) {
        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.toggle('open');
        });
        document.addEventListener('click', function(e) {
            if (!sidebar.contains(e.target) && e.target !== toggle && !toggle.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    }

    // Theme Toggle Handler
    const themeToggleBtn = document.getElementById('themeToggleBtn');
    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', function(e) {
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            const rect = themeToggleBtn.getBoundingClientRect();
            const x = e.clientX || (rect.left + rect.width / 2);
            const y = e.clientY || (rect.top + rect.height / 2);
            
            const overlay = document.createElement('div');
            overlay.className = 'theme-transition-overlay';
            overlay.style.setProperty('--click-x', x + 'px');
            overlay.style.setProperty('--click-y', y + 'px');
            overlay.style.background = newTheme === 'dark' ? '#090D16' : '#FFFFFF';
            document.body.appendChild(overlay);
            
            const icon = themeToggleBtn.querySelector('i');
            if (icon) {
                icon.style.transform = 'rotate(360deg) scale(1.3)';
                icon.style.transition = 'transform 0.6s cubic-bezier(0.34, 1.56, 0.64, 1)';
            }
            
            requestAnimationFrame(() => {
                overlay.classList.add('active');
            });
            
            setTimeout(() => {
                document.documentElement.setAttribute('data-theme', newTheme);
                localStorage.setItem('admin-theme', newTheme);
                updateThemeToggleIcon(newTheme);
            }, 400);
            
            setTimeout(() => {
                overlay.remove();
                if (icon) {
                    icon.style.transform = '';
                    icon.style.transition = '';
                }
            }, 850);
        });
    }

    function updateThemeToggleIcon(theme) {
        const btn = document.getElementById('themeToggleBtn');
        const icon = btn ? btn.querySelector('i') : null;
        if (icon) {
            if (theme === 'dark') {
                icon.className = 'fas fa-sun';
                btn.style.color = '#F59E0B'; // Amber sun color
            } else {
                icon.className = 'fas fa-moon';
                btn.style.color = ''; // Default moon color
            }
        }
    }

    // Initial icon setup
    updateThemeToggleIcon(document.documentElement.getAttribute('data-theme') || 'light');
});

// Real-time Clock and Calendar Widget
function updateClock() {
    const clockEl = document.getElementById('adminDigitalClock');
    const dateEl = document.getElementById('adminCalendarDate');
    if (!clockEl && !dateEl) return;

    const now = new Date();
    
    if (clockEl) {
        let hrs = now.getHours().toString().padStart(2, '0');
        let mins = now.getMinutes().toString().padStart(2, '0');
        let secs = now.getSeconds().toString().padStart(2, '0');
        clockEl.textContent = `${hrs}:${mins}:${secs}`;
    }
    
    if (dateEl && (!dateEl.dataset.initialized || dateEl.textContent === '00-00-0000')) {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        dateEl.textContent = now.toLocaleDateString('id-ID', options);
        dateEl.dataset.initialized = "true";
    }
}
setInterval(updateClock, 1000);
document.addEventListener('DOMContentLoaded', updateClock);
// Pure JS Table to CSV exporter utility
function exportTableToCSV(tableId, filename) {
    const table = document.getElementById(tableId);
    if (!table) return;

    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        if (row.cells.length === 1 && row.cells[0].getAttribute('colspan')) {
            continue;
        }

        let cols = row.querySelectorAll('td, th');
        let rowData = [];
        
        for (let j = 0; j < cols.length; j++) {
            const col = cols[j];
            
            // Skip the Action / Aksi columns
            const cleanText = col.textContent.trim().toLowerCase();
            if (cleanText === 'aksi' || col.querySelector('.btn-group') || col.querySelector('a[href*="edit"]') || col.querySelector('a[href*="hapus"]') || col.querySelector('button[onclick*="hapus"]')) {
                continue;
            }
            
            let text = col.textContent.trim();
            text = text.replace(/"/g, '""');
            rowData.push('"' + text + '"');
        }
        
        if (rowData.length > 0) {
            csv.push(rowData.join(','));
        }
    }
    
    if (csv.length === 0) return;
    
    // Add BOM for Excel UTF-8 compatibility
    const csvContent = 'data:text/csv;charset=utf-8,\uFEFF' + csv.join('\n');
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', filename);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Pure JS Cards to CSV exporter utility for Pesan Kontak
function exportMessagesToCSV() {
    const cards = document.querySelectorAll('.admin-main .col-md-6');
    let csv = [];
    csv.push('"ID","Pengirim","Email","Tanggal","Pesan"');
    
    cards.forEach(card => {
        const idText = card.querySelector('div[style*="font-size:0.7rem"]') || card.querySelector('div[style*="color:#CBD5E1"]');
        const nameText = card.querySelector('div[style*="font-weight:700"]') || card.querySelector('div[style*="font-weight: 700"]');
        const emailText = card.querySelector('a[href^="mailto:"]');
        const dateText = card.querySelector('div[style*="font-size:0.72rem"]') || card.querySelector('div[style*="color:#94A3B8"]');
        const msgText = card.querySelector('p[style*="font-size:0.875rem"]') || card.querySelector('p[style*="color:#475569"]');
        
        if (nameText && emailText && msgText) {
            const id = idText ? idText.textContent.trim().replace('#', '') : '';
            const pengirim = nameText.textContent.trim().replace(/"/g, '""');
            const email = emailText.textContent.trim().replace(/"/g, '""');
            const tanggal = dateText ? dateText.textContent.trim().replace(/"/g, '""') : '';
            const pesan = msgText.textContent.trim().replace(/"/g, '""');
            
            csv.push(`"${id}","${pengirim}","${email}","${tanggal}","${pesan}"`);
        }
    });
    
    if (csv.length <= 1) return;
    
    // Add BOM for Excel UTF-8 compatibility
    const csvContent = 'data:text/csv;charset=utf-8,\uFEFF' + csv.join('\n');
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', 'pesan-kontak.csv');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}


// Deteksi flash message PHP dan tampilkan sebagai toast
<?php $sidebarFlash = getFlash(); if ($sidebarFlash): ?>
document.addEventListener('DOMContentLoaded', function() {
    showToast(<?= json_encode($sidebarFlash['message']) ?>, <?= json_encode($sidebarFlash['type']) ?>);
});
<?php endif; ?>
</script>
