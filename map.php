<?php
include('includes/config.php');

// Check if a specific route calculation is requested
if (isset($_GET['car_id']) && isset($_GET['start']) && isset($_GET['end'])) {
    // Dijkstra calculation for shortest path
    $carId = $_GET['car_id'];
    $start = $_GET['start'];
    $end = $_GET['end'];

    function dijkstra($start, $end, $dbh) {
        $distances = [];
        $previous = [];
        $queue = new SplPriorityQueue();

        // Initialize distances and add nodes to the queue
        $stmt = $dbh->query("SELECT id FROM nodes");
        while ($node = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $distances[$node['id']] = PHP_INT_MAX;
            $previous[$node['id']] = null;
            $queue->insert($node['id'], PHP_INT_MAX);
        }

        $distances[$start] = 0;
        $queue->insert($start, 0);

        while (!$queue->isEmpty()) {
            $minNode = $queue->extract();

            if ($minNode == $end) break;

            $stmt = $dbh->prepare("SELECT * FROM edges WHERE node_start = :node_start");
            $stmt->execute(['node_start' => $minNode]);

            while ($edge = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $alt = $distances[$minNode] + $edge['distance'];
                if ($alt < $distances[$edge['node_end']]) {
                    $distances[$edge['node_end']] = $alt;
                    $previous[$edge['node_end']] = $minNode;
                    $queue->insert($edge['node_end'], -$alt);
                }
            }
        }

        // Backtrack path
        $path = [];
        $u = $end;
        while (isset($previous[$u])) {
            array_unshift($path, $u);
            $u = $previous[$u];
        }
        if ($u == $start) array_unshift($path, $start);
        
        return $path;
    }

    // Execute Dijkstra and return the path as JSON
    $shortestPath = dijkstra($start, $end, $dbh);
    echo json_encode($shortestPath);
    exit;
} else if (isset($_GET['car_id'])) {
    // Fetch all nodes for the specified car_id
    $carId = $_GET['car_id'];
    $stmt = $dbh->prepare("SELECT * FROM nodes WHERE car_id = :car_id");
    $stmt->bindParam(':car_id', $carId, PDO::PARAM_INT);
    $stmt->execute();
    $nodes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($nodes);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Car Route Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.2.0/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet@1.2.0/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    <style>
        body {
            display: flex;
            margin: 0;
        }
        #map {
            height: 100vh;
            width: 70%;
        }
        #sidebar {
            width: 30%;
            padding: 10px;
            background: #f4f4f4;
            overflow-y: auto;
            height: 100vh;
        }
    </style>
</head>
<body>
    <div id="sidebar">
        <h1>Directions</h1>
        <form id="route-form">
            <label for="startNode">Start:</label>
            <select id="startNode"></select>
            <br>
            <label for="endNode">End:</label>
            <select id="endNode"></select>
            <br>
            <button type="submit">Calculate Route</button>
        </form>
        <div id="route-info"></div>
    </div>
    <div id="map"></div>

    <script>
        var map = L.map('map').setView([13.5, 122.0], 7); // Adjusted to center more accurately in Luzon, Philippines
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        const carId = 1; // Replace with dynamic car ID as needed

        // Fetch nodes for display and populate dropdowns
        fetch(`map.php?car_id=${carId}`)
            .then(response => response.json())
            .then(data => {
                const startNodeSelect = document.getElementById('startNode');
                const endNodeSelect = document.getElementById('endNode');

                data.forEach(node => {
                    const optionStart = document.createElement('option');
                    optionStart.value = node.id;
                    optionStart.textContent = node.name;
                    startNodeSelect.appendChild(optionStart);

                    const optionEnd = document.createElement('option');
                    optionEnd.value = node.id;
                    optionEnd.textContent = node.name;
                    endNodeSelect.appendChild(optionEnd);
                });
            });

        // Handle form submission
        document.getElementById('route-form').addEventListener('submit', function(event) {
            event.preventDefault();

            const startNode = document.getElementById('startNode').value;
            const endNode = document.getElementById('endNode').value;

            // Fetch shortest path between nodes
            fetch(`map.php?car_id=${carId}&start=${startNode}&end=${endNode}`)
                .then(response => response.json())
                .then(path => {
                    console.log("Shortest path:", path);
                    visualizePath(path);
                });
        });

        function visualizePath(path) {
            // Clear previous markers and polylines
            map.eachLayer(function (layer) {
                if (layer instanceof L.Marker || layer instanceof L.Polyline) {
                    map.removeLayer(layer);
                }
            });

            // Fetch nodes to place markers on the path
            fetch(`map.php?car_id=${carId}`)
                .then(response => response.json())
                .then(nodes => {
                    // Create markers for the nodes
                    const routeInfo = document.getElementById('route-info');
                    routeInfo.innerHTML = ''; // Clear previous info

                    const latlngs = [];
                    path.forEach(id => {
                        const node = nodes.find(n => n.id == id);
                        latlngs.push([node.latitude, node.longitude]);
                        L.marker([node.latitude, node.longitude]).addTo(map).bindPopup(node.name);
                        
                        // Example step info for sidebar
                        routeInfo.innerHTML += `<p>${node.name}</p>`;
                    });

                    // Draw path on the map
                    const polyline = L.polyline(latlngs, { color: 'blue', weight: 5, opacity: 0.7 }).addTo(map);
                    map.fitBounds(polyline.getBounds());

                    // Optionally, you can add a popup to the polyline
                    polyline.bindPopup("Calculated Route").openPopup();
                });
        }
    </script>
</body>
</html>
