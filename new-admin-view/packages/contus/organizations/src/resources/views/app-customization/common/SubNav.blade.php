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

<ul class="nav nav-tabs" role="tablist">
    <li class="{{ Request::is('admin/app-customization/promotion/banner_carousels') ? 'active' : '' }}">
        <a href=" {{ url('admin/app-customization/promotion/banner_carousels') }}?id={{ request()->query('id') }} "
            style="color: black;">
            <input type="hidden" name="id" value="{{ request()->query('id') }}">
            Banner Carousels
        </a>
    </li>

    <!-- <li data-ng-if="checkAccess('banner_featured_carousel.view')"
        class="{{ Request::is('admin/app-customization/banner_carousels_subscription') ? 'active' : '' }}">
        <a href=" {{ url('admin/app-customization/banner_carousels_subscription') }}?id={{ request()->query('id') }} "
            style="color: black;">
            <input type="hidden" name="id" value="{{ request()->query('id') }}">
            Banner Carousel for Subscription
        </a>
    </li> -->

    <!-- <li class="{{ Request::is('admin/app-customization/promotion/features-row') ? 'active' : '' }}">
        <a href="{{ url('admin/app-customization/promotion/features-row') }}?id={{ request()->query('id') }}"
            style="color: black;">
            <input type="hidden" name="id" value="{{ request()->query('id') }}">
            Featured Rows
        </a>
    </li> -->

    <li class="{{ Request::is('admin/app-customization/promotion/row-order') ? 'active' : '' }}">
        <a href="{{ url('admin/app-customization/promotion/row-order') }}?id={{ request()->query('id') }}"
            style="color: black;">
            <input type="hidden" name="id" value="{{ request()->query('id') }}">
            Rows Order
        </a>
    </li>
</ul>