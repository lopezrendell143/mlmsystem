<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Affiliate') {
    header("Location: ../login.php");
    exit;
}

// 1. DATABASE CONNECTIVITY MAPPER
require_once __DIR__ . '/../../config/database.php';

$userId = $_SESSION['user_id'] ?? 0;
$userProfile = null;

try {
    $sql = "SELECT u.id, u.username, u.email, u.full_name, u.created_at,
                   am.rank_label, am.personal_volume, am.group_volume
            FROM users u
            LEFT JOIN affiliate_metrics am ON u.id = am.user_id
            WHERE u.id = :id LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $userId]);
    $userProfile = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Graceful error fallback state
}

if (!$userProfile) {
    $userProfile = [
        'id' => $userId,
        'username' => $_SESSION['username'] ?? 'User',
        'email' => '',
        'full_name' => 'Distributor Account',
        'created_at' => date('Y-m-d H:i:s'),
        'rank_label' => 'Associate',
        'personal_volume' => 0,
        'group_volume' => 0
    ];
}

$activePage = 'profile'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Distributor Profile - Syntrix Corporate</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    body { background-color: #030b1e; color: #ffffff; font-family: sans-serif; }
    .card-dark { background-color: #081229; border: 1px solid #1e293b; border-radius: 12px; }
    
    .form-control-dark {
      background-color: #0f172a;
      border: 1px solid #334155;
      color: #ffffff;
      padding: 0.6rem 0.75rem;
    }
    .form-control-dark:focus {
      background-color: #0f172a;
      border-color: #10b981;
      color: #ffffff;
      box-shadow: none;
    }
    .form-control-dark:disabled {
      background-color: #050d1e;
      border-color: #1e293b;
      color: #64748b;
    }
    
    .profile-avatar-large {
      width: 85px; height: 85px;
      border-radius: 50%;
      background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(16, 185, 129, 0.1));
      border: 2px solid #10b981;
      display: flex; align-items: center; justify-content: center;
      font-size: 2rem; font-weight: bold; color: #10b981;
    }
    .metric-panel { background-color: #050d1e; border: 1px solid #1e293b; border-radius: 8px; padding: 10px; text-align: center; }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    
    <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      
      <?php if (isset($_GET['status']) && $_GET['status'] === 'updated'): ?>
        <div class="alert alert-success bg-success text-white border-0 alert-dismissible fade show small py-2 mb-3 shadow" role="alert">
          <i class="bi bi-check-circle-fill me-2"></i> Security profile settings synchronized and updated successfully.
          <button type="button" class="btn-close btn-close-white small" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php elseif (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
        <div class="alert alert-danger bg-danger text-white border-0 alert-dismissible fade show small py-2 mb-3 shadow" role="alert">
          <i class="bi bi-exclamation-triangle-fill me-2"></i> Profile update failed. Check inputs or verify system database integrity.
          <button type="button" class="btn-close btn-close-white small" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-secondary" style="border-color: #1e293b !important;">
        <div>
          <h1 class="h3 fw-bold mb-0 text-white">Distributor Settings</h1>
          <p class="text-muted small mb-0">Manage security permissions, verify organizational performance volume, and modify identity information.</p>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-lg-4">
          <div class="card-dark p-4 shadow-lg h-100 d-flex flex-column align-items-center justify-content-start">
            <div class="profile-avatar-large mb-3">
              <?php echo strtoupper(substr($userProfile['username'] ?? 'U', 0, 1)); ?>
            </div>
            <h4 class="fw-bold mb-1 text-white text-center"><?php echo htmlspecialchars($userProfile['full_name']); ?></h4>
            <div class="text-muted mb-3" style="font-size: 0.8rem;">@<?php echo htmlspecialchars($userProfile['username']); ?></div>
            
            <span class="badge bg-gradient px-3 py-2 fw-bold mb-4" style="font-size: 0.65rem; letter-spacing: 0.5px; text-transform: uppercase; border: 1px solid #10b981; color: #10b981; background: rgba(16,185,129,0.1);">
              <?php echo htmlspecialchars($userProfile['rank_label'] ?? 'Associate'); ?>
            </span>
            
            <div class="row w-100 g-2 mb-4">
              <div class="col-6">
                <div class="metric-panel">
                  <div class="text-muted text-uppercase" style="font-size: 0.55rem; font-weight:700;">Personal Vol</div>
                  <div class="text-white fw-bold h6 mb-0 mt-1"><?php echo number_format($userProfile['personal_volume'] ?? 0); ?></div>
                </div>
              </div>
              <div class="col-6">
                <div class="metric-panel">
                  <div class="text-muted text-uppercase" style="font-size: 0.55rem; font-weight:700;">Group Vol</div>
                  <div class="text-white fw-bold h6 mb-0 mt-1"><?php echo number_format($userProfile['group_volume'] ?? 0); ?></div>
                </div>
              </div>
            </div>
            
            <hr class="w-100 border-secondary my-2" style="border-color: #1e293b !important;">
            
            <div class="w-100 text-start small text-muted">
              <div class="d-flex justify-content-between py-2 border-bottom border-secondary" style="border-color: #0f172a !important;">
                <span style="font-size: 0.7rem;">Distributor Record ID:</span>
                <span class="text-light fw-mono font-monospace" style="font-size: 0.75rem;">STX-<?php echo str_pad($userProfile['id'], 5, '0', STR_PAD_LEFT); ?></span>
              </div>
              <div class="d-flex justify-content-between py-2 border-bottom border-secondary" style="border-color: #0f172a !important;">
                <span style="font-size: 0.7rem;">Placement Matrix Focus:</span>
                <span class="text-info" style="font-size: 0.7rem; font-weight: 600;">Dual Balanced</span>
              </div>
              <div class="d-flex justify-content-between py-2">
                <span style="font-size: 0.7rem;">Registration Date:</span>
                <span class="text-light" style="font-size: 0.7rem;"><?php echo date('M d, Y', strtotime($userProfile['created_at'])); ?></span>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-8">
          <div class="card-dark p-4 shadow-lg">
            <h5 class="fw-bold mb-4 text-white-50" style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;"><i class="bi bi-shield-lock me-2 text-success"></i>Identity & Encryption Settings</h5>
            
            <form action="process_profile.php" method="POST">
              <div class="mb-3">
                <label class="form-label small text-muted fw-bold" style="font-size: 0.65rem; text-transform: uppercase;">Full Name</label>
                <input type="text" name="full_name" class="form-control form-control-dark text-white" value="<?php echo htmlspecialchars($userProfile['full_name'] ?? ''); ?>" required>
              </div>

              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label class="form-label small text-muted fw-bold" style="font-size: 0.65rem; text-transform: uppercase;">System Username</label>
                  <input type="text" class="form-control form-control-dark text-white" value="<?php echo htmlspecialchars($userProfile['username'] ?? ''); ?>" readonly disabled>
                </div>
                <div class="col-md-6">
                  <label class="form-label small text-muted fw-bold" style="font-size: 0.65rem; text-transform: uppercase;">Registered Email Matrix Address</label>
                  <input type="email" class="form-control form-control-dark text-white" value="<?php echo htmlspecialchars($userProfile['email'] ?? ''); ?>" readonly disabled>
                </div>
              </div>

              <div class="row g-3 mb-4">
                <div class="col-md-6">
                  <label class="form-label small text-muted fw-bold" style="font-size: 0.65rem; text-transform: uppercase;">Modify Access Password</label>
                  <input type="password" name="new_password" class="form-control form-control-dark" placeholder="Leave blank to maintain original">
                </div>
                <div class="col-md-6">
                  <label class="form-label small text-muted fw-bold" style="font-size: 0.65rem; text-transform: uppercase;">Confirm Access Password</label>
                  <input type="password" name="confirm_password" class="form-control form-control-dark" placeholder="Leave blank to maintain original">
                </div>
              </div>

              <div class="d-flex justify-content-end gap-2">
                <button type="reset" class="btn btn-sm btn-outline-secondary text-white px-4">Clear Form</button>
                <button type="submit" class="btn btn-sm btn-success px-4" style="background-color: #10b981; border-color: #10b981;">Commit Changes</button>
              </div>
            </form>
          </div>
        </div>
      </div>

    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  if (window.history.replaceState) {
      const url = new URL(window.location.href);
      url.searchParams.delete('status');
      window.history.replaceState({ path: url.href }, '', url.href);
  }
</script>
</body>
</html>