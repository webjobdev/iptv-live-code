<style>
    .panel-body {
        background-color: #fff;
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
        border-radius: 5px 5px 10px 10px;
    }

    /* Flex layout for panel heading */
    .panel-heading {
        /* border: 1px solid #eee; */
        box-shadow: 0px -3px 10px rgba(0, 0, 0, 0.2);
        border-radius: 10px 10px 5px 5px;
        background-color: #fff;
    }

    /* ===== Toolbar Container ===== */
    .d-flex {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        align-items: center;
        gap: 8px;
    }

    /* ===== Button Groups ===== */
    .btn-group {
        margin: 0;
    }

    /* ===== Buttons ===== */
    .btn.btn-default {
        border: 1px solid #ccc;
        background-color: #fff;
        padding: 8px 10px;
        transition: all 0.2s ease-in-out;
    }

    .btn.btn-default span {
        font-size: 16px;
    }

    /* ===== Responsive Layout ===== */

    /* Medium devices (tablets and small desktops) */
    @media (max-width: 1200px) {
        .d-flex {
            justify-content: flex-start;
            /* align left on tablets/mobile when stacked */
            gap: 6px;
            margin-top: 10px;
        }

        .btn.btn-default {
            padding: 7px 9px;
        }

        .btn.btn-default span {
            font-size: 15px;
        }
    }

    /* Small devices (phones landscape) */
    @media (max-width: 767px) {
        .d-flex {
            justify-content: flex-start;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 10px;
        }

        .btn.btn-default {
            padding: 6px 8px;
            margin: 2px;
        }

        .btn.btn-default span {
            font-size: 14px;
        }
    }

    /* Extra small devices (phones portrait) */
    @media (max-width: 480px) {
        .d-flex {
            justify-content: center;
        }

        .btn.btn-default {
            width: 42px;
            /* equal button width for compact view */
            height: 42px;
            padding: 0;
            border-radius: 6px;
        }

        .btn.btn-default span {
            font-size: 16px;
            line-height: 42px;
        }
    }

    /* ===== Dashboard Container ===== */
    .dashboard-container {
        margin-top: 15px;
        margin-bottom: 15px;
    }

    /* ===== Grid Layout ===== */
    .dashboard-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;
    }

    /* ===== Each Tile ===== */
    .dashboard-item {
        padding: 10px;
        /* display: flex; */
        /* justify-content: center; */
    }

    /* ===== Main Button Style ===== */
    .dashboard-btn {
        width: 100%;
        height: 100px;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        transition: all 0.2s ease-in-out;
        text-align: center;
        /* color: #657a86; */
    }

    .dashboard-btn p {
        font-weight: 600;
        color: #657a86;
    }

    .dashboard-btn span {
        display: block;
        margin-bottom: 6px;
    }

    .dashboard-btn p {
        margin: 0;
        font-size: 13px;
        white-space: normal;
    }

    /* ===== Responsive Design ===== */

    @media (max-width: 1200px) {
        .dashboard-btn p {
            font-size: 11px;
        }
    }

    @media (max-width: 991px) {
        .dashboard-btn {
            height: auto;
            min-height: 90px;
            padding: 10px 5px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .dashboard-btn span {
            margin-bottom: 5px;
        }
    }

    @media (max-width: 767px) {
        .dashboard-item {
            width: 33.33%;
            /* 3 per row on landscape phone/small tablet */
            padding: 5px;
        }

        .dashboard-btn {
            height: auto;
            min-height: 85px;
            font-size: 12px;
        }

        .dashboard-btn span {
            font-size: 20px;
        }
    }

    @media (max-width: 480px) {
        .dashboard-item {
            width: 50%;
            /* 2 per row on mobile portrait */
            padding: 5px;
        }

        .dashboard-btn {
            min-height: 80px;
        }
    }


    /* ===== Stats Section ===== */
    .stats-section {
        margin-top: 15px;
        margin-bottom: 15px;
    }

    .stats-section .stat-column {
        border-right: 1px solid #e0e0e0;
    }

    .stats-section .stat-column:last-child {
        border-right: none;
    }

    .stats-section .stat-item {
        padding: 10px 0;
        border-bottom: 1px solid #e0e0e0;
        font-size: 14px;
        color: #657a86;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stats-section .stat-item strong {
        font-weight: 600;
        color: #657a86;
    }

    .stats-section .stat-item:last-child {
        border-bottom: none;
    }

    /* ===== Responsive Design ===== */

    /* Tablet View (2 columns collapse into one if too tight) */
    @media (max-width: 992px) {
        .stats-section .stat-column {
            border-right: none;
        }

        .stats-section .row {
            display: flex;
            flex-wrap: wrap;
        }

        .stats-section .col-md-6 {
            width: 100%;
        }

        .stats-section .stat-item {
            font-size: 13px;
        }
    }

    /* Mobile View (single column layout) */
    @media (max-width: 576px) {
        .stats-section {
            margin-top: 15px;
        }

        .stats-section .stat-item {
            font-size: 13px;
            padding: 8px 0;
        }

        .stats-section .stat-item strong {
            display: block;
            margin-bottom: 4px;
        }
    }
</style>

<div class="sidepanel">
    <div class="overlay"></div>
    <div class="pop_over_continer form-page">
        <form name="organizationForm" id="organizationForm" method="POST" data-base-validator
            data-ng-submit="dashCtrl.save($event)" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="sidepanel-header flexbox align-items-center">
                <h5>{{trans('organizations::index.add_organization')}}</h5>
            </div>

            <div class="sidepanel-scroll">
                @include('base::partials.errors')

                <div class="form-group" data-ng-class="{'has-error': errors.name.has}">
                    <label>
                        {{trans('organizations::index.organization_name')}}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="text" name="name" data-unique="@{{dashCtrl.uniqueRoute}}"
                            data-ng-model="dashCtrl.organization.name" class="form-control"
                            placeholder="{{trans('organizations::index.organization_placeholder')}}" value="" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.name">@{{ errors.name[0] }}</p>
                </div>
            </div>

            <div class="bottom-button text-right flexbox align-items-center">
                <input type="button" value="{{trans('base::general.cancel')}}"
                    data-ng-click="dashCtrl.closeSubscriptionEdit()" name="cancel" class="save" />
                <input type="submit" value="{{trans('base::general.submit')}}" name="submit" class="publish-now" />
            </div>
        </form>

        <form name="organizationTranslationForm" id="organizationTranslationForm" method="POST" data-base-validator
            data-ng-submit="dashCtrl.save($event)" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="sidepanel-header flexbox align-items-center">
                <h5>{{trans('organizations::index.add_organization')}}</h5>
            </div>

            <div class="sidepanel-scroll">
                @include('base::partials.errors')

                <div class="form-group" data-ng-class="{'has-error': errors.name.has}">
                    <label>
                        {{trans('organizations::index.organization_name')}}
                        <span class="required">*</span>
                    </label>
                    <div class="form-input">
                        <input type="text" name="name" data-unique="@{{dashCtrl.uniqueRoute}}"
                            data-ng-model="dashCtrl.organization.organization_name" class="form-control"
                            placeholder="{{trans('organizations::index.organization_placeholder')}}" value="" />
                    </div>
                    <p class="error-msg" data-ng-show="errors.name.has">@{{ errors.name.message }}</p>
                </div>
            </div>

            <div class="bottom-button text-right flexbox align-items-center">
                <input type="button" value="{{trans('base::general.cancel')}}"
                    data-ng-click="dashCtrl.closeSubscriptionEdit()" name="cancel" class="save" />
                <input type="submit" value="{{trans('base::general.submit')}}" name="submit" class="publish-now" />
            </div>
        </form>
    </div>
</div>

<div class="panel-group" id="accordion-864287" role="tablist" aria-multiselectable="true"></div>


<!-- Pagination Container -->
<div id="pagination-controls" style="margin: 20px 0;text-align: end;"></div>