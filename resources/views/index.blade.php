<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RFID Attendance System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center">RFID Attendance System</h1>

        <!-- Student Registration Form -->
        <div class="card mb-4">
            <div class="card-header">Register a New Student</div>
            <div class="card-body">
                <form id="registerForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Student Name</label>
                        <input type="text" id="name" class="form-control" placeholder="Enter student name" required>
                    </div>
                    <div class="mb-3">
                        <label for="uid" class="form-label">RFID UID</label>
                        <input type="text" id="uid" class="form-control" placeholder="Enter RFID UID" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
                <div id="registerResponse" class="mt-3"></div>
            </div>
        </div>

        <!-- Attendance Table -->
        <div class="card">
            <div class="card-header">Student Attendance</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>UID</th>
                            <th>Attendance Count</th>
                        </tr>
                    </thead>
                    <tbody id="attendanceTable">
                        <!-- Dynamic rows will be appended here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        const apiBaseUrl = "http://192.168.0.103:8000/api";

        async function loadAttendance() {
            try {
                console.log("Loading attendance data...");
                const response = await axios.get(`http://192.168.0.103:8000/api/attendances`);
                const logs = response.data;

                const tableBody = document.getElementById('attendanceTable');
                tableBody.innerHTML = ''; // Clear existing rows

                logs.forEach(log => {
                    const row = `
                        <tr>
                            <td>${log.student.id}</td>
                            <td>${log.student.name}</td>
                            <td>${log.student.uid}</td>
                            <td>${new Date(log.created_at).toLocaleString()}</td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            } catch (error) {
                console.error("Error loading attendance logs:", error);
            }
        }
          // Handle form submission for registration
          document.getElementById('registerForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const name = document.getElementById('name').value;
            const uid = document.getElementById('uid').value;

            try {
                // Send POST request to the API
                const response = await axios.post(`http://192.168.0.103:8000/api/register`, {
                    name: name,
                    uid: uid
                });

                // Handle success response
                if (response.status === 201) { // 201 Created
                    document.getElementById('registerResponse').innerHTML =
                        `<div class="alert alert-success">${response.data.message}</div>`;
                    document.getElementById('registerForm').reset(); // Clear form
                    loadAttendance(); // Reload attendance table
                } else {
                    // Handle unexpected success response
                    document.getElementById('registerResponse').innerHTML =
                        `<div class="alert alert-warning">Unexpected response: ${response.status}</div>`;
                }
            } catch (error) {
                // Handle error response
                if (error.response) {
                    // Server responded with an error status code
                    const errorMessage = error.response.data.message || "An unknown error occurred.";
                    document.getElementById('registerResponse').innerHTML =
                        `<div class="alert alert-danger">${errorMessage}</div>`;
                } else if (error.request) {
                    // No response from server
                    document.getElementById('registerResponse').innerHTML =
                        `<div class="alert alert-danger">No response from the server. Please try again later.</div>`;
                } else {
                    // Other errors
                    document.getElementById('registerResponse').innerHTML =
                        `<div class="alert alert-danger">Error: ${error.message}</div>`;
                }
            }
        });

        // Load attendance data on page load
        document.addEventListener('DOMContentLoaded', loadAttendance);
    </script>
</body>
</html>
