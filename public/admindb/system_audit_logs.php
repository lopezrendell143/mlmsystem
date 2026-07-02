<?php
session_start();

// Guard: Force administrative security access validation
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit;
}

// 1. DATABASE CONNECTIVITY MAPPER
require_once __DIR__ . '/../../config/database.php';

// Handle local display visibility toggle states
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'clear_local') {
        $_SESSION['clear_local_logs'] = true;
    } elseif ($_POST['action'] === 'restore_local') {
        unset($_SESSION['clear_local_logs']);
    }
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}

$auditLogs = [];
// Only fetch and display rows if the administrator hasn't cleared the active screen view
if (!isset($_SESSION['clear_local_logs'])) {
    try {
        // Select entries from the audit logs table, arranging chronologically down from the newest events
        $sql = "SELECT operator_role, operator_name, action_description, target_node, ip_address, created_at 
                FROM audit_logs 
                ORDER BY created_at DESC, id DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $auditLogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Soft fallback if database tables aren't completely initialized yet
        $auditLogs = [];
    }
}

// Active page state for highlighting "System Audit Logs" in your admin sidebar
$activePage = 'system_audit_logs';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>System Audit Logs - Syntrix Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    body { background-color: #030b1e !important; color: #ffffff !important; font-family: sans-serif; }
    .card-dark { background-color: #081229 !important; border: 1px solid #1e293b !important; border-radius: 12px; }
    
    .form-control-dark {
      background-color: #0f172a !important;
      border: 1px solid #334155 !important;
      color: #ffffff !important;
    }
    
    /* CRITICAL HIGH-CONTRAST OVERRIDES: Forced background & color inheritance alignment */
    .table-custom { color: #ffffff !important; border-color: #1e293b !important; vertical-align: middle; }
    
    .table-custom thead th { 
      background-color: #0f172a !important; 
      color: #94a3b8 !important; 
      font-weight: 600;
      border-bottom: 2px solid #1e293b !important;
    }
    
    .table-custom tbody tr td { 
      background-color: #081229 !important; 
      color: #ffffff !important;
      border-bottom: 1px solid #1e293b !important;
      transition: background 0.15s; 
    }
    
    .table-custom tbody tr:hover td { 
      background-color: rgba(30, 41, 59, 0.75) !important; 
    }

    /* Force enhanced visibility settings across descriptive text and monospaced indicators */
    .text-high-contrast-muted {
      color: #94a3b8 !important;
    }
    .text-high-contrast-light {
      color: #f8fafc !important;
    }

    /* Modal Custom Styling matching Syntrix theme scheme */
    .modal-dark-content {
      background-color: #081229 !important;
      border: 1px solid #1e293b !important;
      color: #ffffff !important;
      border-radius: 14px;
    }
    .modal-dark-header {
      border-bottom: 1px solid #1e293b !important;
    }
    .modal-dark-footer {
      border-top: 1px solid #1e293b !important;
    }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    
    <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      
      <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-secondary">
        <div>
          <h1 class="h3 fw-bold text-white mb-0">System Security Audit Logs</h1>
          <p class="text-high-contrast-muted small mb-0">Review system actions, trace administrative state corrections, and monitor security events.</p>
        </div>
        <div>
          <?php if (isset($_SESSION['clear_local_logs'])): ?>
            <form action="" method="POST" class="d-inline">
              <input type="hidden" name="action" value="restore_local">
              <button type="submit" class="btn btn-sm btn-outline-info fw-bold px-3">
                <i class="bi bi-arrow-clockwise me-1"></i> Restore Live View
              </button>
            </form>
          <?php else: ?>
            <button class="btn btn-sm btn-outline-danger fw-bold px-3" data-bs-toggle="modal" data-bs-target="#confirmClearModal">
              <i class="bi bi-trash3-fill me-1"></i> Clear Local Views
            </button>
          <?php endif; ?>
        </div>
      </div>

      <div class="card-dark p-4 shadow-lg">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold mb-0 text-white-50"><i class="bi bi-shield-check me-2 text-danger"></i>Immutable Access History</h5>
          <span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1 small">
            <?php echo isset($_SESSION['clear_local_logs']) ? 'Local View Hidden' : 'Live Stream Active'; ?>
          </span>
        </div>

        <div class="table-responsive">
          <table class="table table-custom table-hover mb-0" style="font-size: 0.9rem;">
            <thead>
              <tr>
                <th scope="col" class="py-3 px-3">Timestamp</th>
                <th scope="col" class="py-3">Operator Entity</th>
                <th scope="col" class="py-3">Action Context Description</th>
                <th scope="col" class="py-3">Network Node Target</th>
                <th scope="col" class="py-3 text-end px-3">IP Address</th>
              </tr>
            </thead>
            <tbody>
              <?php if (isset($_SESSION['clear_local_logs'])): ?>
                <tr>
                  <td colspan="5" class="text-center py-5 text-high-contrast-muted fs-6">
                    <i class="bi bi-eye-slash-fill d-block fs-3 mb-2 text-danger"></i>
                    Local cache display has been cleared. Database files remain persistent.
                  </td>
                </tr>
              <?php elseif (count($auditLogs) > 0): ?>
                <?php foreach ($auditLogs as $log): ?>
                  <tr>
                    <td class="px-3 text-high-contrast-muted font-monospace" style="font-size: 0.8rem;">
                      <?php echo htmlspecialchars($log['created_at']); ?>
                    </td>
                    <td>
                      <?php 
                        $role = htmlspecialchars($log['operator_role'] ?? 'Admin');
                        $badgeClass = 'bg-danger-subtle text-danger border border-danger';
                        if ($role === 'Staff') {
                            $badgeClass = 'bg-info-subtle text-info border border-info';
                        } elseif ($role === 'Affiliate') {
                            $badgeClass = 'bg-warning-subtle text-warning border border-warning';
                        }
                      ?>
                      <span class="badge <?php echo $badgeClass; ?>"><?php echo $role; ?></span>
                      <strong class="text-white ms-1"><?php echo htmlspecialchars($log['operator_name'] ?? 'System'); ?></strong>
                    </td>
                    <td><span class="text-high-contrast-light"><?php echo htmlspecialchars($log['action_description']); ?></span></td>
                    <td><span class="text-info font-monospace text-uppercase"><?php echo htmlspecialchars($log['target_node'] ?? 'SYSTEM'); ?></span></td>
                    <td class="text-end px-3 font-monospace text-high-contrast-muted" style="font-size: 0.8rem;">
                      <?php echo htmlspecialchars($log['ip_address'] ?? '127.0.0.1'); ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <!-- Baseline Sandbox fallbacks shown if database is empty -->
                <tr>
                  <td class="px-3 text-high-contrast-muted font-monospace" style="font-size: 0.8rem;">2026-06-30 10:39:12</td>
                  <td>
                    <span class="badge bg-danger-subtle text-danger border border-danger">Admin</span>
                    <strong class="text-white ms-1">Rendell</strong>
                  </td>
                  <td><span class="text-high-contrast-light">Altered configuration rule: <span class="text-info font-monospace">Direct Referral Commission</span> set to 10%</span></td>
                  <td><span class="text-white-50 font-monospace text-uppercase">SYS_CONFIG</span></td>
                  <td class="text-end px-3 font-monospace text-high-contrast-muted" style="font-size: 0.8rem;">127.0.0.1</td>
                </tr>
                <tr>
                  <td class="px-3 text-high-contrast-muted font-monospace" style="font-size: 0.8rem;">2026-06-30 10:16:44</td>
                  <td>
                    <span class="badge bg-info-subtle text-info border border-info">Staff</span>
                    <strong class="text-white ms-1">Ops_Desk_1</strong>
                  </td>
                  <td><span class="text-high-contrast-light">Approved identity verification documents for user STX-00241</span></td>
                  <td><span class="text-info font-monospace text-uppercase">STX-00241</span></td>
                  <td class="text-end px-3 font-monospace text-high-contrast-muted" style="font-size: 0.8rem;">192.168.1.45</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    </main>
  </div>
</div>

<!-- Dynamic Confirmation Security Modal -->
<div class="modal fade" id="confirmClearModal" tabindex="-1" aria-labelledby="confirmClearModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content modal-dark-content shadow-lg">
      <div class="modal-header modal-dark-header">
        <h5 class="modal-title fw-bold text-white d-flex align-items-center" id="confirmClearModalLabel">
          <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i> Confirm Local Cache Clear
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="text-high-contrast-light mb-2">Are you sure you want to drop the display matrix layer?</p>
        <p class="text-muted small mb-0">This operation hides entries from your current terminal window session. The master database audit logs will remain completely persistent and unaltered.</p>
      </div>
      <div class="modal-footer modal-dark-footer">
        <button type="button" class="btn btn-sm btn-outline-secondary fw-bold px-3 text-white" data-bs-dismiss="modal">Cancel</button>
        <form action="" method="POST" class="d-inline">
          <input type="hidden" name="action" value="clear_local">
          <button type="submit" class="btn btn-sm btn-danger fw-bold px-3">
            <i class="bi bi-trash3-fill me-1"></i> Clear Display
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>