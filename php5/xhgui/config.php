<?php
/**
 * Default configuration for Xhgui
 */
return array(
    'debug' => false,
    'mode' => 'development',

    // Can be either mongodb or file.
    'save.handler' => 'mongodb',

    // Needed for file save handler. Beware of file locking. You can adujst this file path 
    // to reduce locking problems (eg uniqid, time ...)
    //'save.handler.filename' => __DIR__.'/../data/xhgui_'.date('Ymd').'.dat',
    'db.host' => 'mongodb://127.0.0.1:27017',
    'db.db' => 'xhprof',

    // Allows you to pass additional options like replicaSet to MongoClient.
    'db.options' => array(),
    'templates.path' => dirname(__DIR__) . '/src/templates',
    'date.format' => 'M jS H:i:s',
    'detail.count' => 6,
    'page.limit' => 25,

    // Profile 1 in 100 requests.
    // You can return true to profile every request.
    'profiler.enable' => function() {
        // Never profile ourself.
        if (strpos($_SERVER['REQUEST_URI'], '/xhgui') === 0) {
                return false;
        }

	// Profile if ?xhprof=on, and continue to profile for the next hour.
        if (isset($_GET['xhprof']) && $_GET['xhprof'] == 'on') {
                setcookie('xhprof', 'on', time() + 3600);
                return true;
        }

	// Profile if we have been set to profiling mode.
        if (isset($_COOKIE['xhprof']) && $_COOKIE['xhprof'] == 'on') {
                return true;
        }

        // Profile the CLI when the XHPROF environment variable is set.
        if (getenv('XHPROF') == 'on') {
                return true;
        }
    },

    'profiler.simple_url' => function($url) {
        return preg_replace('/\=\d+/', '', $url);
    }

);
