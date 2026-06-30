<?php
session_start();

// Guard: Force back-office Staff role validation
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Staff') {
    header("Location: ../login.php");
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
    
    /* High-contrast table designs matching staff dashboards */
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
          <h1 class="h3 fw-bold text-white mb-0">System Operational Reports</h1>
          <p class="text-muted small mb-0">Audit system-wide sales volumes, aggregate registration rates, and calculate product line metrics.</p>
        </div>
        <button class="btn btn-sm btn-info text-dark fw-bold px-3" onclick="alert('Compiling raw system log snapshot...');">
          <i class="bi bi-download me-1"></i> Export Master CSV
        </button>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <div class="metric-sheet-white shadow-sm">
            <small class="text-muted text-uppercase tracking-wider fw-bold d-block mb-1" style="font-size: 0.7rem;">Gross Volume Settled</small>
            <h3 class="fw-bold mb-0">$48,250.00</h3>
          </div>
        </div>
        <div class="col-md-4">
          <div class="metric-sheet-white shadow-sm">
            <small class="text-muted text-uppercase tracking-wider fw-bold d-block mb-1" style="font-size: 0.7rem;">New Onboard Nodes</small>
            <h3 class="fw-bold mb-0">+342 Members</h3>
          </div>
        </div>
        <div class="col-md-4">
          <div class="metric-sheet-white shadow-sm">
            <small class="text-muted text-uppercase tracking-wider fw-bold d-block mb-1" style="font-size: 0.7rem;">Fulfillment Success</small>
            <h3 class="fw-bold mb-0 text-success">99.4%</h3>
          </div>
        </div>
      </div>

      <div class="card-dark p-4 shadow-lg">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold mb-0 text-white-50"><i class="bi bi-bar-chart-line me-2 text-info"></i>Weekly Volume Summary Logs</h5>
          <span class="badge bg-dark border border-secondary text-light px-2 py-1 small">Historical Performance</span>
        </div>

        <div class="table-responsive">
          <table class="table table-custom table-hover mb-0" style="font-size: 0.9rem;">
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
              <tr>
                <td class="px-3 fw-bold text-white">Week 26 (Current)</td>
                <td>114 Users</td>
                <td>48 Packages</td>
                <td class="text-info fw-bold font-monospace">$14,210.00</td>
                <td class="text-end px-3">
                  <button class="btn btn-sm btn-outline-info py-0.5" style="font-size: 0.75rem;" onclick="alert('Viewing broken-down ledger charts for Week 26...');">View Details</button>
                </td>
              </tr>
              <tr>
                <td class="px-3 fw-bold text-white-50">Week 25</td>
                <td>228 Users</td>
                <td>96 Packages</td>
                <td class="text-info fw-bold font-monospace">$34,040.00</td>
                <td class="text-end px-3">
                  <button class="btn btn-sm btn-outline-info py-0.5" style="font-size: 0.75rem;" onclick="alert('Viewing broken-down ledger charts for Week 25...');">View Details</button>
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