<!-- Accessories -->
<div class="responsive-box">
    <div class="header-section flexbox align-items-center flex-wrap">
        <h3 class="fs-4 fw-bold" style="font-size: 1.5rem; font-weight: 900; padding-left: 10px;">
            Accessories
        </h3>
    </div>

    <div class="row">
        <div class="justify-content-center mx-auto filter-wrapper">
            <div class="left-side flexbox align-items-center">
                <button type="button" class="btn btn-info button button-blue" data-toggle="modal"
                    data-target="#accessories-modal">
                    <div style="display: flex; justify-content: center; align-items: center;">
                        <svg viewBox="0 0 18 18" width="18px" height="18px">
                            <g>
                                <path
                                    d="M 9.3198 0.3768 C 4.5397 0.3768 0.7 4.218 0.7 8.9999 C 0.7 13.7819 4.5397 17.6231 9.3198 17.6231 C 14.1 17.6231 17.9397 13.7819 17.9397 8.9999 C 17.9397 4.218 14.1 0.3768 9.3198 0.3768 ZM 14.0216 9.3919 C 14.0216 9.6271 13.8649 9.7838 13.6299 9.7838 L 10.2993 9.7838 C 10.1819 9.7838 10.1034 9.8622 10.1034 9.9798 L 10.1034 13.3115 C 10.1034 13.5467 9.9467 13.7035 9.7117 13.7035 L 8.9281 13.7035 C 8.6929 13.7035 8.5363 13.5467 8.5363 13.3115 L 8.5363 9.9798 C 8.5363 9.8622 8.4579 9.7838 8.3403 9.7838 L 5.0099 9.7838 C 4.7748 9.7838 4.6182 9.6271 4.6182 9.3919 L 4.6182 8.6079 C 4.6182 8.3728 4.7748 8.216 5.0099 8.216 L 8.3403 8.216 C 8.4579 8.216 8.5363 8.1376 8.5363 8.02 L 8.5363 4.6883 C 8.5363 4.4532 8.6929 4.2964 8.9281 4.2964 L 9.7117 4.2964 C 9.9467 4.2964 10.1034 4.4532 10.1034 4.6883 L 10.1034 8.02 C 10.1034 8.1376 10.1819 8.216 10.2993 8.216 L 13.6299 8.216 C 13.8649 8.216 14.0216 8.3728 14.0216 8.6079 L 14.0216 9.3919 Z"
                                    fill="#ffffff" />
                            </g>
                        </svg>&nbsp;&nbsp;&nbsp;
                        <span>Add Accessories</span>
                    </div>
                </button>
            </div>
            <p class="assigned-text">
                No accessories were found
            </p>

            <div id="accessories-modal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h1 class="modal-title text-center" style="font-size: 20px; font-weight: bold;">Add
                                Accessories</h1>
                            <div style="display: flex; align-items: center; justify-content: center;">
                                <p class="mb-2" style="margin-bottom:10px; font-size: 12px;">
                                    <i class="glyphicon glyphicon-info-sign"></i>
                                    Please select the Channel Content sets you want to move.
                                </p>
                            </div>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <div class="justify-content-center mx-auto" style="padding: 0px 15px;">
                                    <div class="row">
                                        <!-- Available Channels -->
                                        <div class="col-md-6">
                                            <h4 style="font-size: 15px; font-weight: bold;">Available
                                                Channels</h4>
                                            <input type="text" id="searchAvailable" class="form-control search-box"
                                                placeholder="Search Channels">

                                            <div class="scroll-box" id="availableBundles">
                                                <div class="channel-item dragging" draggable="true"
                                                    data-ng-repeat="channel in subscrCtrl.channelList track by channel.id"
                                                    data-id="@{{ channel.id }}">
                                                    <div style="display: flex; justify-content: center; gap: 4px;">
                                                        <span class="channel-drag text-muted"><i
                                                                class="glyphicon glyphicon-move"></i></span>
                                                        <div class="channel-info">
                                                            <i class="glyphicon glyphicon-blackboard"></i>
                                                            @{{ channel.name }}
                                                        </div>
                                                    </div>
                                                    <!-- <span class="channel-action"><i class="glyphicon glyphicon-play"></i></span> -->
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Assigned Channels -->
                                        <div class="col-md-6">
                                            <h4 style="font-size: 15px; font-weight: bold;">Assigned
                                                Channels</h4>
                                            <input type="text" id="searchAdded" class="form-control search-box"
                                                ng-model="subscrCtrl.channlContentSet.assigned_channels"
                                                placeholder="Search Channels">

                                            <div class="scroll-box" id="addedBundles" style="min-height: 339px;">
                                                <div class="drop-zone">DROP HERE</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
