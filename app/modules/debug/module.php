<?php

use DebugBar\DataCollector\MemoryCollector;
use DebugBar\DataCollector\TimeDataCollector;
use Pagekit\Debug\DebugBar;
use Pagekit\Debug\DataCollector\AuthDataCollector;
use Pagekit\Debug\DataCollector\DatabaseDataCollector;
use Pagekit\Debug\DataCollector\RoutesDataCollector;
use Pagekit\Debug\DataCollector\SystemDataCollector;
use Pagekit\Debug\Storage\SqliteStorage;

return [

    'name' => 'debug',

    'main' => function ($app) {

        if (!$this->config['enabled'] || !$this->config['file']) {
            return;
        }

        $app['debugbar'] = function ($app) {

            $debugbar = new DebugBar();
            $debugbar->setStorage($app['debugbar.storage']);
            $debugbar->addCollector(new MemoryCollector());
            $debugbar->addCollector(new TimeDataCollector());
            $debugbar->addCollector(new RoutesDataCollector($app['router']));

            if (isset($app['info'])) {
                $debugbar->addCollector(new SystemDataCollector($app['info']));
            }

            if (isset($app['db'])) {
                $app['db']->getConfiguration()->setSQLLogger($app['db.debug_stack']);
                $debugbar->addCollector(new DatabaseDataCollector($app['db'], $app['db.debug_stack']));
            }

            if (isset($app['log.debug'])) {
                $debugbar->addCollector($app['log.debug']);
            }

            return $debugbar;
        };

        $app['debugbar.storage'] = function () {
            return new SqliteStorage($this->config['file']);
        };

    },

    'boot' => function ($app) {

        if (!isset($app['debugbar'])) {
            return;
        }

        $app->subscribe($app['debugbar']);

        $app->on('app.request', function ($event) use ($app) {

            if (!$event->isMasterRequest()) {
                return;
            }

            if (isset($app['auth'])) {
                $app['debugbar']->addCollector(new AuthDataCollector($app['auth']));
            }

            $app['view']->data('$debugbar', ['url' => $app['router']->generate('_debugbar', ['id' => $app['debugbar']->getCurrentRequestId()])]);
            $app['view']->style('debugbar', 'app/modules/debug/assets/css/debugbar.css');
            $app['view']->script('debugbar', 'app/modules/debug/app/bundle/debugbar.js', ['vue', 'jquery']);
        });

        $app['callbacks']->get('_debugbar/{id}', '_debugbar', function ($id) use ($app) {
            return $app['response']->json($app['debugbar']->getStorage()->get($id));
        })->setDefault('_debugbar', false);

    },

    'require' => [

        'view',
        'routing'

    ],

    'autoload' => [

        'Pagekit\\Debug\\' => 'src'

    ],

    'config' => [

        'file'    => null,
        'enabled' => false

    ]

];
