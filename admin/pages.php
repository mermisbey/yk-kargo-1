<?php

    $tab = isset($_REQUEST['tab']) ? $_REQUEST['tab'] : "home";

    switch ($tab) {
        case 'home':
            require_once "home.php";
            break;
        case 'garsoft-gonderi-listesi':
            require_once "gonderi-listesi.php";
            break;
        case 'yik_ayarlar':
            require_once "ayar.php";
            break;
        case 'yik_premium':
            require_once "premium.php";
            break;

        default:
            break;
    }