<?php
echo "Testing installation process...\n";

// Test 1: Check if config.php can be loaded
echo "1. Testing config.php... ";
try {
    require_once 'php/config.php';
    echo "OK\n";
} catch (Exception $e) {
    echo "FAILED: " . $e->getMessage() . "\n";
    exit;
}

// Test 2: Test database connection
echo "2. Testing database connection... ";
try {
    $database = new Database();
    $conn = $database->getConnection();
    if ($conn) {
        echo "OK\n";
    } else {
        echo "FAILED: No connection\n";
        exit;
    }
} catch (Exception $e) {
    echo "FAILED: " . $e->getMessage() . "\n";
    exit;
}

// Test 3: Check if we can create database
echo "3. Testing database creation... ";
try {
    $host = 'localhost';
    $username = 'root';
    $password = '';
    
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->exec("set names utf8");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $pdo->exec("CREATE DATABASE IF NOT EXISTS pendaftaran_siswa");
    echo "OK\n";
} catch (Exception $e) {
    echo "FAILED: " . $e->getMessage() . "\n";
    exit;
}

echo "\nAll tests passed! You can proceed with installation.\n";
echo "Access: http://localhost/PWEB-SQL/php/install.php\n";
?>
