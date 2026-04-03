@extends('base::layouts.default')

@section('header')
@include('base::layouts.headers.dashboard')
@endsection

<style>
    .popular-badge {
        position: absolute;
        top: -12px;
        right: 20px;
        border-radius: 20px;
    }

    .gradient-custom {
        background: #00ACCD;
    }

    .gold-plan {
        background-color: #ffd700;
        border: 2px solid #f1c40f;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }

    .gold-plan .badge {
        background-color: #f39c12;
    }

    .pricing-container {
        display: flex;
        justify-content: center;
        gap: 50px;
        flex-wrap: wrap;
        padding: 20px;
        height: 35rem;
    }

    .card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        padding: 30px;
        max-width: 30.5rem;
        width: 100%;
        transition: transform 0.3s, box-shadow 0.3s;
        cursor: pointer;
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 25px rgba(0, 0, 0, 0.15);
    }

    .card.selected {
        border: 2px solid #007bff;
    }

    .card h3 {
        margin-bottom: 15px;
        font-size: 35px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .price {
        font-size: 30px;
        color: #000;
        margin-bottom: 20px;
    }

    .features {
        list-style: none;
        margin-bottom: 25px;
        padding: 0;
    }

    .features li {
        margin-bottom: 10px;
        color: #000;
        /* font-weight: bold; */
    }

    .plnbtn {
        display: inline-block;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 25px;
        /* font-weight: bold; */
        transition: background 0.3s;
    }

    /* .btn:hover {
        color: #ffffff;
        background-color: #0056b3;
    } */

    @media (max-width: 768px) {
        .pricing-container {
            flex-direction: column;
            align-items: center;
        }
    }
</style>

@section('content')
<div data-ng-controller="MonetizationPlanController as planCtrl">
    <div class="dashboard-page " id="dashboard-page">
        <div class="page-heading flexbox align-items-center flex-wrap">
            <h4>{{ __('organizations::index.organization') }}</h4>
        </div>
        @include('base::layouts.subnav')
        @include('organizations::monetization-plan.common.subnav')

        <!-- <div class="bg-light">
            <div style="padding-bottom: 50px;">
                <div class="row text-center" style="margin-bottom: 50px;">
                    <div class="col-xs-12">
                        <h2 class="text-uppercase" style="font-size: 32px; font-weight: bold;">Choose Your Plan</h2>
                        <p class="text-muted">Select the perfect plan for your needs</p>
                    </div>
                </div>
                <div class="pricing-scroll-wrapper" id="pricing-scroll-wrapper">
                    <form name="organizationTranslationForm" id="organizationTranslationForm" method="POST" data-base-validator data-ng-submit=""
                        enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <input type="hidden" id="org-id" name="id" value="{{ request()->id }}">
                        <div class="pricing-container" id="pricing-container">
                        </div>
                    </form>
                </div>
            </div>
        </div> -->
    </div>
</div>
@endsection

<!-- <script>
    window.RAZORPAY_KEY = "{{ config('services.razorpay.key') }}";
</script> -->

@section('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="{{asset('adminview/assets/js/classieSidebarEffects.js')}}"></script>
<script src="{{asset('adminview/assets/js/classieSidebarEffectsDirective.js')}}"></script>
<script src="{{asset('adminview/assets/js/canvasjs.min.js')}}"></script>
<script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
<script src="{{asset('adminview/assets/js/gridView.js')}}"></script>
<script src="{{asset('adminview/assets/js/organization/monetization/monetizationpaln.js')}}"></script>
<script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
<script src="{{asset('adminview/assets/js/grid.js')}}"></script>
<script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection