<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Affiliate') {
    header("Location: ../login.php");
    exit;
}

// 1. DATABASE CONNECTIVITY MAPPER
require_once __DIR__ . '/../../config/database.php';

$userId = $_SESSION['user_id'] ?? 0;

/**
 * Helper function to safely fetch user node downline details using 'full_name'
 */
/**
 * Helper function to safely fetch user node downline details from the users table
 */
function fetchTreeNode($pdo, $nodeId) {
    if (!$nodeId) return null;
    try {
        // FIXED: Removed the broken LEFT JOIN to affiliate_metrics
        $sql = "SELECT id, username, full_name, role
                FROM users 
                WHERE id = :id LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $nodeId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    } catch (PDOException $e) {
        return null;
    }
}

// --- FETCH ALL UNPLACED ACCOUNTS FROM DATABASE ---
$availableUsers = [];
try {
    // Selects ALL records that don't have a placement record yet in your network_tree table
    $placementSql = "SELECT id, username, full_name, role FROM users 
                     WHERE id NOT IN (SELECT DISTINCT user_id FROM network_tree WHERE user_id IS NOT NULL)
                     AND id != :root_id AND role = 'Affiliate'";
    $placementStmt = $pdo->prepare($placementSql);
    $placementStmt->execute(['root_id' => $userId]);
    $availableUsers = $placementStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $availableUsers = [];
}

// --- DYNAMIC LIVE BINARY MATRIX TRAVERSAL ENGINE ---
try {
    // Tier 1: Core Session Root
    $rootNode = fetchTreeNode($pdo, $userId);

    // Tier 2: Directly under Root Node
    $treeMapSql = "SELECT user_id, position FROM network_tree WHERE parent_id = :user_id";
    $treeMapStmt = $pdo->prepare($treeMapSql);
    $treeMapStmt->execute(['user_id' => $userId]);
    $children = $treeMapStmt->fetchAll(PDO::FETCH_ASSOC);

    $leftChildId  = null;
    $rightChildId = null;
    foreach ($children as $child) {
        if (strcasecmp($child['position'], 'Left') === 0)  $leftChildId  = $child['user_id'];
        if (strcasecmp($child['position'], 'Right') === 0) $rightChildId = $child['user_id'];
    }

    $leftNode  = fetchTreeNode($pdo, $leftChildId);
    $rightNode = fetchTreeNode($pdo, $rightChildId);

    // Tier 3: Sub-Downline Legs (Left-Left, Left-Right, Right-Left, Right-Right)
    $llNode = $lrNode = $rlNode = $rrNode = null;

    if ($leftChildId) {
        $stmt = $pdo->prepare("SELECT user_id, position FROM network_tree WHERE parent_id = :id");
        $stmt->execute(['id' => $leftChildId]);
        $subChildren = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($subChildren as $sub) {
            if (strcasecmp($sub['position'], 'Left') === 0)  $llNode = fetchTreeNode($pdo, $sub['user_id']);
            if (strcasecmp($sub['position'], 'Right') === 0) $lrNode = fetchTreeNode($pdo, $sub['user_id']);
        }
    }

    if ($rightChildId) {
        $stmt = $pdo->prepare("SELECT user_id, position FROM network_tree WHERE parent_id = :id");
        $stmt->execute(['id' => $rightChildId]);
        $subChildren = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($subChildren as $sub) {
            if (strcasecmp($sub['position'], 'Left') === 0)  $rlNode = fetchTreeNode($pdo, $sub['user_id']);
            if (strcasecmp($sub['position'], 'Right') === 0) $rrNode = fetchTreeNode($pdo, $sub['user_id']);
        }
    }

} catch (PDOException $e) {
    $rootNode = $leftNode = $rightNode = $llNode = $lrNode = $rlNode = $rrNode = null;
}

$activePage = 'genealogy';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Genealogy Structure - Syntrix Matrix</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    body { background-color: #030b1e; color: #ffffff; font-family: sans-serif; }
    .card-dark { background-color: #081229; border: 1px solid #1e293b; border-radius: 12px; }
    
    .tree-viewport { background-color: #050d1e; border: 1px solid #1e293b; border-radius: 12px; padding: 4rem 1rem; overflow-x: auto; }
    .tree-wrapper { display: flex; flex-direction: column; align-items: center; min-width: 820px; }
    .tree-row { display: flex; justify-content: space-around; width: 100%; margin-bottom: 4rem; position: relative; }

    .node-box {
      background: linear-gradient(135deg, #0f172a, #1e293b);
      border: 2px solid #334155;
      border-radius: 10px;
      padding: 0.85rem 1rem;
      width: 190px;
      text-align: center;
      box-shadow: 0 4px 14px rgba(0,0,0,0.4);
      transition: all 0.2s ease-in-out;
      position: relative;
      z-index: 2;
    }
    .node-box:hover { border-color: #10b981; transform: translateY(-2px); }
    .node-box.root-node { border-color: #3b82f6; background: linear-gradient(135deg, #0a1120, #1d4ed833); }
    .node-box.active-member { border-color: #10b981; }
    .node-box.empty-node { border-style: dashed; border-color: #1e293b; background: transparent; opacity: 0.35; cursor: pointer; }
    .node-box.empty-node:hover { opacity: 0.9; border-color: #10b981; background: rgba(16, 185, 129, 0.04); }

    .node-avatar { width: 38px; height: 38px; border-radius: 50%; background-color: #1e293b; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem auto; font-size: 0.9rem; font-weight: bold; }
    .root-node .node-avatar { background-color: rgba(37, 99, 235, 0.2); color: #3b82f6; border: 1px solid rgba(37, 99, 235, 0.4); }
    .active-member .node-avatar { background-color: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); }
    
    .leg-tag { position: absolute; top: -22px; font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; padding: 2px 8px; border-radius: 4px; }
    .leg-left { left: 10px; color: #38bdf8; background: rgba(56, 189, 248, 0.12); border: 1px solid rgba(56, 189, 248, 0.2); }
    .leg-right { right: 10px; color: #c084fc; background: rgba(192, 132, 252, 0.12); border: 1px solid rgba(192, 132, 252, 0.2); }

    .action-node-btn {
      position: absolute; top: 6px; right: 8px;
      background: transparent; border: none; color: #94a3b8;
      font-size: 0.8rem; cursor: pointer; opacity: 0.4; transition: all 0.2s;
    }
    .action-node-btn:hover { opacity: 1; color: #ef4444; }

    .metric-badge-container { display: flex; justify-content: center; gap: 6px; margin-top: 6px; padding-top: 6px; border-top: 1px solid #1e293b; font-size: 0.65rem; }
    .metric-badge { background-color: #0f172a; padding: 1px 5px; border-radius: 3px; color: #94a3b8; font-weight: 500; }

    .modal-content-dark { background-color: #081229; border: 1px solid #1e293b; color: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
    .modal-header-dark { border-bottom: 1px solid #1e293b; padding: 1.25rem; }
    .modal-footer-dark { border-top: 1px solid #1e293b; padding: 1rem; }
    .form-select-dark { background-color: #030b1e; border: 1px solid #1e293b; color: #fff; padding: 0.6rem; }
    .form-select-dark:focus { background-color: #030b1e; color: #fff; border-color: #3b82f6; box-shadow: none; }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    
    <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      
      <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
        <div class="alert alert-success bg-success text-white border-0 alert-dismissible fade show small py-2 mb-3 shadow" role="alert">
          <i class="bi bi-check-circle-fill me-2"></i> Downline node placed and database synchronization completed successfully.
          <button type="button" class="btn-close btn-close-white small" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php elseif (isset($_GET['status']) && $_GET['status'] === 'removed'): ?>
        <div class="alert alert-warning bg-warning text-dark border-0 alert-dismissible fade show small py-2 mb-3 shadow" role="alert">
          <i class="bi bi-info-circle-fill me-2"></i> Node placement severed. Selected downline account unlinked.
          <button type="button" class="btn-close btn-close-white small" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php elseif (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
        <div class="alert alert-danger bg-danger text-white border-0 alert-dismissible fade show small py-2 mb-3 shadow" role="alert">
          <i class="bi bi-exclamation-triangle-fill me-2"></i> Operation failed. Position conflict or database constraint validation error.
          <button type="button" class="btn-close btn-close-white small" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-secondary" style="border-color: #1e293b !important;">
        <div>
          <h1 class="h3 fw-bold mb-0 text-white">Binary Genealogy Tree</h1>
          <p class="text-muted small mb-0">Real-time organizational hierarchy mapping and leg configuration interface.</p>
        </div>
      </div>

      <div class="card-dark p-4 shadow-lg">
        <div class="tree-viewport">
          <div class="tree-wrapper">
            
            <div class="tree-row justify-content-center">
              <?php if ($rootNode): ?>
                <div class="node-box root-node">
                  <div class="node-avatar"><?php echo strtoupper(substr($rootNode['username'] ?? 'U', 0, 1)); ?></div>
                  <div class="small fw-bold text-truncate text-white"><?php echo htmlspecialchars($rootNode['full_name'] ?? 'Root Account'); ?></div>
                  <div class="text-muted" style="font-size: 0.65rem;">@<?php echo htmlspecialchars($rootNode['username'] ?? ''); ?></div>
                  <div class="text-primary fw-semibold mt-1" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.3px;"><?php echo htmlspecialchars($rootNode['rank_label'] ?? 'Associate'); ?></div>
                  <div class="metric-badge-container">
                    <span class="metric-badge">PV: <?php echo number_format($rootNode['personal_volume'] ?? 0); ?></span>
                    <span class="metric-badge">GV: <?php echo number_format($rootNode['group_volume'] ?? 0); ?></span>
                  </div>
                </div>
              <?php endif; ?>
            </div>

            <div class="tree-row">
              <?php if ($leftNode): ?>
                <div class="node-box active-member">
                  <span class="leg-tag leg-left">Left Team</span>
                  <button class="action-node-btn" onclick="confirmRemoval('<?php echo $leftNode['id']; ?>', 'Left')" title="Sever Placement Node"><i class="bi bi-x-circle"></i></button>
                  <div class="node-avatar"><?php echo strtoupper(substr($leftNode['username'] ?? 'U', 0, 1)); ?></div>
                  <div class="small fw-bold text-truncate"><?php echo htmlspecialchars($leftNode['full_name'] ?? 'No Name'); ?></div>
                  <div class="text-muted" style="font-size: 0.65rem;">@<?php echo htmlspecialchars($leftNode['username']); ?></div>
                  <div class="metric-badge-container">
                    <span class="metric-badge">PV: <?php echo number_format($leftNode['personal_volume'] ?? 0); ?></span>
                    <span class="metric-badge">GV: <?php echo number_format($leftNode['group_volume'] ?? 0); ?></span>
                  </div>
                </div>
              <?php else: ?>
                <div class="node-box empty-node text-center d-flex flex-column justify-content-center align-items-center" onclick="openPlacementModal('Left', '<?php echo $userId; ?>')">
                  <span class="leg-tag leg-left">Left Team</span>
                  <div class="text-muted small"><i class="bi bi-plus-lg fs-5 d-block mb-1 text-success"></i>Available</div>
                </div>
              <?php endif; ?>

              <?php if ($rightNode): ?>
                <div class="node-box active-member">
                  <span class="leg-tag leg-right">Right Team</span>
                  <button class="action-node-btn" onclick="confirmRemoval('<?php echo $rightNode['id']; ?>', 'Right')" title="Sever Placement Node"><i class="bi bi-x-circle"></i></button>
                  <div class="node-avatar"><?php echo strtoupper(substr($rightNode['username'] ?? 'U', 0, 1)); ?></div>
                  <div class="small fw-bold text-truncate"><?php echo htmlspecialchars($rightNode['full_name'] ?? 'No Name'); ?></div>
                  <div class="text-muted" style="font-size: 0.65rem;">@<?php echo htmlspecialchars($rightNode['username']); ?></div>
                  <div class="metric-badge-container">
                    <span class="metric-badge">PV: <?php echo number_format($rightNode['personal_volume'] ?? 0); ?></span>
                    <span class="metric-badge">GV: <?php echo number_format($rightNode['group_volume'] ?? 0); ?></span>
                  </div>
                </div>
              <?php else: ?>
                <div class="node-box empty-node text-center d-flex flex-column justify-content-center align-items-center" onclick="openPlacementModal('Right', '<?php echo $userId; ?>')">
                  <span class="leg-tag leg-right">Right Team</span>
                  <div class="text-muted small"><i class="bi bi-plus-lg fs-5 d-block mb-1 text-success"></i>Available</div>
                </div>
              <?php endif; ?>
            </div>

            <div class="tree-row">
              <?php if ($llNode): ?>
                <div class="node-box active-member">
                  <button class="action-node-btn" onclick="confirmRemoval('<?php echo $llNode['id']; ?>', 'Left')"><i class="bi bi-x-circle"></i></button>
                  <div class="node-avatar"><?php echo strtoupper(substr($llNode['username'] ?? 'U', 0, 1)); ?></div>
                  <div class="small fw-bold text-truncate"><?php echo htmlspecialchars($llNode['full_name'] ?? 'No Name'); ?></div>
                  <div class="text-muted" style="font-size: 0.65rem;">@<?php echo htmlspecialchars($llNode['username']); ?></div>
                  <div class="metric-badge-container">
                    <span class="metric-badge">PV: <?php echo number_format($llNode['personal_volume'] ?? 0); ?></span>
                  </div>
                </div>
              <?php else: ?>
                <div class="node-box empty-node text-center d-flex flex-column justify-content-center align-items-center" <?php if($leftChildId): ?>onclick="openPlacementModal('Left', '<?php echo $leftChildId; ?>')"<?php endif; ?>>
                  <div class="text-muted small"><i class="bi bi-plus-lg fs-6 d-block mb-1 text-success"></i>Available</div>
                </div>
              <?php endif; ?>

              <?php if ($lrNode): ?>
                <div class="node-box active-member">
                  <button class="action-node-btn" onclick="confirmRemoval('<?php echo $lrNode['id']; ?>', 'Right')"><i class="bi bi-x-circle"></i></button>
                  <div class="node-avatar"><?php echo strtoupper(substr($lrNode['username'] ?? 'U', 0, 1)); ?></div>
                  <div class="small fw-bold text-truncate"><?php echo htmlspecialchars($lrNode['full_name'] ?? 'No Name'); ?></div>
                  <div class="text-muted" style="font-size: 0.65rem;">@<?php echo htmlspecialchars($lrNode['username']); ?></div>
                  <div class="metric-badge-container">
                    <span class="metric-badge">PV: <?php echo number_format($lrNode['personal_volume'] ?? 0); ?></span>
                  </div>
                </div>
              <?php else: ?>
                <div class="node-box empty-node text-center d-flex flex-column justify-content-center align-items-center" <?php if($leftChildId): ?>onclick="openPlacementModal('Right', '<?php echo $leftChildId; ?>')"<?php endif; ?>>
                  <div class="text-muted small"><i class="bi bi-plus-lg fs-6 d-block mb-1 text-success"></i>Available</div>
                </div>
              <?php endif; ?>

              <?php if ($rlNode): ?>
                <div class="node-box active-member">
                  <button class="action-node-btn" onclick="confirmRemoval('<?php echo $rlNode['id']; ?>', 'Left')"><i class="bi bi-x-circle"></i></button>
                  <div class="node-avatar"><?php echo strtoupper(substr($rlNode['username'] ?? 'U', 0, 1)); ?></div>
                  <div class="small fw-bold text-truncate"><?php echo htmlspecialchars($rlNode['full_name'] ?? 'No Name'); ?></div>
                  <div class="text-muted" style="font-size: 0.65rem;">@<?php echo htmlspecialchars($rlNode['username']); ?></div>
                  <div class="metric-badge-container">
                    <span class="metric-badge">PV: <?php echo number_format($rlNode['personal_volume'] ?? 0); ?></span>
                  </div>
                </div>
              <?php else: ?>
                <div class="node-box empty-node text-center d-flex flex-column justify-content-center align-items-center" <?php if($rightChildId): ?>onclick="openPlacementModal('Left', '<?php echo $rightChildId; ?>')"<?php endif; ?>>
                  <div class="text-muted small"><i class="bi bi-plus-lg fs-6 d-block mb-1 text-success"></i>Available</div>
                </div>
              <?php endif; ?>

              <?php if ($rrNode): ?>
                <div class="node-box active-member">
                  <button class="action-node-btn" onclick="confirmRemoval('<?php echo $rrNode['id']; ?>', 'Right')"><i class="bi bi-x-circle"></i></button>
                  <div class="node-avatar"><?php echo strtoupper(substr($rrNode['username'] ?? 'U', 0, 1)); ?></div>
                  <div class="small fw-bold text-truncate"><?php echo htmlspecialchars($rrNode['full_name'] ?? 'No Name'); ?></div>
                  <div class="text-muted" style="font-size: 0.65rem;">@<?php echo htmlspecialchars($rrNode['username']); ?></div>
                  <div class="metric-badge-container">
                    <span class="metric-badge">PV: <?php echo number_format($rrNode['personal_volume'] ?? 0); ?></span>
                  </div>
                </div>
              <?php else: ?>
                <div class="node-box empty-node text-center d-flex flex-column justify-content-center align-items-center" <?php if($rightChildId): ?>onclick="openPlacementModal('Right', '<?php echo $rightChildId; ?>')"<?php endif; ?>>
                  <div class="text-muted small"><i class="bi bi-plus-lg fs-6 d-block mb-1 text-success"></i>Available</div>
                </div>
              <?php endif; ?>
            </div>

          </div>
        </div>
      </div>
    </main>
  </div>
</div>

<div class="modal fade" id="placementModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content modal-content-dark">
      <div class="modal-header modal-header-dark">
        <h5 class="modal-title fw-bold"><i class="bi bi-diagram-3 text-success me-2"></i>Placement Configurator</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="process_placement.php" method="POST">
        <div class="modal-body">
          <input type="hidden" name="action_type" value="add">
          <input type="hidden" name="parent_id" id="modalParentId" value="">
          <input type="hidden" name="target_leg" id="modalTargetLeg" value="">
          
          <div class="mb-3">
            <label class="form-label text-white-50 small fw-semibold">Target Node Vector</label>
            <div class="p-2 card-dark border-secondary small fw-bold" id="modalLegDisplay"></div>
          </div>
          
          <div class="mb-3">
            <label for="userSelect" class="form-label text-white-50 small fw-semibold">Select Downline Candidate</label>
            <select class="form-select form-select-dark small" id="userSelect" name="selected_user_id" required>
              <option value="" selected disabled>-- Choose candidate from pool --</option>
              <?php foreach ($availableUsers as $user): ?>
                <option value="<?php echo $user['id']; ?>">
                  <?php echo htmlspecialchars(($user['full_name'] ?? 'Incomplete Profile') . ' (@' . $user['username'] . ')'); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="modal-footer modal-footer-dark">
          <button type="button" class="btn btn-sm btn-outline-secondary text-white" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-sm btn-success px-3">Commit Placement</button>
        </div>
      </form>
    </div>
  </div>
</div>

<form id="removeForm" action="process_placement.php" method="POST" style="display:none;">
  <input type="hidden" name="action_type" value="remove">
  <input type="hidden" name="target_user_id" id="removeUserId" value="">
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function openPlacementModal(leg, parentId) {
    document.getElementById('modalParentId').value = parentId;
    document.getElementById('modalTargetLeg').value = leg;
    
    const displayTag = document.getElementById('modalLegDisplay');
    displayTag.innerText = leg + " Team Node Vector Assignment (Upline Target ID: " + parentId + ")";
    displayTag.style.color = (leg === 'Left') ? '#38bdf8' : '#c084fc';
    
    var placementModal = new bootstrap.Modal(document.getElementById('placementModal'));
    placementModal.show();
  }

  function confirmRemoval(userId, leg) {
    if (confirm("Are you sure you want to sever this downline configuration element from the " + leg + " team matrix?")) {
        document.getElementById('removeUserId').value = userId;
        document.getElementById('removeForm').submit();
    }
  }

  if (window.history.replaceState) {
      const url = new URL(window.location.href);
      url.searchParams.delete('status');
      window.history.replaceState({ path: url.href }, '', url.href);
  }
</script>
</body>
</html>