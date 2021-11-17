<?php

define('PHP_VERSION_REQUIRED', '7.4');

$strOk   = '<span class="ok">&#10004;</span>';
$strFail = '<span class="fail">X</span>';

$requirements = array();

// PHP Version
$requirements['php_version'] = version_compare(PHP_VERSION, PHP_VERSION_REQUIRED ,'>=');

// OpenSSL PHP Extension
$requirements['openssl_enabled'] = extension_loaded('openssl');

// PDO PHP Extension
$requirements['pdo_enabled'] = defined('PDO::ATTR_DRIVER_NAME');

// Mbstring PHP Extension
$requirements['mbstring_enabled'] = extension_loaded('mbstring');

// Tokenizer PHP Extension
$requirements['tokenizer_enabled'] = extension_loaded('tokenizer');

// XML PHP Extension
$requirements['xml_enabled'] = extension_loaded('xml');

// JSON PHP Extension
$requirements['json_enabled'] = extension_loaded('json');

// Ctype PHP Extension
$requirements['ctype'] = extension_loaded('ctype');

// GD PHP Extension
$requirements['gd'] = extension_loaded('gd');

// ImageMagick PHP Extension
$requirements['imagick'] = class_exists("Imagick");

// mod_rewrite
$requirements['mod_rewrite_enabled'] = null;

if (function_exists('apache_get_modules')) {
    $requirements['mod_rewrite_enabled'] = in_array('mod_rewrite', apache_get_modules());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Server Requirements &dash; Laravel PHP Framework</title>
    <style>

        body {
            margin:0;
            font-size: 16px;
            font-family: sans-serif;
            text-align:center;
            color: #999;
        }

        .wrapper {
           width: 300px;
           margin: 16px auto;
        }

        p {
            margin:0;
        }

        p small {
            font-size: 13px;
            display: block;
            margin-bottom: 1em;
        }

        .ok {
            color: #27ae60;
        }

        .fail {
            color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h1>Server Requirements</h1>

        <p>
            PHP >= <?php echo PHP_VERSION_REQUIRED ?> <?php echo $requirements['php_version'] ? $strOk : $strFail; ?>
        </p>

        <p>OpenSSL PHP Extension <?php echo $requirements['openssl_enabled'] ? $strOk : $strFail; ?></p>

        <p>PDO PHP Extension <?php echo $requirements['pdo_enabled'] ? $strOk : $strFail; ?></p>

        <p>Mbstring PHP Extension <?php echo $requirements['mbstring_enabled'] ? $strOk : $strFail; ?></p>

        <p>Tokenizer PHP Extension <?php echo $requirements['tokenizer_enabled'] ? $strOk : $strFail; ?></p>

        <p>XML PHP Extension <?php echo $requirements['xml_enabled'] ? $strOk : $strFail; ?></p>

        <p>Ctype PHP Extension <?php echo $requirements['ctype'] ? $strOk : $strFail; ?></p>

        <p>JSON PHP Extension <?php echo $requirements['json_enabled'] ? $strOk : $strFail; ?></p>

        <p>GD PHP Extension <?php echo $requirements['gd'] ? $strOk : $strFail; ?></p>

        <p>ImageMagick PHP Extension <?php echo $requirements['imagick'] ? $strOk : $strFail; ?></p>
    </div>
</body>
</html>
