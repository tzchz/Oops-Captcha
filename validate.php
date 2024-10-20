<?php
session_start();

if (!isset($_SESSION['captcha_correct'])) {
    die("Error: Login session timed out.");
}

$selectedImages = $_POST['captcha'] ?? [];
$correctImages = $_SESSION['captcha_correct'];

if (empty(array_diff($correctImages, $selectedImages)) && empty(array_diff($selectedImages, $correctImages))) {
    echo "Success!";
} else {
    echo "Wrong Captcha try again!";
}

unset($_SESSION['captcha_correct']);
?>
