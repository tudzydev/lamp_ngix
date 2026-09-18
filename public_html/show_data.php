<?php
// Database Connection
$servername = "db";
$username = "admin";
$password = "1234";
$dbname = "sample_db";

$dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch Summary Stats
$statsSql = "SELECT 
    COUNT(*) AS total,
    SUM(CASE WHEN Survived = 1 THEN 1 ELSE 0 END) AS survived_count,
    SUM(CASE WHEN Survived = 0 THEN 1 ELSE 0 END) AS deceased_count,
    AVG(Age) AS avg_age,
    AVG(Fare) AS avg_fare
FROM titanic";

try {
    $statsStmt = $pdo->query($statsSql);
    $stats = $statsStmt ? $statsStmt->fetch() : false;
} catch (PDOException $e) {
    $stats = false;
}

if (!$stats) {
    $stats = [
        'total' => 0,
        'survived_count' => 0,
        'deceased_count' => 0,
        'avg_age' => 0,
        'avg_fare' => 0
    ];
}

$total = (int)($stats['total'] ?? 0);
$survived = (int)($stats['survived_count'] ?? 0);
$deceased = (int)($stats['deceased_count'] ?? 0);
$survivalRate = $total > 0 ? round(($survived / $total) * 100, 1) : 0;
$avgFare = number_format((float)($stats['avg_fare'] ?? 0), 2);
$avgAge = number_format((float)($stats['avg_age'] ?? 0), 1);

// Fetch Data from 'titanic' Table
$sql = "SELECT * FROM titanic ORDER BY `index` ASC";
try {
    $stmt = $pdo->query($sql);
    $rows = $stmt ? $stmt->fetchAll() : [];
} catch (PDOException $e) {
    $rows = [];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Titanic Passenger Data Explorer</title>
    <!-- Google Fonts & Bootstrap 5 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- DataTables CSS for Bootstrap 5 -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f4f6fb;
            color: #2d3748;
            min-height: 100vh;
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .stat-card {
            border: none;
            border-radius: 1rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            background: #ffffff;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .table-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
            background: #ffffff;
            overflow: hidden;
        }

        .table thead th {
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            background-color: #f8fafc;
            color: #64748b;
            border-bottom: 2px solid #e2e8f0;
            padding: 1rem 0.75rem;
            white-space: nowrap;
        }

        .table tbody td {
            vertical-align: middle;
            font-size: 0.9rem;
            padding: 0.85rem 0.75rem;
        }

        .badge-pill {
            padding: 0.35em 0.75em;
            font-weight: 600;
            border-radius: 50rem;
            font-size: 0.78rem;
        }

        .filter-box {
            background-color: #f8fafc;
            border-radius: 0.75rem;
            padding: 1rem;
            border: 1px solid #e2e8f0;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .dataTables_filter input {
            border-radius: 0.5rem;
            padding: 0.375rem 0.75rem;
            border: 1px solid #cbd5e1;
        }

        .dataTables_length select {
            border-radius: 0.5rem;
            padding: 0.375rem 1.75rem 0.375rem 0.75rem;
            border: 1px solid #cbd5e1;
        }
    </style>
</head>

<body>

    <!-- Navigation Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3 shadow-sm mb-4">
        <div class="container-fluid px-lg-5">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                <i class="bi bi-water text-primary fs-4"></i>
                <span>Titanic Explorer</span>
            </a>
            <div class="d-flex align-items-center gap-2">
                <a href="index.php" class="btn btn-sm btn-outline-light d-flex align-items-center gap-1">
                    <i class="bi bi-house-door"></i> Home
                </a>
                <button onclick="window.location.reload();" class="btn btn-sm btn-primary d-flex align-items-center gap-1">
                    <i class="bi bi-arrow-clockwise"></i> Refresh
                </button>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-lg-5 pb-5">
        <!-- Page Title -->
        <div class="d-md-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Titanic Passenger Dataset</h3>
                <p class="text-muted mb-0">Overview and demographic breakdown of passengers aboard RMS Titanic</p>
            </div>
            <div class="mt-3 mt-md-0">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill">
                    <i class="bi bi-database me-1"></i> Database: sample_db
                </span>
            </div>
        </div>

        <!-- Metrics / Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="card stat-card p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold">Total Records</span>
                            <h3 class="fw-bold mb-0 mt-1"><?php echo number_format($total); ?></h3>
                        </div>
                        <div class="stat-icon bg-primary-subtle text-primary">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card stat-card p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold">Survived</span>
                            <h3 class="fw-bold text-success mb-0 mt-1"><?php echo number_format($survived); ?></h3>
                            <span class="text-muted small"><?php echo $survivalRate; ?>% Survival Rate</span>
                        </div>
                        <div class="stat-icon bg-success-subtle text-success">
                            <i class="bi bi-heart-pulse-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card stat-card p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold">Victims</span>
                            <h3 class="fw-bold text-danger mb-0 mt-1"><?php echo number_format($deceased); ?></h3>
                            <span class="text-muted small"><?php echo $total > 0 ? (100 - $survivalRate) : 0; ?>% Loss Rate</span>
                        </div>
                        <div class="stat-icon bg-danger-subtle text-danger">
                            <i class="bi bi-x-circle-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card stat-card p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold">Avg. Fare & Age</span>
                            <h3 class="fw-bold text-info mb-0 mt-1">$<?php echo $avgFare; ?></h3>
                            <span class="text-muted small">Avg. Age: <?php echo $avgAge; ?> yrs</span>
                        </div>
                        <div class="stat-icon bg-info-subtle text-info">
                            <i class="bi bi-ticket-detailed-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="card table-card p-4">
            <!-- Custom Quick Filters -->
            <div class="filter-box mb-4">
                <div class="row g-3 align-items-center">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted mb-1"><i class="bi bi-funnel me-1"></i> Survival</label>
                        <select id="filter-survival" class="form-select form-select-sm">
                            <option value="">All Passengers</option>
                            <option value="Survived">Survived Only</option>
                            <option value="Victim">Victims Only</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted mb-1"><i class="bi bi-gender-ambiguous me-1"></i> Gender</label>
                        <select id="filter-gender" class="form-select form-select-sm">
                            <option value="">All Genders</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted mb-1"><i class="bi bi-layers me-1"></i> Ticket Class</label>
                        <select id="filter-class" class="form-select form-select-sm">
                            <option value="">All Classes</option>
                            <option value="1st Class">1st Class (Luxury)</option>
                            <option value="2nd Class">2nd Class</option>
                            <option value="3rd Class">3rd Class</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button id="btn-reset-filters" class="btn btn-sm btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-1" style="height: 31px;">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <?php if (!empty($rows)): ?>
                <div class="table-responsive">
                    <table id="titanicTable" class="table table-hover align-middle w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID</th>
                                <th>Status</th>
                                <th>Class</th>
                                <th>Passenger Name</th>
                                <th>Gender</th>
                                <th>Age</th>
                                <th>Sib/Sp</th>
                                <th>Par/Ch</th>
                                <th>Ticket</th>
                                <th>Fare</th>
                                <th>Cabin</th>
                                <th>Port</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $row): ?>
                                <?php
                                $survivedVal = (int)$row['Survived'];
                                $pclassVal = (int)$row['Pclass'];
                                $sexVal = strtolower(trim((string)$row['Sex']));
                                $embarkedVal = strtoupper(trim((string)$row['Embarked']));

                                // Embarked Port Mapping
                                $portName = 'Unknown';
                                if ($embarkedVal === 'S') $portName = 'Southampton';
                                elseif ($embarkedVal === 'C') $portName = 'Cherbourg';
                                elseif ($embarkedVal === 'Q') $portName = 'Queenstown';
                                ?>
                                <tr>
                                    <td class="text-muted fw-semibold"><?php echo htmlspecialchars((string)$row['index']); ?></td>
                                    <td><span class="badge bg-light text-dark border">#<?php echo htmlspecialchars((string)$row['PassengerId']); ?></span></td>
                                    <td>
                                        <?php if ($survivedVal === 1): ?>
                                            <span class="badge badge-pill bg-success-subtle text-success border border-success-subtle d-inline-flex align-items-center gap-1">
                                                <i class="bi bi-check-circle-fill"></i> Survived
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-pill bg-danger-subtle text-danger border border-danger-subtle d-inline-flex align-items-center gap-1">
                                                <i class="bi bi-x-circle-fill"></i> Victim
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($pclassVal === 1): ?>
                                            <span class="badge badge-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                                                <i class="bi bi-star-fill text-warning me-1"></i> 1st Class
                                            </span>
                                        <?php elseif ($pclassVal === 2): ?>
                                            <span class="badge badge-pill bg-primary-subtle text-primary border border-primary-subtle">
                                                2nd Class
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-pill bg-secondary-subtle text-secondary-emphasis border">
                                                3rd Class
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="fw-semibold text-dark">
                                        <?php echo htmlspecialchars((string)$row['Name']); ?>
                                    </td>
                                    <td>
                                        <?php if ($sexVal === 'female'): ?>
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">
                                                <i class="bi bi-gender-female"></i> Female
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">
                                                <i class="bi bi-gender-male"></i> Male
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($row['Age'] !== null && $row['Age'] !== ''): ?>
                                            <span class="fw-medium"><?php echo htmlspecialchars((string)$row['Age']); ?></span> <span class="text-muted small">yrs</span>
                                        <?php else: ?>
                                            <span class="text-muted fst-italic small">Unknown</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars((string)$row['SibSp']); ?></td>
                                    <td><?php echo htmlspecialchars((string)$row['Parch']); ?></td>
                                    <td><code><?php echo htmlspecialchars((string)$row['Ticket']); ?></code></td>
                                    <td class="fw-semibold text-success">
                                        $<?php echo number_format((float)$row['Fare'], 2); ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['Cabin'])): ?>
                                            <span class="badge bg-light text-secondary border"><?php echo htmlspecialchars((string)$row['Cabin']); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($embarkedVal)): ?>
                                            <span class="badge bg-light text-dark border" title="<?php echo htmlspecialchars($portName); ?>">
                                                <i class="bi bi-geo-alt text-primary me-1"></i><?php echo htmlspecialchars($embarkedVal); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <div class="stat-icon bg-light text-muted mx-auto mb-3" style="width: 64px; height: 64px; font-size: 2rem;">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <h5 class="fw-bold">No Records Found</h5>
                    <p class="text-muted mb-3">There are no passenger records available in the database.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#titanicTable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search passenger, ticket, name...",
                    lengthMenu: "Show _MENU_ entries"
                },
                order: [[0, 'asc']]
            });

            // Survival Filter (Column 2)
            $('#filter-survival').on('change', function() {
                var val = $(this).val();
                table.column(2).search(val ? val : '', true, false).draw();
            });

            // Gender Filter (Column 5)
            $('#filter-gender').on('change', function() {
                var val = $(this).val();
                table.column(5).search(val ? val : '', true, false).draw();
            });

            // Class Filter (Column 3)
            $('#filter-class').on('change', function() {
                var val = $(this).val();
                table.column(3).search(val ? val : '', true, false).draw();
            });

            // Reset Filters
            $('#btn-reset-filters').on('click', function() {
                $('#filter-survival').val('');
                $('#filter-gender').val('');
                $('#filter-class').val('');
                table.search('').columns().search('').draw();
            });
        });
    </script>
</body>
</html>
<?php $pdo = null; ?>