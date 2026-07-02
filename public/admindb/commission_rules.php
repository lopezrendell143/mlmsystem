<?php
session_start();

// Guard: Force administrative security access validation
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit;
}

// 1. DATABASE CONNECTIVITY MAPPER - Now Live
require_once __DIR__ . '/../../config/database.php'; 

// Initialize default baseline fallbacks in case table records aren't set yet
$directSponsorRate = "10.00";
$binaryPairAmount = "5000.00";
$weakLegCapAmount = "5000.00";

// Fetch current configurations live from the system_settings table
try {
    $getSettings = $pdo->query("SELECT setting_key, setting_value FROM system_settings");
    $settingsMap = $getSettings->fetchAll(PDO::FETCH_KEY_PAIR);
    
    // If keys don't match the standard naming conventions, check standard alternatives
    if (isset($settingsMap['direct_sponsor'])) $directSponsorRate = $settingsMap['direct_sponsor'];
    elseif (isset($settingsMap['direct_referral_commission'])) $directSponsorRate = $settingsMap['direct_referral_commission'];

    if (isset($settingsMap['binary_pair'])) $binaryPairAmount = $settingsMap['binary_pair'];
    elseif (isset($settingsMap['binary_matching_threshold'])) $binaryPairAmount = $settingsMap['binary_matching_threshold'];

    if (isset($settingsMap['weak_leg_cap'])) $weakLegCapAmount = $settingsMap['weak_leg_cap'];
} catch (PDOException $e) {
    // Graceful fallback to baselines if table columns differ slightly
}

// Instantiate notification state management tracking parameters
$ruleUpdatedSuccess = false;
if (isset($_SESSION['rules_commit_flash'])) {
    $ruleUpdatedSuccess = true;
    unset($_SESSION['rules_commit_flash']);
}

// --- OVERRIDE MATRIX POST RECORDING BLOCK ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'commit_rules') {
    $directSponsor = floatval($_POST['direct_sponsor_rate']);
    $binaryPair = floatval($_POST['binary_pair_amount']);
    $weakLegCap = floatval($_POST['weak_leg_cap']);

    try {
        // Secure execution matrix updating target system rows live
        $sql = "UPDATE system_settings SET setting_value = CASE 
                    WHEN setting_key IN ('direct_sponsor', 'direct_referral_commission') THEN :ds
                    WHEN setting_key IN ('binary_pair', 'binary_matching_threshold') THEN :bp
                    WHEN setting_key = 'weak_leg_cap' THEN :wl
                END WHERE setting_key IN ('direct_sponsor', 'direct_referral_commission', 'binary_pair', 'binary_matching_threshold', 'weak_leg_cap')";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':ds' => $directSponsor, ':bp' => $binaryPair, ':wl' => $weakLegCap]);
        
        $_SESSION['rules_commit_flash'] = true;
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    } catch (Exception $e) {
        // Soft fallback
    }
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
    /* Deep space background core setup */
    body { background-color: #020714; color: #cbd5e1; font-family: sans-serif; }
    
    /* Clean custom deep-navy outer card boxes */
    .card-dark { 
      background-color: #050c1e !important; 
      border: 1px solid #0f1c3f !important; 
      border-radius: 12px; 
    }
    
    .form-control-dark {
      background-color: #020714 !important;
      border: 1px solid #14254c !important;
      color: #ffffff !important;
    }
    .form-control-dark:focus {
      background-color: #020714 !important;
      border-color: #dc3545 !important;
      color: #ffffff !important;
      box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.15);
    }
    
    .input-group-text-dark {
      background-color: #020714 !important;
      border: 1px solid #14254c !important;
      color: #64748b !important;
    }
    
    /* CRITICAL OVERRIDES: Forces every single row cell to be dark, destroying the white background bug */
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
      transition: background 0.15s;
    }
    
    /* Interactive highlighting tracking over dark nodes */
    .table-custom tbody tr:hover td { 
      background-color: #09132d !important; 
    }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    
    <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      
      <form action="" method="POST">
        <input type="hidden" name="action" value="commit_rules">

        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom" style="border-color: #0f1c3f !important;">
          <div>
            <h1 class="h3 fw-bold text-white mb-0">Commission Matrix Configuration</h1>
            <p class="text-muted small mb-0">Adjust system compensation laws, direct referral percentages, and binary pairing thresholds dynamically.</p>
          </div>
          <button type="submit" class="btn btn-danger fw-bold px-4 font-monospace shadow-sm" style="font-size: 0.9rem;">
            <i class="bi bi-shield-lock-fill me-1"></i> Commit Rule Set
          </button>
        </div>

        <?php if ($ruleUpdatedSuccess): ?>
          <div class="alert alert-dismissible fade show border-0 shadow p-3 mb-4" style="background-color: #05211b; border: 1px solid #0e4438 !important; border-radius: 10px;" role="alert">
            <div class="d-flex align-items-center">
              <i class="bi bi-check-circle-fill text-success fs-5 me-3"></i>
              <div>
                <strong class="text-white">Compensation Parameters Comitted</strong>
                <div class="text-muted small mt-0.5">The structural protocol configurations have been securely stored and deployed platform-wide.</div>
              </div>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.75rem; top: 1rem;"></button>
          </div>
        <?php endif; ?>

        <div class="card-dark p-4 shadow-lg">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0 text-white-50"><i class="bi bi-gear-wide-connected me-2 text-danger"></i>Platform Compensation Matrix Rules</h5>
            <span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1 small">Real-Time Rule Calculation</span>
          </div>

          <div class="table-responsive">
            <table class="table table-custom table-hover mb-0" style="font-size: 0.9rem;">
              <thead>
                <tr>
                  <th scope="col" class="py-3 px-3" style="width: 45%;">Rule Parameter Context</th>
                  <th scope="col" class="py-3" style="width: 30%;">Active Multiplier Metric</th>
                  <th scope="col" class="py-3 px-3 text-end" style="width: 25%;">Global Status</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="px-3">
                    <div class="fw-bold text-white">Direct Sponsoring Multiplier</div>
                    <span class="text-muted small" style="font-size: 0.75rem;">Calculated instantly upon product bundle settlement.</span>
                  </td>
                  <td>
                    <div class="input-group input-group-sm" style="width: 150px;">
                      <input type="text" name="direct_sponsor_rate" class="form-control form-control-dark text-center text-info fw-bold font-monospace" value="<?php echo htmlspecialchars($directSponsorRate); ?>">
                      <span class="input-group-text input-group-text-dark small">%</span>
                    </div>
                  </td>
                  <td class="text-end px-3">
                    <span class="badge bg-success-subtle text-success border border-success px-2 py-1">ACTIVE</span>
                  </td>
                </tr>

                <tr>
                  <td class="px-3">
                    <div class="fw-bold text-white">Binary Team Matching Pair</div>
                    <span class="text-muted small" style="font-size: 0.75rem;">Fires when left and right node groups balance thresholds.</span>
                  </td>
                  <td>
                    <div class="input-group input-group-sm" style="width: 150px;">
                      <span class="input-group-text input-group-text-dark small">₱</span>
                      <input type="text" name="binary_pair_amount" class="form-control form-control-dark text-center text-info fw-bold font-monospace" value="<?php echo htmlspecialchars($binaryPairAmount); ?>">
                    </div>
                  </td>
                  <td class="text-end px-3">
                    <span class="badge bg-success-subtle text-success border border-success px-2 py-1">ACTIVE</span>
                  </td>
                </tr>

                <tr>
                  <td class="px-3">
                    <div class="fw-bold text-white">Maximum Weak-Leg Cap (Daily)</div>
                    <span class="text-muted small" style="font-size: 0.75rem;">Safety threshold to maintain network liquidity pools.</span>
                  </td>
                  <td>
                    <div class="input-group input-group-sm" style="width: 150px;">
                      <span class="input-group-text input-group-text-dark small">₱</span>
                      <input type="text" name="weak_leg_cap" class="form-control form-control-dark text-center text-info fw-bold font-monospace" value="<?php echo htmlspecialchars($weakLegCapAmount); ?>">
                    </div>
                  </td>
                  <td class="text-end px-3">
                    <span class="badge bg-success-subtle text-success border border-success px-2 py-1">ACTIVE</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </form>

    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>