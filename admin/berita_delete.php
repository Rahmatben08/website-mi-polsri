<?php
// admin/berita_delete.php
require_once __DIR__.'/../includes/config.php';
requireAdminAuth();
$db = getDB();

$id    = (int)($_GET['id']    ?? 0);
$token = $_GET['token'] ?? '';

if (!$id || !validateCsrfToken($token)) {
    setFlash('error', 'Aksi tidak valid.');
    redirect(APP_URL . '/admin/berita.php');
}

$sTitle = $db->prepare("SELECT judul FROM berita WHERE id=?");
$sTitle->execute([$id]);
$title = $sTitle->fetchColumn();

$stmt = $db->prepare("DELETE FROM berita WHERE id = ?");
if ($stmt->execute([$id])) {
    if ($title) {
        logAktivitas('HAPUS', "Menghapus berita: " . $title);
    }
    setFlash('success', 'Berita berhasil dihapus.');
} else {
    setFlash('error', 'Gagal menghapus berita.');
}
redirect(APP_URL . '/admin/berita.php');
