<?php
require_once __DIR__ . '/config.php';
logout_user();
session_start();
flash('info', 'You have been logged out. See you soon!');
redirect('index.php');
