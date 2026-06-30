<?php
session_start();

// Guard: Force administrative security access validation
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit;
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
    body { background-color: #030b1e; color: #ffffff; font-family: sans-serif; }
    .card-dark { background-color: #081229; border: 1px solid #1e293b; border-radius: 12px; }
    
    .form-control-dark {
      background-color: #0f172a;
      border: 1px solid #334155;
      color: #ffffff;
    }
    
    /* High-contrast dark operational tables */
    .table-custom { color: #ffffff; border-color: #1e293b; vertical-align: middle; }
    .table-custom thead { background-color: #0f172a; color: #94a3b8; }
    .table-custom tbody tr { border-bottom: 1px solid #1e293b; transition: background 0.15s; }
    .table-custom tbody tr:hover { background-color: rgba(30, 41, 59, 0.5); }
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
          <p class="text-muted small mb-0">Review system actions, trace administrative state corrections, and monitor security events.</p>
        </div>
        <button class="btn btn-sm btn-outline-danger fw-bold px-3" onclick="alert('Clearing local session displays (Database logs remain persistent)...');">
          <i class="bi bi-trash3-fill me-1"></i> Clear Local Views
        </button>
      </div>

      <div class="card-dark p-4 shadow-lg">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold mb-0 text-white-50"><i class="bi bi-shield-checkged me-2 text-danger"></i>Immutable Access History</h5>
          <span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1 small">Live Stream Active</span>
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
              <tr>
                <td class="px-3 text-muted font-monospace" style="font-size: 0.8rem;">2026-06-30 10:39:12</td>
                <td>
                  <span class="badge bg-danger-subtle text-danger border border-danger">Admin</span>
                  <strong class="text-white ms-1">Rendell</strong>
                </td>
                <td><span class="text-light">Altered configuration rule: <span class="text-info font-monospace">Direct Referral Commission</span> set to 10%</span></td>
                <td><span class="text-white-50 font-monospace text-uppercase">SYS_CONFIG</span></td>
                <td class="text-end px-3 font-monospace text-muted" style="font-size: 0.8rem;">127.0.0.1</td>
              </tr>
              
              <tr>
                <td class="px-3 text-muted font-monospace" style="font-size: 0.8rem;">2026-06-30 10:16:44</td>
                <td>
                  <span class="badge bg-info-subtle text-info border border-info">Staff</span>
                  <strong class="text-white ms-1">Ops_Desk_1</strong>
                </td>
                <td><span class="text-light">Approved identity verification documents for user STX-00241</span></td>
                <td><span class="text-info font-monospace text-uppercase">STX-00241</span></td>
                <td class="text-end px-3 font-monospace text-muted" style="font-size: 0.8rem;">192.168.1.45</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>