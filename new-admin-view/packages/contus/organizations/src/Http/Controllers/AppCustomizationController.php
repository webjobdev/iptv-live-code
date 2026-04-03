<?php

namespace Contus\Organizations\Http\Controllers;

use Contus\Base\Controller;

class AppCustomizationController extends Controller
{

    // banner_carousels
    public function showdetails()
    {
        return view('organizations::app-customization.promotion.banner-carousel.index');
    }
    public function bnrGridList()
    {
        return view('organizations::app-customization.promotion.banner-carousel.gridView');
    }
    public function bnrAdd()
    {
        return view('organizations::app-customization.promotion.banner-carousel.add');
    }

    // banner_carousels_subscription
    public function carSubIndex()
    {
        return view('organizations::app-customization.promotion.banner-carousel-subscription.index');
    }

    public function carSubGrid()
    {
        return view('organizations::app-customization.promotion.banner-carousel-subscription.gridView');
    }

    public function carSubAdd()
    {
        return view('organizations::app-customization.promotion.banner-carousel-subscription.add');
    }

    public function carSubEdit()
    {
        return view('organizations::app-customization.promotion.banner-carousel-subscription.edit');
    }

    // Featured Rows
    public function featureIndex()
    {
        return view('organizations::app-customization.promotion.feature-row.index');
    }

    public function featuregridList()
    {
        return view('organizations::app-customization.promotion.feature-row.gridView');
    }

    // row order
    public function RowIndex()
    {
        return view('organizations::app-customization.promotion.rows-order.index');
    }

    public function RowGridList()
    {
        return view('organizations::app-customization.promotion.rows-order.gridView');
    }

    public function RowAdd()
    {
        return view('organizations::app-customization.promotion.rows-order.add');
    }

    public function RowView()
    {
        return view('organizations::app-customization.promotion.rows-order.view');
    }

    // general
    public function GenIndex()
    {
        return view('organizations::app-customization.general.index');
    }

    public function GenGridList()
    {
        return view('organizations::app-customization.general.gridView');
    }

    // setting
    public function SetIndex()
    {
        return view('organizations::app-customization.setting.index');
    }

    public function SetGridList()
    {
        return view('organizations::app-customization.setting.gridView');
    }

    public function SetAdd()
    {
        return view('organizations::app-customization.setting.add');
    }

    public function SetEdit()
    {
        return view('organizations::app-customization.setting.edit');
    }

    // channel-listing
    public function ChnlIndex()
    {
        return view('organizations::app-customization.channel_listing.index');
    }

    public function ChnlGridList()
    {
        return view('organizations::app-customization.channel_listing.gridView');
    }

    public function ChnlAdd()
    {
        return view('organizations::app-customization.channel_listing.add');
    }

    public function ChnlEdit()
    {
        // dd(123);
        return view('organizations::app-customization.channel_listing.view');
    }

}
