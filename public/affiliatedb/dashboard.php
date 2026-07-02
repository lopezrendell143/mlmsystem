<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Affiliate') {
    header("Location: ../login.php"); 
    exit;
}

// 1. DATABASE CONNECTIVITY MAPPER
require_once __DIR__ . '/../../config/database.php';

$userId = $_SESSION['user_id'] ?? 0;

// --- LIVE AFFILIATE AGGREGATIONS ENGINE ---
try {
    // A. Total Active Downline Count (Users recruited down the line)
    $downlineSql = "SELECT COUNT(*) as total_downline FROM users WHERE referrer_id = :user_id OR referrer_id IN (SELECT id FROM users WHERE referrer_id = :user_id2)";
    $downlineStmt = $pdo->prepare($downlineSql);
    $downlineStmt->execute(['user_id' => $userId, 'user_id2' => $userId]);
    $totalDownline = $downlineStmt->fetch(PDO::FETCH_ASSOC)['total_downline'] ?? 0;

    // B. Direct Recruits Count (Users with this affiliate's ID directly as referrer)
    $directSql = "SELECT COUNT(*) as direct_recruits FROM users WHERE referrer_id = :user_id";
    $directStmt = $pdo->prepare($directSql);
    $directStmt->execute(['user_id' => $userId]);
    $directRecruits = $directStmt->fetch(PDO::FETCH_ASSOC)['direct_recruits'] ?? 0;

    // C. E-Wallet Balance and Network Group Volumes
    // Assuming your schema holds transactional balance states inside your commissions/wallet architecture
    $walletSql = "SELECT 
                    COALESCE(ewallet_balance, 0.00) as ewallet,
                    COALESCE(total_gv, 0) as gv,
                    COALESCE(matched_leg_volume, 0) as matched_volume,
                    COALESCE(estimated_match_bonus, 0.00) as match_bonus,
                    COALESCE(rank_qualification, 100) as rank_percentage
                  FROM affiliate_metrics WHERE user_id = :user_id LIMIT 1";
    $walletStmt = $pdo->prepare($walletSql);
    $walletStmt->execute(['user_id' => $userId]);
    $metrics = $walletStmt->fetch(PDO::FETCH_ASSOC);

    // Fallbacks if no metric line item exists yet for a new affiliate node
    $ewalletBalance = $metrics['ewallet'] ?? 0.00;
    $totalGV = $metrics['gv'] ?? 0;
    $matchedLegVolume = $metrics['matched_volume'] ?? 0;
    $estimatedMatchBonus = $metrics['match_bonus'] ?? 0.00;
    $rankQualification = $metrics['rank_percentage'] ?? 0;

} catch (PDOException $e) {
    // High safety fallback mesh parameters
    $totalDownline = 0;
    $directRecruits = 0;
    $ewalletBalance = 0.00;
    $totalGV = 0;
    $matchedLegVolume = 0;
    $estimatedMatchBonus = 0.00;
    $rankQualification = 0;
}

$activePage = 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Affiliate Dashboard - Syntrix</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background-color: #030b1e; color: #ffffff; font-family: sans-serif; }
    .card-metric { background-color: #ffffff; color: #0f172a; border-radius: 12px; border: none; }
    .card-dark { background-color: #081229; border: 1px solid #1e293b; border-radius: 12px; }
    .tree-box-canvas { background-color: #0d1e3d; border: 1px dashed #334155; border-radius: 12px; min-height: 300px; }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    
    <!-- PLACED HERE: Dynamic Sidebar Component Injection -->
    <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

    <!-- Main Live Content Workplane Area -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      
      <!-- Dashboard Top Summary Header -->
      <div class="row g-3 mb-4">
        <div class="col-lg-7">
          <div class="card-dark p-4 h-100">
            <h5 class="fw-bold mb-3 text-white">Network Performance Summary</h5>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-secondary"><i class="bi bi-people-fill me-2"></i>Total Active Downline</span>
              <span class="fw-bold text-white"><?php echo $totalDownline; ?></span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-secondary"><i class="bi bi-person-plus-fill me-2"></i>Direct Recruits</span>
              <span class="fw-bold text-white"><?php echo $directRecruits; ?></span>
            </div>
            <div class="d-flex justify-content-between">
              <span class="text-secondary"><i class="bi bi-patch-check me-2"></i>Rank Qualification %</span>
              <span class="fw-bold text-success"><?php echo $rankQualification; ?>%</span>
            </div>
          </div>
        </div>

        <div class="col-lg-5">
          <div class="card-dark p-4 h-100">
            <h5 class="fw-bold mb-3 text-white">Critical Next Steps</h5>
            <ul class="list-unstyled mb-0 lh-lg text-secondary">
              <li><i class="bi bi-check2-circle text-success me-2"></i>Complete Rank Maintenance PV</li>
              <li><i class="bi bi-check2-circle text-success me-2"></i>Review Commission Qualification</li>
              <li><i class="bi bi-check2-circle text-success me-2"></i>Review Genealogy Balancing</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Financial Statistics Metrics Row (Updated to Live PHP Peso Values) -->
      <div class="row g-3 mb-4">
        <div class="col-md-3">
          <div class="card card-metric p-3 shadow-sm">
            <small class="text-muted text-uppercase tracking-wider fw-bold">Total GV (Group Volume)</small>
            <h4 class="fw-bold mt-1 mb-0">₱<?php echo number_format($totalGV); ?></h4>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card card-metric p-3 shadow-sm">
            <small class="text-muted text-uppercase tracking-wider fw-bold">Matched Leg Volume</small>
            <h4 class="fw-bold mt-1 mb-0">₱<?php echo number_format($matchedLegVolume); ?></h4>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card card-metric p-3 shadow-sm">
            <small class="text-muted text-uppercase tracking-wider fw-bold">E-Wallet Balance</small>
            <h4 class="fw-bold mt-1 mb-0 text-success">₱<?php echo number_format($ewalletBalance, 2); ?></h4>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card card-metric p-3 shadow-sm">
            <small class="text-muted text-uppercase tracking-wider fw-bold">Estimated Match Bonus</small>
            <h4 class="fw-bold mt-1 mb-0">₱<?php echo number_format($estimatedMatchBonus, 2); ?></h4>
          </div>
        </div>
      </div>

      <!-- Live Genealogy Canvas Layout Frame -->
      <div class="row">
        <div class="col-12">
          <div class="p-4 shadow-sm card-dark rounded-3">
            <h5 class="fw-bold text-white mb-3">My Genealogy & Volumes</h5>
            <div class="tree-box-canvas d-flex align-items-center justify-content-center text-secondary text-center p-5">
              <div>
                <p class="mb-2 text-white-50">Hierarchical Binary Tree Renderer Engine Workspace Active</p>
                <small class="text-muted d-block">[Binary Placement Structure Leg Maps Online]</small>
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