<?php
session_start();

// Guard: Force affiliate access role validation
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Affiliate') {
    header("Location: ../login.php");
    exit;
}

// Set active page flag so the sidebar highlights "Commissions" correctly
$activePage = 'commissions'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Commissions - Syntrix</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    body { background-color: #030b1e; color: #ffffff; font-family: sans-serif; }
    .card-dark { background-color: #081229; border: 1px solid #1e293b; border-radius: 12px; }
    
    /* Micro high-contrast mini analytics display sheets */
    .metric-card-white {
      background-color: #ffffff;
      color: #0f172a;
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    /* High-contrast dark ledger tables */
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
          <h1 class="h3 fw-bold mb-0 text-white">Commission Earnings</h1>
          <p class="text-muted small mb-0">Review real-time balance metrics, structural pair bonuses, and ledger distribution histories.</p>
        </div>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <div class="metric-card-white">
            <span class="text-muted d-block small fw-bold text-uppercase tracking-wider mb-1">Total Accumulated</span>
            <h2 class="fw-bold mb-0">$5,175.00</h2>
          </div>
        </div>
        <div class="col-md-4">
          <div class="metric-card-white">
            <span class="text-muted d-block small fw-bold text-uppercase tracking-wider mb-1">Withdrawn to Date</span>
            <h2 class="fw-bold mb-0">$1,725.00</h2>
          </div>
        </div>
        <div class="col-md-4">
          <div class="metric-card-white">
            <span class="text-brand-green d-block small fw-bold text-uppercase tracking-wider mb-1" style="color: #10b981;">Available E-Wallet</span>
            <h2 class="fw-bold mb-0" style="color: #10b981;">$3,450.00</h2>
          </div>
        </div>
      </div>

      <div class="card-dark p-4 shadow-lg">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold mb-0 text-white-50"><i class="bi bi-cash-stack me-2 text-success"></i>Earnings Distribution Log</h5>
          <span class="badge bg-dark border border-secondary text-light px-3 py-1.5 small">Prototype Mock Records</span>
        </div>

        <div class="table-responsive">
          <table class="table table-custom table-hover mb-0">
            <thead>
              <tr>
                <th scope="col" class="py-3 px-3">Transaction ID</th>
                <th scope="col" class="py-3">Bonus Category</th>
                <th scope="col" class="py-3">Source Origin node</th>
                <th scope="col" class="py-3">Gross Amount</th>
                <th scope="col" class="py-3">Settlement Date</th>
                <th scope="col" class="py-3 text-end px-3">Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="px-3 font-monospace text-muted">TXN-99831</td>
                <td>
                  <div class="fw-bold">Binary Pairing Match</div>
                  <span class="text-muted-custom small text-info" style="font-size: 0.7rem;">Left/Right Volume Balance Trigger</span>
                </td>
                <td><span class="text-light small">System Network Engine</span></td>
                <td class="fw-bold text-success">+$1,725.00</td>
                <td class="text-muted small">2026-06-28</td>
                <td class="text-end px-3">
                  <span class="badge bg-success-subtle text-success border border-success px-2 py-1" style="font-size: 0.7rem;">CREDITED</span>
                </td>
              </tr>
              
              <tr>
                <td class="px-3 font-monospace text-muted">TXN-99412</td>
                <td>
                  <div class="fw-bold">Direct Recruitment Reward</div>
                  <span class="text-muted-custom small text-primary" style="font-size: 0.7rem;">Sponsorship: Alex Mercer</span>
                </td>
                <td><span class="text-light small">User ID: STX-00241</span></td>
                <td class="fw-bold text-success">+$150.00</td>
                <td class="text-muted small">2026-06-14</td>
                <td class="text-end px-3">
                  <span class="badge bg-success-subtle text-success border border-success px-2 py-1" style="font-size: 0.7rem;">CREDITED</span>
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