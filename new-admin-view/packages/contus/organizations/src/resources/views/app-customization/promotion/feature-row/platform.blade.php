<!-- status -->
<div class="form-group row" style="margin-bottom: 15px;">
    <label for="prefix" class="col-sm-1 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
        Status<span class="required">*</span>:
    </label>
    <label class="switch" style="margin: 10px 0px 10px 16px;">
        <input type="checkbox" name="featured_row_status" ng-model="fturow.featured_row_status"
            ng-checked="record.content_sets.featured_row_status == 1">
        <span class="slider round"></span>
    </label>
</div>

<!-- updated -->
<div class="form-group row" style="margin-bottom: 15px;">
    <label for="prefix" class="col-sm-1 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
        Updated:
    </label>
    <label style="margin: 10px 0px 10px 16px;">
        @{{ record.user.email }}
    </label>
</div>

<!-- select platform -->
<div class="form-group row" style="margin-bottom: 15px;">
    <label class="col-sm-2 control-label" style="font-size: 14px; color: #000; margin-top: 10px;">
        Select Platform<span class="required">*</span>:
    </label>
    @php
        $selectedPlatforms = old(
            'platforms',
            $organizationDetail->platforms ?? '[]',
        );
        $selectedPlatforms = json_decode($selectedPlatforms, true) ?? [];
    @endphp
    <div class="col-sm-7" style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr;">
        @foreach (['Stb', 'Pc/Lg', 'Ios', 'tvOS', 'Android Mobile', 'Samsung Tv', 'Web', 'Others/Roku'] as $platform)
            <div>
                <input type="checkbox" class="form-check-input" name="platforms[]" value="{{ $platform }}"
                    ng-checked="fturow.platforms && fturow.platforms.includes('{{ $platform }}')"
                    ng-model="fturow.platforms['{{ $platform }}']" data-id="{{ $loop->index + 1 }}"
                    ng-click="fturCtrl.togglePlatform('{{ $platform }}', fturow)">
                <label class="form-check-label">{{ $platform }}</label>
            </div>
        @endforeach
    </div>
</div>