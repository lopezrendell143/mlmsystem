<?php
session_start();

// Guard: Force administrative security access validation
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit;
}

// Active page state for highlighting "Commission Rules" in your admin sidebar
$activePage = 'commission_rules';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Commission Rules - Syntrix Admin</title>
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
    .form-control-dark:focus {
      background-color: #0f172a;
      border-color: #dc3545;
      color: #ffffff;
      box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.15);
    }
    
    /* High-contrast dark structural data tables */
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
          <h1 class="h3 fw-bold text-white mb-0">Commission Matrix Configuration</h1>
          <p class="text-muted small mb-0">Adjust system compensation laws, direct referral percentages, and binary pairing thresholds dynamically.</p>
        </div>
        <button class="btn btn-sm btn-danger fw-bold px-3" onclick="alert('Saving modified threshold variations to root registry...');">
          <i class="bi bi-shield-lock-fill me-1"></i> Commit Rule Set
        </button>
      </div>

      <div class="card-dark p-4 shadow-lg">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold mb-0 text-white-50"><i class="bi bi-gear-wide-connected me-2 text-danger"></i>Platform Compensation Matrix Rules</h5>
          <span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1 small">Real-Time Rule Calculation</span>
        </div>

        <div class="table-responsive">
          <table class="table table-custom table-hover mb-0" style="font-size: 0.9rem;">
            <thead>
              <tr>
                <th scope="col" class="py-3 px-3" style="width: 35%;">Rule Parameter Context</th>
                <th scope="col" class="py-3" style="width: 25%;">Active Multiplier Metric</th>
                <th scope="col" class="py-3" style="width: 20%;">Global Status</th>
                <th scope="col" class="py-3 text-end px-3" style="width: 20%;">Override Settings</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="px-3">
                  <div class="fw-bold text-white">Direct Sponsoring Multiplier</div>
                  <span class="text-muted small" style="font-size: 0.75rem;">Calculated instantly upon product bundle settlement.</span>
                </td>
                <td>
                  <div class="input-group input-group-sm" style="width: 140px;">
                    <input type="text" class="form-control form-control-dark text-center text-info fw-bold font-monospace" value="10.00">
                    <span class="input-group-text bg-dark border-secondary text-muted small">%</span>
                  </div>
                </td>
                <td><span class="badge bg-success-subtle text-success border border-success px-2 py-1">ACTIVE</span></td>
                <td class="text-end px-3">
                  <button class="btn btn-sm btn-outline-danger py-0.5 font-monospace" style="font-size: 0.75rem;" onclick="alert('Parameter cached for global save sequence.');">UPDATE</button>
                </td>
              </tr>

              <tr>
                <td class="px-3">
                  <div class="fw-bold text-white">Binary Team Matching Pair</div>
                  <span class="text-muted small" style="font-size: 0.75rem;">Fires when left and right node groups balance thresholds.</span>
                </td>
                <td>
                  <div class="input-group input-group-sm" style="width: 140px;">
                    <span class="input-group-text bg-dark border-secondary text-muted small">$</span>
                    <input type="text" class="form-control form-control-dark text-center text-info fw-bold font-monospace" value="100.00">
                  </div>
                </td>
                <td><span class="badge bg-success-subtle text-success border border-success px-2 py-1">ACTIVE</span></td>
                <td class="text-end px-3">
                  <button class="btn btn-sm btn-outline-danger py-0.5 font-monospace" style="font-size: 0.75rem;" onclick="alert('Parameter cached for global save sequence.');">UPDATE</button>
                </td>
              </tr>

              <tr>
                <td class="px-3">
                  <div class="fw-bold text-white">Maximum Weak-Leg Cap (Daily)</div>
                  <span class="text-muted small" style="font-size: 0.75rem;">Safety threshold to maintain network liquidity pools.</span>
                </td>
                <td>
                  <div class="input-group input-group-sm" style="width: 140px;">
                    <span class="input-group-text bg-dark border-secondary text-muted small">$</span>
                    <input type="text" class="form-control form-control-dark text-center text-info fw-bold font-monospace" value="5000.00">
                  </div>
                </td>
                <td><span class="badge bg-success-subtle text-success border border-success px-2 py-1">ACTIVE</span></td>
                <td class="text-end px-3">
                  <button class="btn btn-sm btn-outline-danger py-0.5 font-monospace" style="font-size: 0.75rem;" onclick="alert('Parameter cached for global save sequence.');">UPDATE</button>
                </td>
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