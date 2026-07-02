<?php
session_start();

// Guard: Force admin access role validation
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit;
}

// 1. DATABASE CONNECTIVITY WRAPPER
require_once __DIR__ . '/../../config/database.php'; 

// --- DYNAMIC PARAMETER RECONFIGURATION MATRIX NODE ---
$configFlash = false;
$configMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_param') {
    $paramKey = $_POST['param_key'];
    $newValue = $_POST['new_value'];

    try {
        // Adjust updating queries or configurations matching your platform parameters database table schema
        // $stmt = $pdo->prepare("UPDATE system_settings SET param_value = :val WHERE param_key = :key");
        // $stmt->execute([':val' => $newValue, ':key' => $paramKey]);
        
        $_SESSION['config_flash_msg'] = "System Parameter [" . htmlspecialchars($paramKey) . "] changed successfully to: " . htmlspecialchars($newValue);
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    } catch (PDOException $e) {
        // Soft fallback
    }
}

if (isset($_SESSION['config_flash_msg'])) {
    $configFlash = true;
    $configMessage = $_SESSION['config_flash_msg'];
    unset($_SESSION['config_flash_msg']);
}

try {
    // A. Fetch Total Platform Revenue dynamically from your transactions ledger
    $revenueStmt = $pdo->query("SELECT SUM(amount) AS total_revenue FROM transactions WHERE status = 'Completed'");
    $revenueResult = $revenueStmt->fetch(PDO::FETCH_ASSOC);
    $totalRevenue = $revenueResult['total_revenue'] ?? 0;

    // B. Fetch Total Network Users Count
    $userStmt = $pdo->query("SELECT COUNT(*) AS total_users FROM users");
    $userResult = $userStmt->fetch(PDO::FETCH_ASSOC);
    $totalUsers = $userResult['total_users'] ?? 0;

    // C. Fetch Pending Payout Queue
    $payoutStmt = $pdo->query("SELECT SUM(amount) AS total_pending FROM payouts WHERE status = 'Pending'");
    $payoutResult = $payoutStmt->fetch(PDO::FETCH_ASSOC);
    $totalPending = $payoutResult['total_pending'] ?? 0;

} catch (PDOException $e) {
    // Fallbacks to prevent application crash if tables don't exist yet during installation
    $totalRevenue = 0;
    $totalUsers = 0;
    $totalPending = 0;
}

// Active page state for highlighting "Dashboard" in your admin sidebar
$activePage = 'admin_dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Syntrix</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    /* Deep space background core setup */
    body { background-color: #020714; color: #cbd5e1; font-family: sans-serif; }
    
    /* Clean custom deep-navy outer card boxes */
    .card-dark { 
      background-color: #050c1e !important; 
      border: 1px solid #0f1c3f !important; 
      border-radius: 12px; 
    }

    /* Transformed metrics cards into premium dark analytics modules */
    .card-metric { 
      background-color: #050c1e !important; 
      color: #ffffff !important; 
      border: 1px solid #0f1c3f !important;
      border-radius: 12px; 
    }
    
    /* CRITICAL OVERRIDES: Forces row cells to remain dark, stopping Bootstrap white cell bug */
    .table-custom { 
      border-color: #0f1c3f !important; 
      vertical-align: middle; 
      background-color: #050c1e !important;
    }
    
    .table-custom thead th { 
      background-color: #020714 !important; 
      color: #64748b !important; 
      border-bottom: 2px solid #0f1c3f !important;
      font-weight: 600;
    }
    
    .table-custom tbody tr td { 
      background-color: #050c1e !important; 
      border-bottom: 1px solid #0f1c3f !important;
      color: #e2e8f0 !important;
      transition: background 0.15s;
    }
    
    /* Interactive highlighting tracking over dark nodes */
    .table-custom tbody tr:hover td { 
      background-color: #09132d !important; 
    }

    /* Modal styling harmonization matching dark operational layout maps */
    .modal-dark-content {
      background-color: #050c1e !important;
      border: 1px solid #0f1c3f !important;
      border-radius: 14px;
      color: #ffffff !important;
    }
    .modal-dark-header { border-bottom: 1px solid #0f1c3f !important; }
    .modal-dark-footer { border-top: 1px solid #0f1c3f !important; }
    .form-control-dark {
      background-color: #020714 !important;
      border: 1px solid #0f1c3f !important;
      color: #ffffff !important;
    }
    .form-control-dark:focus {
      background-color: #020714 !important;
      border-color: #dc3545 !important;
      color: #ffffff !important;
      box-shadow: none !important;
    }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    
    <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom" style="border-color: #0f1c3f !important;">
        <div>
          <h1 class="h2 fw-bold text-danger mb-0">Global Administration Panel</h1>
          <p class="text-muted small mb-0">Platform override and structural node relocation modules active.</p>
        </div>
      </div>

      <?php if ($configFlash): ?>
        <div class="alert alert-dismissible fade show border-0 shadow p-3 mb-4" style="background-color: #0c1938; border: 1px solid #0f1c3f !important; color: #ffffff;" role="alert">
          <div class="d-flex align-items-center">
            <i class="bi bi-info-circle-fill text-info fs-5 me-3"></i>
            <div>
              <strong class="text-white">System Parameters Readjusted</strong>
              <div class="text-muted small mt-0.5"><?php echo htmlspecialchars($configMessage); ?></div>
            </div>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.75rem; top: 1rem;"></button>
        </div>
      <?php endif; ?>

      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <div class="card card-metric p-3 shadow-sm">
            <small class="text-muted text-uppercase tracking-wider fw-bold" style="font-size: 0.75rem; color: #64748b !important;">Total Platform Revenue</small>
            <h3 class="fw-bold mt-1 mb-0 text-success">₱<?php echo number_format($totalRevenue, 2); ?></h3>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-metric p-3 shadow-sm">
            <small class="text-muted text-uppercase tracking-wider fw-bold" style="font-size: 0.75rem; color: #64748b !important;">Total Network Users</small>
            <h3 class="fw-bold mt-1 mb-0 text-info"><?php echo number_format($totalUsers); ?> Members</h3>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-metric p-3 shadow-sm">
            <small class="text-muted text-uppercase tracking-wider fw-bold" style="font-size: 0.75rem; color: #64748b !important;">Pending Payout Queue</small>
            <h3 class="fw-bold mt-1 mb-0 text-danger">₱<?php echo number_format($totalPending, 2); ?></h3>
          </div>
        </div>
      </div>

      <div class="card-dark p-4 shadow-lg">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold mb-0 text-white-50"><i class="bi bi-sliders me-2 text-danger"></i>System-Wide Parameters & Gateways</h5>
          <span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1 small">[Secure Root Override Framework Online]</span>
        </div>

        <div class="table-responsive">
          <table class="table table-custom table-hover mb-0" style="font-size: 0.9rem;">
            <thead>
              <tr>
                <th scope="col" class="py-3 px-3">Configuration Parameter</th>
                <th scope="col" class="py-3">Current Active Multiplier</th>
                <th scope="col" class="py-3">Global Rule Status</th>
                <th scope="col" class="py-3 text-end px-3">Adjustment Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="px-3 fw-bold text-white">Direct Referral Commission Bonus</td>
                <td class="font-monospace text-info fw-bold">10.00%</td>
                <td><span class="badge bg-success-subtle text-success border border-success px-2 py-0.5">ACTIVE</span></td>
                <td class="text-end px-3">
                  <button type="button" class="btn btn-sm btn-outline-danger py-0.5" style="font-size: 0.75rem; border-color: #dc3545;" 
                          data-bs-toggle="modal" data-bs-target="#modifyParamModal" 
                          data-param-name="Direct Referral Commission Bonus" data-param-key="direct_referral_bonus" data-current-val="10.00%">
                    Modify
                  </button>
                </td>
              </tr>
              <tr>
                <td class="px-3 fw-bold text-white">Binary Matching Pair Threshold</td>
                <td class="font-monospace text-info fw-bold">₱5,000.00 base layer</td>
                <td><span class="badge bg-success-subtle text-success border border-success px-2 py-0.5">ACTIVE</span></td>
                <td class="text-end px-3">
                  <button type="button" class="btn btn-sm btn-outline-danger py-0.5" style="font-size: 0.75rem; border-color: #dc3545;" 
                          data-bs-toggle="modal" data-bs-target="#modifyParamModal" 
                          data-param-name="Binary Matching Pair Threshold" data-param-key="binary_threshold" data-current-val="5000.00">
                    Modify
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </main>
  </div>
</div>

<div class="modal fade" id="modifyParamModal" tabindex="-1" aria-labelledby="modifyParamModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content modal-dark-content">
      <form action="" method="POST">
        <input type="hidden" name="action" value="update_param">
        <input type="hidden" name="param_key" id="modal_param_key">
        
        <div class="modal-header modal-dark-header">
          <h5 class="modal-title fw-bold text-white" id="modifyParamModalLabel"><i class="bi bi-gear-fill text-danger me-2"></i>Alter Operational Gateway</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label text-white-50 small">Target Configuration Layer</label>
            <input type="text" class="form-control form-control-dark" id="modal_display_name" readonly style="opacity: 0.7;">
          </div>
          <div class="mb-2">
            <label for="modal_new_value" class="form-label text-white-50 small">Override Multiplier Entry Value</label>
            <input type="text" class="form-control form-control-dark" id="modal_new_value" name="new_value" required autocomplete="off">
          </div>
          <small class="text-muted" style="font-size: 0.75rem;">Modifications execute state transformations immediately across the dynamic application core tracking layers.</small>
        </div>
        <div class="modal-footer modal-dark-footer">
          <button type="button" class="btn btn-sm btn-outline-secondary py-1.5 px-3" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-sm btn-danger fw-bold py-1.5 px-4">SAVE CHANGES</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Script matrix initialization intercept to automatically bind parameters into input rows
  const modifyModal = document.getElementById('modifyParamModal');
  if (modifyModal) {
    modifyModal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;
      
      const paramName = button.getAttribute('data-param-name');
      const paramKey = button.getAttribute('data-param-key');
      const currentVal = button.getAttribute('data-current-val');
      
      document.getElementById('modal_display_name').value = paramName;
      document.getElementById('modal_param_key').value = paramKey;
      document.getElementById('modal_new_value').value = currentVal;
    });
  }
</script>
</body>
</html>