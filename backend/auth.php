<?php
session_start();

$users = [
    'zandro' => [
        'password' => '$2y$12$cNIV722tdG71S5rmQjwic.6Jdh32eQ0Q/WU6z.lNVbVSrPDbk54wa',
        'name' => 'Zandro Sean D. Animos',
        'role' => 'Project Manager / Data Analyst'
    ],
    'sam' => [
        'password' => '$2y$12$6Wgq0k8lQLKXz5OC97KHRu5N6QdUQ9MLOvQCDiDZi3ehSYtXRBwda',
        'name' => 'Sam Aisele A. Austria',
        'role' => 'Frontend Developer / Lead Documenter'
    ],
    'jaypee' => [
        'password' => '$2y$12$7qRY5Oqx8fRVi3xTcehxTuZSO2xwqp/ocKEjLQo4fxuBFaeJZZrlm',
        'name' => 'Jaypee D. Cervantes',
        'role' => 'UI/UX Designer / Database Manager'
    ],
    'lorenzo' => [
        'password' => '$2y$12$qHzhsJIbpNJDYBHzi9YjDOvAX88vTGQUS5MNMRyEBiCEaZI3ZGoYa',
        'name' => 'Lorenzo M. Chavez',
        'role' => 'Backend Developer / IoT Developer'
    ]
];

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    header('Location: ../pages/login.php?error=empty');
    exit;
}

$key = strtolower($username);

if (!isset($users[$key]) || !password_verify($password, $users[$key]['password'])) {
    header('Location: ../pages/login.php?error=invalid');
    exit;
}

session_regenerate_id(true);
$_SESSION['loggedin'] = true;
$_SESSION['user_id'] = $key;
$_SESSION['username'] = $key;
$_SESSION['full_name'] = $users[$key]['name'];
$_SESSION['role'] = $users[$key]['role'];

header('Location: ../pages/home.php');
exit;
