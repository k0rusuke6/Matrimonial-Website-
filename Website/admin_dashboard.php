<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reports & Analytics</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
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
      background: linear-gradient(120deg, #f9f9f9, #e9e9e9);
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

    /* Main Content */
    .main-content {
      flex: 1;
      padding: 20px;
      overflow-y: auto;
    }

    .section-title {
      font-size: 22px;
      font-weight: bold;
      margin: 20px 0 10px;
    }

    .stats-section, .charts-section, .table-section {
      margin-bottom: 30px;
    }

    .stat-box {
      background: white;
      border-radius: 8px;
      padding: 15px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      margin-bottom: 10px;
    }

    .stat-box p {
      font-size: 16px;
      margin: 0;
    }

    .stat-box h2 {
      margin: 0;
      font-size: 28px;
      font-weight: bold;
      color: #555;
    }

    .chart-container {
      background: white;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    th, td {
      padding: 15px;
      text-align: left;
      border-bottom: 1px solid #f0f0f0;
    }

    th {
      background: #34495e;
      color: white;
    }

    button {
      padding: 10px 15px;
      border: none;
      background: #007bff;
      color: white;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #0056b3;
    }
	
  </style>
</head>
<body>
  <!-- Sidebar -->
 <div class="sidebar">
    <h1>Admin Panel</h1>
    <a href="admin_dashboard.php" class="menu-item"><i>🏠</i> Dashboard</a>
    <a href="approve.php" class="menu-item"><i>✔️</i> Profile Approvals</a>
    <a href="msg.php" class="menu-item"><i>📨</i> Messages & Notifications</a>
    <a href="#" class="menu-item logout-btn" onclick="openLogoutModal()"><i>🚪</i> Logout</a>
</div>

<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="modal">
    <div class="modal-content">
        <h2>Logout Confirmation</h2>
        <p>Are you sure you want to log out?</p>
        <div class="modal-buttons">
            <button class="confirm-btn" onclick="logout()">Yes, Logout</button>
            <button class="cancel-btn" onclick="closeLogoutModal()">Cancel</button>
        </div>
    </div>
</div>

<style>
/* Modal Styling */
/* Modal Styling */
.modal {
    display: none; /* Hidden by default */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.4); /* Dark overlay */
    align-items: center;
    justify-content: center;
}

/* Make sure the modal content is centered and styled properly */
.modal-content {
    background: white;
    width: 350px;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
    text-align: center;
    animation: fadeIn 0.3s ease-in-out;
}

.modal h2 {
    margin-bottom: 10px;
    font-size: 22px;
    color: #333;
}

.modal p {
    font-size: 16px;
    color: #666;
    margin-bottom: 20px;
}

.modal-buttons {
    display: flex;
    justify-content: center;
    gap: 15px;
}

.confirm-btn {
    background: #dc3545; /* Red color for logout */
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: 0.3s;
}

.confirm-btn:hover {
    background: #c82333;
}

.cancel-btn {
    background: #6c757d; /* Grey color for cancel */
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: 0.3s;
}

.cancel-btn:hover {
    background: #5a6268;
}

/* Smooth Fade-in Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

</style>

<script>
// Open Logout Modal
function openLogoutModal() {
    document.getElementById("logoutModal").style.display = "flex";
}

// Close Logout Modal
function closeLogoutModal() {
    document.getElementById("logoutModal").style.display = "none";
}

// Logout Function
function logout() {
    window.location.href = "admin_login.php"; // Redirect to login page
}
</script>


  <!-- Main Content -->
  <div class="main-content">
    <div class="section-title">User Statistics</div>
    <div class="stats-section">
      <div class="stat-box">
        <p>Total Registered Users</p>
        <h2>5,000</h2>
      </div>
      <div class="stat-box">
        <p>Rejected Users</p>
        <h2>4,200</h2>
      </div>
      <div class="stat-box">
        <p>Approved Profiles</p>
        <h2>3,800</h2>
      </div>
      <div class="stat-box">
        <p>Male-to-Female Ratio</p>
        <h2>60:40</h2>
      </div>
    </div>

    <div class="section-title">Profile Approvals & Rejections</div>
    <div class="charts-section">
      <div class="chart-container">
       <canvas id="approvalChart" width="300" height="300"></canvas>
      </div>
    </div>

    

    <div class="section-title">System Logs</div>
    <div class="table-section">
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Admin</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>2025-03-18</td>
            <td>Admin 1</td>
            <td>Approved a profile</td>
          </tr>
          <tr>
            <td>2025-03-17</td>
            <td>Admin 2</td>
            <td>Generated a report</td>
          </tr>
          <tr>
            <td>2025-03-16</td>
            <td>Admin 3</td>
            <td>Flagged an error log</td>
          </tr>
        </tbody>
      </table>
      <button>Download CSV</button>
    </div>
  </div>

  <script>
  // Fetch analytics data from PHP
  function fetchAnalyticsData() {
    fetch("dashboard_data.php")
      .then(response => response.json())
      .then(data => {
        // Update the stats section
        document.querySelector(".stat-box:nth-child(1) h2").textContent = data.total_users;
        document.querySelector(".stat-box:nth-child(2) h2").textContent = data.active_users;
        document.querySelector(".stat-box:nth-child(3) h2").textContent = data.approved_profiles;
        document.querySelector(".stat-box:nth-child(4) h2").textContent = data.male_to_female_ratio;

        // Update Profile Approvals & Rejections Chart
        updateApprovalChart(data.approved_profiles, data.rejected_profiles, data.pending_profiles);
      });
  }

  // Function to update Approval Chart
  function updateApprovalChart(approved, rejected, pending) {
    const ctx1 = document.getElementById('approvalChart').getContext('2d');
    new Chart(ctx1, {
      type: 'pie',
      data: {
        labels: ['Approved', 'Rejected', 'Pending'],
        datasets: [{
          data: [approved, rejected, pending],
          backgroundColor: ['#28a745', '#dc3545', '#ffc107']
        }]
      },
      options: {
        maintainAspectRatio: false,
        responsive: true,
      }
    });
  }

  // Fetch analytics data on page load
  fetchAnalyticsData();
</script>

</body>
</html>

