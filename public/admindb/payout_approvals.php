<?php
session_start();

// Guard: Force administrative security access validation
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit;
}

// 1. DATABASE CONNECTIVITY MAPPER
require_once __DIR__ . '/../../config/database.php';

// Instantiate notification state management tracking parameters
$payoutActionFlash = false;
$payoutStatusMessage = '';
if (isset($_SESSION['payout_action_flash'])) {
    $payoutActionFlash = true;
    $payoutStatusMessage = $_SESSION['payout_action_flash'];
    unset($_SESSION['payout_action_flash']);
}

// --- OVERRIDE MATRIX POST RECORDING BLOCK ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $payoutId = intval($_POST['payout_id']);
    $actionType = $_POST['action']; // Expected values: 'release_payout' or 'reject_payout'
    $targetStatus = ($actionType === 'release_payout') ? 'Completed' : 'Rejected';

    try {
        // Adjust column names here matching your physical ledger schema constraints if necessary
        $updateSql = "UPDATE payouts SET status = :status WHERE id = :id";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->execute([
            ':status' => $targetStatus,
            ':id'      => $payoutId
        ]);
        
        $_SESSION['payout_action_flash'] = "Transaction ID STX-PAY-{$payoutId} has been successfully updated to status: {$targetStatus}.";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    } catch (PDOException $e) {
        // Soft fallback
    }
}

// 2. RETRIEVE MASTER PENDING QUEUE LOG DATA
try {
    // Selects records awaiting operator verification sequence matching your database layout
    $sql = "SELECT * FROM payouts WHERE status = 'Pending' ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $pendingPayouts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculate live aggregates across existing matching records
    $totalPendingAmount = 0;
    foreach ($pendingPayouts as $payout) {
        $totalPendingAmount += floatval($payout['amount'] ?? 0);
    }
} catch (PDOException $e) {
    $pendingPayouts = [];
    $totalPendingAmount = 0;
}

// Active page state for highlighting "Payout Approvals" in your admin sidebar
$activePage = 'payout_approvals';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payout Approvals - Syntrix Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    /* Pure ultra-dark core environment */
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

    /* Force visibility settings on subtitle context descriptions */
    .text-high-contrast-muted {
      color: #94a3b8 !important;
    }
    .text-high-contrast-address {
      color: #cbd5e1 !important;
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
          <h1 class="h3 fw-bold text-white mb-0">E-Wallet Payout Approvals</h1>
          <p class="text-high-contrast-muted small mb-0">Audit withdrawal ledger requests, check blockchain endpoint destination hashes, and authorize payouts.</p>
        </div>
        <span class="badge bg-danger text-white fw-bold px-3 py-2">₱<?php echo number_format($totalPendingAmount, 2); ?> Pending Disbursal</span>
      </div>

      <?php if ($payoutActionFlash): ?>
        <div class="alert alert-dismissible fade show border-0 shadow p-3 mb-4" style="background-color: #05211b; border: 1px solid #0e4438 !important; border-radius: 10px;" role="alert">
          <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill text-success fs-5 me-3"></i>
            <div>
              <strong class="text-white">Authorization Verified</strong>
              <div class="text-high-contrast-muted small mt-0.5"><?php echo htmlspecialchars($payoutStatusMessage); ?></div>
            </div>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.75rem; top: 1rem;"></button>
        </div>
      <?php endif; ?>

      <div class="card-dark p-4 shadow-lg">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold mb-0 text-white-50"><i class="bi bi-wallet2 me-2 text-danger"></i>Pending Withdrawal Queue</h5>
          <span class="text-high-contrast-muted small">Total: <?php echo count($pendingPayouts); ?> Queue Items</span>
        </div>

        <div class="table-responsive">
          <table class="table table-custom table-hover mb-0" style="font-size: 0.9rem;">
            <thead>
              <tr>
                <th scope="col" class="py-3 px-3">Member Details</th>
                <th scope="col" class="py-3">Withdrawal Pathway</th>
                <th scope="col" class="py-3">Destination Address Details</th>
                <th scope="col" class="py-3">Gross Amount</th>
                <th scope="col" class="py-3 text-end px-3">Authorization Rules</th>
              </tr>
            </thead>
            <tbody>
              <?php if (count($pendingPayouts) > 0): ?>
                <?php foreach ($pendingPayouts as $payout): ?>
                  <tr>
                    <td class="px-3">
                      <div class="fw-bold text-white"><?php echo htmlspecialchars($payout['full_name'] ?? $payout['username'] ?? 'Unknown Member'); ?></div>
                      <small class="text-high-contrast-muted font-monospace" style="font-size: 0.75rem;">User Reference ID: #<?php echo htmlspecialchars($payout['user_id']); ?></small>
                    </td>
                    <td><span class="badge bg-dark text-info border border-info px-2 py-1"><?php echo htmlspecialchars($payout['method'] ?? 'USDT (TRC-20)'); ?></span></td>
                    <td><span class="font-monospace text-high-contrast-address text-wrap" style="font-size: 0.8rem;"><?php echo htmlspecialchars($payout['account_details'] ?? 'No Address Data Available'); ?></span></td>
                    <td><span class="text-danger fw-bold font-monospace">₱<?php echo number_format($payout['amount'], 2); ?></span></td>
                    <td class="text-end px-3">
                      <form action="" method="POST" class="d-inline">
                        <input type="hidden" name="payout_id" value="<?php echo $payout['id']; ?>">
                        <input type="hidden" name="action" value="release_payout">
                        <button type="submit" class="btn btn-sm btn-danger fw-bold me-1 py-1 px-3" style="font-size: 0.75rem;">RELEASE</button>
                      </form>

                      <form action="" method="POST" class="d-inline">
                        <input type="hidden" name="payout_id" value="<?php echo $payout['id']; ?>">
                        <input type="hidden" name="action" value="reject_payout">
                        <button type="submit" class="btn btn-sm btn-outline-secondary py-1" style="font-size: 0.75rem;">REJECT</button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5" class="text-center text-high-contrast-muted py-4" style="background-color: #081229 !important;">No pending e-wallet disbursal entries found inside current queue.</td>
                </tr>
              <?php endif; ?>
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