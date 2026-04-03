import glob

files = [
    'd:/jay_savaliya/ott-laravel/iptvmiddlewaresolutions/new-admin-view/packages/contus/organizations/src/resources/views/organization-contentset/tv_show_sets/add.blade.php',
    'd:/jay_savaliya/ott-laravel/iptvmiddlewaresolutions/new-admin-view/packages/contus/organizations/src/resources/views/organization-contentset/tv_show_sets/edit.blade.php',
    'd:/jay_savaliya/ott-laravel/iptvmiddlewaresolutions/new-admin-view/packages/contus/organizations/src/resources/views/organization-contentset/tv_show_sets/view.blade.php'
]

new_html = """                                    <!-- tv show -->
                                    <div class="row dual-list-container" ng-show="tvsset.item_type == 'tv_show'" style="margin-bottom: 15px;">
                                        <!-- Available tvs -->
                                        <div class="col-md-6">
                                            <div class="list-wrapper">
                                                <div class="list-header">
                                                    <i class="glyphicon glyphicon-list-alt"></i> Available Tv Shows
                                                </div>
                                                <input type="text" id="searchAvailable" class="form-control search-box"
                                                    placeholder="Search Tv Shows">

                                                <div class="scroll-box" id="availableBundles">
                                                    <div class="tvs-item" draggable="true"
                                                        data-ng-repeat="live in tvsetCtrl.tvsset" data-id="{{ live.id }}">
                                                        <span class="tvs-drag">
                                                            <i class="glyphicon glyphicon-move"></i>
                                                        </span>
                                                        <div class="tvs-info">
                                                            <i class="glyphicon glyphicon-film" style="color: #666; font-size: 14px;"></i>
                                                            {{ live.title }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Assigned tvss -->
                                        <div class="col-md-6">
                                            <div class="list-wrapper">
                                                <div class="list-header">
                                                    <i class="glyphicon glyphicon-check"></i> Assigned Tv Shows
                                                </div>

                                                <input type="text" id="searchAdded" class="form-control search-box"
                                                    placeholder="Search Tv Shows">

                                                <div class="scroll-box">
                                                    <div class="tvs-item" draggable="true" ng-model="tvsset.assigned_tv_show"
                                                        data-ng-repeat="show in tvsset.selectedBundles"
                                                        data-id="{{ show.id }}">
                                                        <span class="tvs-drag"><i class="glyphicon glyphicon-move"></i></span>
                                                        <div class="tvs-info">
                                                            <i class="glyphicon glyphicon-film" style="color: #666; font-size: 14px;"></i>
                                                            {{ show.title }}
                                                        </div>
                                                        <span class="tvs-action bundle-remove" data-ng-click="removeContent(show)">
                                                            <i class="glyphicon glyphicon-trash"></i>
                                                        </span>
                                                    </div>

                                                    <div id="addedBundles" style="min-height: 80px;" ng-model="tvsset.assigned_tv_show">
                                                        <div class="drop-zone" ng-show="!tvsset.selectedBundles.length">
                                                            <i class="glyphicon glyphicon-download-alt" style="margin-right: 8px;"></i>
                                                            DROP HERE
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- season code -->
                                    <div class="row dual-list-container" ng-show="tvsset.item_type == 'season'" style="margin-bottom: 15px;">
                                        <!-- Available tvs -->
                                        <div class="col-md-6">
                                            <div class="list-wrapper">
                                                <div class="list-header">
                                                    <i class="glyphicon glyphicon-list-alt"></i> Available Tv Shows Season
                                                </div>
                                                <input type="text" id="searchAvailableSeason" class="form-control search-box"
                                                    placeholder="Search Tv Shows Season">
                                                <div class="scroll-box" id="availableSeasonBundles">
                                                        <div class="content-container panel-default" draggable="true" data-ng-repeat="season in tvsetCtrl.seasons"
                                                            data-id="{{ season.id }}">
                                                            <div class="content-header">
                                                                <span class="tvs-drag">
                                                                    <i class="glyphicon glyphicon-move"></i>
                                                                </span>
                                                                {{ season.title }}
                                                            </div>
                                                            <div class="item-box"
                                                                ng-repeat="s in (season.get_season_data || season.get_seasons || [])"
                                                                draggable="true">
                                                                <span class="tvs-drag">
                                                                    <i class="glyphicon glyphicon-move"></i>
                                                                </span>
                                                                <i class="glyphicon glyphicon-blackboard"></i>
                                                                {{ s.title }} {{ s.season_number }}
                                                            </div>
                                                        </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Assigned tvss -->
                                        <div class="col-md-6">
                                            <div class="list-wrapper">
                                                <div class="list-header">
                                                    <i class="glyphicon glyphicon-check"></i> Assigned Tv Shows Season
                                                </div>

                                                <input type="text" id="searchAddedSeason" class="form-control search-box"
                                                    placeholder="Search Tv Shows Season">

                                                <div class="scroll-box">
                                                        <div class="content-container panel-default" draggable="true"
                                                            data-ng-repeat="season in tvsset.selectedSeasonBundles"
                                                            ng-model="tvsset.assigned_tv_show_season">
                                                            <div class="content-header"
                                                                style="display: flex; justify-content: space-between; align-items: center;">
                                                                <div>
                                                                    <span class="tvs-drag">
                                                                        <i class="glyphicon glyphicon-move"></i>
                                                                    </span>
                                                                    {{ season.title }}
                                                                </div>
                                                                <span class="tvs-action bundle-remove" data-ng-click="removeSeason(season)">
                                                                    <i class="glyphicon glyphicon-trash"></i>
                                                                </span>
                                                            </div>
                                                            <div class="item-box"
                                                                ng-repeat="s in (season.get_season_data || season.get_seasons || [])"
                                                                draggable="true">
                                                                <span class="tvs-drag">
                                                                    <i class="glyphicon glyphicon-move"></i>
                                                                </span>
                                                                <i class="glyphicon glyphicon-blackboard"></i>
                                                                {{ s.title }} {{ s.season_number }}
                                                            </div>
                                                        </div>

                                                    <div id="addedSeasonBundles" style="min-height: 80px;" ng-model="tvsset.assigned_tv_show_season">
                                                        <div class="drop-zone" ng-show="!tvsset.selectedSeasonBundles.length">
                                                            <i class="glyphicon glyphicon-download-alt" style="margin-right: 8px;"></i>
                                                            DROP HERE
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- episode code -->
                                    <div class="row dual-list-container" ng-show="tvsset.item_type == 'episode'" style="margin-bottom: 15px;">
                                        <!-- Available tvs -->
                                        <div class="col-md-6">
                                            <div class="list-wrapper">
                                                <div class="list-header">
                                                    <i class="glyphicon glyphicon-list-alt"></i> Available Season Episode
                                                </div>
                                                <input type="text" id="searchAvailableEpisode" class="form-control search-box"
                                                    placeholder="Search Tv Shows Season">
                                                <div class="scroll-box" id="availableEpisodeBundles">
                                                        <div class="content-container-episode panel-default" draggable="true" data-ng-repeat="episode in tvsetCtrl.episodes"
                                                            data-id="{{ episode.id }}">
                                                            <div class="content-header">
                                                                <span class="tvs-drag">
                                                                    <i class="glyphicon glyphicon-move"></i>
                                                                </span>
                                                                {{ episode.title }}
                                                            </div>
                                                            <div class="item-box"
                                                                ng-repeat="season in (episode.get_season_data || episode.get_seasons || [])"
                                                                draggable="true" style="flex-direction: column; align-items: stretch; background-color: #f7f7f7;">
                                                                <div style="display: flex; align-items: center; margin-bottom: 8px; font-weight: 500;">
                                                                    <span class="tvs-drag">
                                                                        <i class="glyphicon glyphicon-move"></i>
                                                                    </span>
                                                                    <i class="glyphicon glyphicon-blackboard"></i>
                                                                    {{ season.title }} {{ season.season_number }}
                                                                </div>

                                                                <div class="item-box"
                                                                    ng-repeat="ep in (season.get_episodes || [])"
                                                                    draggable="true" style="background-color: #fff; margin-top: 2px;">
                                                                    <span class="tvs-drag">
                                                                        <i class="glyphicon glyphicon-move"></i>
                                                                    </span>
                                                                    <i class="glyphicon glyphicon-blackboard"></i>
                                                                    {{ ep.episode_name }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Assigned tvss -->
                                        <div class="col-md-6">
                                            <div class="list-wrapper">
                                                <div class="list-header">
                                                    <i class="glyphicon glyphicon-check"></i> Assigned Season Episode
                                                </div>

                                                <input type="text" id="searchAddedEpisode" class="form-control search-box"
                                                    placeholder="Search Tv Shows">

                                                <div class="scroll-box">
                                                        <div class="content-container-episode panel-default" draggable="true"
                                                            data-ng-repeat="episode in tvsset.selectedEpisodeBundles"
                                                            ng-model="tvsset.assigned_tv_show_episode"
                                                            data-id="{{ episode.id }}">
                                                            <div class="content-header" style="display: flex; justify-content: space-between; align-items: center;">
                                                                <div>
                                                                    <span class="tvs-drag">
                                                                        <i class="glyphicon glyphicon-move"></i>
                                                                    </span>
                                                                    {{ episode.title }}
                                                                </div>
                                                                <span class="tvs-action bundle-remove" data-ng-click="removeSeasonEpisode(episode)">
                                                                    <i class="glyphicon glyphicon-trash"></i>
                                                                </span>
                                                            </div>
                                                            <div class="item-box"
                                                                ng-repeat="season in (episode.get_season_data || episode.get_seasons || [])"
                                                                draggable="true" style="flex-direction: column; align-items: stretch; background-color: #f7f7f7;">
                                                                <div style="display: flex; align-items: center; margin-bottom: 8px; font-weight: 500;">
                                                                    <span class="tvs-drag">
                                                                        <i class="glyphicon glyphicon-move"></i>
                                                                    </span>
                                                                    <i class="glyphicon glyphicon-blackboard"></i>
                                                                    {{ season.title }} {{ season.season_number }}
                                                                </div>

                                                                <div class="item-box"
                                                                    ng-repeat="ep in (season.get_episodes || [])"
                                                                    draggable="true" style="background-color: #fff; margin-top: 2px;">
                                                                    <span class="tvs-drag">
                                                                        <i class="glyphicon glyphicon-move"></i>
                                                                    </span>
                                                                    <i class="glyphicon glyphicon-blackboard"></i>
                                                                    {{ ep.episode_name }}
                                                                </div>
                                                            </div>
                                                        </div>

                                                    <div id="addedEpisodeBundles" style="min-height: 80px;" ng-model="tvsset.assigned_tv_show_episode">
                                                        <div class="drop-zone" ng-show="!tvsset.selectedEpisodeBundles.length">
                                                            <i class="glyphicon glyphicon-download-alt" style="margin-right: 8px;"></i>
                                                            DROP HERE
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>"""

new_html = new_html.replace('{{', '@{{')

for file_path in files:
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    start_tag = '<!-- tv show -->'
    end_tag = '<!-- ==========***********========== -->'

    start_idx = content.find(start_tag)
    
    if start_idx == -1:
        print(f"Skipping {file_path}, start tag not found.")
        continue

    # Find the next ==========***********========== AFTER the start_index
    end_idx = content.find(end_tag, start_idx)

    if end_idx == -1:
        print(f"Skipping {file_path}, end tag not found.")
        continue
    
    
    # We want to replace from start_tag up to the end_tag, but keeping exactly enough closing divs
    # Let me just replace the exact text.
    # To be extremely safe, I will split and find the wrapper closing tag:
    
    substring_to_replace = content[start_idx:end_idx]
    
    # Let's find exactly `                                </div>\n                            </div>\n                        </div>\n                    </div>\n\n                    ` backwards from end_idx.
    # Actually, if I just replace substring_to_replace with new_html + "\n                                </div>\n                            </div>\n                        </div>\n                    </div>\n\n                    ", 
    # it might be hardcoded to their indentation level.
    # Let's look at what is just before end_idx:
    
    before_end_cut = content[:end_idx].rstrip()
    last_divs = before_end_cut.rsplit("</div>", 4)
    # this might be messy.
    
    # Let's just find `</div>\n                                    </div>` which closes the episode block
    # and use regex string matching the whole block in a simpler way:
    import re
    
    pattern = r'                                    <!-- tv show -->.*?<!-- episode code -->.*?                                        </div>\n                                    </div>'
    content = re.sub(pattern, new_html, content, flags=re.DOTALL)
    
    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(content)
        
    print(f"Updated {file_path}")
