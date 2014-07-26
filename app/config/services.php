<?php

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new \Phalcon\DI\FactoryDefault();

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function() use ($config) {
	$url = new \Phalcon\Mvc\Url();
	$url->setBaseUri($config->application->baseUri);
	return $url;
});

/**
 * Setting up the view component
 */
$di->set('view', function() use ($config) {
	$view = new \Phalcon\Mvc\View();
	$view->setViewsDir($config->application->viewsDir);
    $view->registerEngines(array(
        ".volt" => 'Phalcon\Mvc\View\Engine\Volt'
    ));
	return $view;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('db', function() use ($config) {
	return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
		"host" => $config->database->host,
		"username" => $config->database->username,
		"password" => $config->database->password,
		"dbname" => $config->database->name
	));
});

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function() {
    $session = new Phalcon\Session\Adapter\Files();
    $session->start();
    return $session;
});

/**
 * Forms manager
 */
$di->set('forms', function() {
    return new Phalcon\Forms\Manager();
});

/**
 * Flash sessions
 */
$di->set('flash', function() {
    return new Phalcon\Flash\Session();
});

/**
 * Filter
 */
$di->set('filter', function() {
    return new Phalcon\Filter();
});

/**
 * Config
 */
$di->set('config', $config);