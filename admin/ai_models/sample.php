<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Vehicle Data</title>
</head>
<body>

    <form id="vehicleForm">
        <label for="vehicleId">Vehicle ID:</label>
        <input type="number" id="vehicleId" name="vehicleId" required>

        <label for="current_mileage">Current Mileage:</label>
        <input type="number" id="current_mileage" name="current_mileage" required>

        <button type="submit">Submit</button>
    </form>

    <div id="result"></div>

    <script>
        document.getElementById("vehicleForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent page reload
            
            let formData = new FormData(this);

            fetch("../run_maintenance.php", { // ✅ Corrected file path
                method: "POST",
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not ok " + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                document.getElementById("result").innerText = JSON.stringify(data, null, 2);
            })
            .catch(error => {
                document.getElementById("result").innerText = "Error: " + error.message;
                console.error("Error:", error);
            });
        });
    </script>

</body>
</html>
