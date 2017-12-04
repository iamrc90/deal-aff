<?php
/**
 * Created by PhpStorm.
 * User: ripulchhabra
 * Date: 28/11/17
 * Time: 8:23 PM
 */

Breadcrumbs::register('home', function ($breadcrumbs) {
    $breadcrumbs->push('Home', route('home'));
});


Breadcrumbs::register('category', function ($breadcrumbs,$heading) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push($heading, route('categorywise_posts', $heading));
});

Breadcrumbs::register('deal', function ($breadcrumbs, $deal) {
    $breadcrumbs->parent('category',\App\Category::find($deal->category_id)->slug);
    $breadcrumbs->push($deal->title, route('viewDeal', $deal));
});