@switch($field)
    @case('order')
        <div class="form-group" data-ng-class="{'has-error': errors.order.has}">
            <label>{{trans('video::general.order')}}</label>
            <div class="form-input">
                <input type="text" name="order" class="form-control" data-ng-model="{{$ngmodel}}" placeholder="{{trans('video::general.order')}}" value="{{old('order')}}" />
            </div>
            <p class="error-msg" data-ng-show="errors.order.has">@{{ errors.order.message }}</p> 
        </div>
        @break

    @case('status')
        <div class="form-group">
            <div class="switch-concept flexbox align-items-center">
                <svg viewBox="0 0 17 14" version="1.1" x="0px" y="0px" width="17px" height="14px">
                    <g>
                    <path d="M 12.6775 0.4999 L 0.6816 0.4999 C 0.3159 0.4999 -0.0001 0.8102 -0.0001 1.2068 L -0.0001 12.7929 C -0.0001 13.1722 0.2991 13.4999 0.6816 13.4999 L 12.6775 13.4999 C 13.0433 13.4999 13.3591 13.1894 13.3591 12.7929 L 13.3591 9.4481 L 15.8362 12.0171 C 15.9692 12.155 16.1355 12.2239 16.3184 12.2239 C 16.4015 12.2239 16.5012 12.2067 16.5844 12.1722 C 16.8337 12.0688 17 11.8101 17 11.5171 L 17 2.4655 C 17 2.1895 16.8337 1.9309 16.5844 1.8102 C 16.335 1.7067 16.0358 1.7584 15.8529 1.9653 L 13.3758 4.5343 L 13.3758 1.2068 C 13.3591 0.8102 13.0599 0.4999 12.6775 0.4999 ZM 11.9958 6.2413 L 11.9958 7.7584 L 11.9958 12.1033 L 1.3466 12.1033 L 1.3466 1.8964 L 11.9958 1.8964 L 11.9958 6.2413 ZM 15.6367 4.1722 L 15.6367 9.8447 L 13.3591 7.4826 L 13.3591 6.5516 L 15.6367 4.1722 Z"
                        fill="#3d3d3d"></path>
                    </g>
                </svg>
                <div class="swich-content flexbox align-items-center flex-wrap">
                    <span>Status</span>
                    <div class="right-side flexbox align-items-center">
                    <span class="text">Inactive</span>
                    <label class="switch">
                    <input type="checkbox" name="is_active" data-ng-model="{{$ngmodel}}" ng-true-value="'1'" ng-false-value="'0'">
                    <span class="slider round"></span>
                    </label>
                    <span class="text">Active</span>
                    </div>
                </div>
            </div>
        </div>
        @break
    @case('side-panel-form-btns')
        <div class="bottom-button text-right flexbox align-items-center">
            <span class="save" data-ng-click="closesidePanelForm()">{{ trans('base::general.cancel') }}</span>
            <button class="publish-now">{{trans('base::general.submit')}}</button>
        </div>
        @break
    @default
        <div></div>
@endswitch
