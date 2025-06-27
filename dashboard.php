<!DOCTYPE html>
<html>
<head>
    <title>NiKaula Coffee Shop - Quick Access</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link href="assets/fontawesome/css/all.min.css" rel="stylesheet">
    <style>
        body { 
            background: linear-gradient(135deg, #6f4e37 0%, #8b4513 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card { 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .btn-coffee {
            background: #6f4e37;
            border: none;
            color: white;
            border-radius: 25px;
            transition: all 0.3s;
        }
        .btn-coffee:hover {
            background: #8b4513;
            color: white;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center min-vh-100 align-items-center">
            <div class="col-md-6">
                <div class="card p-4">
                    <div class="text-center mb-4">
                        <i class="fas fa-coffee fa-4x text-warning"></i>
                        <h2 class="mt-3" style="color: #6f4e37;">NiKaula Coffee Shop</h2>
                        <p class="text-muted">Quick Access Panel</p>
                    </div>

                    <div class="row text-center">
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <i class="fas fa-sign-in-alt fa-2x mb-3" style="color: #6f4e37;"></i>
                                    <h5>Login</h5>
                                    <p class="small">admin / admin123</p>
                                    <a href="login.php" class="btn btn-coffee">Login</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <i class="fas fa-cash-register fa-2x mb-3" style="color: #6f4e37;"></i>
                                    <h5>Simple Kasir</h5>
                                    <p class="small">Quick Test</p>
                                    <a href="simple_kasir.php" class="btn btn-coffee">Test Kasir</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <i class="fas fa-store fa-2x mb-3" style="color: #6f4e37;"></i>
                                    <h5>Full Kasir</h5>
                                    <p class="small">Complete POS</p>
                                    <a href="index.php" class="btn btn-coffee">Open Kasir</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <i class="fas fa-tools fa-2x mb-3" style="color: #6f4e37;"></i>
                                    <h5>Tools</h5>
                                    <p class="small">Debug & Setup</p>
                                    <div class="btn-group-vertical w-100">
                                        <a href="debug_kasir.php" class="btn btn-sm btn-outline-secondary mb-1">Debug</a>
                                        <a href="test_database.php" class="btn btn-sm btn-outline-secondary">Test DB</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle mr-2"></i>Status System</h6>
                        <?php
                        include 'config.php';
                        if ($conn) {
                            $barang_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM barang"));
                            $cart_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM keranjang"));
                            echo "<small>✅ Database: Connected<br>";
                            echo "☕ Menu: $barang_count items<br>";
                            echo "🛒 Cart: $cart_count items</small>";
                            mysqli_close($conn);
                        } else {
                            echo "<small>❌ Database: Not connected</small>";
                        }
                        ?>
                    </div>

                    <div class="text-center">
                        <small class="text-muted">
                            <i class="fas fa-clock mr-1"></i>
                            Last updated: <?php echo date('F j, Y g:i A'); ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
