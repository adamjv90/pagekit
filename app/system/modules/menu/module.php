<?php

use Pagekit\Menu\MenuProvider;
use Pagekit\Menu\Widget\MenuWidget;

return [

    'name' => 'system/menu',

    'main' => function ($app) {

        $app['menus'] = function() {
            return new MenuProvider;
        };

        $app->on('app.request', function() use ($app) {
            $app['menus']->registerFilter('access', 'Pagekit\Menu\Filter\AccessFilter', 16);
            $app['menus']->registerFilter('status', 'Pagekit\Menu\Filter\StatusFilter', 16);
            $app['menus']->registerFilter('priority', 'Pagekit\Menu\Filter\PriorityFilter');
            $app['menus']->registerFilter('active', 'Pagekit\Menu\Filter\ActiveFilter');
        });

        $app->on('widget.types', function($event, $widgets) {
            $widgets->registerType(new MenuWidget);
        });

        $app->on('view.site:views/admin/index', function ($event, $view) use ($app) {

            $app['scripts']->register('widget-menu', 'system/menu:app/bundle/widgets/menu.js', '~widgets');
            $view->data('$menus', array_values($app['module']->get('system/site')->getMenus()));

        });

    },

    'autoload' => [

        'Pagekit\\Menu\\' => 'src'

    ],

    'resources' => [

        'system/menu:' => ''

    ]

];
