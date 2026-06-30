<?php
session_start();

// Guard: Force affiliate access role validation
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Affiliate') {
    header("Location: ../login.php");
    exit;
}

// Set active page flag so the sidebar highlights "Support Ticket" correctly
$activePage = 'support'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Support Tickets - Syntrix</title>
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
      border-color: #5ce65c;
      color: #ffffff;
      box-shadow: 0 0 0 0.25rem rgba(92, 230, 92, 0.15);
    }
    
    /* High-contrast table configurations matching image_03fe8a.jpg templates */
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

    <!-- Main Content Workplane -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      
      <!-- Top Title Bar Headers -->
      <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-secondary">
        <div>
          <h1 class="h3 fw-bold mb-0 text-white">Helpdesk & Support Matrix</h1>
          <p class="text-muted small mb-0">Open technical help requests, submit priority bug notices, and review historical resolutions.</p>
        </div>
      </div>

      <div class="row g-4">
        <!-- Left Column: Interactive Ticket Generator Terminal -->
        <div class="col-xl-5">
          <div class="card-dark p-4 shadow-lg">
            <h5 class="fw-bold mb-3 text-white-50"><i class="bi bi-pencil-square me-2 text-success"></i>Create New Ticket</h5>
            
            <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Helpdesk node instance successfully queued for review!');">
              <div class="mb-3">
                <label class="form-label small text-muted fw-bold">Incident Subject / Title</label>
                <input type="text" class="form-control form-control-dark py-2" placeholder="Brief summary of issue..." required>
              </div>

              <div class="mb-3">
                <label class="form-label small text-muted fw-bold">Department Routing</label>
                <select class="form-select form-control-dark py-2">
                  <option value="billing">Payout & Commission Discrepancies</option>
                  <option value="genealogy">Genealogy & Placement Structure</option>
                  <option value="technical">Technical Bugs & Profile Access</option>
                </select>
              </div>

              <div class="mb-4">
                <label class="form-label small text-muted fw-bold">Detailed Description</label>
                <textarea class="form-control form-control-dark" rows="5" placeholder="Provide system environment logs or background details..." required></textarea>
              </div>

              <button type="submit" class="btn btn-brand-green w-100 py-2 text-dark fw-bold uppercase">
                Dispatch Ticket Node
              </button>
            </form>
          </div>
        </div>

        <!-- Right Column: Live Interactive Audit Log -->
        <div class="col-xl-7">
          <div class="card-dark p-4 shadow-lg h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="fw-bold mb-0 text-white-50"><i class="bi bi-ticket-perforated-fill me-2 text-success"></i>Open Incident Log</h5>
              <span class="badge bg-dark border border-secondary text-muted px-2 py-1 small">Total: 1 Record</span>
            </div>

            <div class="table-responsive">
              <table class="table table-custom table-hover mb-0" style="font-size: 0.9rem;">
                <thead>
                  <tr>
                    <th scope="col" class="py-3 px-3">Ticket ID</th>
                    <th scope="col" class="py-3">Subject Matter</th>
                    <th scope="col" class="py-3">Priority</th>
                    <th scope="col" class="py-3">Last Updated</th>
                    <th scope="col" class="py-3 text-end px-3">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Row Node 1: Pending Ticket -->
                  <tr>
                    <td class="px-3 font-monospace text-muted">TCK-48195</td>
                    <td>
                      <div class="fw-bold">Binary volume synchronization lag</div>
                      <span class="text-muted small" style="font-size: 0.7rem;">Dept: Genealogy Network</span>
                    </td>
                    <td><span class="badge bg-danger-subtle text-danger border border-danger px-2 py-0.5" style="font-size: 0.65rem;">HIGH</span></td>
                    <td class="text-muted small">2026-06-30</td>
                    <td class="text-end px-3">
                      <span class="badge bg-warning-subtle text-warning border border-warning px-2 py-1" style="font-size: 0.65rem;">OPEN</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>