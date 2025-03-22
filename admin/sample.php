<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Vehicle Data</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- ✅ jQuery added -->
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
        $(document).ready(function () {
            $("#vehicleForm").submit(function (event) {
                event.preventDefault(); // Prevent page reload

                $.ajax({
                    url: "run_maintenance.php", // ✅ Make sure this file path is correct
                    type: "POST",
                    data: $(this).serialize(), // ✅ Serialize form data
                    dataType: "json", // ✅ Expect JSON response
                    success: function (data) {
                        $("#result").html("<pre>" + JSON.stringify(data, null, 2) + "</pre>");
                    },
                    error: function (xhr, status, error) {
                        $("#result").html("Error: " + xhr.responseText);
                        console.error("AJAX Error:", status, error);
                    }
                });
            });
        });
    </script>

</body>
</html>
