<nav class="nav nav-pills" style="margin: 1rem 0rem 1rem 0rem;">
    <li class="{{ Request::routeIs('subscriber.report') ? 'active' : '' }}" style="margin-right: 0.5rem;">
        <a href="{{ url('admin/subscriber-reports') }}"
            style="{{ Request::routeIs('subscriber.report') ? 'background-color: #00ACCD;' : '' }}"
            class="btn btn-default">
            <svg version="1.1" id="Uploaded to svgrepo.com" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" viewBox="0 0 32 32"
                xml:space="preserve">
                <style type="text/css">
                    .puchipuchi_een {
                        fill: #111918;
                    }
                </style>
                <path class="puchipuchi_een" d="M24,14h-6V8c0-1.104-0.896-2-2-2s-2,0.896-2,2v6H8c-1.104,0-2,0.896-2,2s0.896,2,2,2h6v6
	c0,1.104,0.896,2,2,2s2-0.896,2-2v-6h6c1.104,0,2-0.896,2-2S25.104,14,24,14z" />
            </svg>
            New Reports
        </a>
    </li>


    <li class="{{ Request::routeIs('subscriber.save.template') ? 'active' : '' }}" style="margin-right: 0.5rem;">
        <a href="{{ url('admin/subscriber/save-templates') }}"
            style="{{ Request::routeIs('subscriber.save.template') ? 'background-color: #00ACCD;' : '' }}" class="btn btn-default">
            <svg fill="#000000" width="20px" height="20px" viewBox="-3 -3 24 24" xmlns="http://www.w3.org/2000/svg"
                preserveAspectRatio="xMinYMin" class="jam jam-save">
                <path
                    d='M2 0h11.22a2 2 0 0 1 1.345.52l2.78 2.527A2 2 0 0 1 18 4.527V16a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm0 2v14h14V4.527L13.22 2H2zm4 8h6a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-4a2 2 0 0 1 2-2zm0 2v4h6v-4H6zm7-9a1 1 0 0 1 1 1v3a1 1 0 0 1-2 0V4a1 1 0 0 1 1-1zM5 3h5a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1zm1 3h3V5H6v1z' />
            </svg>
            Saved Report Templates
        </a>
    </li>

    <li class="{{ Request::routeIs('subscriber.activation') ? 'active' : '' }}"
        style="margin-right: 0.5rem;">
        <a href="{{ url('admin/subscriber/generate') }}"
            style="{{ Request::routeIs('subscriber.activation') ? 'background-color: #00ACCD;' : '' }}"
            class="btn btn-default">
            <svg fill="#000000" width="20px" height="20px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                data-name="Layer 1">
                <path
                    d="M19.9,12.66a1,1,0,0,1,0-1.32L21.18,9.9a1,1,0,0,0,.12-1.17l-2-3.46a1,1,0,0,0-1.07-.48l-1.88.38a1,1,0,0,1-1.15-.66l-.61-1.83A1,1,0,0,0,13.64,2h-4a1,1,0,0,0-1,.68L8.08,4.51a1,1,0,0,1-1.15.66L5,4.79A1,1,0,0,0,4,5.27L2,8.73A1,1,0,0,0,2.1,9.9l1.27,1.44a1,1,0,0,1,0,1.32L2.1,14.1A1,1,0,0,0,2,15.27l2,3.46a1,1,0,0,0,1.07.48l1.88-.38a1,1,0,0,1,1.15.66l.61,1.83a1,1,0,0,0,1,.68h4a1,1,0,0,0,.95-.68l.61-1.83a1,1,0,0,1,1.15-.66l1.88.38a1,1,0,0,0,1.07-.48l2-3.46a1,1,0,0,0-.12-1.17ZM18.41,14l.8.9-1.28,2.22-1.18-.24a3,3,0,0,0-3.45,2L12.92,20H10.36L10,18.86a3,3,0,0,0-3.45-2l-1.18.24L4.07,14.89l.8-.9a3,3,0,0,0,0-4l-.8-.9L5.35,6.89l1.18.24a3,3,0,0,0,3.45-2L10.36,4h2.56l.38,1.14a3,3,0,0,0,3.45,2l1.18-.24,1.28,2.22-.8.9A3,3,0,0,0,18.41,14ZM11.64,8a4,4,0,1,0,4,4A4,4,0,0,0,11.64,8Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,11.64,14Z" />
            </svg>
            Generated Reports
        </a>
    </li>

</nav>