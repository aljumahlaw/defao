<?php

/**
 * Tinker Tests for Document Deletion
 * Run: php artisan tinker < test_deletion.tinker.php
 * Or: php artisan tinker (then paste commands)
 */

use App\Models\Document;
use App\Models\User;

// Test 1: Sequential Delete
echo "\n=== Test 1: Sequential Delete ===\n";
$docs = Document::limit(5)->get();
if ($docs->count() >= 2) {
    $doc1 = $docs[0];
    $doc2 = $docs[1];
    
    echo "Deleting document ID: {$doc1->id}\n";
    $result1 = $doc1->delete();
    echo "Result: " . ($result1 ? 'SUCCESS' : 'FAILED') . "\n";
    
    echo "Deleting document ID: {$doc2->id}\n";
    $result2 = $doc2->delete();
    echo "Result: " . ($result2 ? 'SUCCESS' : 'FAILED') . "\n";
    
    // Check soft deleted
    $deleted1 = Document::onlyTrashed()->find($doc1->id);
    $deleted2 = Document::onlyTrashed()->find($doc2->id);
    echo "Doc1 soft deleted: " . ($deleted1 ? 'YES' : 'NO') . "\n";
    echo "Doc2 soft deleted: " . ($deleted2 ? 'YES' : 'NO') . "\n";
} else {
    echo "Not enough documents to test\n";
}

// Test 2: Bulk Delete Simulation
echo "\n=== Test 2: Bulk Delete Simulation ===\n";
$testDocs = Document::limit(3)->get();
if ($testDocs->count() >= 3) {
    $ids = $testDocs->pluck('id')->toArray();
    echo "Deleting documents: " . implode(', ', $ids) . "\n";
    
    try {
        $count = Document::whereIn('id', $ids)->delete();
        echo "Deleted count: {$count}\n";
        echo "SUCCESS\n";
    } catch (\Exception $e) {
        echo "FAILED: " . $e->getMessage() . "\n";
    }
} else {
    echo "Not enough documents to test\n";
}

// Test 3: visibleTo Scope
echo "\n=== Test 3: visibleTo Scope ===\n";
$admin = User::where('role', 'admin')->first();
if ($admin) {
    $visibleCount = Document::visibleTo($admin)->count();
    echo "Admin visible documents: {$visibleCount}\n";
}

// Test 4: Foreign Key Check
echo "\n=== Test 4: Foreign Key Relations ===\n";
$doc = Document::first();
if ($doc) {
    echo "Document ID: {$doc->id}\n";
    echo "Tasks count: " . $doc->tasks()->count() . "\n";
    echo "DocumentTasks count: " . $doc->documentTasks()->count() . "\n";
    echo "Activities count: " . $doc->activities()->count() . "\n";
}

echo "\n=== Tests Complete ===\n";


