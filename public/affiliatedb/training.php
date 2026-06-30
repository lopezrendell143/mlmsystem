<?php
session_start();

// Guard: Force affiliate access role validation
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Affiliate') {
    header("Location: ../login.php");
    exit;
}

// Set active page flag so the sidebar highlights "Training Desk" correctly
$activePage = 'training'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Training Desk - Syntrix</title>
  <!-- Bootstrap 5 & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    body { background-color: #030b1e; color: #ffffff; font-family: sans-serif; }
    .card-dark { background-color: #081229; border: 1px solid #1e293b; border-radius: 12px; }
    
    /* Video thumbnail dummy box */
    .video-thumbnail-placeholder {
      background: linear-gradient(135deg, #0f172a, #1e293b);
      border: 1px solid #334155;
      border-radius: 8px;
      height: 160px;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }
    .video-thumbnail-placeholder .bi-play-btn-fill {
      font-size: 3rem;
      color: #10b981;
      transition: transform 0.2s, color 0.2s;
      z-index: 2;
    }
    .card-dark:hover .video-thumbnail-placeholder .bi-play-btn-fill {
      transform: scale(1.1);
      color: #5ce65c;
    }
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
          <h1 class="h3 fw-bold mb-0 text-white">Academy & Training Desk</h1>
          <p class="text-muted small mb-0">Access step-by-step video masterclasses and operational system guides below.</p>
        </div>
      </div>

      <!-- Main Video Content Grid -->
      <h5 class="fw-bold mb-3 text-white-50"><i class="bi bi-collection-play me-2 text-success"></i>Video Knowledge Base</h5>
      <div class="row g-4 mb-5">
        
        <!-- Video 1 -->
        <div class="col-md-4">
          <div class="card-dark p-3 shadow-lg h-100 d-flex flex-column justify-content-between" style="cursor: pointer;" onclick="alert('Launching system overview presentation module...');">
            <div>
              <div class="video-thumbnail-placeholder mb-3">
                <i class="bi bi-play-btn-fill"></i>
                <div class="position-absolute bottom-0 end-0 m-2 badge bg-dark opacity-75 small">12:40</div>
              </div>
              <h6 class="fw-bold text-white mb-2">Syntrix System Architecture Overview</h6>
              <p class="text-muted small mb-0">Learn how navigation workflows, node security, and your user roles coordinate seamlessly.</p>
            </div>
            <div class="mt-3 text-brand-green small fw-semibold" style="color: #5ce65c;">Watch Lesson <i class="bi bi-arrow-right ms-1"></i></div>
          </div>
        </div>

        <!-- Video 2 -->
        <div class="col-md-4">
          <div class="card-dark p-3 shadow-lg h-100 d-flex flex-column justify-content-between" style="cursor: pointer;" onclick="alert('Launching network configuration tutorial...');">
            <div>
              <div class="video-thumbnail-placeholder mb-3">
                <i class="bi bi-play-btn-fill"></i>
                <div class="position-absolute bottom-0 end-0 m-2 badge bg-dark opacity-75 small">18:15</div>
              </div>
              <h6 class="fw-bold text-white mb-2">Mastering Binary Team Placements</h6>
              <p class="text-muted small mb-0">An in-depth guide regarding left/right legs, balance thresholds, and maximizing match structures.</p>
            </div>
            <div class="mt-3 text-brand-green small fw-semibold" style="color: #5ce65c;">Watch Lesson <i class="bi bi-arrow-right ms-1"></i></div>
          </div>
        </div>

        <!-- Video 3 -->
        <div class="col-md-4">
          <div class="card-dark p-3 shadow-lg h-100 d-flex flex-column justify-content-between" style="cursor: pointer;" onclick="alert('Launching payout verification workshop...');">
            <div>
              <div class="video-thumbnail-placeholder mb-3">
                <i class="bi bi-play-btn-fill"></i>
                <div class="position-absolute bottom-0 end-0 m-2 badge bg-dark opacity-75 small">09:30</div>
              </div>
              <h6 class="fw-bold text-white mb-2">E-Wallet Transactions & Cashout Paths</h6>
              <p class="text-muted small mb-0">Understand withdrawal configuration requests, validation statuses, and administrative processing timelines.</p>
            </div>
            <div class="mt-3 text-brand-green small fw-semibold" style="color: #5ce65c;">Watch Lesson <i class="bi bi-arrow-right ms-1"></i></div>
          </div>
        </div>

      </div>

      <!-- PDF / Document Download Row -->
      <h5 class="fw-bold mb-3 text-white-50"><i class="bi bi-file-earmark-pdf me-2 text-success"></i>Downloadable Document Center</h5>
      <div class="card-dark p-4 shadow-lg">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
          <div class="d-flex align-items-center gap-3">
            <i class="bi bi-file-earmark-zip-fill text-danger fs-2"></i>
            <div>
              <h6 class="fw-bold mb-1 text-white">Complete Digital Marketing Starter Kit</h6>
              <p class="text-muted small mb-0">Includes full PDF specifications, system guide rules, and legal compliance matrix summaries.</p>
            </div>
          </div>
          <button class="btn btn-sm btn-outline-light text-white px-4" onclick="alert('Asset archival package download started...');">
            <i class="bi bi-download me-2"></i>Download .ZIP (14.2 MB)
          </button>
        </div>
      </div>

    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>