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
    <li class="{{ Request::is('admin/monetization-plan/subscription') ? 'active' : '' }}">
        <a href=" {{ url('admin/monetization-plan/subscription') }}?id={{ request()->query('id') }} " style="color: black;">
            <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 2H6v2h12V2zM4 6h16v2H4V6zm-2 4h20v12H2V10zm18 10v-8H4v8h16z" fill="#000000" />
            </svg>
            <input type="hidden" name="id" value="{{ request()->query('id') }}">
            Subscription
        </a>
    </li>

    <li class="{{ Request::is('admin/monetization-plan/accessories') ? 'active' : '' }}">
        <a href=" {{ url('admin/monetization-plan/accessories') }}?id={{ request()->query('id') }} " style="color: black;">
            <svg width="20px" height="20px" viewBox="0 0 16.007 16.007" xmlns="http://www.w3.org/2000/svg">
                <g color="#000000" font-weight="400" fill="#474747">
                    <path
                        d="M5.5 6.007a.5.5 0 100 1h6a.5.5 0 100-1zm0 2a.5.5 0 100 1h5a.5.5 0 100-1zm0 2a.5.5 0 100 1h6a.5.5 0 100-1zm0 2a.5.5 0 100 1h3a.5.5 0 100-1z"
                        style="line-height:normal;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-feature-settings:normal;text-indent:0;text-align:start;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;text-transform:none;text-orientation:mixed;shape-padding:0;isolation:auto;mix-blend-mode:normal"
                        font-family="sans-serif" overflow="visible" />
                    <path
                        d="M4 1.007c-1.09 0-2 .91-2 2v11c0 1.09.91 2 2 2h9c1.09 0 2-.91 2-2v-11c0-1.09-.91-2-2-2v13H4v-13z"
                        style="line-height:normal;-inkscape-font-specification:Sans;text-indent:0;text-align:start;text-decoration-line:none;text-transform:none;marker:none"
                        font-family="Sans" overflow="visible" />
                    <path
                        d="M5.492 0A.5.5 0 005 .506v3a.5.5 0 101 0v-3A.5.5 0 005.492 0zm2 0A.5.5 0 007 .506v3a.5.5 0 101 0v-3A.5.5 0 007.492 0zm2 0A.5.5 0 009 .506v3a.5.5 0 101 0v-3A.5.5 0 009.492 0zm2 0A.5.5 0 0011 .506v3a.5.5 0 101 0v-3A.5.5 0 0011.492 0z"
                        style="line-height:normal;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-feature-settings:normal;text-indent:0;text-align:start;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;text-transform:none;text-orientation:mixed;shape-padding:0;isolation:auto;mix-blend-mode:normal"
                        font-family="sans-serif" overflow="visible" />
                </g>
            </svg>
            <input type="hidden" name="id" value="{{ request()->query('id') }}">
            Accessories
        </a>
    </li>

</ul>
