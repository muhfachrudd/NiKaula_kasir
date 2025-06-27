<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NiKaula Coffee Shop - Info</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link href="assets/fontawesome/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6f4e37 0%, #8b4513 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .info-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .coffee-icon {
            color: #6f4e37;
            font-size: 4rem;
        }
        .btn-coffee {
            background: #6f4e37;
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            transition: all 0.3s;
        }
        .btn-coffee:hover {
            background: #8b4513;
            color: white;
            transform: translateY(-2px);
        }
        .login-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #6f4e37;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center min-vh-100 align-items-center">
            <div class="col-md-8">
                <div class="info-card p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-coffee coffee-icon"></i>
                        <h1 class="mt-3 mb-0" style="color: #6f4e37;">NiKaula Coffee Shop</h1>
                        <p class="text-muted">Modern POS System</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="login-info mb-4">
                                <h5><i class="fas fa-key mr-2"></i>Login Information</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-6"><strong>Username:</strong></div>
                                    <div class="col-6"><code>admin</code></div>
                                </div>
                                <div class="row">
                                    <div class="col-6"><strong>Password:</strong></div>
                                    <div class="col-6"><code>admin123</code></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="login-info mb-4">
                                <h5><i class="fas fa-store mr-2"></i>Shop Information</h5>
                                <hr>
                                <p class="mb-1"><strong>Name:</strong> NiKaula Coffee Shop</p>
                                <p class="mb-1"><strong>Address:</strong> Jl. Coffee Street No. 123</p>
                                <p class="mb-1"><strong>Phone:</strong> 021-1234567</p>
                            </div>
                        </div>
                    </div>

                    <div class="login-info mb-4">
                        <h5><i class="fas fa-coffee mr-2"></i>Menu Items Available</h5>
                        <hr>
                        <div class="row text-center">
                            <div class="col-3">
                                <div class="p-3">
                                    <i class="fas fa-coffee fa-2x mb-2" style="color: #6f4e37;"></i>
                                    <br><strong>8</strong><br>Coffee Items
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="p-3">
                                    <i class="fas fa-glass-whiskey fa-2x mb-2" style="color: #6f4e37;"></i>
                                    <br><strong>8</strong><br>Beverages
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="p-3">
                                    <i class="fas fa-bread-slice fa-2x mb-2" style="color: #6f4e37;"></i>
                                    <br><strong>16</strong><br>Food Items
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="p-3">
                                    <i class="fas fa-birthday-cake fa-2x mb-2" style="color: #6f4e37;"></i>
                                    <br><strong>6</strong><br>Desserts
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <a href="create_tables.php" class="btn btn-coffee mr-3">
                            <i class="fas fa-database mr-2"></i>Setup Database
                        </a>
                        <a href="login.php" class="btn btn-coffee mr-3">
                            <i class="fas fa-sign-in-alt mr-2"></i>Go to Login
                        </a>
                        <a href="test_database.php" class="btn btn-outline-secondary">
                            <i class="fas fa-vial mr-2"></i>Test System
                        </a>
                    </div>

                    <div class="text-center mt-4">
                        <small class="text-muted">
                            <i class="fas fa-clock mr-1"></i>
                            Setup completed on <?php echo date('F j, Y'); ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
