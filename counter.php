<?php
$file = 'online_users.txt';
$timeout = 15; // 15 seconds tak koi activity nahi toh offline
$ip = $_SERVER['REMOTE_ADDR'];
$time = time();

$users = [];
// Purane users ka data padho
if (file_exists($file)) {
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        list($user_ip, $user_time) = explode('|', $line);
        // Jo log abhi bhi time limit ke andar hain, unko rakho
        if ($time - $user_time < $timeout) {
            $users[$user_ip] = $user_time;
        }
    }
}

// Naye/Current user ko update karo
$users[$ip] = $time;

// Data wapas file mein save karo
$out = "";
foreach ($users as $user_ip => $user_time) {
    $out .= $user_ip . '|' . $user_time . PHP_EOL;
}
file_put_contents($file, $out, LOCK_EX);

// Total live users ki ginti HTML ko bhejo
echo count($users);
?>