<?php
session_start();

// PROTECTION CHECK: Redirection kapag hindi pa nakalogin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

// Database Connection
require_once 'db.php';

$message = "";
$error = "";

// HANDLE FORM SUBMISSIONS (ADD & DELETE)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. ADD PLANT ITEM
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $plant_name = trim($_POST['plant_name']);
        $quantity = (int)$_POST['quantity'];
        $status = $_POST['status'];

        if (!empty($plant_name) && $quantity >= 0) {
            $stmt = $conn->prepare("INSERT INTO inventory (plant_name, quantity, status) VALUES (?, ?, ?)");
            $stmt->bind_param("sis", $plant_name, $quantity, $status);
            if ($stmt->execute()) {
                $message = "Plant added successfully!";
            } else {
                $error = "Failed to add plant item.";
            }
            $stmt->close();
        } else {
            $error = "Please fill in all fields correctly.";
        }
    }

    // 2. DELETE PLANT ITEM
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare("DELETE FROM inventory WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = "Plant item deleted successfully!";
        } else {
            $error = "Failed to delete item.";
        }
        $stmt->close();
    }
}

// FETCH ALL ITEMS FROM DATABASE
$result = $conn->query("SELECT * FROM inventory ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>VerdEX Showcase - Inventory</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .alert-success { background-color: #d1e7dd; color: #0f5132; padding: 12px; border-radius: 6px; margin-bottom: 15px; }
        .alert-danger { background-color: #f8d7da; color: #842029; padding: 12px; border-radius: 6px; margin-bottom: 15px; }
        
        .form-grid { display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 10px; align-items: end; }
        .form-group { display: flex; flex-direction: column; gap: 5px; }
        .form-group label { font-size: 13px; font-weight: 600; color: #374151; }
        .form-group input, .form-group select { padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; }
        .btn-submit { background-color: #15803d; color: white; border: none; padding: 9px 18px; border-radius: 6px; font-weight: 600; cursor: pointer; }
        .btn-submit:hover { background-color: #166534; }

        .inventory-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .inventory-table th, .inventory-table td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        .inventory-table th { background-color: #f9fafb; font-size: 13px; color: #4b5563; text-transform: uppercase; }

        .status-badge { padding: 4px 10px; border-radius: 12px; font-weight: 600; font-size: 12px; display: inline-block; }
        .status-healthy { background-color: #dcfce7; color: #15803d; }
        .status-attention { background-color: #fef3c7; color: #b45309; }
        .status-critical { background-color: #fee2e2; color: #b91c1c; }

        .btn-delete { background-color: #ef4444; color: white; border: none; padding: 5px 10px; border-radius: 4px; font-size: 12px; cursor: pointer; }
        .btn-delete:hover { background-color: #dc2626; }
    </style>
</head>
<body class="app-body">

    <!-- NAVBAR NAVIGATION -->
    <nav class="navbar">
        <div class="navbar-brand">🌱 VerdEX Showcase</div>
        <div class="navbar-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="project-description.php">Project Description</a>
            <a href="project-features.php">Features</a>
            <a href="group-members.php">Group Members</a>
            <a href="inventory.php" class="active">Inventory</a>
        </div>
        <div class="navbar-right">
            <span class="welcome-text">Hi, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>

    <main class="page-content">

        <div class="page-header">
            <h1>Plant Inventory Management</h1>
            <p class="page-subtitle">Track and monitor your hydroponic crops in real time</p>
        </div>

        <!-- Alert Notification -->
        <?php if (!empty($message)): ?>
            <div class="alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Form Card for Adding New Plant -->
        <div class="card">
            <h2>➕ Add New Plant Stock</h2>
            <form action="inventory.php" method="POST" class="form-grid">
                <input type="hidden" name="action" value="add">
                
                <div class="form-group">
                    <label for="plant_name">Plant Name</label>
                    <input type="text" id="plant_name" name="plant_name" placeholder="e.g. Butterhead Lettuce" required>
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity (Pots/Units)</label>
                    <input type="number" id="quantity" name="quantity" min="0" placeholder="0" required>
                </div>

                <div class="form-group">
                    <label for="status">Health Status</label>
                    <select id="status" name="status" required>
                        <option value="Healthy">Healthy</option>
                        <option value="Needs Attention">Needs Attention</option>
                        <option value="Critical">Critical</option>
                    </select>
                </div>

                <button type="submit" class="btn-submit">Add Plant</button>
            </form>
        </div>

        <!-- Table Display Card -->
        <div class="card">
            <h2>📦 Current Inventory List</h2>
            <table class="inventory-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Plant Name</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Date Added</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <?php 
                                $statusClass = 'status-healthy';
                                if ($row['status'] === 'Needs Attention') {
                                    $statusClass = 'status-attention';
                                } elseif ($row['status'] === 'Critical') {
                                    $statusClass = 'status-critical';
                                }
                            ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($row['plant_name']); ?></strong></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars($row['status']); ?></span></td>
                                <td><?php echo date('M d, Y', strtotime($row['date_added'])); ?></td>
                                <td>
                                    <form action="inventory.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');" style="display:inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" class="btn-delete">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: #6b7280; padding: 20px;">No inventory items found. Add some above!</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </main>

</body>
</html>