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
    <li ng-class="{'active': ROCtrl.btnNo == 0}">
        <a style="color: black;" ng-click="ROCtrl.btnNo=0">
            Image
        </a>
    </li>

    <li ng-class="{'active': ROCtrl.btnNo == 1}">
        <a style="color: black;" ng-click="ROCtrl.btnNo=1">
            Gradient
        </a>
    </li>

    <li ng-class="{'active': ROCtrl.btnNo == 2}">
        <a style="color: black;" ng-click="ROCtrl.btnNo=2">
            No Set
        </a>
    </li>
</ul>
