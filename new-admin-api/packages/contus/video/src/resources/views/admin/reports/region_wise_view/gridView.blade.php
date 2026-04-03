<div class="tab-content">
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
                  <td class="">@{{((currentPage - 1) * rowsPerPage) + $index +1}}</td>
               <td>
                  <p class="img_description ng-binding">@{{record._id}}</p>
               </td>
               <td class="center">@{{record.count}}</td>
               <td class="center">@{{record.percentage | decimalPoint}}%</td>
            </tr>
         </tbody>
      </table>
   </div>
   @include('base::layouts.pagination')
</div>