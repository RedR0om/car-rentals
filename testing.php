<?php

class Graph
{
    private $graph = [];

    // Add a new edge with a distance
    public function addEdge($start, $end, $distance)
    {
        $this->graph[$start][$end] = $distance;
        $this->graph[$end][$start] = $distance; // Assuming the graph is undirected
    }

    // Dijkstra's algorithm
    public function dijkstra($source)
    {
        $dist = [];
        $previous = [];
        $queue = new SplPriorityQueue();

        // Initialize distances and previous nodes
        foreach ($this->graph as $vertex => $adj) {
            $dist[$vertex] = INF;  // Set all distances to infinity initially
            $previous[$vertex] = null; // No known previous node yet
        }
        $dist[$source] = 0;  // Distance to source is 0

        // Enqueue the source node with a priority of 0
        $queue->insert($source, 0);

        while (!$queue->isEmpty()) {
            // Extract the node with the smallest distance (highest priority)
            $min = $queue->extract();

            if (!isset($this->graph[$min])) continue;

            // Loop through all neighboring nodes of the current node
            foreach ($this->graph[$min] as $neighbor => $cost) {
                $alt = $dist[$min] + $cost;  // Calculate the new distance

                // If a shorter path is found
                if ($alt < $dist[$neighbor]) {
                    $dist[$neighbor] = $alt;  // Update the shortest distance
                    $previous[$neighbor] = $min;  // Update the previous node
                    $queue->insert($neighbor, -$alt); // Reinsert the neighbor with a new priority
                }
            }
        }

        return [$dist, $previous]; // Return distances and paths
    }

    // Generate the shortest path
    public function shortestPath($distances, $previous, $destination)
    {
        $path = [];
        $current = $destination;

        while ($current) {
            array_unshift($path, $current);
            $current = $previous[$current];
        }

        return $path;
    }
}

// Create a new graph instance
$graph = new Graph();

// Define your own nodes and distances (edges)
$graph->addEdge(1, 2, 5); // Rental Center (Node 1) to Customer Pickup Point (Node 2) = 5 km
$graph->addEdge(2, 3, 8); // Customer Pickup Point (Node 2) to Customer Home (Node 3) = 8 km
$graph->addEdge(2, 4, 4); // Customer Pickup Point (Node 2) to Repair Center (Node 4) = 4 km
$graph->addEdge(3, 5, 6); // Customer Home (Node 3) to Drop-off Point (Node 5) = 6 km
$graph->addEdge(4, 5, 7); // Repair Center (Node 4) to Drop-off Point (Node 5) = 7 km

// Find the shortest path from the Rental Center (Node 1) to Drop-off Point (Node 5)
list($distances, $previous) = $graph->dijkstra(1);

// Get the shortest path to the Drop-off Point
$path = $graph->shortestPath($distances, $previous, 5);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shortest Path Visualization</title>
    <style>
        svg {
            width: 400px;
            height: 400px;
            border: 1px solid black;
            margin: 20px;
        }

        circle {
            fill: #4285F4;
        }

        line {
            stroke: #000;
            stroke-width: 2;
        }

        text {
            font-family: Arial, sans-serif;
            font-size: 14px;
            fill: #000;
        }
    </style>
</head>

<body>

    <h2>Shortest Path Visualization</h2>

    <svg viewBox="0 0 400 400">
        <!-- Define Nodes as Circles -->
        <circle cx="50" cy="50" r="15"></circle> <!-- Node 1 -->
        <circle cx="150" cy="150" r="15"></circle> <!-- Node 2 -->
        <circle cx="250" cy="250" r="15"></circle> <!-- Node 3 -->
        <circle cx="50" cy="300" r="15"></circle> <!-- Node 4 -->
        <circle cx="350" cy="50" r="15"></circle> <!-- Node 5 -->

        <!-- Define Labels for Nodes -->
        <text x="45" y="45">1</text>
        <text x="145" y="145">2</text>
        <text x="245" y="245">3</text>
        <text x="45" y="295">4</text>
        <text x="345" y="45">5</text>

        <!-- Define Edges as Lines (Distances) -->
        <line x1="50" y1="50" x2="150" y2="150"></line> <!-- 1 -> 2 -->
        <line x1="150" y1="150" x2="250" y2="250"></line> <!-- 2 -> 3 -->
        <line x1="150" y1="150" x2="50" y2="300"></line> <!-- 2 -> 4 -->
        <line x1="250" y1="250" x2="350" y2="50"></line> <!-- 3 -> 5 -->
        <line x1="50" y1="300" x2="350" y2="50"></line> <!-- 4 -> 5 -->

        <!-- Draw the Shortest Path in Red -->
        <?php if (in_array(1, $path) && in_array(2, $path)): ?>
            <line x1="50" y1="50" x2="150" y2="150" stroke="red" stroke-width="4"></line>
        <?php endif; ?>
        <?php if (in_array(2, $path) && in_array(4, $path)): ?>
            <line x1="150" y1="150" x2="50" y2="300" stroke="red" stroke-width="4"></line>
        <?php endif; ?>
        <?php if (in_array(4, $path) && in_array(5, $path)): ?>
            <line x1="50" y1="300" x2="350" y2="50" stroke="red" stroke-width="4"></line>
        <?php endif; ?>
    </svg>

</body>

</html>