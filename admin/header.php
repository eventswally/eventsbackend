<?php requireLogin(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Dashboard' ?> - Events Wally Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #6750A4;
            --primary-dark: #4F378B;
            --primary-light: #EADDFF;
            --secondary: #625B71;
            --surface: #FFFBFE;
            --on-surface: #1C1B1F;
            --surface-variant: #E7E0EC;
            --primary-gradient: linear-gradient(135deg, #6750A4 0%, #7C4DFF 100%);
            --featured-gradient: linear-gradient(135deg, #FF6B35 0%, #F7931E 100%);
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background-color: #F5F5F5;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }
        .sidebar {
            background: linear-gradient(135deg, #6750A4 0%, #7C4DFF 100%);
            min-height: 100vh;
            color: white;
            position: fixed;
            width: 260px;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
        }
        .sidebar-header {
            padding: 30px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-header h4 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 4px;
            letter-spacing: -0.5px;
        }
        .sidebar-header .subtitle {
            font-size: 13px;
            opacity: 0.8;
            font-weight: 400;
        }
        .sidebar nav {
            padding: 20px 12px;
        }
        .sidebar a {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-radius: 12px;
            margin: 4px 0;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 15px;
        }
        .sidebar a i {
            font-size: 18px;
            width: 20px;
            text-align: center;
        }
        .sidebar a:hover {
            background: rgba(255,255,255,0.15);
            color: white;
            transform: translateX(4px);
        }
        .sidebar a.active {
            background: rgba(255,255,255,0.25);
            color: white;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .sidebar-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 24px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-footer .user-info {
            font-size: 13px;
            opacity: 0.7;
            margin-bottom: 4px;
        }
        .sidebar-footer .user-name {
            font-weight: 600;
            font-size: 15px;
        }
        .main-content {
            margin-left: 260px;
            padding: 32px 40px;
            min-height: 100vh;
        }
        .page-header {
            margin-bottom: 32px;
        }
        .page-header h1 {
            font-size: 32px;
            font-weight: 700;
            color: var(--on-surface);
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        .page-header .breadcrumb {
            background: none;
            padding: 0;
            margin: 0;
            font-size: 14px;
        }
        .stats-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            margin-bottom: 24px;
            transition: all 0.2s ease;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .stats-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
            transform: translateY(-2px);
        }
        .stats-card .icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin-bottom: 12px;
        }
        .stats-card h3 {
            font-size: 32px;
            font-weight: 700;
            margin: 0;
            color: var(--on-surface);
        }
        .stats-card p {
            margin: 4px 0 0;
            color: #666;
            font-size: 14px;
            font-weight: 500;
        }
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            border: 1px solid rgba(0,0,0,0.05);
            margin-bottom: 24px;
        }
        .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(0,0,0,0.06);
            padding: 20px 24px;
            font-weight: 600;
            font-size: 18px;
        }
        .card-body {
            padding: 24px;
        }
        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(103, 80, 164, 0.3);
        }
        .btn-outline-primary {
            border-color: var(--primary);
            color: var(--primary);
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
        }
        .btn-outline-primary:hover {
            background: var(--primary);
            border-color: var(--primary);
        }
        .table {
            font-size: 14px;
        }
        .table thead th {
            background: var(--surface-variant);
            font-weight: 600;
            border: none;
            padding: 14px 16px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #666;
        }
        .table tbody td {
            padding: 16px;
            vertical-align: middle;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .table tbody tr:hover {
            background-color: rgba(103, 80, 164, 0.03);
        }
        .table-actions {
            display: flex;
            gap: 8px;
        }
        .table-actions a {
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s ease;
        }
        .badge {
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 12px;
        }
        .badge-featured {
            background: var(--featured-gradient);
            color: white;
        }
        .badge-success {
            background-color: #00C853;
            color: white;
        }
        .form-control, .form-select {
            border-radius: 12px;
            border: 1px solid rgba(0,0,0,0.12);
            padding: 12px 16px;
            font-size: 15px;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(103, 80, 164, 0.1);
        }
        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
            color: var(--on-surface);
        }
        .alert {
            border-radius: 12px;
            border: none;
            padding: 16px 20px;
            font-weight: 500;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                min-height: auto;
            }
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
            .sidebar-footer {
                position: relative;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 px-0 sidebar">
                <div class="sidebar-header">
                    <h4>🎉 Events Wally</h4>
                    <p class="subtitle">Admin Panel</p>
                </div>
                
                <nav>
                    <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="cities.php" class="<?= basename($_SERVER['PHP_SELF']) == 'cities.php' ? 'active' : '' ?>">
                        <i class="bi bi-geo-alt"></i>
                        <span>Cities</span>
                    </a>
                    <a href="categories.php" class="<?= basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : '' ?>">
                        <i class="bi bi-grid"></i>
                        <span>Categories</span>
                    </a>
                    <a href="planners.php" class="<?= basename($_SERVER['PHP_SELF']) == 'planners.php' ? 'active' : '' ?>">
                        <i class="bi bi-people"></i>
                        <span>Event Planners</span>
                    </a>
                    <hr style="border-color: rgba(255,255,255,0.2); margin: 16px 12px;">
                    <a href="logout.php">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </a>
                </nav>
                
                <div class="sidebar-footer">
                    <p class="user-info">Logged in as:</p>
                    <p class="user-name"><?= $_SESSION['admin_name'] ?? $_SESSION['admin_username'] ?></p>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10 main-content">
