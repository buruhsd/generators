<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configurations
    |--------------------------------------------------------------------------
    |
    | Created by:   Renato Hysa
    |
    | URL:          www.devlob.com
    |
    | This file is for configuring the scaffolding options.
    |--------------------------------------------------------------------------
    */

    /*
     *  Mark as 'true' whatever you find necessary to be included in the scaffolding.
    */
    'include_controller' => true,
    'include_request' => true,
    'include_model' => true,
    'include_views' => true,
    'include_routes' => true,
    'include_unittesting' => true,

    /* You need to add this line
     * # Admin resource controllers START
     * in routes.php for resource routing.
     *
     * You can also change it to your like here, but do NOT forget to include it in routes.php.
     * All your future resource routes will be added below the resource search.
    */
    'resource_search' => '',

    /* You need to add this line
     * # Admin datatables START
     * in routes.php for datatables.
     *
     * You can also change it to your like here, but do NOT forget to include it in routes.php.
     * All your future datatable routes will be added below the datatable search.
    */
    'datatable_search' => ''
];
