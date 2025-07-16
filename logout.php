<?php
session_start();
session_unset(); // Unset all variables
session_destroy(); // Fully kill session
echo json_encode(['success' => true]);
