<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get authenticated user
$user = auth()->user();
if (!$user) {
    echo "No authenticated user. Please login first.\n";
    exit(1);
}

// Get first task
$task = App\Models\DocumentTask::first();
if (!$task) {
    echo "No tasks found.\n";
    exit(1);
}

$doc = $task->document;

$result = [
    'user_id' => $user->id,
    'doc_user_id' => $doc->user_id,
    'doc_assignee_id' => $doc->assignee_id,
    'visibleTo_result' => App\Models\Document::visibleTo($user)->where('id', $doc->id)->exists(),
];

echo "=== Test Results ===\n";
echo "User ID: " . $result['user_id'] . "\n";
echo "Document User ID: " . $result['doc_user_id'] . "\n";
echo "Document Assignee ID: " . ($result['doc_assignee_id'] ?? 'NULL') . "\n";
echo "VisibleTo Result: " . ($result['visibleTo_result'] ? 'TRUE' : 'FALSE') . "\n";
echo "\n";

// Additional checks
echo "=== Additional Checks ===\n";
echo "User is creator: " . ($user->id === $doc->user_id ? 'YES' : 'NO') . "\n";
echo "User is assignee: " . ($user->id === $doc->assignee_id ? 'YES' : 'NO') . "\n";

// Policy check
$policy = new App\Policies\DocumentPolicy();
echo "Policy::view() result: " . ($policy->view($user, $doc) ? 'ALLOW' : 'DENY') . "\n";
