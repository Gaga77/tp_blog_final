<?php

/*
*   USER CONFIGURATION FILES GOES AT END OF FILE
***********************************************************************************************************************/

// Use Composer autoloader
require_once __DIR__ .  '/../vendor/autoload.php';



// define Phunder parameters
Phunder\Core\Config::loadConfigValues();

// User defined constants
if (is_file(__DIR__ . '/phunder_config.php')) {
    include_once __DIR__ . '/phunder_config.php';
}

// Core defined constants
const PHUNDER_ROOT_DIR = __DIR__ . '/../';
Phunder\Core\Config::loadParameters();

// Secure session setup
Phunder\Core\Config::configureSession();

/***********************************************************************************************************************
*   USER CONFIGURATION FILES GOES BELOW
*/

// on inclus notre fichier de configuration personnalisé
include __DIR__ . '/app_bootstrap.php';
