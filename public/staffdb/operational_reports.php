<?php
session_start();

// Guard: Force back-office Staff role validation
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Staff') {
    header("Location: ../login.php");
    exit;
}

// 1. DATABASE CONNECTIVITY MAPPER
require_once __DIR__ . '/../../config/database.php';

// --- LIVE AGGREGATIONS ENGINE ---
try {
    // A. Count total products currently logged in the fulfillment stream
    $countSql = "SELECT COUNT(*) as total_items FROM warehouse_fulfillment";
    $countStmt = $pdo->query($countSql);
    $totalWarehouseProducts = $countStmt->fetch(PDO::FETCH_ASSOC)['total_items'] ?? 0;

    // B. Calculate active fulfillment rate mapping based on status fields
    $readySql = "SELECT COUNT(*) as ready_items FROM warehouse_fulfillment WHERE LOWER(status) = 'ready' OR LOWER(status) = 'active'";
    $readyStmt = $pdo->query($readySql);
    $readyWarehouseProducts = $readyStmt->fetch(PDO::FETCH_ASSOC)['ready_items'] ?? 0;

    $fulfillmentRate = $totalWarehouseProducts > 0 
        ? round(($readyWarehouseProducts / $totalWarehouseProducts) * 100, 1) 
        : 100.0;

    // C. Monitor unresolved vs resolved customer support tickets
    $ticketSql = "SELECT COUNT(*) as open_tickets FROM support_tickets WHERE LOWER(status) != 'resolved'";
    $ticketStmt = $pdo->query($ticketSql);
    $openTicketsCount = $ticketStmt->fetch(PDO::FETCH_ASSOC)['open_tickets'] ?? 0;

    // D. DYNAMIC LIVE WEEKLY SUMMARY LOG GENERATOR
    $weeklySql = "SELECT 
                    WEEK(created_at) as week_num,
                    YEAR(created_at) as year_num,
                    COUNT(id) as packages_sold,
                    (COUNT(id) * 3500) as estimated_revenue
                  FROM warehouse_fulfillment 
                  GROUP BY YEAR(created_at), WEEK(created_at) 
                  ORDER BY year_num DESC, week_num DESC 
                  LIMIT 5";
    $weeklyStmt = $pdo->query($weeklySql);
    $liveWeeklyLogs = $weeklyStmt->fetchAll(PDO::FETCH_ASSOC);

    // E. Dynamic Registration Metrics Pull
    $regSql = "SELECT WEEK(created_at) as week_num, COUNT(id) as reg_count FROM users GROUP BY WEEK(created_at)";
    $regStmt = $pdo->query($regSql);
    $regData = $regStmt->fetchAll(PDO::FETCH_KEY_PAIR);

} catch (PDOException $e) {
    $totalWarehouseProducts = 0;
    $fulfillmentRate = 100.0;
    $openTicketsCount = 0;
    $liveWeeklyLogs = [];
    $regData = [];
}

// --- MASTER CSV EXPORT WORKER PIPELINE ---
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=System_Operational_Report_' . date('Y-m-d') . '.csv');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Reporting Period', 'New Registrations', 'Product Bundles Sold', 'Total Sales Revenue (PHP)']);
    
    if (count($liveWeeklyLogs) > 0) {
        foreach ($liveWeeklyLogs as $log) {
            $wnum = $log['week_num'];
            $regs = $regData[$wnum] ?? 0;
            fputcsv($output, [
                'Week ' . $wnum,
                $regs . ' Users',
                $log['packages_sold'] . ' Packages',
                'PHP ' . number_format($log['estimated_revenue'], 2)
            ]);
        }
    } else {
        fputcsv($output, ['Week ' . date('W'), '0 Users', '0 Packages', 'PHP 0.00']);
    }
    fclose($output);
    exit;
}

// Active page state for highlighting "Operational Reports" in your staff sidebar
$activePage = 'operational_reports';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Operational Reports - Syntrix</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    body { background-color: #030b1e; color: #ffffff; font-family: sans-serif; }
    .card-dark { background-color: #081229; border: 1px solid #1e293b; border-radius: 12px; }
    
    /* High-contrast mini-analytics cards matching dashboard style */
    .metric-sheet-white {
      background-color: #ffffff;
      color: #0f172a;
      border-radius: 12px;
      padding: 1.25rem;
    }
    
    /* HIGH SPECIFICITY TEXT VISIBILITY FIXES FOR THE OPERATIONAL TABLE */
    table.table-custom { 
      background-color: #081229 !important;
      color: #ffffff !important; 
      border-color: #1e293b !important; 
      vertical-align: middle; 
    }
    table.table-custom thead th { 
      background-color: #0f172a !important; 
      color: #94a3b8 !important; 
      border-bottom: 2px solid #1e293b !important; 
    }
    table.table-custom tbody tr td { 
      background-color: #081229 !important; 
      color: #ffffff !important; 
      border-bottom: 1px solid #1e293b !important;
    }
    table.table-custom tbody tr:hover td { 
      background-color: rgba(30, 41, 59, 0.8) !important; 
    }

    /* Modal Styling High-Contrast Parameters */
    .modal-content-dark { background-color: #081229; border: 1px solid #1e293b; color: #ffffff; }
    .modal-header-dark { border-bottom: 1px solid #1e293b; }
    .modal-footer-dark { border-top: 1px solid #1e293b; }
    
    .text-light-muted { color: #cbd5e1 !important; }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    
    <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      
      <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-secondary" style="border-color: #1e293b !important;">
        <div>
          <h1 class="h3 fw-bold text-white mb-0">System Operational Reports</h1>
          <p class="text-muted small mb-0">Audit system-wide sales volumes, aggregate registration rates, and calculate product line metrics.</p>
        </div>
        <a href="operational_reports.php?export=csv" class="btn btn-sm btn-info text-dark fw-bold px-3">
          <i class="bi bi-download me-1"></i> Export Master CSV
        </a>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <div class="metric-sheet-white shadow-sm">
            <small class="text-muted text-uppercase tracking-wider fw-bold d-block mb-1" style="font-size: 0.7rem;">Live Stock Items Tracked</small>
            <h3 class="fw-bold mb-0"><?php echo $totalWarehouseProducts; ?> Products</h3>
          </div>
        </div>
        <div class="col-md-4">
          <div class="metric-sheet-white shadow-sm">
            <small class="text-muted text-uppercase tracking-wider fw-bold d-block mb-1" style="font-size: 0.7rem;">Pending Helpdesk Issues</small>
            <h3 class="fw-bold mb-0"><?php echo $openTicketsCount; ?> Tickets</h3>
          </div>
        </div>
        <div class="col-md-4">
          <div class="metric-sheet-white shadow-sm">
            <small class="text-muted text-uppercase tracking-wider fw-bold d-block mb-1" style="font-size: 0.7rem;">Fulfillment Success</small>
            <h3 class="fw-bold mb-0 text-success"><?php echo $fulfillmentRate; ?>%</h3>
          </div>
        </div>
      </div>

      <div class="card-dark p-4 shadow-lg">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold mb-0 text-white-50"><i class="bi bi-bar-chart-line me-2 text-info"></i>Weekly Volume Summary Logs</h5>
          <span class="badge bg-dark border border-secondary text-light px-2 py-1 small">Historical Performance</span>
        </div>

        <div class="table-responsive">
          <table class="table table-custom mb-0" style="font-size: 0.9rem;">
            <thead>
              <tr>
                <th scope="col" class="py-3 px-3">Reporting Period</th>
                <th scope="col" class="py-3">New Registrations</th>
                <th scope="col" class="py-3">Product Bundles Sold</th>
                <th scope="col" class="py-3">Total Sales Revenue</th>
                <th scope="col" class="py-3 text-end px-3">Audit Log</th>
              </tr>
            </thead>
            <tbody>
              <?php if (count($liveWeeklyLogs) > 0): ?>
                <?php foreach ($liveWeeklyLogs as $log): 
                    $currentWeekNum = $log['week_num'];
                    $userRegs = $regData[$currentWeekNum] ?? 0;
                    $isCurrentWeek = ($currentWeekNum == date('W')) ? ' (Current)' : '';
                ?>
                  <tr>
                    <td class="px-3 fw-bold text-white">Week <?php echo $currentWeekNum . $isCurrentWeek; ?></td>
                    <td class="text-white"><?php echo $userRegs; ?> Users</td>
                    <td class="text-white"><?php echo $log['packages_sold']; ?> Packages</td>
                    <td class="text-info fw-bold font-monospace">₱<?php echo number_format($log['estimated_revenue'], 2); ?></td>
                    <td class="text-end px-3">
                      <button class="btn btn-sm btn-outline-info py-0.5" style="font-size: 0.75rem;" data-bs-toggle="modal" data-bs-target="#viewDetailsModal_<?php echo $currentWeekNum; ?>">View Details</button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td class="px-3 fw-bold text-white">Week <?php echo date('W'); ?> (Current)</td>
                  <td class="text-white">0 Users</td>
                  <td class="text-white">0 Packages</td>
                  <td class="text-info fw-bold font-monospace">₱0.00</td>
                  <td class="text-end px-3">
                    <button class="btn btn-sm btn-outline-info py-0.5" style="font-size: 0.75rem;" onclick="alert('No entries tracked yet.');">View Details</button>
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

<?php if (count($liveWeeklyLogs) > 0): ?>
  <?php foreach ($liveWeeklyLogs as $log): 
      $currentWeekNum = $log['week_num'];
      $userRegs = $regData[$currentWeekNum] ?? 0;
  ?>
  <div class="modal fade" id="viewDetailsModal_<?php echo $currentWeekNum; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content modal-content-dark shadow-lg">
        <div class="modal-header modal-header-dark">
          <h5 class="modal-title fw-bold text-white"><i class="bi bi-folder-symlink me-2 text-info"></i>Audit Log Breakdown: Week <?php echo $currentWeekNum; ?></h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body font-monospace small">
          <div class="mb-3 border-bottom border-secondary pb-2">
            <span class="text-light-muted d-block">CHRONO INDEXING RANGE:</span>
            <span class="text-white fw-bold">Year <?php echo $log['year_num']; ?>, Calendar Week Node <?php echo $currentWeekNum; ?></span>
          </div>
          <div class="mb-3 border-bottom border-secondary pb-2">
            <span class="text-light-muted d-block">NEW MEMBERSHIP SIGNUPS:</span>
            <span class="text-info fw-bold">+<?php echo $userRegs; ?> Affiliate Nodes</span>
          </div>
          <div class="mb-3 border-bottom border-secondary pb-2">
            <span class="text-light-muted d-block">TOTAL LOGISTICS DISPATCHES:</span>
            <span class="text-white fw-bold"><?php echo $log['packages_sold']; ?> Bundles Outbound</span>
          </div>
          <div class="mb-1">
            <span class="text-light-muted d-block">GROSS REVENUE ESTIMATE:</span>
            <span class="text-success fw-bold text-decoration-underline" style="font-size: 1.1rem;">₱<?php echo number_format($log['estimated_revenue'], 2); ?> PHP</span>
          </div>
        </div>
        <div class="modal-footer modal-footer-dark">
          <button type="button" class="btn btn-sm btn-outline-secondary font-monospace" data-bs-dismiss="modal">DISMISS REVIEW</button>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>