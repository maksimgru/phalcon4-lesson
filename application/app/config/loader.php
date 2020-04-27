<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->formsDir,
        $config->application->modelsDir,
        $config->application->modelsTraitsDir,
        $config->application->pluginsDir,
        $config->application->constantsDir,
    ]
);

$loader->registerNamespaces(
    [
        'App\Forms'         => $config->application->formsDir,
        'App\Models'        => $config->application->modelsDir,
        'App\Models\Traits' => $config->application->modelsTraitsDir,
        'App\Plugins'       => $config->application->pluginsDir,
        'App\Constants'     => $config->application->constantsDir,
    ]
);

// Register autoloader
$loader->register();
