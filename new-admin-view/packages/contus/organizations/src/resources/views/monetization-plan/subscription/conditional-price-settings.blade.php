<div class="responsive-box" id="conditional">
    <div class="header-section flexbox align-items-center flex-wrap">
        <h3 class="fs-4 fw-bold" style="font-size: 1.5rem; font-weight: 900; padding-left: 10px;">
            Conditional Price Settings
        </h3>
    </div>

    <div class="row">
        <div class="justify-content-center mx-auto filter-wrapper">
            <!-- Subscription -->
            <div class="pricing-section">
                <h4>Subscription</h4>
                <div class="left-side flexbox align-items-center">
                    <button type="button" class="button button-blue" id=""
                        ng-click="subscrCtrl.addSubscriptionRuleSec()">
                        <div style="display: flex; justify-content: center; align-items: center;">
                            <svg viewBox="0 0 18 18" width="18px" height="18px">
                                <g>
                                    <path
                                        d="M 9.3198 0.3768 C 4.5397 0.3768 0.7 4.218 0.7 8.9999 C 0.7 13.7819 4.5397 17.6231 9.3198 17.6231 C 14.1 17.6231 17.9397 13.7819 17.9397 8.9999 C 17.9397 4.218 14.1 0.3768 9.3198 0.3768 ZM 14.0216 9.3919 C 14.0216 9.6271 13.8649 9.7838 13.6299 9.7838 L 10.2993 9.7838 C 10.1819 9.7838 10.1034 9.8622 10.1034 9.9798 L 10.1034 13.3115 C 10.1034 13.5467 9.9467 13.7035 9.7117 13.7035 L 8.9281 13.7035 C 8.6929 13.7035 8.5363 13.5467 8.5363 13.3115 L 8.5363 9.9798 C 8.5363 9.8622 8.4579 9.7838 8.3403 9.7838 L 5.0099 9.7838 C 4.7748 9.7838 4.6182 9.6271 4.6182 9.3919 L 4.6182 8.6079 C 4.6182 8.3728 4.7748 8.216 5.0099 8.216 L 8.3403 8.216 C 8.4579 8.216 8.5363 8.1376 8.5363 8.02 L 8.5363 4.6883 C 8.5363 4.4532 8.6929 4.2964 8.9281 4.2964 L 9.7117 4.2964 C 9.9467 4.2964 10.1034 4.4532 10.1034 4.6883 L 10.1034 8.02 C 10.1034 8.1376 10.1819 8.216 10.2993 8.216 L 13.6299 8.216 C 13.8649 8.216 14.0216 8.3728 14.0216 8.6079 L 14.0216 9.3919 Z"
                                        fill="#ffffff" />
                                </g>
                            </svg>&nbsp;&nbsp;&nbsp;
                            <span>Add Pricing Rule</span>
                        </div>
                    </button>
                </div>

                <br>

                <!-- <div class="rules-div">
                    <div class="accordion accordian-div" id="accordionExample"
                        ng-repeat="rule in rule.subsRule track by rule.id" style="margin-top: 20px">
                        <div class="card" style="padding-left: 0%;">
                            <div class="card-header accordian-header" id="headingOne">
                                <h2 class="mb-0" data-toggle="collapse" data-target="#collapseOne"
                                    aria-expanded="true" aria-controls="collapseOne"
                                    style="display: flex; align-items: center; justify-content: center; margin: 0px 10px;">
                                    <svg width="15px" height="15px" viewBox="0 0 16 16"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill="#000000"
                                            d="M7.11329,12.2849 L8.00014,13.1715 L8.88676,12.285 C9.2773,11.8945 9.91044,11.8946 10.3009,12.2851 C10.6915,12.6756 10.6915,13.3088 10.3009,13.6993 L8.00014,16 L5.69918,13.6993 C5.30859,13.3088 5.30857,12.6756 5.69913,12.285 C6.08963,11.8945 6.72273,11.8945 7.11329,12.2849 Z M3.71489,5.69905 C4.07536077,6.05952077 4.10310633,6.62672953 3.79811882,7.01902974 L3.71494,7.11324 L2.82842,7.99986 L3.71503,8.88671 C4.10548,9.27727 4.10543,9.91037 3.71494,10.3009 C3.35442308,10.6613615 2.78713408,10.6890893 2.39482967,10.3840048 L2.30062,10.3008 L-1.02140518e-14,7.99986 L2.30064,5.69907 C2.69116,5.30852 3.32436,5.30851 3.71489,5.69905 Z M13.6994,5.69907 L16,7.99986 L13.6994,10.3008 C13.3088,10.6914 12.6756,10.6914 12.2851,10.3009 C11.8946,9.91037 11.8945,9.27727 12.285,8.88671 L13.1716,7.99986 L12.2851,7.11324 C11.8946,6.7227 11.8946,6.08956 12.2851,5.69905 C12.6756,5.30851 13.3088,5.30852 13.6994,5.69907 Z M8,6 C8.55228,6 9,6.44772 9,7 C9.55229,7 10,7.44772 10,8 C10,8.55229 9.55229,9 9,9 C9,9.55228 8.55229,10 8,10 C7.44772,10 7,9.55229 7,9 C6.44772,9 6,8.55229 6,8 C6,7.44772 6.44772,7 7,7 C7,6.44772 7.44772,6 8,6 Z M8.00014,-1.02140518e-14 L10.3009,2.30064 C10.6915,2.69116 10.6915,3.32436 10.3009,3.71489 C9.91044,4.1054 9.2773,4.10542 8.88676,3.71494 L8.00014,2.82842 L7.11329,3.71503 C6.72273,4.10548 6.08963,4.10543 5.69913,3.71494 C5.30857,3.32438 5.30859,2.69115 5.69918,2.30062 L8.00014,-1.02140518e-14 Z" />
                                    </svg>
                                    <button class="btn" type="button">
                                        Rule for Product
                                    </button>
                                </h2>
                                <div class="form-group row toggle"
                                    style="margin-bottom: 0px; display: flex; align-items: center; margin: 0px 10px;">
                                    <label class="switch">
                                        <input type="checkbox" name="status" ng-checked="record.status == 1"
                                            ng-click="strmUrlCtrl.toggleStatus(record)">
                                        <span class="slider round"></span>
                                    </label>
                                    <div class="form-group" style="margin-bottom: 0px;">
                                        <label ng-click="subscrCtrl.removeSubscriptionRuleSec($index)">
                                            <svg height="20px" width="20px" version="1.1" id="_x32_"
                                                xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"
                                                xml:space="preserve">
                                                <style type="text/css">
                                                    .st0 {
                                                        fill: #000000;
                                                    }
                                                </style>
                                                <g>
                                                    <path class="st0"
                                                        d="M359.77,224.004H152.228c-5.928,0-10.732,4.804-10.732,10.73v42.535c0,5.926,4.805,10.734,10.732,10.734
                                                                                                                                                                                                                                                                                                                                  H359.77c5.928,0,10.732-4.809,10.732-10.734v-42.535C370.502,228.808,365.697,224.004,359.77,224.004z" />
                                                    <path class="st0"
                                                        d="M256,0C114.613,0,0,114.617,0,256c0,141.387,114.613,256,256,256c141.383,0,256-114.613,256-256
                                                                                                                                                                                                                                                                                                                                  C512,114.617,397.383,0,256,0z M256,448c-105.871,0-192-86.129-192-192c0-105.867,86.129-192,192-192c105.867,0,192,86.133,192,192                                                                                                                                                                                                                                                                              C448,361.871,361.867,448,256,448z" />
                                                </g>
                                            </svg>
                                            <input type="button" name="status">
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div id="collapseOne" class="collapse show border-2 row" aria-labelledby="headingOne"
                                data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="form-group row card-body"
                                        style="margin-bottom: 0px; padding: 20px 50px">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Targeted
                                                Product
                                            </strong></label>
                                        <div class=" select-buttons col-sm-6">
                                            <select name="where" id="where" ng-model="rule.subs_targted_product"
                                                class="form-control"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px;">
                                                <option value="" disabled selected>Targeted
                                                    Product</option>
                                                <option value="p1">Product 1</option>
                                                <option value="p2">Product 2</option>
                                                <option value="p3">Product 3</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row card-body" style="padding: 0px 50px">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 14px; color: #000; margin-top: 10px;"><strong
                                                class="text-muted">If
                                            </strong></label>
                                        <div class=" select-buttons col-sm-6">

                                        </div>
                                    </div>

                                    <div class="form-group row card-body"
                                        style="margin-bottom: 15px; padding: 20px 50px">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Condition
                                            </strong></label>
                                        <div class=" select-buttons col-sm-6">

                                            <select name="where" id="where" ng-model="rule.subs_condtn"
                                                class="form-control"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px;">
                                                <option value="" disabled selected>Condition
                                                </option>
                                                <option value="c1">Condition 1</option>
                                                <option value="c2">Condition 2</option>
                                                <option value="c3">Condition 3</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted">There are no available items for creating a Pricing Rule</p>
                </div> -->
            </div>

            <!-- Content Add-ons -->
            <div class="pricing-section">
                <h4>Content Add-ons</h4>
                <div class="left-side flexbox align-items-center">
                    <button type="button" class="button button-blue" id=""
                        ng-click="subscrCtrl.addContentAddOnsRuleSec()">

                        <div style="display: flex; justify-content: center; align-items: center;">
                            <svg viewBox="0 0 18 18" width="18px" height="18px">
                                <g>
                                    <path
                                        d="M 9.3198 0.3768 C 4.5397 0.3768 0.7 4.218 0.7 8.9999 C 0.7 13.7819 4.5397 17.6231 9.3198 17.6231 C 14.1 17.6231 17.9397 13.7819 17.9397 8.9999 C 17.9397 4.218 14.1 0.3768 9.3198 0.3768 ZM 14.0216 9.3919 C 14.0216 9.6271 13.8649 9.7838 13.6299 9.7838 L 10.2993 9.7838 C 10.1819 9.7838 10.1034 9.8622 10.1034 9.9798 L 10.1034 13.3115 C 10.1034 13.5467 9.9467 13.7035 9.7117 13.7035 L 8.9281 13.7035 C 8.6929 13.7035 8.5363 13.5467 8.5363 13.3115 L 8.5363 9.9798 C 8.5363 9.8622 8.4579 9.7838 8.3403 9.7838 L 5.0099 9.7838 C 4.7748 9.7838 4.6182 9.6271 4.6182 9.3919 L 4.6182 8.6079 C 4.6182 8.3728 4.7748 8.216 5.0099 8.216 L 8.3403 8.216 C 8.4579 8.216 8.5363 8.1376 8.5363 8.02 L 8.5363 4.6883 C 8.5363 4.4532 8.6929 4.2964 8.9281 4.2964 L 9.7117 4.2964 C 9.9467 4.2964 10.1034 4.4532 10.1034 4.6883 L 10.1034 8.02 C 10.1034 8.1376 10.1819 8.216 10.2993 8.216 L 13.6299 8.216 C 13.8649 8.216 14.0216 8.3728 14.0216 8.6079 L 14.0216 9.3919 Z"
                                        fill="#ffffff" />
                                </g>
                            </svg>&nbsp;&nbsp;&nbsp;
                            <span>Add Pricing Rule</span>
                        </div>
                    </button>
                </div>
                <br>
                <!-- <div class="rules-div">
                    <div class="accordion accordian-div" id="accordionExample"
                        ng-repeat="rule in rule.contentRule track by rule.id" style="margin-top: 20px">
                        <div class="card" style="padding-left: 0%;">
                            <div class="card-header accordian-header" id="headingOne">
                                <h2 class="mb-0" data-toggle="collapse" data-target="#collapseOne"
                                    aria-expanded="true" aria-controls="collapseOne"
                                    style="display: flex; align-items: center; justify-content: center; margin: 0px 10px;">
                                    <svg width="15px" height="15px" viewBox="0 0 16 16"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill="#000000"
                                            d="M7.11329,12.2849 L8.00014,13.1715 L8.88676,12.285 C9.2773,11.8945 9.91044,11.8946 10.3009,12.2851 C10.6915,12.6756 10.6915,13.3088 10.3009,13.6993 L8.00014,16 L5.69918,13.6993 C5.30859,13.3088 5.30857,12.6756 5.69913,12.285 C6.08963,11.8945 6.72273,11.8945 7.11329,12.2849 Z M3.71489,5.69905 C4.07536077,6.05952077 4.10310633,6.62672953 3.79811882,7.01902974 L3.71494,7.11324 L2.82842,7.99986 L3.71503,8.88671 C4.10548,9.27727 4.10543,9.91037 3.71494,10.3009 C3.35442308,10.6613615 2.78713408,10.6890893 2.39482967,10.3840048 L2.30062,10.3008 L-1.02140518e-14,7.99986 L2.30064,5.69907 C2.69116,5.30852 3.32436,5.30851 3.71489,5.69905 Z M13.6994,5.69907 L16,7.99986 L13.6994,10.3008 C13.3088,10.6914 12.6756,10.6914 12.2851,10.3009 C11.8946,9.91037 11.8945,9.27727 12.285,8.88671 L13.1716,7.99986 L12.2851,7.11324 C11.8946,6.7227 11.8946,6.08956 12.2851,5.69905 C12.6756,5.30851 13.3088,5.30852 13.6994,5.69907 Z M8,6 C8.55228,6 9,6.44772 9,7 C9.55229,7 10,7.44772 10,8 C10,8.55229 9.55229,9 9,9 C9,9.55228 8.55229,10 8,10 C7.44772,10 7,9.55229 7,9 C6.44772,9 6,8.55229 6,8 C6,7.44772 6.44772,7 7,7 C7,6.44772 7.44772,6 8,6 Z M8.00014,-1.02140518e-14 L10.3009,2.30064 C10.6915,2.69116 10.6915,3.32436 10.3009,3.71489 C9.91044,4.1054 9.2773,4.10542 8.88676,3.71494 L8.00014,2.82842 L7.11329,3.71503 C6.72273,4.10548 6.08963,4.10543 5.69913,3.71494 C5.30857,3.32438 5.30859,2.69115 5.69918,2.30062 L8.00014,-1.02140518e-14 Z" />
                                    </svg>
                                    <button class="btn" type="button">
                                        Rule for Content Add Ons
                                    </button>
                                </h2>
                                <div class="form-group row toggle"
                                    style="margin-bottom: 0px; display: flex; align-items: center; margin: 0px 10px;">
                                    <label class="switch">
                                        <input type="checkbox" name="status" ng-checked="record.status == 1"
                                            ng-click="strmUrlCtrl.toggleStatus(record)">
                                        <span class="slider round"></span>
                                    </label>
                                    <div class="form-group" style="margin-bottom: 0px;">
                                        <label ng-click="subscrCtrl.removeContentAddOnsRuleSec($index)">
                                            <svg height="20px" width="20px" version="1.1" id="_x32_"
                                                xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"
                                                xml:space="preserve">
                                                <style type="text/css">
                                                    .st0 {
                                                        fill: #000000;
                                                    }
                                                </style>
                                                <g>
                                                    <path class="st0"
                                                        d="M359.77,224.004H152.228c-5.928,0-10.732,4.804-10.732,10.73v42.535c0,5.926,4.805,10.734,10.732,10.734
                                                                                                                                                                                                                                                                                                                                  H359.77c5.928,0,10.732-4.809,10.732-10.734v-42.535C370.502,228.808,365.697,224.004,359.77,224.004z" />
                                                    <path class="st0"
                                                        d="M256,0C114.613,0,0,114.617,0,256c0,141.387,114.613,256,256,256c141.383,0,256-114.613,256-256
                                                                                                                                                                                                                                                                                                                                  C512,114.617,397.383,0,256,0z M256,448c-105.871,0-192-86.129-192-192c0-105.867,86.129-192,192-192c105.867,0,192,86.133,192,192
                                                                                                                                                                                                                                                                                                                                  C448,361.871,361.867,448,256,448z" />
                                                </g>
                                            </svg>
                                            <input type="button" name="status">
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div id="collapseOne" class="collapse show border-2 row" aria-labelledby="headingOne"
                                data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="form-group row card-body"
                                        style="margin-bottom: 0px; padding: 20px 50px">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Targeted
                                                Product
                                            </strong></label>
                                        <div class=" select-buttons col-sm-6">

                                            <select name="where" id="where" ng-model="rule.content_product"
                                                class="form-control"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px;">
                                                <option value="" disabled selected>Targeted
                                                    Product</option>
                                                <option value="p1">Product 1</option>
                                                <option value="p2">Product 2</option>
                                                <option value="p3">Product 3</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row card-body" style="padding: 0px 50px">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 14px; color: #000; margin-top: 10px;"><strong
                                                class="text-muted">If
                                            </strong></label>
                                        <div class=" select-buttons col-sm-6">

                                        </div>
                                    </div>

                                    <div class="form-group row card-body"
                                        style="margin-bottom: 15px; padding: 20px 50px">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Condition
                                            </strong></label>
                                        <div class=" select-buttons col-sm-6">

                                            <select name="where" id="where" ng-model="rule.content_where"
                                                class="form-control"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px;">
                                                <option value="" disabled selected>Condition
                                                </option>
                                                <option value="c1">Condition 1</option>
                                                <option value="c2">Condition 2</option>
                                                <option value="c3">Condition 3</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted" ng-if="rule.contentRule.length === 0">There are no available items for
                            creating
                            a Pricing Rule</p>
                    </div>
                </div> -->
            </div>

            <!-- Accessories -->
            <div class="pricing-section">
                <h4>Accessories</h4>
                <div class="left-side flexbox align-items-center">
                    <button type="button" class="button button-blue" id=""
                        ng-click="subscrCtrl.addAccessoriesRuleSec()">
                        <div style="display: flex; justify-content: center; align-items: center;">
                            <svg viewBox="0 0 18 18" width="18px" height="18px">
                                <g>
                                    <path
                                        d="M 9.3198 0.3768 C 4.5397 0.3768 0.7 4.218 0.7 8.9999 C 0.7 13.7819 4.5397 17.6231 9.3198 17.6231 C 14.1 17.6231 17.9397 13.7819 17.9397 8.9999 C 17.9397 4.218 14.1 0.3768 9.3198 0.3768 ZM 14.0216 9.3919 C 14.0216 9.6271 13.8649 9.7838 13.6299 9.7838 L 10.2993 9.7838 C 10.1819 9.7838 10.1034 9.8622 10.1034 9.9798 L 10.1034 13.3115 C 10.1034 13.5467 9.9467 13.7035 9.7117 13.7035 L 8.9281 13.7035 C 8.6929 13.7035 8.5363 13.5467 8.5363 13.3115 L 8.5363 9.9798 C 8.5363 9.8622 8.4579 9.7838 8.3403 9.7838 L 5.0099 9.7838 C 4.7748 9.7838 4.6182 9.6271 4.6182 9.3919 L 4.6182 8.6079 C 4.6182 8.3728 4.7748 8.216 5.0099 8.216 L 8.3403 8.216 C 8.4579 8.216 8.5363 8.1376 8.5363 8.02 L 8.5363 4.6883 C 8.5363 4.4532 8.6929 4.2964 8.9281 4.2964 L 9.7117 4.2964 C 9.9467 4.2964 10.1034 4.4532 10.1034 4.6883 L 10.1034 8.02 C 10.1034 8.1376 10.1819 8.216 10.2993 8.216 L 13.6299 8.216 C 13.8649 8.216 14.0216 8.3728 14.0216 8.6079 L 14.0216 9.3919 Z"
                                        fill="#ffffff" />
                                </g>
                            </svg>&nbsp;&nbsp;&nbsp;
                            <span>Add Pricing Rule</span>
                        </div>
                    </button>
                </div>
                <br>
                <!-- <div class="rules-div">
                    <div class="accordion accordian-div" id="accordionExample"
                        ng-repeat="rule in rule.accessories track by rule.id" style="margin-top: 20px">
                        <div class="card" style="padding-left: 0%;">
                            <div class="card-header accordian-header" id="headingOne">
                                <h2 class="mb-0" data-toggle="collapse" data-target="#collapseOne"
                                    aria-expanded="true" aria-controls="collapseOne"
                                    style="display: flex; align-items: center; justify-content: center; margin: 0px 10px;">
                                    <svg width="15px" height="15px" viewBox="0 0 16 16"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill="#000000"
                                            d="M7.11329,12.2849 L8.00014,13.1715 L8.88676,12.285 C9.2773,11.8945 9.91044,11.8946 10.3009,12.2851 C10.6915,12.6756 10.6915,13.3088 10.3009,13.6993 L8.00014,16 L5.69918,13.6993 C5.30859,13.3088 5.30857,12.6756 5.69913,12.285 C6.08963,11.8945 6.72273,11.8945 7.11329,12.2849 Z M3.71489,5.69905 C4.07536077,6.05952077 4.10310633,6.62672953 3.79811882,7.01902974 L3.71494,7.11324 L2.82842,7.99986 L3.71503,8.88671 C4.10548,9.27727 4.10543,9.91037 3.71494,10.3009 C3.35442308,10.6613615 2.78713408,10.6890893 2.39482967,10.3840048 L2.30062,10.3008 L-1.02140518e-14,7.99986 L2.30064,5.69907 C2.69116,5.30852 3.32436,5.30851 3.71489,5.69905 Z M13.6994,5.69907 L16,7.99986 L13.6994,10.3008 C13.3088,10.6914 12.6756,10.6914 12.2851,10.3009 C11.8946,9.91037 11.8945,9.27727 12.285,8.88671 L13.1716,7.99986 L12.2851,7.11324 C11.8946,6.7227 11.8946,6.08956 12.2851,5.69905 C12.6756,5.30851 13.3088,5.30852 13.6994,5.69907 Z M8,6 C8.55228,6 9,6.44772 9,7 C9.55229,7 10,7.44772 10,8 C10,8.55229 9.55229,9 9,9 C9,9.55228 8.55229,10 8,10 C7.44772,10 7,9.55229 7,9 C6.44772,9 6,8.55229 6,8 C6,7.44772 6.44772,7 7,7 C7,6.44772 7.44772,6 8,6 Z M8.00014,-1.02140518e-14 L10.3009,2.30064 C10.6915,2.69116 10.6915,3.32436 10.3009,3.71489 C9.91044,4.1054 9.2773,4.10542 8.88676,3.71494 L8.00014,2.82842 L7.11329,3.71503 C6.72273,4.10548 6.08963,4.10543 5.69913,3.71494 C5.30857,3.32438 5.30859,2.69115 5.69918,2.30062 L8.00014,-1.02140518e-14 Z" />
                                    </svg>
                                    <button class="btn" type="button">
                                        Rule for Accessories
                                    </button>
                                </h2>
                                <div class="form-group row toggle"
                                    style="margin-bottom: 0px; display: flex; align-items: center; margin: 0px 10px;">
                                    <label class="switch">
                                        <input type="checkbox" name="status" ng-checked="record.status == 1"
                                            ng-click="strmUrlCtrl.toggleStatus(record)">
                                        <span class="slider round"></span>
                                    </label>
                                    <div class="form-group" style="margin-bottom: 0px;">
                                        <label ng-click="subscrCtrl.removeAccessoriesRuleSec($index)">
                                            <svg height="20px" width="20px" version="1.1" id="_x32_"
                                                xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"
                                                xml:space="preserve">
                                                <style type="text/css">
                                                    .st0 {
                                                        fill: #000000;
                                                    }
                                                </style>
                                                <g>
                                                    <path class="st0"
                                                        d="M359.77,224.004H152.228c-5.928,0-10.732,4.804-10.732,10.73v42.535c0,5.926,4.805,10.734,10.732,10.734
                                                                                                                                                                                                                                                                                                                              H359.77c5.928,0,10.732-4.809,10.732-10.734v-42.535C370.502,228.808,365.697,224.004,359.77,224.004z" />
                                                    <path class="st0"
                                                        d="M256,0C114.613,0,0,114.617,0,256c0,141.387,114.613,256,256,256c141.383,0,256-114.613,256-256
                                                                                                                                                                                                                                                                                                                              C512,114.617,397.383,0,256,0z M256,448c-105.871,0-192-86.129-192-192c0-105.867,86.129-192,192-192c105.867,0,192,86.133,192,192
                                                                                                                                                                                                                                                                                                                              C448,361.871,361.867,448,256,448z" />
                                                </g>
                                            </svg>
                                            <input type="button" name="status">
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div id="collapseOne" class="collapse show border-2 row" aria-labelledby="headingOne"
                                data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="form-group row card-body"
                                        style="margin-bottom: 0px; padding: 20px 50px">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Targeted
                                                Product
                                            </strong></label>
                                        <div class=" select-buttons col-sm-6">
                                            <select name="where" id="where" ng-model="rule.acces_product"
                                                class="form-control"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px;">
                                                <option value="" disabled selected>Targeted
                                                    Product</option>
                                                <option value="p1">Product 1</option>
                                                <option value="p2">Product 2</option>
                                                <option value="p3">Product 3</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row card-body" style="padding: 0px 50px">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 14px; color: #000; margin-top: 10px;"><strong
                                                class="text-muted">If
                                            </strong></label>
                                        <div class=" select-buttons col-sm-6">

                                        </div>
                                    </div>

                                    <div class="form-group row card-body"
                                        style="margin-bottom: 15px; padding: 20px 50px">
                                        <label class="col-sm-2 control-label"
                                            style="font-size: 14px; color: #000; margin-top: 10px;"><strong>Condition
                                            </strong></label>
                                        <div class=" select-buttons col-sm-6">
                                            <select name="where" id="where" ng-model="rule.acces_condtn"
                                                class="form-control"
                                                style="border: 2px solid rgba(128, 130, 133, 0.36); border-radius: 20px; padding: 0px 9px;">
                                                <option value="" disabled selected>Condition
                                                </option>
                                                <option value="c1">Condition 1</option>
                                                <option value="c2">Condition 2</option>
                                                <option value="c3">Condition 3</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted">There are no available items for creating a Pricing Rule</p>
                </div> -->
            </div>
        </div>
    </div>
</div>
