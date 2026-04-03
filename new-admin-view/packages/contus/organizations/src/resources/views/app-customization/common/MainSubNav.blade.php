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
    <li
        class="{{ Request::is('admin/app-customization/promotion/banner_carousels', 'admin/app-customization/banner_carousels_subscription', 'admin/app-customization/promotion/features-row', 'admin/app-customization/promotion/row-order') ? 'active' : '' }}">
        <a href=" {{ url('admin/app-customization/promotion/banner_carousels') }}?id={{ request()->query('id') }} "
            style="color: black;">
            <!-- License: MIT. Made by halfmage: https://github.com/halfmage/pixelarticons -->
            <svg width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M3 3h18v18H3V3zm2 2v2h2V5H5zm4 0v6h6V5H9zm8 0v2h2V5h-2zm2 4h-2v2h2V9zm0 4h-2v2h2v-2zm0 4h-2v2h2v-2zm-4 2v-6H9v6h6zm-8 0v-2H5v2h2zm-2-4h2v-2H5v2zm0-4h2V9H5v2z"
                    fill="#000000" />
            </svg>
            <input type="hidden" name="id" value="{{ request()->query('id') }}">
            Promotion
        </a>
    </li>

    <li class="{{ Request::is('admin/app-customization/channel-listing') ? 'active' : '' }}">
        <a href=" {{ url('admin/app-customization/channel-listing') }}?id={{ request()->query('id') }} " style="color: black;">
            <svg width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M22 18L2 18" stroke="#71717A" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M22 2L2 2" stroke="#71717A" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path
                    d="M3.5 14L20.5 14C21.3284 14 22 13.3284 22 12.5V7.5C22 6.67157 21.3284 6 20.5 6L3.5 6C2.67157 6 2 6.67157 2 7.5L2 12.5C2 13.3284 2.67157 14 3.5 14Z"
                    stroke="#71717A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M14 22L2 22" stroke="#71717A" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
            <input type="hidden" name="id" value="{{ request()->query('id') }}">
            Channel Listing
        </a>
    </li>

    <li class="{{ Request::is('admin/app-customization/general') ? 'active' : '' }}">
        <a href=" {{ url('admin/app-customization/general') }}?id={{ request()->query('id') }} " style="color: black;">
            <svg width="800px" height="800px" viewBox="0 0 91 91" enable-background="new 0 0 91 91" id="Layer_1"
                version="1.1" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink">
                <g>
                    <g>
                        <path
                            d="M23.996,60.6c-0.171-0.17-0.411-0.262-0.648-0.238C19.255,60.666,5.769,62.949,4.07,79.516    c-0.011,0.113-0.033,0.227-0.061,0.332c-0.037,0.135-0.935,3.338-3.511,5.746c-0.225,0.211-0.315,0.529-0.236,0.826    c0.08,0.299,0.317,0.527,0.618,0.598c1.712,0.396,4.434,0.869,7.675,0.869c3.186,0,6.194-0.455,8.94-1.352    c7.397-2.41,12.62-7.922,15.521-16.379c0.104-0.305,0.024-0.641-0.204-0.865L23.996,60.6z"
                            fill="#6EC4A7" />
                        <path
                            d="M42.738,57.686l-6.882-6.779c-0.33-0.324-0.858-0.32-1.181,0.008l-6.618,6.717    c-0.324,0.33-0.32,0.857,0.008,1.182l6.879,6.781c0.156,0.154,0.367,0.24,0.586,0.24c0.278,0.02,0.439-0.09,0.595-0.248    l6.621-6.719c0.156-0.158,0.242-0.371,0.241-0.594C42.985,58.053,42.895,57.842,42.738,57.686z"
                            fill="#45596B" />
                        <path
                            d="M89.125,3.709c-1.013-0.996-2.459-1.566-3.969-1.566c-1.545,0-3.018,0.594-4.036,1.631l-42.375,43.01    c-0.156,0.158-0.242,0.371-0.24,0.594c0.001,0.221,0.091,0.434,0.249,0.588l6.88,6.777c0.163,0.16,0.374,0.24,0.587,0.24    c0.216,0,0.432-0.082,0.594-0.248l42.369-43.006c1.059-1.07,1.636-2.498,1.626-4.021C90.797,6.186,90.198,4.766,89.125,3.709z"
                            fill="#647F94" />
                    </g>
                </g>
            </svg>
            <input type="hidden" name="id" value="{{ request()->query('id') }}">
            General
        </a>
    </li>

    <li class="{{ Request::is('admin/app-customization/setting') ? 'active' : '' }}">
        <a href=" {{ url('admin/app-customization/setting') }}?id={{ request()->query('id') }} " style="color: black;">
            <svg fill="#000000" width="800px" height="800px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                data-name="Layer 1">
                <path
                    d="M19.9,12.66a1,1,0,0,1,0-1.32L21.18,9.9a1,1,0,0,0,.12-1.17l-2-3.46a1,1,0,0,0-1.07-.48l-1.88.38a1,1,0,0,1-1.15-.66l-.61-1.83A1,1,0,0,0,13.64,2h-4a1,1,0,0,0-1,.68L8.08,4.51a1,1,0,0,1-1.15.66L5,4.79A1,1,0,0,0,4,5.27L2,8.73A1,1,0,0,0,2.1,9.9l1.27,1.44a1,1,0,0,1,0,1.32L2.1,14.1A1,1,0,0,0,2,15.27l2,3.46a1,1,0,0,0,1.07.48l1.88-.38a1,1,0,0,1,1.15.66l.61,1.83a1,1,0,0,0,1,.68h4a1,1,0,0,0,.95-.68l.61-1.83a1,1,0,0,1,1.15-.66l1.88.38a1,1,0,0,0,1.07-.48l2-3.46a1,1,0,0,0-.12-1.17ZM18.41,14l.8.9-1.28,2.22-1.18-.24a3,3,0,0,0-3.45,2L12.92,20H10.36L10,18.86a3,3,0,0,0-3.45-2l-1.18.24L4.07,14.89l.8-.9a3,3,0,0,0,0-4l-.8-.9L5.35,6.89l1.18.24a3,3,0,0,0,3.45-2L10.36,4h2.56l.38,1.14a3,3,0,0,0,3.45,2l1.18-.24,1.28,2.22-.8.9A3,3,0,0,0,18.41,14ZM11.64,8a4,4,0,1,0,4,4A4,4,0,0,0,11.64,8Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,11.64,14Z" />
            </svg>
            <input type="hidden" name="id" value="{{ request()->query('id') }}">
            Settings
        </a>
    </li>
</ul>