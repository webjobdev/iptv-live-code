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
    <li class="{{ Request::is('admin/contentset') ? 'active' : '' }}">
        <a href=" {{ url('admin/contentset') }}?id={{ request()->query('id') }} "
            style="color: black;">
            <svg width="20px" height="20px" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M15.284 14.546A2.975 2.975 0 0117 14c1.654 0 3 1.346 3 3s-1.346 3-3 3-3-1.346-3-3c.004-.279.047-.555.129-.822l-1.575-1.125A3.964 3.964 0 0110 16a3.964 3.964 0 01-2.554-.947l-1.575 1.125c.076.262.129.535.129.822 0 1.654-1.346 3-3 3s-3-1.346-3-3 1.346-3 3-3c.615 0 1.214.191 1.716.546l1.56-1.114A3.97 3.97 0 016 12c0-1.858 1.28-3.411 3-3.858V5.815A2.993 2.993 0 017 3c0-1.654 1.346-3 3-3s3 1.346 3 3a2.996 2.996 0 01-2 2.816v2.326c1.72.447 3 2 3 3.858-.003.49-.096.976-.276 1.432l1.56 1.114zm1.037 3.146A1 1 0 0017 18a1 1 0 000-2 1 1 0 00-.679 1.692zm-14 0A1 1 0 003 18a1 1 0 000-2 1 1 0 00-.679 1.692zM11 3c0-.551-.449-1-1-1-.551 0-1 .449-1 1 0 .551.449 1 1 1 .551 0 1-.449 1-1z"
                    fill="#5C5F62" />
            </svg>
            <input type="hidden" name="id" value="{{ request()->query('id') }}">
            Channel Sets
        </a>
    </li>

    <li class="{{ Request::is('admin/live-event/content-set') ? 'active' : '' }}">
        <a href=" {{ url('admin/live-event/content-set') }}?id={{ request()->query('id') }} " style="color: black;">
            <svg fill="#000000" xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 52 52"
                enable-background="new 0 0 52 52" xml:space="preserve">
                <g>
                    <path
                        d="M46.5,20h-41C4.7,20,4,20.7,4,21.5V46c0,2.2,1.8,4,4,4h36c2.2,0,4-1.8,4-4V21.5C48,20.7,47.3,20,46.5,20z M19,42c0,0.6-0.4,1-1,1h-4c-0.6,0-1-0.4-1-1v-4c0-0.6,0.4-1,1-1h4c0.6,0,1,0.4,1,1V42z M19,32c0,0.6-0.4,1-1,1h-4 c-0.6,0-1-0.4-1-1v-4c0-0.6,0.4-1,1-1h4c0.6,0,1,0.4,1,1V32z M29,42c0,0.6-0.4,1-1,1h-4c-0.6,0-1-0.4-1-1v-4c0-0.6,0.4-1,1-1h4 c0.6,0,1,0.4,1,1V42z M29,32c0,0.6-0.4,1-1,1h-4c-0.6,0-1-0.4-1-1v-4c0-0.6,0.4-1,1-1h4c0.6,0,1,0.4,1,1V32z M39,42 c0,0.6-0.4,1-1,1h-4c-0.6,0-1-0.4-1-1v-4c0-0.6,0.4-1,1-1h4c0.6,0,1,0.4,1,1V42z M39,32c0,0.6-0.4,1-1,1h-4c-0.6,0-1-0.4-1-1v-4 c0-0.6,0.4-1,1-1h4c0.6,0,1,0.4,1,1V32z" />
                    <path
                        d="M44,7h-4h-1V5c0-1.6-1.3-3-3-3l0,0c-1.6,0-3,1.3-3,3v2H19V5c0-1.6-1.3-3-3-3l0,0c-1.6,0-3,1.3-3,3v2h-1H8 c-2.2,0-4,1.8-4,4v2.5C4,14.3,4.7,15,5.5,15h41c0.8,0,1.5-0.7,1.5-1.5V11C48,8.8,46.2,7,44,7z" />
                </g>
            </svg>
            <input type="hidden" name="id" value="{{ request()->query('id') }}">
            Live Event Sets
        </a>
    </li>

    <li class="{{ Request::is('admin/vod/content-set') ? 'active' : '' }}">
        <a href=" {{ url('admin/vod/content-set') }}?id={{ request()->query('id') }} " style="color: black;">
            <svg fill="#000000" width="20px" height="20px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M21.53,7.15a1,1,0,0,0-1,0L17,8.89A3,3,0,0,0,14,6H5A3,3,0,0,0,2,9v6a3,3,0,0,0,3,3h9a3,3,0,0,0,3-2.89l3.56,1.78A1,1,0,0,0,21,17a1,1,0,0,0,.53-.15A1,1,0,0,0,22,16V8A1,1,0,0,0,21.53,7.15ZM15,15a1,1,0,0,1-1,1H5a1,1,0,0,1-1-1V9A1,1,0,0,1,5,8h9a1,1,0,0,1,1,1Zm5-.62-3-1.5V11.12l3-1.5Z" />
            </svg>
            <input type="hidden" name="id" value="{{ request()->query('id') }}">
            Vod (Movie) Sets
        </a>
    </li>

    <li class="{{ Request::is('admin/tv-show/content-set') ? 'active' : '' }}">
        <a href=" {{ url('admin/tv-show/content-set') }}?id={{ request()->query('id') }} " style="color: black;">
            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                x="0px" y="0px" width="20px" height="20px" viewBox="0 0 495.861 495.861"
                style="enable-background:new 0 0 495.861 495.861;" xml:space="preserve">
                <g>
                    <g>
                        <path
                            d="M212.443,40.199l-18.645-24.266l-87.577,67.298L18.645,15.934L0,40.199l61.534,47.283l1.989,1.53H9.321v353.431h35.701 v1.529v35.955h61.2v-35.955v-1.529h296.819v1.529v35.955h61.201v-35.955v-1.529h31.619V89.012H148.918l1.989-1.53L212.443,40.199z M432.619,237.168c15.775,0,28.559,12.788,28.559,28.559c0,15.771-12.787,28.56-28.559,28.56s-28.559-12.785-28.559-28.56 S416.848,237.168,432.619,237.168z M404.061,170.867c0-15.774,12.787-28.559,28.559-28.559s28.561,12.788,28.561,28.559 c0,15.771-12.789,28.559-28.561,28.559S404.061,186.642,404.061,170.867z M356.119,398.098H56.24V133.361h299.879V398.098z" />
                    </g>
                </g>
            </svg>
            <input type="hidden" name="id" value="{{ request()->query('id') }}">
            Tv Show Sets
        </a>
    </li>
</ul>