<?php
$role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'Guest';
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'User';

// FIX: Check if an active page variable is passed, default to empty string if not
$currentPage = isset($activePage) ? $activePage : '';

$sidebarConfig = [
    'Admin' => [
        'bg_class'    => 'bg-admin-dark',
        'badge_class' => 'bg-danger text-white',
        'badge_text'  => 'System Admin',
        'hover_color' => '#ef4444',
        'links'       => [
            ['slug' => 'admin_dashboard', 'title' => 'Global Overview', 'icon' => 'bi-speedometer2', 'url' => '/mlmsystem/public/admindb/admin_dashboard.php'],
            ['slug' => 'users', 'title' => 'User Management', 'icon' => 'bi-people', 'url' => '/mlmsystem/public/admindb/user_management.php'],
            ['slug' => 'rules', 'title' => 'Commission Rules', 'icon' => 'bi-sliders', 'url' => '/mlmsystem/public/admindb/commission_rules.php'],
            ['slug' => 'payouts', 'title' => 'Payout Approvals', 'icon' => 'bi-currency-exchange', 'url' => '/mlmsystem/public/admindb/payout_approvals.php'],
            ['slug' => 'audit', 'title' => 'System Audit Logs', 'icon' => 'bi-shield-check', 'url' => '/mlmsystem/public/admindb/system_audit_logs.php'],
        ]
    ],
    'Staff' => [
        'bg_class'    => 'bg-staff-dark',
        'badge_class' => 'bg-info text-dark',
        'badge_text'  => 'Operations Team',
        'hover_color' => '#38bdf8',
        'links'       => [
            ['slug' => 'staff_dashboard', 'title' => 'Operations Hub', 'icon' => 'bi-kanban', 'url' => '/mlmsystem/public/staffdb/staff_dashboard.php'],
            ['slug' => 'tickets', 'title' => 'Support Tickets', 'icon' => 'bi-ticket-detailed', 'url' => '/mlmsystem/public/staffdb/support_tickets.php'],
            ['slug' => 'verification', 'title' => 'Member Verification', 'icon' => 'bi-person-check', 'url' => '/mlmsystem/public/staffdb/member_verification.php'],
            ['slug' => 'inventory', 'title' => 'Inventory Status', 'icon' => 'bi-box-seam', 'url' => '/mlmsystem/public/staffdb/inventory_status.php'],
            ['slug' => 'reports', 'title' => 'Operational Reports', 'icon' => 'bi-file-earmark-text', 'url' => '/mlmsystem/public/staffdb/operational_reports.php'],
        ]
    ],
    'Affiliate' => [
        'bg_class'    => 'bg-affiliate-dark',
        'badge_class' => 'badge-rank-bronze',
        'badge_text'  => 'Bronze Leader',
        'hover_color' => '#5ce65c',
        'links'       => [
            ['slug' => 'dashboard', 'title' => 'Dashboard', 'icon' => 'bi-grid-1x2', 'url' => '/mlmsystem/public/affiliatedb/dashboard.php'],
            ['slug' => 'genealogy', 'title' => 'Genealogy Tree', 'icon' => 'bi-diagram-3', 'url' => '/mlmsystem/public/affiliatedb/genealogy.php'],
            ['slug' => 'profile', 'title' => 'My Profile', 'icon' => 'bi-person-circle', 'url' => '/mlmsystem/public/affiliatedb/profile.php'],
            ['slug' => 'referrals', 'title' => 'My Referrals', 'icon' => 'bi-people-fill', 'url' => '/mlmsystem/public/affiliatedb/referrals.php'],
            ['slug' => 'commissions', 'title' => 'Commissions', 'icon' => 'bi-cash-stack', 'url' => '/mlmsystem/public/affiliatedb/commissions.php'],
            ['slug' => 'payments', 'title' => 'Payments', 'icon' => 'bi-credit-card', 'url' => '/mlmsystem/public/affiliatedb/payments.php'],
            ['slug' => 'rank', 'title' => 'Rank Progress', 'icon' => 'bi-graph-up-arrow', 'url' => '/mlmsystem/public/affiliatedb/progress.php'],
            ['slug' => 'training', 'title' => 'Training Desk', 'icon' => 'bi-book', 'url' => '/mlmsystem/public/affiliatedb/training.php'],
            ['slug' => 'support', 'title' => 'Support Ticket', 'icon' => 'bi-question-circle', 'url' => '/mlmsystem/public/affiliatedb/support.php'],
        ]
    ]
];

$currentConfig = isset($sidebarConfig[$role]) ? $sidebarConfig[$role] : $sidebarConfig['Affiliate'];
?>

<style>
  .sidebar-panel { background-color: #081229; min-height: 100vh; border-right: 1px solid #1e293b; }
  .sidebar-link { color: #94a3b8; text-decoration: none; display: block; padding: 0.75rem 1.2rem; transition: all 0.2s; border-radius: 6px; }
  .sidebar-link:hover, .sidebar-link.active { 
      background-color: #1e293b; 
      color: <?php echo $currentConfig['hover_color']; ?> !important; 
  }
  .badge-rank-bronze { background-color: #b45309; color: #fef3c7; font-size: 0.75rem; padding: 0.25rem 0.6rem; border-radius: 4px; }
  .sidebar-logo {
    background: linear-gradient(135deg, #2563eb, #10b981);
    width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;
    color: white; font-weight: bold; font-size: 1rem; border-radius: 4px;
  }
</style>

<nav class="col-md-3 col-lg-2 d-md-block sidebar-panel p-3">
  <!-- Brand Header Block Wrapper -->
  <div class="d-flex align-items-center gap-2 mb-4 px-2">
    <div class="sidebar-logo">S</div>
    <span class="fs-5 fw-bold text-white">Syntrix</span>
  </div>
  
  <!-- Identity Matrix Tag -->
  <div class="mb-3 px-2 d-flex align-items-center gap-2">
    <span class="badge <?php echo $currentConfig['badge_class']; ?>"><?php echo $currentConfig['badge_text']; ?></span>
    <small class="text-muted text-truncate" style="max-width: 80px;"><?php echo htmlspecialchars($username); ?></small>
  </div>
  
  <!-- Links Collection Workspace -->
  <div class="d-flex flex-column gap-1">
    <?php foreach ($currentConfig['links'] as $link): 
        // FIX: Compare the slug identifier against our page variable to accurately toggle highlight states
        $isActive = ($currentPage === $link['slug']);
    ?>
        <a href="<?php echo $link['url']; ?>" class="sidebar-link <?php echo $isActive ? 'active' : ''; ?>">
          <i class="bi <?php echo $link['icon']; ?> me-2"></i><?php echo $link['title']; ?>
        </a>
    <?php endforeach; ?>
    
    <hr class="text-secondary my-2">
    <!-- FIX: Root relative link back to the main login layout -->
    <a href="/mlmsystem/public/logout.php" class="nav-link text-danger">
  <i class="bi bi-box-arrow-left me-2"></i> Logout
</a>
  </div>
</nav>