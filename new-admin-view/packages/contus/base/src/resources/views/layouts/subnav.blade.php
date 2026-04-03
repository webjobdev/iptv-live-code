<style>
    /* Base nav styling */
    .nav.nav-pills {
        display: flex;
        flex-wrap: wrap;
        padding: 0;
        margin: 1rem 0;
        list-style: none;
    }

    /* Ensure nav items align properly */
    .nav.nav-pills li {
        margin: 0.3rem 0.5rem 0.3rem 0;
        list-style: none;
    }

    /* Style links as pill buttons */
    .nav.nav-pills li a {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease-in-out;
        white-space: nowrap;
    }

    /* Active state */
    .nav.nav-pills li.active a,
    .nav.nav-pills li a:hover {
        background-color: #00ACCD !important;
        color: #fff !important;
    }

    /* Responsive: stack vertically on small screens */
    @media (max-width: 768px) {
        .nav.nav-pills {
            flex-direction: column;
            align-items: stretch;
        }

        .nav.nav-pills li {
            width: 100%;
            margin: 0.3rem 0;
        }

        .nav.nav-pills li a {
            width: 100%;
            text-align: left;
            border-radius: 8px;
        }
    }

    /* Extra small screens (mobile) */
    @media (max-width: 480px) {
        .nav.nav-pills li a {
            font-size: 13px;
            padding: 0.6rem 0.8rem;
        }
    }
</style>

<nav class="nav nav-pills" style="margin: 1rem 0rem 1rem 0rem;">
    <li class="{{ Request::is('admin/general/details') ? 'active' : '' }}" style="margin-right: 0.5rem;">
        <a href="{{ url('admin/general/details') }}?id={{ request()->query('id') }}"
            style="{{ Request::is('admin/general/details') ? 'background-color: #00ACCD;' : '' }}"
            class="btn btn-default">
            <input type="hidden" name="id" value="{{ request()->query('id') }}">
            <i class="glyphicon glyphicon-pencil"></i> General
        </a>
    </li>

    <li class="{{ Request::is('admin/contentset', 'admin/live-event/content-set', 'admin/vod/content-set', 'admin/tv-show/content-set') ? 'active' : '' }}"
        style="margin-right: 0.5rem;">
        <a href="{{ url('admin/contentset') }}?id={{ request()->query('id') }}"
            style="{{ Request::is('admin/contentset', 'admin/live-event/content-set', 'admin/vod/content-set', 'admin/tv-show/content-set') ? 'background-color: #00ACCD;' : '' }}"
            class="btn btn-default">
            <input type="hidden" name="id" value="{{ request()->query('id') }}">
            <i class="glyphicon glyphicon-film"></i> Content Sets
        </a>
    </li>

    <li data-ng-if="checkAccess('monetization_plans.view')"
        class="{{ Request::is('admin/monetization-plan/subscription') || Request::is('admin/monetization-plan/accessories') || Request::is('admin/monitization-plan/subscription/add') ? 'active' : '' }}"
        style="margin-right: 0.5rem;">
        <a href="{{ url('admin/monetization-plan/subscription') }}?id={{ request()->query('id') }}"
            style="{{ Request::is('admin/monetization-plan/subscription') || Request::is('admin/monetization-plan/accessories') || Request::is('admin/monitization-plan/subscription/add', 'admin/monitization-plan/subscription/edit/*') ? 'background-color: #00ACCD; color: #ffffff' : '' }}"
            class="btn btn-default">
            <input type="hidden" name="id" value="{{ request()->query('id') }}">
            <i class="glyphicon glyphicon-briefcase"></i> Monetization Plans
        </a>
    </li>

    <li class="{{ Request::is('admin/partner-product') ? 'active' : '' }}" style="margin-right: 0.5rem;">
        <a href="{{ url('admin/partner-product') }}?id={{ request()->query('id') }}"
            style="{{ Request::is('admin/partner-product') ? 'background-color: #00ACCD;' : '' }}"
            class="btn btn-default">
            <input type="hidden" name="id" value="{{ request()->query('id') }}">
            <i class="glyphicon glyphicon-th-large"></i> Partner Products
        </a>
    </li>

    <li data-ng-if="checkAccess('announcements.view')"
        class="{{ Request::is('admin/announcment') || Request::is('admin/reminders') || Request::is('admin/activation/add') || Request::is('admin/disabled-accounts/add') || Request::is('admin/push-notifications') ? 'active' : '' }}"
        style="margin-right: 0.5rem;">
        <a href="{{ url('admin/announcment') }}?id={{ request()->query('id') }}"
            style="{{ Request::is('admin/announcment') ? 'background-color: #00ACCD;' : '' }}" class="btn btn-default">
            <input type="hidden" name="id" value="{{ request()->query('id') }}">
            <i class="glyphicon glyphicon-bullhorn"></i> Announcements and Reminders
        </a>
    </li>

    <li class="{{ Request::is('admin/app-customization/promotion/banner_carousels', 'admin/app-customization/banner_carousels_subscription', 'admin/app-customization/promotion/features-row', 'admin/app-customization/setting', 'admin/app-customization/setting/add', 'admin/app-customization/setting/edit/*', 'admin/app-customization/general', 'admin/app-customization/channel-listing', 'admin/app-customization/promotion/row-order') ? 'active' : '' }}"
        style="margin-right: 0.5rem;">
        <a href="{{ url('admin/app-customization/promotion/banner_carousels') }}?id={{ request()->query('id') }}"
            style="{{ Request::is('admin/app-customization/promotion/banner_carousels') ? 'background-color: #00ACCD;' : '' }}"
            class="btn btn-default">
            <input type="hidden" name="id" value="{{ request()->query('id') }}">
            <i class="glyphicon glyphicon-cog"></i> Apps Customization
        </a>
    </li>

    <li class="{{ Request::is('admin/organizations/payment-service', 'admin/organizations/payment-service/currency', 'admin/organizations/payment-service/currency-converter') ? 'active' : '' }}"
        style="margin-right: 0.5rem;">
        <a href="{{ url('admin/organizations/payment-service') }}?id={{ request()->query('id') }}"
            style="{{ Request::is('admin/organizations/payment-service') ? 'background-color: #00ACCD;' : '' }}"
            class="btn btn-default">
            <i class="glyphicon glyphicon-credit-card"></i> Payment Services
        </a>
    </li>

    <li data-ng-if="checkAccess('shopping_cart.view')" class="{{ Request::is('admin/shoppingcart') ? 'active' : '' }}"
        style="margin-right: 0.5rem;">
        <a href="{{ url('admin/shoppingcart') }}?id={{ request()->query('id') }}"
            style="{{ Request::is('admin/shoppingcart') ? 'background-color: #00ACCD;' : '' }}" class="btn btn-default">
            <input type="hidden" name="id" value="{{ request()->query('id') }}">
            <i class="glyphicon glyphicon-shopping-cart"></i> Shopping Cart
        </a>
    </li>
</nav>