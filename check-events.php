<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CHECKING EVENTS ===\n";

// Check all events
$events = DB::table('events')->get();
echo "Total events: " . count($events) . "\n\n";

foreach ($events as $event) {
    echo "Event ID: " . $event->id . "\n";
    echo "Title: " . $event->title . "\n";
    echo "Type: " . $event->type . "\n";
    echo "Start Date: " . $event->start_date . "\n";
    echo "Duration: " . $event->duration . " minutes\n";
    echo "Created by: " . $event->created_by . "\n";
    echo "---------------------------------\n";
}

// Check specific event with ID 4 if it exists
$event4 = DB::table('events')->where('id', 4)->first();
echo "\n=== EVENT WITH ID 4 ===\n";
if ($event4) {
    echo "Found event with ID 4:\n";
    echo "Title: " . $event4->title . "\n";
    echo "Start Date: " . $event4->start_date . "\n";
} else {
    echo "No event with ID 4 found in the database.\n";
}

// Check API route registration
echo "\n=== API ROUTES ===\n";
$routes = Route::getRoutes();
$apiEventRoutes = [];

foreach ($routes as $route) {
    if (strpos($route->uri, 'api/events') === 0) {
        $apiEventRoutes[] = [
            'uri' => $route->uri,
            'methods' => implode('|', $route->methods),
            'action' => $route->getActionName()
        ];
    }
}

if (count($apiEventRoutes) > 0) {
    foreach ($apiEventRoutes as $route) {
        echo "URI: " . $route['uri'] . "\n";
        echo "Methods: " . $route['methods'] . "\n";
        echo "Action: " . $route['action'] . "\n";
        echo "---------------------------------\n";
    }
} else {
    echo "No API routes found for events.\n";
} 