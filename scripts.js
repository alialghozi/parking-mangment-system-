// Function to handle form submissions with success and error alerts
async function handleSubmit(url, formData, resultElementId, formElementId = null) {
  try {
      const response = await fetch(url, {
          method: 'POST',
          headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: formData
      });

      if (response.ok) {
          const data = await response.text();
          document.getElementById(resultElementId).innerHTML = `<p style="color:green;">Success: ${data}</p>`;
          // Optionally reset the form if formElementId is provided
          if (formElementId) {
              document.getElementById(formElementId).reset();
          }
      } else {
          const errorMsg = await response.text();
          document.getElementById(resultElementId).innerHTML = `<p style="color:red;">Error: ${errorMsg}</p>`;
      }
  } catch (error) {
      document.getElementById(resultElementId).innerHTML = `<p style="color:red;">Error: ${error.message}</p>`;
  }
}

// Event handler for adding parking area with slots
document.getElementById('add-area-form-with-slots').addEventListener('submit', function(event) {
  event.preventDefault();

  const areaName = document.getElementById('area_name_slots').value.trim();
  const totalSlots = document.getElementById('total_slots_slots').value.trim();

  if (!areaName || !totalSlots) {
      alert('Both Area Name and Total Slots are required.');
      return;
  }

  const formData = `area_name=${encodeURIComponent(areaName)}&total_slots=${encodeURIComponent(totalSlots)}`;
  handleSubmit('add_parking_area_with_slots.php', formData, 'area-with-slots-result', 'add-area-form-with-slots');
});

// Event handler for adding a single parking area
document.getElementById('add-area-form').addEventListener('submit', function(event) {
  event.preventDefault();

  const areaName = document.getElementById('area_name').value.trim();
  const totalSlots = document.getElementById('total_slots').value.trim();

  if (!areaName || !totalSlots) {
      alert('Both Area Name and Total Slots are required.');
      return;
  }

  const formData = `area_name=${encodeURIComponent(areaName)}&total_slots=${encodeURIComponent(totalSlots)}`;
  handleSubmit('add_parking_area.php', formData, 'area-result', 'add-area-form');
});

// Event handler for adding a single parking slot
document.getElementById('add-slot-form').addEventListener('submit', function(event) {
  event.preventDefault();

  const slotNumber = document.getElementById('slot_number').value.trim();
  const areaId = document.getElementById('area_id').value.trim();
  const isAvailable = document.getElementById('is_available').value;

  if (!slotNumber || !areaId) {
      alert('Slot Number and Area ID are required.');
      return;
  }

  const formData = `slot_number=${encodeURIComponent(slotNumber)}&area_id=${encodeURIComponent(areaId)}&is_available=${encodeURIComponent(isAvailable)}`;
  handleSubmit('add_parking_slot.php', formData, 'slot-result', 'add-slot-form');
});

// Event handler for updating a parking slot
document.getElementById('update-slot-form').addEventListener('submit', function(event) {
  event.preventDefault();

  const slotId = document.getElementById('slot_id').value.trim();
  const slotNumber = document.getElementById('slot_number').value.trim();
  const isAvailable = document.getElementById('is_available').value;

  if (!slotId || !slotNumber) {
      alert('Slot ID and Slot Number are required.');
      return;
  }

  const formData = `slot_id=${encodeURIComponent(slotId)}&slot_number=${encodeURIComponent(slotNumber)}&is_available=${encodeURIComponent(isAvailable)}`;
  handleSubmit('update_parking_slot.php', formData, 'slot-result', 'update-slot-form');
});

// Event handler for updating vehicle entry
document.getElementById('update-entry-form').addEventListener('submit', function(event) {
  event.preventDefault();

  const vehicleId = document.getElementById('update_vehicle_id').value.trim();
  const licensePlate = document.getElementById('update_license_plate').value.trim();
  const vehicleType = document.getElementById('update_vehicle_type').value.trim();

  if (!vehicleId || !licensePlate || !vehicleType) {
      alert('Vehicle ID, License Plate, and Vehicle Type are required.');
      return;
  }

  const formData = `action=update&vehicle_id=${encodeURIComponent(vehicleId)}&license_plate=${encodeURIComponent(licensePlate)}&vehicle_type=${encodeURIComponent(vehicleType)}`;
  handleSubmit('vehicle_entry.php', formData, 'vehicle-result', 'update-entry-form');
});

// Event handler for adding a vehicle entry
document.getElementById('vehicle-entry-form').addEventListener('submit', function(event) {
  event.preventDefault();

  const licensePlate = document.getElementById('license_plate').value.trim();
  const vehicleType = document.getElementById('vehicle_type').value.trim();

  if (!licensePlate || !vehicleType) {
      alert('Both License Plate and Vehicle Type are required.');
      return;
  }

  const formData = `action=add&license_plate=${encodeURIComponent(licensePlate)}&vehicle_type=${encodeURIComponent(vehicleType)}`;
  handleSubmit('vehicle_entry.php', formData, 'vehicle-result', 'vehicle-entry-form');
});

// Event handler for deleting a vehicle entry
document.getElementById('delete-entry-form').addEventListener('submit', function(event) {
  event.preventDefault();

  const vehicleId = document.getElementById('delete_vehicle_id').value.trim();

  if (!vehicleId) {
      alert('Vehicle ID is required.');
      return;
  }

  const formData = `action=delete&vehicle_id=${encodeURIComponent(vehicleId)}`;
  handleSubmit('vehicle_entry.php', formData, 'vehicle-result', 'delete-entry-form');
});

// Event listeners for generating reports
document.getElementById('slot-occupancy-btn').addEventListener('click', function() {
  generateReport('slot_occupancy');
});

document.getElementById('revenue-report-btn').addEventListener('click', function() {
  generateReport('revenue');
});

document.getElementById('vehicle-log-btn').addEventListener('click', function() {
  generateReport('vehicle_log');
});

// Function to generate reports
function generateReport(reportType) {
  fetch(`generate_report.php?report=${reportType}`)
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }
        return response.text();
    })
    .then(data => {
        console.log('Report Data:', data); // Log the returned report data for debugging
        document.getElementById('report-display').innerHTML = data; // Display report data
    })
    .catch(error => {
        console.error('Error generating report:', error);
        document.getElementById('report-display').innerHTML = `<p style="color:red;">Error: ${error.message}</p>`;
    });
}

// Event listeners for report buttons
document.getElementById('slot-occupancy-btn').addEventListener('click', function() {
  generateReport('slot_occupancy');
});

document.getElementById('revenue-report-btn').addEventListener('click', function() {
  generateReport('revenue');
});

document.getElementById('vehicle-log-btn').addEventListener('click', function() {
  generateReport('vehicle_log');
});

// Function to generate reports
function generateReport(reportType) {
  fetch(`generate_report.php?report=${reportType}`)
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }
        return response.text();
    })
    .then(data => {
        console.log('Report Data:', data); // Log the returned report data for debugging
        document.getElementById('report-display').innerHTML = data; // Display report data
    })
    .catch(error => {
        console.error('Error generating report:', error);
        document.getElementById('report-display').innerHTML = `<p style="color:red;">Error: ${error.message}</p>`;
    });
}

// Event listeners for report buttons
document.getElementById('slot-occupancy-btn').addEventListener('click', function() {
  generateReport('slot_occupancy');
});

document.getElementById('revenue-report-btn').addEventListener('click', function() {
  generateReport('revenue');
});

document.getElementById('vehicle-log-btn').addEventListener('click', function() {
  generateReport('vehicle_log');
});

// Function to generate reports
function generateReport(reportType) {
  fetch(`generate_report.php?report=${reportType}`)
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }
        return response.text();
    })
    .then(data => {
        console.log('Report Data:', data); // Log the returned report data for debugging
        document.getElementById('report-display').innerHTML = data; // Display report data
    })
    .catch(error => {
        console.error('Error generating report:', error);
        document.getElementById('report-display').innerHTML = `<p style="color:red;">Error: ${error.message}</p>`;
    });
}

// Event listeners for report buttons
document.getElementById('slot-occupancy-btn').addEventListener('click', function() {
  generateReport('slot_occupancy');
});

document.getElementById('revenue-report-btn').addEventListener('click', function() {
  generateReport('revenue');
});

document.getElementById('vehicle-log-btn').addEventListener('click', function() {
  generateReport('vehicle_log');
});

// Function to generate reports
function generateReport(reportType) {
  fetch(`generate_report.php?report=${reportType}`)
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }
        return response.text();
    })
    .then(data => {
        console.log('Report Data:', data); // Log the returned report data for debugging
        document.getElementById('report-display').innerHTML = data; // Display report data
    })
    .catch(error => {
        console.error('Error generating report:', error);
        document.getElementById('report-display').innerHTML = `<p style="color:red;">Error: ${error.message}</p>`;
    });
}

// Event listeners for report buttons
document.getElementById('slot-occupancy-btn').addEventListener('click', function() {
  generateReport('slot_occupancy');
});

document.getElementById('revenue-report-btn').addEventListener('click', function() {
  generateReport('revenue');
});

document.getElementById('vehicle-log-btn').addEventListener('click', function() {
  generateReport('vehicle_log');
});

// Function to generate reports
function generateReport(reportType) {
  fetch(`generate_report.php?report=${reportType}`)
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }
        return response.text();
    })
    .then(data => {
        console.log('Report Data:', data); // Log the returned report data for debugging
        document.getElementById('report-display').innerHTML = data; // Display report data
    })
    .catch(error => {
        console.error('Error generating report:', error);
        document.getElementById('report-display').innerHTML = `<p style="color:red;">Error: ${error.message}</p>`;
    });
}

// Event listeners for report buttons
document.getElementById('slot-occupancy-btn').addEventListener('click', function() {
  generateReport('slot_occupancy');
});

document.getElementById('revenue-report-btn').addEventListener('click', function() {
  generateReport('revenue');
});

document.getElementById('vehicle-log-btn').addEventListener('click', function() {
  generateReport('vehicle_log');
});

// Function to generate reports
function generateReport(reportType) {
  fetch(`generate_report.php?report=${reportType}`)
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }
        return response.text();
    })
    .then(data => {
        console.log('Report Data:', data); // Log the returned report data for debugging
        document.getElementById('report-display').innerHTML = data; // Display report data
    })
    .catch(error => {
        console.error('Error generating report:', error);
        document.getElementById('report-display').innerHTML = `<p style="color:red;">Error: ${error.message}</p>`;
    });
}

// Event listeners for report buttons
document.getElementById('slot-occupancy-btn').addEventListener('click', function() {
  generateReport('slot_occupancy');
});

document.getElementById('revenue-report-btn').addEventListener('click', function() {
  generateReport('revenue');
});

document.getElementById('vehicle-log-btn').addEventListener('click', function() {
  generateReport('vehicle_log');
});

// Function to generate reports
function generateReport(reportType) {
  fetch(`generate_report.php?report=${reportType}`)
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }
        return response.text();
    })
    .then(data => {
        console.log('Report Data:', data); // Log the returned report data for debugging
        document.getElementById('report-display').innerHTML = data; // Display report data
    })
    .catch(error => {
        console.error('Error generating report:', error);
        document.getElementById('report-display').innerHTML = `<p style="color:red;">Error: ${error.message}</p>`;
    });
}

// Event listeners for report buttons
document.getElementById('slot-occupancy-btn').addEventListener('click', function() {
  generateReport('slot_occupancy');
});

document.getElementById('revenue-report-btn').addEventListener('click', function() {
  generateReport('revenue');
});

document.getElementById('vehicle-log-btn').addEventListener('click', function() {
  generateReport('vehicle_log');
});

// Function to generate reports
function generateReport(reportType) {
  fetch(`generate_report.php?report=${reportType}`)
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }
        return response.text();
    })
    .then(data => {
        console.log('Report Data:', data); // Log the returned report data for debugging
        document.getElementById('report-display').innerHTML = data; // Display report data
    })
    .catch(error => {
        console.error('Error generating report:', error);
        document.getElementById('report-display').innerHTML = `<p style="color:red;">Error: ${error.message}</p>`;
    });
}

// Event listeners for report buttons
document.getElementById('slot-occupancy-btn').addEventListener('click', function() {
  generateReport('slot_occupancy');
});

document.getElementById('revenue-report-btn').addEventListener('click', function() {
  generateReport('revenue');
});

document.getElementById('vehicle-log-btn').addEventListener('click', function() {
  generateReport('vehicle_log');
});

// Function to generate reports
function generateReport(reportType) {
  fetch(`generate_report.php?report=${reportType}`)
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }
        return response.text();
    })
    .then(data => {
        console.log('Report Data:', data); // Log the returned report data for debugging
        document.getElementById('report-display').innerHTML = data; // Display report data
    })
    .catch(error => {
        console.error('Error generating report:', error);
        document.getElementById('report-display').innerHTML = `<p style="color:red;">Error: ${error.message}</p>`;
    });
}

// Event listeners for report buttons
document.getElementById('slot-occupancy-btn').addEventListener('click', function() {
  generateReport('slot_occupancy');
});

document.getElementById('revenue-report-btn').addEventListener('click', function() {
  generateReport('revenue');
});

document.getElementById('vehicle-log-btn').addEventListener('click', function() {
  generateReport('vehicle_log');
});

// Function to generate reports
function generateReport(reportType) {
  fetch(`generate_report.php?report=${reportType}`)
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }
        return response.text();
    })
    .then(data => {
        console.log('Report Data:', data); // Log the returned report data for debugging
        document.getElementById('report-display').innerHTML = data; // Display report data
    })
    .catch(error => {
        console.error('Error generating report:', error);
        document.getElementById('report-display').innerHTML = `<p style="color:red;">Error: ${error.message}</p>`;
    });
}

// Event listeners for report buttons
document.getElementById('slot-occupancy-btn').addEventListener('click', function() {
  generateReport('slot_occupancy');
});

document.getElementById('revenue-report-btn').addEventListener('click', function() {
  generateReport('revenue');
});

document.getElementById('vehicle-log-btn').addEventListener('click', function() {
  generateReport('vehicle_log');
});

    


// Event listener for checking slot availability
document.getElementById('check-availability-btn').addEventListener('click', function() {
  const slotNumber = document.getElementById('check_slot_number').value.trim();

  if (!slotNumber) {
      alert('Please enter a slot number.');
      return;
  }

  // Call the PHP file to check availability
  fetch(`check_availability.php?slot_number=${encodeURIComponent(slotNumber)}`)
      .then(response => response.json())
      .then(data => {
          const resultDiv = document.getElementById('availability-result');
          if (data.available) {
              resultDiv.innerHTML = `<p>Slot ${slotNumber} is available.</p>`;
              document.getElementById('slot_number').value = slotNumber; // Pre-fill slot number
              document.getElementById('slot-reservation-form').style.display = 'block'; // Show reservation form
          } else {
              resultDiv.innerHTML = `<p>Slot ${slotNumber} is not available.</p>`;
              document.getElementById('slot-reservation-form').style.display = 'none'; // Hide reservation form
          }
      })
      .catch(error => {
          console.error('Error checking availability:', error);
      });
});

// Handle slot reservation submission
document.getElementById('slot-reservation-form').addEventListener('submit', function(event) {
  event.preventDefault();

  const slotNumber = document.getElementById('slot_number').value.trim();
  const areaName = document.getElementById('area_name').value.trim();

  if (!slotNumber || !areaName) {
      alert('Both Slot Number and Area Name are required.');
      return;
  }

  // Send reservation request to the server
  const formData = `slot_number=${encodeURIComponent(slotNumber)}&area_name=${encodeURIComponent(areaName)}`;
  handleSubmit('reserve_slot.php', formData, 'reservation-result', 'slot-reservation-form');
});
