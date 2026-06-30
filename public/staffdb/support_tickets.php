<?php
session_start();

// Guard: Force back-office Staff role validation
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Staff') {
    header("Location: ../login.php");
    exit;
}

// Active page state for highlighting "Support Tickets" in your staff sidebar
$activePage = 'support_tickets';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Support Tickets - Syntrix</title>
  <!-- Bootstrap 5 & Icons -->
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
      border-color: #38bdf8;
      color: #ffffff;
      box-shadow: 0 0 0 0.25rem rgba(56, 189, 248, 0.15);
    }
    
    /* High-contrast admin tables */
    .table-custom { color: #ffffff; border-color: #1e293b; vertical-align: middle; }
    .table-custom thead { background-color: #0f172a; color: #94a3b8; }
    .table-custom tbody tr { border-bottom: 1px solid #1e293b; transition: background 0.15s; }
    .table-custom tbody tr:hover { background-color: rgba(30, 41, 59, 0.5); }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    
    <!-- Dynamic Sidebar Component Injection -->
    <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

    <!-- Main Live Content Workplane Area -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      
      <!-- Top Title Bar Headers -->
      <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-secondary">
        <div>
          <h1 class="h3 fw-bold text-white mb-0">Helpdesk Ticket Control</h1>
          <p class="text-muted small mb-0">Review affiliate incident logs, dispatch resolutions, and coordinate departmental responses.</p>
        </div>
        <span class="badge bg-info text-dark fw-bold px-3 py-2">14 Unresolved Incidents</span>
      </div>

      <!-- Live Interactive Filtering Controls -->
      <div class="card-dark p-3 mb-4 shadow-sm">
        <div class="row g-3 align-items-center">
          <div class="col-md-5">
            <div class="input-group">
              <span class="input-group-text bg-dark border-secondary text-muted"><i class="bi bi-search"></i></span>
              <input type="text" class="form-control form-control-dark" placeholder="Search ticket text, users, or reference IDs...">
            </div>
          </div>
          <div class="col-md-4">
            <select class="form-select form-control-dark">
              <option value="">All Departments</option>
              <option value="technical">Technical Helpdesk</option>
              <option value="genealogy">Genealogy & Tree Placement</option>
              <option value="billing">Payout & Financials</option>
            </select>
          </div>
          <div class="col-md-3">
            <button class="btn btn-info w-100 fw-bold text-dark" onclick="alert('Filtering records database queue...');">
              Apply Filters
            </button>
          </div>
        </div>
      </div>

      <!-- Master Ticket Queue Data Table -->
      <div class="card-dark p-4 shadow-lg">
        <div class="table-responsive">
          <table class="table table-custom table-hover mb-0" style="font-size: 0.9rem;">
            <thead>
              <tr>
                <th scope="col" class="py-3 px-3">Ticket ID</th>
                <th scope="col" class="py-3">Affiliate User</th>
                <th scope="col" class="py-3">Issue Context</th>
                <th scope="col" class="py-3">Priority</th>
                <th scope="col" class="py-3">Received</th>
                <th scope="col" class="py-3 text-end px-3">Actions</th>
              </tr>
            </thead>
            <tbody>
              <!-- Ticket Item 1 -->
              <tr>
                <td class="px-3 font-monospace text-info fw-bold">TCK-48195</td>
                <td>
                  <div class="fw-bold text-white">Rendell Admin</div>
                  <small class="text-muted font-monospace" style="font-size: 0.75rem;">ID: #1024</small>
                </td>
                <td>
                  <div class="fw-semibold">Binary volume synchronization lag</div>
                  <span class="text-muted small" style="font-size: 0.7rem;">Dept: Genealogy Network</span>
                </td>
                <td><span class="badge bg-danger-subtle text-danger border border-danger px-2 py-0.5" style="font-size: 0.65rem;">HIGH</span></td>
                <td class="text-muted small">2026-06-30</td>
                <td class="text-end px-3">
                  <button class="btn btn-sm btn-info text-dark font-monospace fw-bold me-1" style="font-size: 0.75rem;" onclick="alert('Opening modal workspace interface for TCK-48195...');">REPLY</button>
                  <button class="btn btn-sm btn-outline-success font-monospace" style="font-size: 0.75rem;" onclick="alert('Marking ticket as resolved...');">CLOSE</button>
                </td>
              </tr>
              <!-- Ticket Item 2 -->
              <tr>
                <td class="px-3 font-monospace text-info fw-bold">TCK-48201</td>
                <td>
                  <div class="fw-bold text-white">Sarah Connor</div>
                  <small class="text-muted font-monospace" style="font-size: 0.75rem;">ID: #1088</small>
                </td>
                <td>
                  <div class="fw-semibold">USDT TRC-20 cashout address entry mismatch</div>
                  <span class="text-muted small" style="font-size: 0.7rem;">Dept: Payout & Financials</span>
                </td>
                <td><span class="badge bg-warning-subtle text-warning border border-warning px-2 py-0.5" style="font-size: 0.65rem;">MEDIUM</span></td>
                <td class="text-muted small">2026-06-29</td>
                <td class="text-end px-3">
                  <button class="btn btn-sm btn-info text-dark font-monospace fw-bold me-1" style="font-size: 0.75rem;" onclick="alert('Opening modal workspace interface for TCK-48201...');">REPLY</button>
                  <button class="btn btn-sm btn-outline-success font-monospace" style="font-size: 0.75rem;" onclick="alert('Marking ticket as resolved...');">CLOSE</button>
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