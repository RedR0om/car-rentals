<?php
function dijkstra($startNodeId, $endNodeId, $pdo)
{
    $dist = [];
    $prev = [];
    $nodes = [];

    // Initialize distances and nodes
    $sql = "SELECT id FROM nodes";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $dist[$row['id']] = PHP_INT_MAX; // Initialize distances to infinity
        $prev[$row['id']] = null; // Previous nodes
        $nodes[] = $row['id']; // List of all nodes
    }
    $dist[$startNodeId] = 0; // Start node distance

    while (!empty($nodes)) {
        // Get the node with the lowest distance
        $minNode = null;
        foreach ($nodes as $node) {
            if ($minNode === null || $dist[$node] < $dist[$minNode]) {
                $minNode = $node;
            }
        }

        if ($minNode === $endNodeId) {
            break; // Exit if the end node is reached
        }

        $nodes = array_diff($nodes, [$minNode]); // Remove the node from the list

        // Get edges for the current node
        $sql = "SELECT end_node_id, weight FROM edges WHERE start_node_id = :startNodeId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':startNodeId', $minNode, PDO::PARAM_INT);
        $stmt->execute();

        while ($edge = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $alt = $dist[$minNode] + $edge['weight'];
            if ($alt < $dist[$edge['end_node_id']]) {
                $dist[$edge['end_node_id']] = $alt;
                $prev[$edge['end_node_id']] = $minNode;
            }
        }
    }

    // Retrieve the path
    $path = [];
    for ($node = $endNodeId; $node !== null; $node = $prev[$node]) {
        array_unshift($path, $node);
    }
    return $path;
}
?>