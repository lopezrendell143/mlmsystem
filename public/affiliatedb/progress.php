<?php
session_start();

// Guard: Force affiliate access role validation
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Affiliate') {
    header("Location: ../login.php");
    exit;
}

// Set active page flag so the sidebar highlights "Rank Progress" correctly
$activePage = 'rank'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rank Progress - Syntrix</title>
  <!-- Bootstrap 5 & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    body { background-color: #030b1e; color: #ffffff; font-family: sans-serif; }
    .card-dark { background-color: #081229; border: 1px solid #1e293b; border-radius: 12px; }
    
    /* Progress bar overrides for a high-tech glowing look */
    .progress-custom-bg { background-color: #0f172a; border: 1px solid #1e293b; height: 16px; border-radius: 8px; }
    .progress-bar-glow { background: linear-gradient(90deg, #10b981, #5ce65c); box-shadow: 0 0 8px rgba(92, 230, 92, 0.4); }

    /* Milestone node badges */
    .milestone-badge { background-color: #0f172a; border: 1px solid #334155; border-radius: 8px; padding: 1rem; }
    .milestone-badge.achieved { border-color: #10b981; background: linear-gradient(135deg, #081229, rgba(16, 185, 129, 0.05)); }
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
          <h1 class="h3 fw-bold mb-0 text-white">Leadership Rank Advancements</h1>
          <p class="text-muted small mb-0">Audit your matrix rank status, track ongoing volume qualifications, and review milestone criteria.</p>
        </div>
      </div>

      <!-- Current Rank Tracker Header Panel -->
      <div class="card-dark p-4 mb-4 shadow-lg">
        <div class="row align-items-center g-3">
          <div class="col-md-6">
            <span class="text-muted small d-block uppercase font-monospace mb-1">Current Active Standing</span>
            <div class="d-flex align-items-center gap-3">
              <h2 class="fw-bold mb-0 text-warning">Bronze Leader</h2>
              <span class="badge bg-success-subtle text-success border border-success py-1.5 px-3 small">Active & Qualified</span>
            </div>
          </div>
          <div class="col-md-6 text-md-end">
            <span class="text-muted small d-block uppercase font-monospace mb-1">Next target tier</span>
            <h4 class="fw-bold mb-0 text-info"><i class="bi bi-gem me-2"></i>Silver Executive</h4>
          </div>
        </div>

        <hr class="border-secondary my-4">

        <!-- Visual Rank Completion Meter -->
        <div class="mb-2 d-flex justify-content-between align-items-center small">
          <span class="text-white-50 fw-semibold">Overall Milestone Completion</span>
          <span class="text-brand-green fw-bold" style="color: #5ce65c;">93% Achieved</span>
        </div>
        <div class="progress progress-custom-bg mb-2">
          <div class="progress-bar progress-bar-glow" role="progressbar" style="width: 93%;" aria-valuenow="93" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <p class="text-muted mb-0" style="font-size: 0.75rem;">Keep accumulating group leg volumes to unlock increased match bonus ratios.</p>
      </div>

      <!-- Rank Qualifications Breakdown Grid -->
      <h5 class="fw-bold mb-3 text-white-50"><i class="bi bi-list-task me-2 text-success"></i>Qualification Matrix Checklist</h5>
      <div class="row g-4">
        
        <!-- Requirement Block 1: PV -->
        <div class="col-md-4">
          <div class="card-dark p-4 shadow-lg h-100 d-flex flex-column justify-content-between">
            <div>
              <div class="d-flex justify-content-between align-items-start mb-3">
                <h6 class="fw-bold text-white mb-0">Personal Volume (PV)</h6>
                <i class="bi bi-check-circle-fill text-success fs-5"></i>
              </div>
              <p class="text-muted small">Maintain monthly personal product points to stay active in commission processing queues.</p>
            </div>
            <div>
              <div class="d-flex justify-content-between text-white font-monospace small mt-2">
                <span>Current: 180 PV</span>
                <span class="text-muted">Req: 100 PV</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Requirement Block 2: Downline Directs -->
        <div class="col-md-4">
          <div class="card-dark p-4 shadow-lg h-100 d-flex flex-column justify-content-between">
            <div>
              <div class="d-flex justify-content-between align-items-start mb-3">
                <h6 class="fw-bold text-white mb-0">Active Frontlines</h6>
                <i class="bi bi-dash-circle-fill text-warning fs-5"></i>
              </div>
              <p class="text-muted small">The minimum number of directly sponsored active accounts required globally within your network tree.</p>
            </div>
            <div>
              <div class="d-flex justify-content-between text-white font-monospace small mt-2">
                <span>Current: 1 User</span>
                <span class="text-warning">Req: 2 Users</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Requirement Block 3: Group Volume Balance -->
        <div class="col-md-4">
          <div class="card-dark p-4 shadow-lg h-100 d-flex flex-column justify-content-between">
            <div>
              <div class="d-flex justify-content-between align-items-start mb-3">
                <h6 class="fw-bold text-white mb-0">Leg Group Volume (GV)</h6>
                <i class="bi bi-check-circle-fill text-success fs-5"></i>
              </div>
              <p class="text-muted small">Accumulated binary sales metrics collected inside your organizational group structure.</p>
            </div>
            <div>
              <div class="d-flex justify-content-between text-white font-monospace small mt-2">
                <span>Current: $12.8K</span>
                <span class="text-muted">Req: $10.0K</span>
              </div>
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