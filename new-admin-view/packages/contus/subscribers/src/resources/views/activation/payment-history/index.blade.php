@extends('base::layouts.default')

@section('header')
    @include('base::layouts.headers.dashboard')
@endsection

<style>
    /* Base nav tabs styling */
    .nav.nav-tabs {
        display: flex;
        flex-wrap: wrap;
        border-bottom: 2px solid #ddd;
        padding-left: 0;
        margin-bottom: 1rem;
    }

    /* Tab items */
    .nav.nav-tabs li {
        margin: 0;
        list-style: none;
    }

    .nav.nav-tabs li a {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 10px 15px;
        font-size: 14px;
        font-weight: 500;
        color: #000;
        border: 1px solid transparent;
        border-radius: 4px 4px 0 0;
        transition: all 0.3s ease-in-out;
        text-decoration: none;
    }

    /* Active tab */
    .nav.nav-tabs li.active a,
    .nav.nav-tabs li a:hover {
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-bottom: 2px solid #00ACCD;
        color: #00ACCD !important;
    }

    /* SVG icons should align with text */
    .nav.nav-tabs li a svg {
        flex-shrink: 0;
        width: 18px;
        height: 18px;
    }

    /* Responsive styles */
    @media (max-width: 992px) {
        .nav.nav-tabs {
            justify-content: flex-start;
            overflow-x: auto;
            white-space: nowrap;
            border-bottom: none;
        }

        .nav.nav-tabs li {
            flex: 0 0 auto;
            margin-right: 6px;
        }

        .nav.nav-tabs li a {
            border-radius: 6px;
            border: 1px solid #ddd;
        }

        .nav.nav-tabs li.active a {
            border-bottom: 1px solid #00ACCD;
        }
    }

    @media (max-width: 576px) {
        .nav.nav-tabs {
            flex-direction: column;
        }

        .nav.nav-tabs li {
            width: 100%;
            margin: 4px 0;
        }

        .nav.nav-tabs li a {
            width: 100%;
            justify-content: flex-start;
            border-radius: 6px;
        }
    }
</style>

@section('content')
    <div data-ng-controller="PaymentHistoryController as pysCtrl">
        <div class="" id="dashboard-page">
            @include('subscribers::layouts.subscribernav')

            <br>
            <h1 style="font-size: 1.7rem; font-weight: 900;">Activation</h1>
            <br>
            <p>Pay subscriptions, add credit cards, assign new devices to the subscriber, see older payments and statements.
            </p>
            <br>

            @include('subscribers::layouts.tabview')

            <br>
            <h1 style="font-weight: 900; font-size: 1.2rem;">
                Here you can see all the subscriber's payments (both successful and unsuccessful.)
            </h1>
            <br>

            <div class="contentpanel product order_list">
                @include('base::partials.errors')
                <div class="response-msg"></div>
                <div data-grid-view data-rows-per-page="10" data-route-name="subscriber/payment-history"
                    data-request-subscriber-id="{{ request('subscriber-id') }}"
                    data-template-route="admin/activation/payment-history" data-count="false">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- ✅ Load required scripts once, in correct order --}}
    <script src="{{ asset('adminview/assets/js/classieSidebarEffects.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/classieSidebarEffectsDirective.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/canvasjs.min.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/requestFactory.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/gridView.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/subscribers/payment-history.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/common.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/grid.js') }}"></script>
    <script src="{{ asset('adminview/assets/js/common/directive.js') }}"></script>
@endsection