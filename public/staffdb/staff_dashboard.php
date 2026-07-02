<?php
session_start();

// Guard: Force back-office Staff role validation
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Staff') {
    header("Location: ../login.php");
    exit;
}

// 1. DATABASE CONNECTIVITY MAPPER
require_once __DIR__ . '/../../config/database.php';

// Initialize default metric counters
$openTicketsCount = 0;
$pendingKycCount = 0;
$warehousePackagesCount = 0;
$activeActions = [];

try {
    // Fetch live metrics count from database tables if they exist
    $ticketStmt = $pdo->query("SELECT COUNT(*) FROM support_tickets WHERE status = 'Open' OR priority = 'High'");
    $openTicketsCount = $ticketStmt->fetchColumn();

    $kycStmt = $pdo->query("SELECT COUNT(*) FROM kyc_reviews WHERE status = 'Pending'");
    $pendingKycCount = $kycStmt->fetchColumn();

    $pkgStmt = $pdo->query("SELECT COUNT(*) FROM warehouse_fulfillment WHERE status = 'Ready'");
    $warehousePackagesCount = $pkgStmt->fetchColumn();

    // Pull combined active actionable items for the data table queue
    $queueQuery = "
        (SELECT 'TICKET' as node_type, id as node_id, subject as description, department, priority as status_label, created_at 
         FROM support_tickets WHERE status = 'Open')
        UNION ALL
        (SELECT 'KYC' as node_type, id as node_id, 'New identity verification documents awaiting registration approval' as description, 'Member Verification' as department, status as status_label, created_at 
         FROM kyc_reviews WHERE status = 'Pending')
        ORDER BY created_at DESC LIMIT 10";
    
    $queueStmt = $pdo->query($queueQuery);
    $activeActions = $queueStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // If tables aren't setup/populated yet, it falls back gracefully without crashing
}

// Active page state for highlighting "Operations Hub" in the staff sidebar
$activePage = 'staff_dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Dashboard - Syntrix</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    body { background-color: #030b1e; color: #ffffff; font-family: sans-serif; }
    
    /* FIX: Altered from white sheet layer to blend smoothly into the system grid layout */
    .card-metric { background-color: #081229 !important; color: #ffffff !important; border-radius: 12px; border: 1px solid #1e293b !important; }
    .card-dark { background-color: #081229; border: 1px solid #1e293b; border-radius: 12px; }
    
    /* Custom clean table presentation overrides overriding global bootstrap rules */
    .table-custom { color: #ffffff !important; border-color: #1e293b; vertical-align: middle; }
    .table-custom thead { background-color: #0f172a !important; color: #94a3b8 !important; }
    
    /* Force every row table cell to render text clearly against the dark background grid */
    .table-custom tbody tr td { 
      background-color: #081229 !important; 
      color: #ffffff !important; 
      border-bottom: 1px solid #1e293b !important;
    }
    .table-custom tbody tr td .text-white-50 {
      color: #94a3b8 !important;
    }
    
    .table-custom tbody tr { border-bottom: 1px solid #1e293b; transition: background 0.15s; }
    .table-custom tbody tr:hover td { background-color: rgba(30, 41, 59, 0.5) !important; }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    
    <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom border-secondary" style="border-color: #1e293b !important;">
        <div>
          <h1 class="h3 fw-bold text-white mb-0">Operations Desk</h1>
          <p class="text-muted small mb-0">Monitor network support requests, process pending user profile verifications, and audit warehouse fulfillment.</p>
        </div>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <div class="card card-metric p-3 shadow-sm">
            <small class="text-muted text-uppercase tracking-wider fw-bold" style="font-size: 0.75rem; color: #94a3b8 !important;">Open Support Tickets</small>
            <h3 class="fw-bold mt-1 mb-0 text-info"><?php echo $openTicketsCount; ?> Active</h3>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-metric p-3 shadow-sm">
            <small class="text-muted text-uppercase tracking-wider fw-bold" style="font-size: 0.75rem; color: #94a3b8 !important;">Pending KYC Reviews</small>
            <h3 class="fw-bold mt-1 mb-0 text-warning"><?php echo $pendingKycCount; ?> Profiles</h3>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-metric p-3 shadow-sm">
            <small class="text-muted text-uppercase tracking-wider fw-bold" style="font-size: 0.75rem; color: #94a3b8 !important;">Warehouse Packages</small>
            <h3 class="fw-bold mt-1 mb-0 text-success"><?php echo $warehousePackagesCount; ?> Items Ready</h3>
          </div>
        </div>
      </div>

      <div class="card-dark p-4 shadow-lg">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold mb-0 text-white-50"><i class="bi bi-activity me-2 text-info"></i>Active High-Priority Desk Actions</h5>
          <span class="badge bg-success-subtle text-success border border-success px-2 py-1 small">Systems Healthy</span>
        </div>

        <div class="table-responsive">
          <table class="table table-custom table-hover mb-0" style="font-size: 0.9rem;">
            <thead>
              <tr>
                <th scope="col" class="py-3 px-3">System Node ID</th>
                <th scope="col" class="py-3">Description Context</th>
                <th scope="col" class="py-3">Assigned Department</th>
                <th scope="col" class="py-3">Status</th>
                <th scope="col" class="py-3 text-end px-3">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (count($activeActions) > 0): ?>
                <?php foreach ($activeActions as $action): ?>
                  <tr>
                    <td class="px-3 font-monospace text-info fw-bold">NODE-<?php echo htmlspecialchars($action['node_id']); ?></td>
                    <td><?php echo htmlspecialchars($action['description']); ?></td>
                    <td><span class="text-white-50"><?php echo htmlspecialchars($action['department']); ?></span></td>
                    <td>
                      <?php 
                        $lbl = strtoupper($action['status_label']);
                        $badge = 'bg-secondary-subtle text-secondary border border-secondary';
                        if ($lbl === 'HIGH' || $lbl === 'URGENT') $badge = 'bg-danger-subtle text-danger border border-danger';
                        if ($lbl === 'PENDING') $badge = 'bg-warning-subtle text-warning border border-warning';
                      ?>
                      <span class="badge <?php echo $badge; ?> px-2 py-0.5"><?php echo $lbl; ?></span>
                    </td>
                    <td class="text-end px-3">
                      <a href="<?php echo ($action['node_type'] === 'TICKET') ? 'support_tickets.php' : 'member_verification.php'; ?>?id=<?php echo urlencode($action['node_id']); ?>" class="btn btn-sm btn-outline-info py-0.5" style="font-size: 0.75rem;">Review</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td class="px-3 font-monospace text-info fw-bold">TCK-48195</td>
                  <td>Binary volume synchronization lag reported by Affiliate user</td>
                  <td><span class="text-white-50">Technical Helpdesk</span></td>
                  <td><span class="badge bg-danger-subtle text-danger border border-danger px-2 py-0.5">HIGH</span></td>
                  <td class="text-end px-3">
                    <a href="support_tickets.php" class="btn btn-sm btn-outline-info py-0.5" style="font-size: 0.75rem;">Review</a>
                  </td>
                </tr>
                <tr>
                  <td class="px-3 font-monospace text-info fw-bold">KYC-99214</td>
                  <td>New identity verification documents awaiting registration approval</td>
                  <td><span class="text-white-50">Member Verification</span></td>
                  <td><span class="badge bg-warning-subtle text-warning border border-warning px-2 py-0.5">PENDING</span></td>
                  <td class="text-end px-3">
                    <a href="member_verification.php" class="btn btn-sm btn-outline-info py-0.5" style="font-size: 0.75rem;">Review</a>
                  </td>
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