<?php
/**
 * Events Wally - Landing Page
 * Redirects to admin panel or shows API documentation
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Wally - API & Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            max-width: 900px;
        }
        .card {
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px 20px 0 0 !important;
            padding: 30px;
        }
        .btn-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            color: white;
        }
        .endpoint {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            border-left: 4px solid #667eea;
        }
        .endpoint code {
            color: #667eea;
            font-weight: 600;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        .status-active {
            background: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                <h1 class="mb-2">🎉 Events Wally</h1>
                <p class="mb-0">Pakistan's Premier Event Planners Directory</p>
                <p class="mt-2 mb-0"><span class="status-badge status-active">● API Active</span></p>
            </div>
            <div class="card-body p-4">
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <div class="d-grid">
                            <a href="admin/" class="btn btn-custom">
                                <i class="bi bi-speedometer2"></i> Admin Panel
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-grid">
                            <a href="admin/login.php" class="btn btn-outline-primary">
                                <i class="bi bi-box-arrow-in-right"></i> Admin Login
                            </a>
                        </div>
                    </div>
                </div>

                <h5 class="mb-3">📡 API Endpoints</h5>
                
                <div class="endpoint">
                    <strong>Get Cities</strong>
                    <br><code>GET /api/cities/</code>
                    <br><small class="text-muted">Returns all active cities</small>
                    <br><a href="api/cities/" target="_blank" class="btn btn-sm btn-outline-primary mt-2">Test API</a>
                </div>

                <div class="endpoint">
                    <strong>Get Categories</strong>
                    <br><code>GET /api/categories/</code>
                    <br><small class="text-muted">Returns all event categories</small>
                    <br><a href="api/categories/" target="_blank" class="btn btn-sm btn-outline-primary mt-2">Test API</a>
                </div>

                <div class="endpoint">
                    <strong>Get Event Planners</strong>
                    <br><code>GET /api/planners/?city_id=1</code>
                    <br><small class="text-muted">Returns planners filtered by city, category, or search</small>
                    <br><a href="api/planners/?city_id=1" target="_blank" class="btn btn-sm btn-outline-primary mt-2">Test API</a>
                </div>

                <div class="endpoint">
                    <strong>Get Planner Details</strong>
                    <br><code>GET /api/planners/detail.php?id=1</code>
                    <br><small class="text-muted">Returns complete planner information</small>
                    <br><a href="api/planners/detail.php?id=1" target="_blank" class="btn btn-sm btn-outline-primary mt-2">Test API</a>
                </div>

                <hr class="my-4">

                <div class="alert alert-info">
                    <strong>📱 Mobile App:</strong> Open the Events Wally Android app to browse event planners.
                    <br><strong>🔐 Admin Credentials:</strong> Username: <code>admin</code> | Password: <code>admin123</code>
                </div>

                <div class="text-center mt-4">
                    <p class="text-muted mb-0">
                        <strong>Version 1.0.0</strong> | Built with ❤️ for Pakistan
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
