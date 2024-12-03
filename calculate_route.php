<?php
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

?>