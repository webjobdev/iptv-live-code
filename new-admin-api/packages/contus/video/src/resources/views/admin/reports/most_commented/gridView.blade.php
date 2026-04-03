<div>
    <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
        <div class="table_loader">
            <div class="loader"></div>
        </div>
    </div>
    <div class="table_responsive">  
        <table class="table" data-ng-class="{'no-records': noRecords}">
            <thead>
                <tr>
                    <th data-ng-repeat="field in heading" data-ng-class="{center:field.class}">
                        @{{field.name}}
                        <span data-ng-if="field.sort==true" id="" class="th-inner sortable both" data-ng-class="{showGridArrow:field.sort}" data-ng-click="fieldOrder($event,field.value)"></span>
                        <span data-ng-if="field.sort==false" data-ng-class="{showGridArrow:field.sort}"></span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr data-ng-if="noRecords">
                    <td colspan="@{{heading.length + 1}}" class="no-data center">{{trans('base::general.not_found')}}</td>
                </tr>
                <tr data-ng-if="showRecords" data-ng-repeat="record in records track by $index" data-ng-show="showRecords" class="list-repeat">                    
                        <td class="center">@{{((currentPage - 1) * rowsPerPage) + $index +1}}</td>
                    <td>
                        <div class="product_img flexbox align-items-center table-image-text"> 
                            <div class="image" bg-image="@{{record.video.thumbnail_image}}" on-error-src="{{url('contus/base/images/no-preview.png')}}">
                            </div>
                            <div class="product_description">
                                <p class="grid-title">@{{record.video.title}}
                                </p>
                            </div>
                        </div>
                    </td>
                    <td>@{{record.video.categories[0].title}}</td>
                    <td class="center">@{{record.aggregate}}</td>
                </tr>                                    
            </tbody>                        
        </table>
    </div>
    @include('base::layouts.pagination')
</div>