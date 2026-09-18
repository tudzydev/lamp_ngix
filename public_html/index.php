<?php
$servername = "db";
$username = "admin";
$password = "1234";
$dbname = "sample_db";

$dbConnected = false;
$dbSelected = false;
$errorMessage = "";

$dbhandle = @mysqli_connect($servername, $username, $password);
if ($dbhandle) {
    $dbConnected = true;
    if (@mysqli_select_db($dbhandle, $dbname)) {
        $dbSelected = true;
    } else {
        $errorMessage = mysqli_error($dbhandle);
    }
} else {
    $errorMessage = mysqli_connect_error();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LEMP Stack Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f6fb;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .main-card {
            border: none;
            border-radius: 1.25rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            background: #ffffff;
            max-width: 600px;
            width: 100%;
        }
        .status-badge {
            font-size: 0.85rem;
            padding: 0.4rem 0.8rem;
            border-radius: 50rem;
        }
    </style>
</head>
<body>
    <div class="container p-3">
        <div class="card main-card mx-auto p-4 p-md-5">
            <div class="text-center mb-4">
                <div class="d-inline-flex p-3 rounded-circle bg-primary-subtle text-primary mb-3">
                    <i class="bi bi-layers-fill fs-1"></i>
                </div>
                <h3 class="fw-bold mb-1">LEMP Stack Environment</h3>
                <p class="text-muted">Nginx • PHP 7.4-FPM • MariaDB 11</p>
            </div>

            <!-- Health Status -->
            <div class="list-group mb-4 shadow-sm">
                <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-hdd-network text-primary fs-5"></i>
                        <div>
                            <div class="fw-semibold">MariaDB Server Connection</div>
                            <small class="text-muted">Host: <?php echo $servername; ?></small>
                        </div>
                    </div>
                    <?php if ($dbConnected): ?>
                        <span class="badge bg-success-subtle text-success border border-success-subtle status-badge">
                            <i class="bi bi-check-circle-fill me-1"></i> Connected
                        </span>
                    <?php else: ?>
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle status-badge">
                            <i class="bi bi-x-circle-fill me-1"></i> Failed
                        </span>
                    <?php endif; ?>
                </div>

                <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-database text-info fs-5"></i>
                        <div>
                            <div class="fw-semibold">Database Selection</div>
                            <small class="text-muted">Target: <?php echo $dbname; ?></small>
                        </div>
                    </div>
                    <?php if ($dbSelected): ?>
                        <span class="badge bg-success-subtle text-success border border-success-subtle status-badge">
                            <i class="bi bi-check-circle-fill me-1"></i> Ready
                        </span>
                    <?php else: ?>
                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle status-badge">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Not Found
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Action Button -->
            <a href="show_data.php" class="btn btn-primary btn-lg w-100 py-3 fw-semibold shadow-sm d-flex align-items-center justify-content-center gap-2">
                <span>Explore Titanic Dataset</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</body>
</html>