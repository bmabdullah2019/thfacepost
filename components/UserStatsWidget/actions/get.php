<?php
header('Content-Type: application/json; charset=utf-8');
echo json_encode(user_stats_get());
exit();