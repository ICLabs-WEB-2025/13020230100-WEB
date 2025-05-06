<!DOCTYPE html>
<html>
<head>
    <title>LaundryApp Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; }
        .sidebar { width: 250px; height: 100vh; background: #4A90E2; color: white; position: fixed; }
        .sidebar ul { list-style: none; padding: 0; }
        .sidebar ul li { padding: 15px; cursor: pointer; }
        .sidebar ul li:hover { background: #357ABD; }
        .content { margin-left: 250px; padding: 20px; }
        .card { background: #fff; padding: 15px; margin: 10px 0; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>LaundryApp</h2>
        <ul>
            <li><i class="fas fa-tachometer-alt"></i> Dashboard</li>
            <li><i class="fas fa-users"></i> Customers</li>
            <li><i class="fas fa-concierge-bell"></i> Services</li>
            <li><i class="fas fa-shopping-cart"></i> Orders</li>
            <li><i class="fas fa-chart-bar"></i> Reports</li>
        </ul>
    </div>
    <div class="content">
        <h1>Dashboard</h1>
        <div class="card">Total Customers: 150</div>
        <div class="card">Pending Orders: 5</div>
        <div class="card">Revenue Today: Rp 1,500,000</div>
    </div>
</body>
</html>