<?php
session_start();

// Guard: Force back-office Staff role validation
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Staff') {
    header("Location: ../login.php");
    exit;
}

// 1. DATABASE CONNECTIVITY MAPPER
require_once __DIR__ . '/../../config/database.php';

// Initialize container variable for structural tracking
$ticketsList = [];

try {
    // 2. LIVE DATA FETCH QUEUE
    // Ingest all ticket records matching your database architecture columns
    $sql = "SELECT id, ticket_code, user_fullname, user_id, operational_subject, assigned_department, priority_level, created_at 
            FROM support_tickets 
            ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $ticketsList = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Fallback safely to prevent terminal execution crash if migrations are pending
    $ticketsList = [];
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    body { background-color: #030b1e; color: #ffffff; font-family: sans-serif; }
    .card-dark { background-color: #081229; border: 1px solid #1e293b; border-radius: 12px; }
    
    /* FIX: Force high contrast visibility for inputs, filters, and icons against dark theme */
    .form-control-dark {
      background-color: #0f172a !important;
      border: 1px solid #334155 !important;
      color: #ffffff !important;
    }
    .form-control-dark::placeholder {
      color: #64748b !important;
      opacity: 1;
    }
    .form-control-dark:focus {
      background-color: #0f172a !important;
      border-color: #0ea5e9 !important;
      color: #ffffff !important;
      box-shadow: 0 0 0 0.25rem rgba(14, 165, 233, 0.15) !important;
    }
    .input-group-text-custom {
      background-color: #0f172a !important;
      border: 1px solid #334155 !important;
      color: #94a3b8 !important;
    }
    
    /* Custom clean dark table rules matching dashboard interface specs */
    .table-custom { color: #ffffff !important; border-color: #1e293b; vertical-align: middle; }
    .table-custom thead th { background-color: #0f172a !important; color: #94a3b8 !important; font-weight: 600; border-bottom: 2px solid #1e293b; }
    
    /* Force every row table cell to render text clearly against the dark background grid */
    .table-custom tbody tr td { 
      background-color: #081229 !important; 
      color: #ffffff !important; 
      border-bottom: 1px solid #1e293b !important;
    }
    .table-custom tbody tr td .text-muted {
      color: #94a3b8 !important;
    }
    .table-custom tbody tr td .fw-semibold {
      color: #cbd5e1 !important;
    }
    
    .table-custom tbody tr { border-bottom: 1px solid #1e293b; transition: background 0.15s; }
    .table-custom tbody tr:hover td { background-color: rgba(30, 41, 59, 0.4) !important; }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    
    <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom border-secondary" style="border-color: #1e293b !important;">
        <div>
          <h1 class="h3 fw-bold text-white mb-0">Helpdesk Support Tickets</h1>
          <p class="text-muted small mb-0">Address client inquiries, clear transaction synchronization snags, and resolve multi-level affiliate network tickets.</p>
        </div>
      </div>

      <div class="card-dark p-3 mb-4 shadow-sm">
        <div class="row g-2">
          <div class="col-md-6">
            <div class="input-group input-group-sm">
              <span class="input-group-text input-group-text-custom"><i class="bi bi-search"></i></span>
              <input type="text" class="form-control form-control-dark font-monospace" placeholder="Filter by Ticket Code, Member ID or Name...">
            </div>
          </div>
          <div class="col-md-3">
            <select class="form-select form-select-sm form-control-dark">
              <option value="" selected style="color: #94a3b8;">All Status Contexts</option>
              <option>Open Queues</option>
              <option>High Priority</option>
              <option>Resolved</option>
            </select>
          </div>
          <div class="col-md-3">
            <select class="form-select form-select-sm form-control-dark">
              <option value="" selected style="color: #94a3b8;">All Departments</option>
              <option>Technical Helpdesk</option>
              <option>Payout & Financials</option>
              <option>Registration Matrix</option>
            </select>
          </div>
        </div>
      </div>

      <div class="card-dark p-4 shadow-lg">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold mb-0 text-white-50"><i class="bi bi-ticket-perforated me-2 text-info"></i>Active Support Registry Pipeline</h5>
          <span class="badge bg-info-subtle text-info border border-info px-2 py-1 small">Inbound Gateway Active</span>
        </div>

        <div class="table-responsive">
          <table class="table table-custom table-hover mb-0" style="font-size: 0.9rem;">
            <thead>
              <tr>
                <th scope="col" class="py-3 px-3" style="width: 12%;">Code</th>
                <th scope="col" class="py-3" style="width: 22%;">Affiliate Identity</th>
                <th scope="col" class="py-3" style="width: 33%;">Problem Context Description</th>
                <th scope="col" class="py-3" style="width: 10%;">Priority</th>
                <th scope="col" class="py-3" style="width: 11%;">Created Date</th>
                <th scope="col" class="py-3 text-end px-3" style="width: 12%;">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (count($ticketsList) > 0): ?>
                <?php foreach ($ticketsList as $ticket): ?>
                  <tr>
                    <td class="px-3 font-monospace text-info fw-bold"><?php echo htmlspecialchars($ticket['ticket_code']); ?></td>
                    <td>
                      <div class="fw-bold text-white"><?php echo htmlspecialchars($ticket['user_fullname']); ?></div>
                      <small class="text-muted font-monospace" style="font-size: 0.75rem;">ID: #<?php echo htmlspecialchars($ticket['user_id']); ?></small>
                    </td>
                    <td>
                      <div class="fw-semibold"><?php echo htmlspecialchars($ticket['operational_subject']); ?></div>
                      <span class="text-muted small" style="font-size: 0.7rem;">Dept: <?php echo htmlspecialchars($ticket['assigned_department']); ?></span>
                    </td>
                    <td>
                      <?php 
                        $priority = strtoupper($ticket['priority_level']);
                        $badgeClass = 'bg-secondary-subtle text-secondary border border-secondary';
                        if ($priority === 'HIGH' || $priority === 'URGENT') $badgeClass = 'bg-danger-subtle text-danger border border-danger';
                        elseif ($priority === 'MEDIUM') $badgeClass = 'bg-warning-subtle text-warning border border-warning';
                      ?>
                      <span class="badge <?php echo $badgeClass; ?> px-2 py-0.5" style="font-size: 0.65rem;"><?php echo $priority; ?></span>
                    </td>
                    <td class="text-muted small"><?php echo htmlspecialchars(date('Y-m-d', strtotime($ticket['created_at']))); ?></td>
                    <td class="text-end px-3">
                      <button class="btn btn-sm btn-info text-dark font-monospace fw-bold me-1" style="font-size: 0.75rem;" onclick="alert('Opening modal workspace interface for <?php echo htmlspecialchars($ticket['ticket_code']); ?>...');">REPLY</button>
                      <button class="btn btn-sm btn-outline-success font-monospace" style="font-size: 0.75rem;" onclick="alert('Marking ticket as resolved...');">CLOSE</button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-center py-4 font-monospace text-muted">
                    <i class="bi bi-inboxes me-2"></i>No active database records found inside support_tickets table queue.
                  </td>
                </tr>
              <?php endif; ?>
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