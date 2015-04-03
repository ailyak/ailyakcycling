<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = false;


$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../views',
));

$ymlParser = new \Symfony\Component\Yaml\Yaml();
$app['config'] = $ymlParser->parse(file_get_contents(__DIR__ . '/../config/config.yml'));


$app->get('/', function () use ($app) {

    $url = "https://graph.facebook.com/v2.3/561003627289743/feed?limit=10&access_token={$app['config']['facebook']['token']}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    $data = curl_exec($ch);
    curl_close($ch);
    $dataDecoded = json_decode($data, true);
    $posts = $dataDecoded['data'];
    
    return $app['twig']->render('facebook/posts.twig', array(
                'posts' => $posts,
    ));
});

$app->get('/test', function() use ($app) {
    
    $items = [
        new \Ailyak\Ride\Item('Помпа'),
        new \Ailyak\Ride\Item('Фар'),
    ];
    
    return $app['twig']->render('beforeRide/requirements.twig', array('items' => $items));
});

$app->run();
