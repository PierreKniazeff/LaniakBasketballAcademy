<?php

require_once('./vendor/autoload.php');

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

$host = 'http://sdu6.gozaimass.io:4444'; // Selenium Server
$capabilities = DesiredCapabilities::chrome();
$driver = RemoteWebDriver::create($host, $capabilities);

$driver->get('http://www.levelnext.fr');
echo "Title of the current page is: " . $driver->getTitle();

$driver->quit();

?>
