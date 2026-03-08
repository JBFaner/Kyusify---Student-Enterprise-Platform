<?php
$user = \App\Models\User::firstOrCreate(
    ['email' => 'student@qcu.edu.ph'], 
    ['name' => 'Demo Student', 'password' => bcrypt('password'), 'role' => 'seller', 'status' => 'active']
);
\App\Models\Enterprise::firstOrCreate(
    ['user_id' => $user->id], 
    ['name' => 'QCU Tech Solutions', 'description' => 'Providing tech accessories to students on campus.', 'status' => 'pending', 'is_student_verified' => false]
);
