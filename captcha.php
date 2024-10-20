<?php
session_start();

function getRandomFolder($dir) {
    $folders = array_filter(glob($dir . '/*'), 'is_dir');
    return $folders[array_rand($folders)];
}

function getImagesFromFolder($folder) {
    $images = array_diff(scandir($folder), array('..', '.'));
    return array_map(function($image) use ($folder) {
        return $folder . '/' . $image;
    }, $images);
}

function getAnswer($folderName) {
    $answerFile = "./ans/" . basename($folderName);
    return file($answerFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

$selectedFolder = getRandomFolder('./img');
$captchaImages = getImagesFromFolder($selectedFolder);
$correctAnswers = getAnswer($selectedFolder);

$_SESSION['captcha_correct'] = $correctAnswers;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Oops, Captcha!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f1f1f1;
            margin: 0;
        }
        .captcha-container {
            width: 320px;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }
        .captcha-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #4285F4;
            border-radius: 4px;
            color: white;
        }
        .captcha-header h1 {
            font-size: 16px;
            text-align: left;
            margin: 0;
        }
        .captcha-header h2 {
            font-size: 20px;
            text-align: left;
            margin: 0;
        }
        .captcha-header .logo {
            width: 50px;
            height: 50px;
            background: url('bugs.jpg') no-repeat center/cover;
        }
        .captcha-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .captcha-grid div {
            width: 75px;
            height: 75px;
            margin-bottom: 10px;
            position: relative;
        }
        .captcha-grid input[type="checkbox"] {
            display: none;
        }
        .captcha-grid label {
            display: block;
            width: 100%;
            height: 100%;
            cursor: pointer;
            overflow: hidden;
            border-radius: 4px;
        }
        .captcha-grid img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .captcha-grid input[type="checkbox"]:checked + label img {
            opacity: 0.6;
        }
        .captcha-grid input[type="checkbox"]:checked + label::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            color: #4caf50;
            font-weight: bold;
        }
        .captcha-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        .captcha-footer .refresh-icon{
            width: 20px;
            height: 20px;
            cursor: pointer;
            margin-left: 10px;
        }
        .captcha-footer .info-icon {
            width: 20px;
            height: 20px;
            cursor: pointer;
            margin-left: -150px;
        }
        .captcha-footer .info-icon {
            background: url('info.png') no-repeat center/cover;
        }
        .captcha-footer .refresh-icon {
            background: url('refresh.png') no-repeat center/cover;
        }
        button {
            background-color: #4285F4;
            color: #ffffff;
            border: none;
            padding: 15px 20px;
            font-size: 14px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #357ae8;
        }
        #top-right-corner {
            position: fixed;
            top: 0;
            right: 0;
        }
    </style>
    <link rel="icon" href="https://assets.060418.best/favicon.ico">
</head>
<body>
    <div class="captcha-container">
        <div class="captcha-header">
            <div>
                <br>
                <h1>Select all squares with</h1>
                <h2>Bugs</h2>
                <br>
            </div>
            <div class="logo"></div>
        </div>
        <form method="post" action="validate.php">
            <div class="captcha-grid">
                <?php foreach ($captchaImages as $index => $image) : ?>
                    <div>
                        <input type="checkbox" name="captcha[]" value="<?php echo htmlspecialchars(basename($image)); ?>" id="image<?php echo $index; ?>">
                        <label for="image<?php echo $index; ?>">
                            <img src="<?php echo htmlspecialchars($image); ?>" alt="Captcha Image">
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="captcha-footer">
                <a onclick="javascript:location.reload();" class="refresh-icon"></a>
                <a onclick="window.open('https://github.com/tzchz/oops-captcha');" class="info-icon"></a>
                <button type="submit">VERIFY</button>
            </div>
        </form>
    </div>
    <div id="top-right-corner">
        <a href="https://github.com/tzchz/oops-captcha">
            <img decoding="async" width="149" height="149" src="https://github.blog/wp-content/uploads/2008/12/forkme_right_darkblue_121621.png" class="attachment-full size-full" alt="Fork me on GitHub" loading="lazy">
        </a>
    </div>
</body>
</html>
