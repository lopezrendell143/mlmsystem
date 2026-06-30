<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Affiliate') {
    header("Location: ../login.php"); // Added ../ to go back to public/
    exit;
}

$activePage = 'genealogy';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Genealogy Tree - Syntrix</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    body { background-color: #030b1e; color: #ffffff; font-family: sans-serif; }
    .card-dark { background-color: #081229; border: 1px solid #1e293b; border-radius: 12px; }
    
    /* Tree Structure Visual Canvas Styles */
    .tree-viewport {
      background-color: #050d1e;
      border: 1px solid #1e293b;
      border-radius: 12px;
      padding: 3rem 1rem;
      overflow-x: auto;
    }
    
    .tree-wrapper {
      display: flex;
      flex-direction: column;
      align-items: center;
      min-width: 700px;
    }
    
    .tree-row {
      display: flex;
      justify-content: space-around;
      width: 100%;
      margin-bottom: 3.5rem;
      position: relative;
    }

    /* Target User Node Blocks */
    .node-box {
      background: linear-gradient(135deg, #0f172a, #1e293b);
      border: 2px solid #334155;
      border-radius: 10px;
      padding: 0.75rem 1rem;
      width: 160px;
      text-align: center;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
      transition: all 0.2s ease-in-out;
      position: relative;
      z-index: 2;
    }
    
    .node-box:hover {
      border-color: #5ce65c;
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(92, 230, 92, 0.2);
    }

    .node-box.root-node { border-color: #2563eb; }
    .node-box.empty-node { border-style: dashed; background: transparent; opacity: 0.5; }

    .node-avatar {
      width: 40px; height: 40px;
      border-radius: 50%;
      background-color: #1e293b;
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 0.5rem auto;
      font-weight: bold;
    }
    .root-node .node-avatar { background-color: rgba(37, 99, 235, 0.2); color: #3b82f6; }
    .active-member .node-avatar { background-color: rgba(92, 230, 92, 0.2); color: #5ce65c; }
    
    .leg-tag {
      position: absolute;
      top: -20px;
      font-size: 0.7rem;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      padding: 2px 6px;
      border-radius: 4px;
    }
    .leg-left { left: 10px; color: #38bdf8; background: rgba(56, 189, 248, 0.1); }
    .leg-right { right: 10px; color: #a855f7; background: rgba(168, 85, 247, 0.1); }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    
    <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      
      <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-secondary">
        <div>
          <h1 class="h3 fw-bold mb-0 text-white">Genealogy Explorer</h1>
          <p class="text-muted small mb-0">Visualize, track, and manage your binary organizational downline positions.</p>
        </div>
        <div class="btn-group shadow-sm">
          <button class="btn btn-sm btn-outline-secondary text-white active">Binary Tree</button>
          <button class="btn btn-sm btn-outline-secondary text-white">Sponsor Map</button>
        </div>
      </div>

      <div class="card-dark p-4 shadow-lg">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h5 class="fw-bold mb-0 text-white-50"><i class="bi bi-diagram-3-fill me-2 text-success"></i>Live Placement Matrix</h5>
          <span class="badge bg-dark border border-secondary text-light px-3 py-2">Focus: Current Session User</span>
        </div>

        <div class="tree-viewport">
          <div class="tree-wrapper">
            
            <div class="tree-row justify-content-center">
              <div class="node-box root-node">
                <div class="node-avatar">R</div>
                <div class="small fw-bold text-truncate"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
                <div class="text-muted" style="font-size: 0.65rem;">ID: STX-00134</div>
                <div class="text-success fw-semibold mt-1" style="font-size: 0.7rem;">Bronze Leader</div>
              </div>
            </div>

            <div class="tree-row">
              <div class="node-box active-member">
                <span class="leg-tag leg-left">Left Leg</span>
                <div class="node-avatar">AM</div>
                <div class="small fw-bold text-truncate">Alex Mercer</div>
                <div class="text-muted" style="font-size: 0.65rem;">ID: STX-00241</div>
                <div class="text-info mt-1" style="font-size: 0.7rem;">PV: 450 | GV: 12.4K</div>
              </div>

              <div class="node-box active-member">
                <span class="leg-tag leg-right">Right Leg</span>
                <div class="node-avatar">SC</div>
                <div class="small fw-bold text-truncate">Sarah Connor</div>
                <div class="text-muted" style="font-size: 0.65rem;">ID: STX-00249</div>
                <div class="text-info mt-1" style="font-size: 0.7rem;">PV: 600 | GV: 12.8K</div>
              </div>
            </div>

            <div class="tree-row">
              <div class="node-box active-member">
                <div class="node-avatar">JD</div>
                <div class="small fw-bold text-truncate">John Doe</div>
                <div class="text-muted" style="font-size: 0.65rem;">PV: 120</div>
              </div>
              
              <div class="node-box empty-node text-center d-flex flex-column justify-content-center align-items-center">
                <div class="text-muted small"><i class="bi bi-person-plus fs-5 d-block mb-1"></i>Open Slot</div>
              </div>

              <div class="node-box active-member">
                <div class="node-avatar">EK</div>
                <div class="small fw-bold text-truncate">Elena Kyle</div>
                <div class="text-muted" style="font-size: 0.65rem;">PV: 300</div>
              </div>

              <div class="node-box active-member">
                <div class="node-avatar">MW</div>
                <div class="small fw-bold text-truncate">Marcus Wright</div>
                <div class="text-muted" style="font-size: 0.65rem;">PV: 150</div>
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