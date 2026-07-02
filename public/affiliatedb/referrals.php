<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Affiliate') {
    header("Location: ../login.php");
    exit;
}

// 1. DATABASE CONNECTIVITY MAPPER
require_once __DIR__ . '/../../config/database.php';

$userId = $_SESSION['user_id'] ?? 0;
$username = $_SESSION['username'] ?? 'user';

// FIXED: Clear path generation specifically built for local XAMPP architectures
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];

// Dynamically extracts only the web project subdirectory without the system files paths
$scriptName = $_SERVER['SCRIPT_NAME']; 
$projectPath = substr($scriptName, 0, strpos($scriptName, '/public/'));

$invitationLink = $protocol . $host . $projectPath . "/public/register.php?sponsor_id=" . urlencode($userId);

$referrals = [];

try {
    $sql = "SELECT u.id, u.username, u.full_name, u.email, u.placement, u.created_at,
                   am.rank_label, am.personal_volume
            FROM users u
            LEFT JOIN affiliate_metrics am ON u.id = am.user_id
            WHERE u.sponsor_id = :sponsor_id AND u.role = 'Affiliate'
            ORDER BY u.id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['sponsor_id' => $userId]);
    $referrals = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Graceful backup fallback handle
}

$activePage = 'referrals'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Direct Referrals Backoffice - Syntrix Corporate</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    body { background-color: #030b1e; color: #ffffff; font-family: sans-serif; }
    .card-dark { background-color: #081229; border: 1px solid #1e293b; border-radius: 12px; }
    
    .table-custom { color: #ffffff; border-color: #1e293b; vertical-align: middle; }
    .table-custom thead { background-color: #0f172a; color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .table-custom tbody tr { border-bottom: 1px solid #1e293b; background-color: transparent; transition: background 0.15s ease; }
    .table-custom tbody tr:hover { background-color: rgba(30, 41, 59, 0.3); }

    .link-display-field {
      background-color: #050d1e;
      border: 1px solid #1e293b;
      color: #38bdf8 !important;
      font-family: monospace;
      font-size: 0.8rem;
    }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    
    <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      
      <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-secondary" style="border-color: #1e293b !important;">
        <div>
          <h1 class="h3 fw-bold mb-0 text-white">Direct Referrals</h1>
          <p class="text-muted small mb-0">Track frontline acquisitions, manage invitation nodes, and monitor real-time generation volumes.</p>
        </div>
      </div>

      <div class="card-dark p-4 shadow-lg mb-4">
        <h5 class="fw-bold text-white mb-2" style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;"><i class="bi bi-link-45deg text-info me-2"></i>Personal Referral Allocation Token</h5>
        <p class="text-muted small mb-3">Distribute this secure authorization gateway link to ensure incoming enrollments route automatically to your node position.</p>
        
        <div class="input-group">
          <input type="text" class="form-control link-display-field py-2 px-3 text-white" id="invitationUrl" value="<?php echo htmlspecialchars($invitationLink); ?>" readonly>
          <button class="btn btn-info text-dark fw-bold px-4 small" type="button" onclick="copyInvitationLink()" id="copyBtn">
            <i class="bi bi-clipboard-check me-2"></i>Copy Link
          </button>
        </div>
      </div>

      <div class="card-dark p-4 shadow-lg">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold mb-0 text-white-50" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Frontline Network Node Summary</h5>
          <span class="badge bg-secondary font-monospace px-2 py-1" style="font-size: 0.7rem;"><?php echo count($referrals); ?> Active Nodes</span>
        </div>

        <div class="table-responsive">
          <table class="table table-custom mb-0">
            <thead>
              <tr>
                <th scope="col" class="py-3 px-3">Distributor ID</th>
                <th scope="col" class="py-3">Account Identity</th>
                <th scope="col" class="py-3">Email Matrix</th>
                <th scope="col" class="py-3">Matrix Placement</th>
                <th scope="col" class="py-3">Personal Vol</th>
                <th scope="col" class="py-3 text-end px-3">Enrollment Date</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($referrals)): ?>
                <tr>
                  <td colspan="6" class="text-center text-muted small py-4">No real-time frontend referral nodes detected along this leg configuration matrix.</td>
                </tr>
              <?php else: ?>
                <?php foreach ($referrals as $row): ?>
                  <tr>
                    <td class="px-3 font-monospace text-success fw-bold" style="font-size: 0.75rem;">
                      STX-<?php echo str_pad($row['id'], 5, '0', STR_PAD_LEFT); ?>
                    </td>
                    <td>
                      <div class="fw-bold text-white small"><?php echo htmlspecialchars($row['full_name'] ?? 'Incomplete Profile'); ?></div>
                      <span class="text-muted d-block" style="font-size: 0.65rem;">@<?php echo htmlspecialchars($row['username']); ?> • <?php echo htmlspecialchars($row['rank_label'] ?? 'Associate'); ?></span>
                    </td>
                    <td><span class="text-white-50 small"><?php echo htmlspecialchars($row['email']); ?></span></td>
                    <td>
                      <?php if (strcasecmp($row['placement'] ?? '', 'Left') === 0): ?>
                        <span class="badge px-2 py-1" style="font-size: 0.6rem; color: #38bdf8; background: rgba(56,189,248,0.1); border: 1px solid rgba(56,189,248,0.2);">LEFT LEG</span>
                      <?php else: ?>
                        <span class="badge px-2 py-1" style="font-size: 0.6rem; color: #c084fc; background: rgba(192,132,252,0.1); border: 1px solid rgba(192,132,252,0.2);">RIGHT LEG</span>
                      <?php endif; ?>
                    </td>
                    <td class="fw-bold text-info small"><?php echo number_format($row['personal_volume'] ?? 0); ?> PV</td>
                    <td class="text-muted text-end px-3 small"><?php echo date('Y-m-d', strtotime($row['created_at'])); ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function copyInvitationLink() {
      const copyText = document.getElementById("invitationUrl");
      copyText.select();
      copyText.setSelectionRange(0, 99999);
      navigator.clipboard.writeText(copyText.value);
      
      const copyBtn = document.getElementById("copyBtn");
      copyBtn.innerHTML = '<i class="bi bi-check-lg me-2"></i>Copied!';
      copyBtn.classList.remove('btn-info');
      copyBtn.classList.add('btn-success');
      
      setTimeout(() => {
          copyBtn.innerHTML = '<i class="bi bi-clipboard-check me-2"></i>Copy Link';
          copyBtn.classList.remove('btn-success');
          copyBtn.classList.add('btn-info');
      }, 2000);
  }
</script>
</body>
</html>