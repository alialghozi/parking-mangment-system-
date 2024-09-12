<?php
session_start();
if (isset($_SESSION['message'])) {
    echo "<div class='message-box'>" . $_SESSION['message'] . "</div>";
    unset($_SESSION['message']); // Remove the message after displaying it
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autonomous Parking System</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <!-- Hero Section -->
    <div class="hero-section">
        <nav>
            <div class="logo">
                <img src="freepik_br_f491d4a1-3c0a-42b7-9055-50e598ef1f93.png" alt="Garage Repair Logo" class="logo-image">
            </div>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="#">Features</a></li>
                <li><a href="#">Implementation</a></li>
                <li><a href="#content">Contact</a></li>
            </ul>
            <div class="sign-in"><a href="logout.php">Log out</a></div>
        </nav>
        <div class="content">
            <h1>Autonomous Parking System</h1>
            <p>Seamless and smart parking solutions that revolutionize the way we park our vehicles.</p>
        </div>
    </div>

    <!-- Container for Forms -->
    <div class="container">
        <header>
            <h1>Parking Management System</h1>
        </header>
        <div class="form-container">
            <!-- Add Parking Area Form -->
            <section class="form-box">
                <h2>Add Parking Area</h2>
                <form id="add-area-form" action="add_parking_area.php" method="POST">
                    <div class="form-group">
                        <label for="area_name">Area Name:</label>
                        <input type="text" id="area_name" name="area_name" required placeholder="East Wing">
                    </div>
                    <div class="form-group">
                        <label for="total_slots">Total Slots:</label>
                        <input type="number" id="total_slots" name="total_slots" required min="1">
                    </div>
                    <input type="hidden" name="action" value="add">
                    <button type="submit" class="btn primary-btn">Add Area</button>
                </form>
            </section>

            <!-- Vehicle Entry Form -->
            <section class="form-box">
                <h2>Vehicle Entry</h2>
                <form id="vehicle-entry-form" action="vehicle_entry.php" method="POST">
                    <div class="form-group">
                        <label for="license_plate">License Plate:</label>
                        <input type="text" id="license_plate" name="license_plate" required placeholder="ABC 1234">
                    </div>
                    <div class="form-group">
                        <label for="vehicle_type">Vehicle Type:</label>
                        <input type="text" id="vehicle_type" name="vehicle_type" required>
                    </div>
                    <div class="form-group">
                        <label for="slot_id">Slot ID:</label>
                        <input type="number" id="slot_id" name="slot_id" required>
                    </div>
                    <input type="hidden" name="action" value="add">
                    <button type="submit" class="btn primary-btn">Log Entry</button>
                </form>
            </section>
            <section class="form-box">
                <h2>Update Vehicle Entry</h2>
                <form id="update-entry-form" action="vehicle_update.php" method="POST">
                    <div class="form-group">
                        <label for="update_vehicle_id">Vehicle ID:</label>
                        <input type="text" id="update_vehicle_id" name="vehicle_id" required placeholder="Enter Vehicle ID">
                    </div>
                    <div class="form-group">
                        <label for="update_license_plate">License Plate:</label>
                        <input type="text" id="update_license_plate" name="license_plate" required placeholder="New License Plate">
                    </div>
                    <div class="form-group">
                        <label for="update_vehicle_type">Vehicle Type:</label>
                        <input type="text" id="update_vehicle_type" name="vehicle_type" required placeholder="New Vehicle Type">
                    </div>
                    <!-- Add hidden action field -->
                    <input type="hidden" name="action" value="update">
                    <button type="submit" class="btn primary-btn">Update Entry</button>
                </form>
            </section>

            <!-- Form for Deleting Vehicle Entry -->
            <section class="form-box">
                <h2>Delete Vehicle Entry</h2>
                <form id="delete-entry-form" action="vehicle_delete.php" method="POST">
                    <div class="form-group">
                        <label for="delete_vehicle_id">Vehicle ID:</label>
                        <input type="text" id="delete_vehicle_id" name="vehicle_id" required placeholder="Enter Vehicle ID">
                    </div>
                    <!-- Add hidden action field -->
                    <input type="hidden" name="action" value="delete">
                    <button type="submit" class="btn danger-btn">Delete Entry</button>
                </form>
            </section>


            <!-- Payment Processing Form -->
            <section class="form-box">
                <h2>Payment Processing</h2>
                <form id="payment-form" action="process_payment.php" method="POST">
                    <div class="form-group">
                        <label for="vehicle_id">Vehicle ID:</label>
                        <input type="text" id="vehicle_id" name="vehicle_id" required>
                        <button type="button" id="check-cost-btn" class="btn secondary-btn">Check Cost</button>
                    </div>
                    <div id="cost-display" style="display:none;">
                        <p id="cost-message"></p>
                    </div>
                    <div class="form-group" id="payment-fields" style="display:none;">
                        <label for="amount_paid">Amount Paid (RM):</label>
                        <input type="number" id="amount_paid" name="amount_paid" required min="1">
                    </div>
                    <div class="form-group" id="payment-method" style="display:none;">
                        <label for="payment_method">Payment Method:</label>
                        <input type="text" id="payment_method" name="payment_method" required>
                    </div>
                    <input type="hidden" name="action" value="pay">
                    <button type="submit" class="btn primary-btn" style="display:none;" id="submit-payment-btn">Submit Payment</button>
                </form>
            </section>
        </div>
    </div>
    <div class="container">
            <section class="report-container">
                <h2>Generate Reports</h2>
                <div class="btn-group">
                    <button class="btn secondary-btn" id="slot-occupancy-btn">Slot Occupancy Report</button>
                    <button class="btn secondary-btn" id="revenue-report-btn">Revenue Report</button>
                    <button class="btn secondary-btn" id="vehicle-log-btn">Vehicle Log Report</button>
                </div>
                <div id="report-display">
                    <p>Report data will appear here after the user generates a report.</p>
                </div>
            </section>
    </div>

    <!-- Footer Section -->
    <div class="footer-section">
        <div class="footer-content" id="content">
            <h4>Our Location</h4>
            <p>Jalan Tun Abdul Razak, 05200, Alor Setar, Kedah Darul Aman, Malaysia.</p>
            <p>Phone: +(60)196176860</p>
            <p>Email: <a href="mailto:alialialghuzi@gmail.com">alialialghuzi@gmail.com</a></p>
            <div class="footer-social">
                <a href="#"><i class="fa fa-facebook social-icon"></i></a>
                <a href="#"><i class="fa fa-twitter social-icon"></i></a>
                <a href="#"><i class="fa fa-youtube-play social-icon"></i></a>
                <a href="#"><i class="fa fa-instagram social-icon"></i></a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2024 Parking Management System. All rights reserved.</p>
        </div>
    </div>

    <!-- Scripts -->
    <script src="scripts.js"></script>
    <script>
        // Report generation and cost checking logic
        document.getElementById('check-cost-btn').addEventListener('click', function() {
            var vehicleId = document.getElementById('vehicle_id').value.trim();
            if (!vehicleId) {
                alert('Please enter a Vehicle ID');
                return;
            }
            fetch('check_cost.php', {
                method: 'POST',
                body: new FormData(document.getElementById('payment-form'))
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    document.getElementById('cost-message').innerText = data.error;
                } else {
                    document.getElementById('cost-message').innerHTML = `Total Cost: RM ${data.total_cost} (Parked for ${data.hours_parked} hours)`;
                }
                document.getElementById('cost-display').style.display = 'block';
                document.getElementById('payment-fields').style.display = 'block';
                document.getElementById('payment-method').style.display = 'block';
                document.getElementById('submit-payment-btn').style.display = 'block';
            });
        });

        document.getElementById('slot-occupancy-btn').addEventListener('click', function() {
            generateReport('slot_occupancy');
        });

        document.getElementById('revenue-report-btn').addEventListener('click', function() {
            generateReport('revenue');
        });

        document.getElementById('vehicle-log-btn').addEventListener('click', function() {
            generateReport('vehicle_log');
        });

        function generateReport(reportType) {
            fetch(`generate_report.php?report=${reportType}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('report-display').innerHTML = data;
                })
                .catch(error => {
                    console.error('Error generating report:', error);
                });
        }
    </script>
</body>
</html>
