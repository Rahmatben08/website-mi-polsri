<?php
require_once __DIR__.'/../includes/config.php';
if (isAdminLoggedIn()) redirect(APP_URL.'/admin/dashboard.php');
$error = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    if (!validateCsrfToken($_POST['csrf_token']??'')) {
        $error='Token tidak valid. Refresh dan coba lagi.';
    } else {
        $username=trim($_POST['username']??'');
        $password=$_POST['password']??'';
        if (empty($username)||empty($password)) {
            $error='Username dan password wajib diisi.';
        } else {
            $db=getDB();
            $stmt=$db->prepare("SELECT * FROM admin WHERE username=? LIMIT 1");
            $stmt->execute([$username]);
            $admin=$stmt->fetch();
            if ($admin && password_verify($password,$admin['password'])) {
                session_regenerate_id(true);
                $_SESSION['admin_id']=$admin['id'];
                $_SESSION['admin_user']=$admin['username'];
                setFlash('success','Selamat datang, '.$admin['username'].'!');
                redirect(APP_URL.'/admin/dashboard.php');
            } else {
                $error='Username atau password salah.';
                sleep(1);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Login | MI Polsri</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css">
</head>
<body style="background:var(--clr-bg-soft);display:flex;align-items:center;justify-content:center;min-height:100vh;">
<div style="width:100%;max-width:420px;padding:20px;">
    <div style="text-align:center;margin-bottom:32px;">
        <div style="width:60px;height:60px;background:linear-gradient(135deg,var(--clr-primary),var(--clr-primary-dark));border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
            <img src="../assets/images/mi.png?v=1" alt="Logo" style="width:36px;height:36px;object-fit:contain;" onerror="this.outerHTML='<i class=\'fas fa-microchip\' style=\'color:white;font-size:1.5rem;\'></i>'">
        </div>
        <h4 style="font-family:var(--font-display);font-size:1.3rem;margin-bottom:4px;">Admin Panel</h4>
        <p style="font-size:0.82rem;color:var(--clr-text-muted);">Jurusan Manajemen Informatika Polsri</p>
    </div>
    <div style="background:var(--clr-bg-card);border:1px solid var(--clr-border);border-radius:var(--radius-xl);padding:36px;box-shadow:var(--clr-shadow-card);">
        <h5 style="margin-bottom:24px;font-size:1.1rem;">Masuk ke Akun Admin</h5>
        <?php if($error): ?>
        <div style="background:#FEF2F2;color:#991B1B;border:1px solid #FECACA;border-radius:var(--radius-md);padding:12px 16px;font-size:0.875rem;margin-bottom:20px;">
            <i class="fas fa-exclamation-circle me-2"></i><?= e($error) ?>
        </div>
        <?php endif; ?>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <div style="margin-bottom:18px;">
                <label class="form-label-custom" for="username">Username</label>
                <div style="position:relative;">
                    <i class="fas fa-user" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--clr-text-light);font-size:0.875rem;"></i>
                    <input type="text" id="username" name="username" class="form-control-custom" style="padding-left:40px;" placeholder="Masukkan username" value="<?= isset($_POST['username'])?e($_POST['username']):'' ?>" required autofocus>
                </div>
            </div>
            <div style="margin-bottom:24px;">
                <label class="form-label-custom" for="password">Password</label>
                <div style="position:relative;">
                    <i class="fas fa-lock" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--clr-text-light);font-size:0.875rem;"></i>
                    <input type="password" id="password" name="password" class="form-control-custom" style="padding-left:40px;padding-right:44px;" placeholder="Masukkan password" required>
                    <button type="button" id="togglePw" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--clr-text-light);">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn-submit"><i class="fas fa-sign-in-alt"></i> Masuk</button>
        </form>
        <div style="text-align:center;margin-top:20px;">
            <a href="<?= APP_URL ?>/index.php" style="font-size:0.82rem;color:var(--clr-text-muted);">
                <i class="fas fa-arrow-left me-1"></i>Kembali ke Website
            </a>
        </div>
    </div>
    <div style="text-align:center;margin-top:16px;font-size:0.75rem;color:var(--clr-text-light);">
        Default: <code>admin</code> / <code>admin123</code>
    </div>
</div>
<script>
document.getElementById('togglePw').addEventListener('click',function(){
    const pw=document.getElementById('password');
    const ic=document.getElementById('eyeIcon');
    pw.type=pw.type==='password'?'text':'password';
    ic.className=pw.type==='password'?'fas fa-eye':'fas fa-eye-slash';
});
</script>
</body></html>
