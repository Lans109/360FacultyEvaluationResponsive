<?php
// config.php
session_start();

// Simulated user data
$valid_users = [
    'stephen.lacsa@lpunetwork.edu.ph' => [
        'password' => 'password123',
        'name' => 'Prince Pipen',
        'id' => '1',
        'profile_pic' => 'default-avatar.png',
        'courses' => [
            ['code' => 'CSCN10C', 'duration' => '1st 2425', 'lessons' => 12],
            ['code' => 'DCSN06C', 'duration' => '1st 2425', 'lessons' => 15]
        ]
    ]
];