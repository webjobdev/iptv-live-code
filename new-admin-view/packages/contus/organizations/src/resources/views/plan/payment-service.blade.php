@extends('base::layouts.default')

@section('header')
@include('base::layouts.headers.dashboard')
@endsection



@section('content')
<div data-ng-controller="MonetizationPlanController as planCtrl">
    <div class="dashboard-page " id="dashboard-page">
        <div class="page-heading flexbox align-items-center flex-wrap">
            <h4>{{ __('organizations::index.organization') }}</h4>
        </div>
        @include('base::layouts.subnav')

        <br>

        <div class="thumbnail" style="border: none; box-shadow: 0 4px 10px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden; max-width: 300px;">
            <img src="https://thebusinessrule.com/wp-content/uploads/2023/02/Razorpay-5.jpg" alt="Card image" style="width: 100%; height: auto;">

            <div class="caption" style="padding: 15px;">
                <h4 style="margin-top: 0; font-weight: bold;">Razorpay Integration</h4>
                <p style="color: #555; text-align: justify;">Build secure and smooth payment experiences using Razorpay's powerful API and UI toolkit.</p><br>
                <p><a href="https://razorpay.com/docs/api/" class="btn btn-primary" style="background-color: #00ACCD;" role="button">Learn More</a></p>
            </div>
        </div>


    </div>
</div>
@endsection

<!-- <script>
    window.RAZORPAY_KEY = "{{ config('services.razorpay.key') }}";
</script> -->

@section('scripts')
<!-- <script src="https://checkout.razorpay.com/v1/checkout.js"></script> -->
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