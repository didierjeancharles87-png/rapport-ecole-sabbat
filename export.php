<?php
declare(strict_types=1);
require __DIR__ . '/backend/helpers/core.php';
if (!is_installed()) { redirect(BASE_URL . 'database/install.php'); }
boot_app();
require_once APP_ROOT . '/backend/services/ExportBuilder.php';
require_once APP_ROOT . '/backend/services/Pdf.php';
require_once APP_ROOT . '/backend/services/Xlsx.php';
require_once APP_ROOT . '/backend/services/Pptx.php';

$type = (string)($_GET['type'] ?? ''); $format = (string)($_GET['format'] ?? '');
if (!in_array($type, ['ecole', 'mission', 'stats'], true) || !in_array($format, ['pdf', 'xlsx', 'pptx'], true)) { http_response_code(400); exit('Paramètres invalides.'); }
require_perm('export.' . $type);
$per = period_from($_GET, 'mois'); $classe = classe_filter();
try {
    $doc = match ($type) { 'ecole' => ExportBuilder::ecole($per, $classe), 'mission' => ExportBuilder::mission($per, $classe), default => ExportBuilder::stats($per) };
    $dir = APP_ROOT . '/storage/exports'; if (!is_dir($dir)) @mkdir($dir, 0775, true);
    foreach (glob($dir . '/*') ?: [] as $old) { if (is_file($old) && filemtime($old) < time() - 7 * 86400) @unlink($old); }
    $names = ['ecole' => 'Ecole_du_Sabbat', 'mission' => 'Mission', 'stats' => 'Statistiques'];
    $slug = preg_replace('/[^A-Za-z0-9]+/', '-', iconv('UTF-8', 'ASCII//TRANSLIT', $per['mode'] === 'plage' ? $per['from'] . '_' . $per['to'] : $per['label']) ?: 'periode');
    $file = $dir . '/' . $names[$type] . '_' . trim((string)$slug, '-') . '_' . date('His') . '.' . $format;
    match ($format) {
        'pdf' => file_put_contents($file, PdfRenderer::render($doc)),
        'xlsx' => XlsxRenderer::render($doc, $file),
        'pptx' => PptxRenderer::render($doc, $file),
    };
    log_action('export.' . $type, strtoupper($format) . ' - ' . $per['label']);
    $mime = ['pdf' => 'application/pdf', 'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation'][$format];
    while (ob_get_level() > 0) ob_end_clean();
    header('Content-Type: ' . $mime);
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    header('Content-Length: ' . filesize($file));
    header('X-Content-Type-Options: nosniff');
    readfile($file);
} catch (Throwable $e) {
    flash('danger', "L'exportation a échoué : " . $e->getMessage());
    redirect(url('exports'));
}
