
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <style>
    /* General Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', Arial, sans-serif;
    }

    body {
      display: flex;
      height: 100vh;
      background: linear-gradient(120deg, #f0f0f0, #e8e8e8);
      color: #333;
    }

    /* Sidebar Styles */
    .sidebar {
      width: 250px;
      background-color: #2c3e50;
      padding: 20px 0;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .sidebar h1 {
      color: #fff;
      font-size: 20px;
      margin-bottom: 30px;
      text-transform: uppercase;
    }

    .menu-item {
      width: 100%;
      padding: 15px;
      color: #bdc3c7;
      text-decoration: none;
      font-size: 16px;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: 0.3s;
    }

    .menu-item:hover {
      background-color: #34495e;
      color: #fff;
    }

    .menu-item i {
      font-size: 18px;
    }

    /* Main Content Styles */
    .main-content {
      flex: 1;
      padding: 20px;
      overflow-y: auto;
    }

    .welcome {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 20px;
    }

    .stats-section {
      display: flex;
      justify-content: space-between;
      margin-bottom: 30px;
    }

    .stat-box {
      flex: 1;
      background: white;
      border-radius: 8px;
      padding: 20px;
      text-align: center;
      margin: 0 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .stat-box h2 {
      font-size: 18px;
      color: #555;
    }

    .stat-box p {
      font-size: 36px;
      font-weight: bold;
      margin-top: 10px;
    }

    .recent-activity {
      background: white;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .recent-activity h2 {
      margin-bottom: 10px;
      font-size: 20px;
      color: #555;
    }

    .activity-item {
      padding: 10px;
      border-bottom: 1px solid #f0f0f0;
      font-size: 16px;
      color: #666;
    }

    .activity-item:last-child {
      border-bottom: none;
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <h1>Admin Panel</h1>
    <a href="admin_dashboard.php" class="menu-item"><i>🏠</i> Dashboard</a>
    <a href="approve.php" class="menu-item"><i>✔️</i> Profile Approvals</a>
    <a href="analytic.php" class="menu-item"><i>📊</i> Reports & Analytics</a>
    <a href="msg.php" class="menu-item"><i>📨</i> Messages & Notifications</a>
    <a href="set.php" class="menu-item"><i>⚙️</i> Admin Settings</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="welcome">Welcome, Admin!</div>

    <!-- Quick Stats Section -->
    <div class="stats-section">
      <div class="stat-box">
        <h2>Total Users</h2>
        <p id="total-users">0</p>
      </div>
      <div class="stat-box">
        <h2>Pending Profiles</h2>
        <p id="pending-profiles">0</p>
      </div>
      <div class="stat-box">
        <h2>Rejected Profiles</h2>
        <p id="active-profiles">0</p>
      </div>
    </div>

    <!-- Recent Activity -->
    <div class="recent-activity">
      <h2>Recent Activity</h2>
      <div id="activity-feed">
        <div class="activity-item">John Doe signed up.</div>
        <div class="activity-item">Profile approved for Jane Smith.</div>
        <div class="activity-item">Admin flagged a report.</div>
      </div>
    </div>
  </div>

  <script>
  // Fetch data from PHP script
  fetch("dashboard_data.php")
    .then(response => response.json())
    .then(data => {
        document.getElementById('total-users').textContent = data.total_users;
        document.getElementById('pending-profiles').textContent = data.pending_profiles;
        document.getElementById('active-profiles').textContent = data.active_profiles;

        // Populate activity feed dynamically
        const activityFeed = document.getElementById('activity-feed');
        activityFeed.innerHTML = ""; // Clear existing data

        data.recent_activities.forEach(activity => {
            const activityItem = document.createElement('div');
            activityItem.className = 'activity-item';
            activityItem.textContent = activity;
            activityFeed.appendChild(activityItem);
        });
    })
    .catch(error => console.error("Error fetching data:", error));
</script>

</body>
</html>
