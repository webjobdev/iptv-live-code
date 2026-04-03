'use strict';
var commonGeofencing = ['$http', '$rootScope', '$filter','$interval', '$window', 'requestFactory', function (http, rootScope, $filter,$interval, $window, requestFactory) {
    var self = this;
    rootScope.regions = {};
    rootScope.allowedData = {};
    rootScope.regionsDB = []; 
    this.globalRegionsDB;
    rootScope.selectedcountries = {};
    rootScope.allowedcountries = {};
    rootScope.allowedRegions = [];
    rootScope.selected_regions = {};
    rootScope.showCountryTab = false;
    rootScope.regions.showregions=false;
    rootScope.geo_regions = [];
    rootScope.geoCountries =[];
    rootScope.geo_countries = [];
    rootScope.videoAllowedData = [];
    this.localGeoRegionsWithCountry = [];
    this.serviceStatus = { geo_regions_detailsService: false, geo_regionsService: false };

     /**
     *  This function is to  get the selected 
     *  country and region details from the database.
     */
  this.defineGeoProperties = function (data, videoID = null) {
    rootScope.info = data.info;
    if(data.info.geo_setting.type == 'individual_allowed_countries'){
      rootScope.showCountryTab = true;
    }
    rootScope.geoType = (data.info.geo_setting.type == 'individual_allowed_countries') 
    ? data.info.geo_setting.type : 'all_countries' ;
    rootScope.geoCountries = data.info.selected_countries;
    self.getCountries(videoID);
    if(videoID == null){
      angular.forEach(data.info.selected_countries,function(item){
        var definedRegions = item.regions;
        var selected_region_details = [];
        var type = '';
        type = (videoID === null) ? 'global' : 'individual';
        requestFactory.post(requestFactory.getUrl('geo-regions-details'),{'country_id' : item.country_id, 'type' : type,'videoID':videoID}, function (response) {
            rootScope.regionsDB = response.info.regions;
            rootScope.allowedData[item.country_short_code] = response.info.regions;
        });
      });
    }else{
      rootScope.allowedData[videoID] = [];
      angular.forEach(data.info.selected_countries,function(item){
        var definedRegions = item.regions;
        var selected_region_details = [];
        var type = '';
        type = (videoID === null) ? 'global' : 'individual';
        requestFactory.post(requestFactory.getUrl('geo-regions-details'),{'country_id' : item.country_id, 'type' : type,'videoID':videoID}, function (response) {
            rootScope.regionsDB = response.info.regions;
            rootScope.allowedData[videoID][item.country_short_code] = response.info.regions;
        });
      });
    }
  };
  this.getCountries =function(id=null){ 
    requestFactory.get(requestFactory.getUrl('geo-countries'), function (response) {
      if(id == null){
      rootScope.showCountryTab = true;
      }
      angular.forEach(response.info.countries, function(item, index){
        angular.forEach(rootScope.geoCountries, function(country, index){
          if(country.country_short_code == item.short_code) {
            item.Selected = true;
          }
        });
        
      });
      if(id === null){
        rootScope.geo_countries=response.info.countries;
      } else {
        rootScope.geo_countries[id] = response.info.countries;
      }
    }, rootScope.fillError);
  };
  this.processGeoRegions = function(rootGeoRegions, geoCountry, videoID, index,region_type =null){
    var localGeoRegions;
    var localGeoRegionsDB;
    var geoRegion = [];
    localGeoRegions = (videoID === null) ? Object.assign({}, rootGeoRegions[geoCountry.short_code]) : Object.assign({}, rootGeoRegions[videoID][geoCountry.short_code]);
    if(self.globalRegionsDB.length > 0) {
      localGeoRegionsDB = (videoID === null) ? self.globalRegionsDB[geoCountry.short_code] : self.globalRegionsDB[videoID][geoCountry.short_code];
    } else {
      localGeoRegionsDB = null;
    }
    angular.forEach(localGeoRegions, function (item) {
      if(localGeoRegionsDB !== null && localGeoRegionsDB !== undefined && localGeoRegionsDB.length > 0&& region_type != "all_regions"){
        angular.forEach( localGeoRegionsDB ,function(regions,index){
          if(item.country_id == regions.country_id  && regions.short_code == item.short_code) {
            item.Selected = geoCountry.Selected ;
            geoRegion.push(item);
          }
        });
      } else{
        if(item.country_id == geoCountry.id &&  geoCountry.Selected == true){
          item.Selected = geoCountry.Selected;
          geoRegion.push(item);
        } else if (item.country_id == geoCountry.id &&  geoCountry.Selected != true){
          item.Selected = geoCountry.Selected;
        }
      }
    });
    if(region_type == "all_regions"){
      if(geoCountry.Selected == true){
        this.addGeoLocationData(geoCountry, geoRegion, videoID,region_type,region_type);
      } else {
        this.removeGeoLocationData(geoCountry, geoRegion, videoID,region_type,region_type);
      }
    }
    if(index !== null){
        var className = document.getElementById('content'+index).className;
        var id = document.getElementById('content'+index); 
        var geoId = document.getElementById('geoid-'+index); 
        if(className === 'content'){
          id.classList.add("open");
          geoId.classList.add('open');
        } else if(className === 'content open'){
          if(className.search('open') !== -1){
            id.classList.remove('open');
            geoId.classList.remove('open');
          }
        }
      }
    };
    this.getRegions=function(geoCountry, index = null, videoID = null, $event = null,region_type = null){
      var regionLength;
      if(videoID === null){
        regionLength = (rootScope.geo_regions.hasOwnProperty(geoCountry.short_code)) ? rootScope.geo_regions[geoCountry.short_code].length : 0;
      } else {
        regionLength = (rootScope.geo_regions.hasOwnProperty(videoID) && rootScope.geo_regions[videoID].hasOwnProperty(geoCountry.short_code)) 
                        ? rootScope.geo_regions[videoID][geoCountry.short_code].length : 0;
      }
        requestFactory.get(requestFactory.getUrl('geo-regions/' +geoCountry.id), function (response) {
          self.serviceStatus.geo_regionsService = true;
          if(videoID === null){
            rootScope.geo_regions[geoCountry.short_code] = response.info.regions;
          } else {
            this.localGeoRegionsWithCountry[geoCountry.short_code] = response.info.regions;
            rootScope.geo_regions[videoID] = this.localGeoRegionsWithCountry;
          }
          // Handle region bar is clicked
          if($event !== null) {
            if(videoID === null){
              rootScope.geo_regions[geoCountry.short_code] = response.info.regions;
              angular.forEach(rootScope.geo_regions[geoCountry.short_code], function (item) {
                  angular.forEach( rootScope.allowedData[geoCountry.short_code] ,function(regions,index){
                    if(item.short_code == regions.short_code) {
                      item.Selected = true ;
                    }
                  });
              });
            } else {
              this.localGeoRegionsWithCountry[geoCountry.short_code] = response.info.regions;
              rootScope.geo_regions[videoID] = this.localGeoRegionsWithCountry;
              angular.forEach(rootScope.geo_regions[videoID][geoCountry.short_code], function (item) {
                angular.forEach( rootScope.allowedData[videoID][geoCountry.short_code] ,function(regions,index){
                  if(item.short_code == regions.short_code) {
                    item.Selected = true ;
                  }
                });
              });
            }
          } else {
            var regionsDB = []; 
            var type;
            type = (videoID === null) ? 'global' : 'individual';
            requestFactory.post(requestFactory.getUrl('geo-regions-details'),{'country_id' : geoCountry.id, 'type' : type}, function (response) {
              self.serviceStatus.geo_regions_detailsService = true;
              if(videoID === null){
                regionsDB[geoCountry.short_code] = response.info.regions;
              } else {
                regionsDB[videoID] = [];
                regionsDB[videoID][geoCountry.short_code] = response.info.regions;
              }
              self.globalRegionsDB = regionsDB;
              self.processGeoRegions(rootScope.geo_regions, geoCountry, videoID, index,region_type);
            });
          }
        }.bind(this));
      if(index !== null){
        var className = document.getElementById('content'+index).className;
        var id = document.getElementById('content'+index); 
        var geoId = document.getElementById('geoid-'+index); 
        if(className === 'content'){
          id.classList.add("open");
          geoId.classList.add('open');
        } else if(className === 'content open'){
          if(className.search('open') !== -1){
            id.classList.remove('open');
            geoId.classList.remove('open');
          }
        }
      }
    };
   
    this.addGeoLocationData = function(geoCountry, geoRegions, videoID){
      if(videoID === null){
        if(rootScope.allowedData[geoCountry.short_code] == undefined){
          rootScope.allowedData[geoCountry.short_code]=geoRegions;  
        }else{
        rootScope.allowedData[geoCountry.short_code].push(geoRegions[0]);}
      } else {
        if(rootScope.allowedData[videoID][geoCountry.short_code] == undefined){
          rootScope.allowedData[videoID][geoCountry.short_code]=geoRegions;  
        }else{
        rootScope.allowedData[videoID][geoCountry.short_code].push(geoRegions[0]);}
      }
    };
    this.removeGeoLocationData = function(geoCountry, geoRegions, videoID,region_type){
      if(videoID === null){
          angular.forEach(rootScope.allowedData[geoCountry.short_code], function (item, $index) {
            if(item.country_id == geoCountry.id && item.id == geoRegions.id){
              rootScope.allowedData[geoCountry.short_code].splice($index,1);
            }
          });
          angular.forEach(rootScope.allowedRegions, function (item, $index) {
            if(item.country_id == geoCountry.id && item.id == geoRegions.id){
              rootScope.allowedRegions.splice($index,1);    
            }
          });
          if(rootScope.allowedData.hasOwnProperty(geoCountry.short_code) && rootScope.allowedData[geoCountry.short_code].length == 0){
            delete rootScope.allowedData[geoCountry.short_code]
          }
        if(region_type == "all_regions"){
          delete rootScope.allowedData[geoCountry.short_code]
        }
      } else {
        angular.forEach(rootScope.allowedData[videoID][geoCountry.short_code], function (item, $index) {
          if(item.country_id == geoCountry.id && item.id == geoRegions.id){
            rootScope.allowedData[videoID][geoCountry.short_code].splice($index,1);
          }
        });
        angular.forEach(rootScope.allowedRegions, function (item, $index) {
          if(item.country_id == geoCountry.id && item.id == geoRegions.id){
            rootScope.allowedRegions.splice($index,1);    
          }
        });
        if(rootScope.allowedData[videoID].hasOwnProperty(geoCountry.short_code) && rootScope.allowedData[videoID][geoCountry.short_code].length == 0){
          delete rootScope.allowedData[videoID][geoCountry.short_code]
        }
      if(region_type == "all_regions"){
        delete rootScope.allowedData[videoID][geoCountry.short_code]
      }
      }
    };
    this.toggleCountriesSelection = function(geoCountry, index =null, videoID = null,$event= null,region_type="all_regions"){
      geoCountry.countrySelected = geoCountry.Selected;
      this.getRegions(geoCountry, index, videoID,$event,region_type);
      var region = [];
      var item;
    };
    this.toggleRegionsSelection = function(geoCountry, geoRegions, videoID = null){
      var localGeoRegions;
      var regions;
      regions = [];
      if(geoRegions.Selected == true){
        rootScope.allowedRegions.push(geoRegions);
        angular.forEach(rootScope.allowedRegions, function (item, $index) {
          if(item.country_id == geoCountry.id && item.id == geoRegions.id){
            regions.push(rootScope.allowedRegions[$index]);
          }
        });
        this.addGeoLocationData(geoCountry, regions, videoID);
      } else {
        this.removeGeoLocationData(geoCountry, geoRegions, videoID);
      }
      if(videoID === null){
          geoCountry.Selected = (rootScope.allowedData[geoCountry.short_code] != undefined 
          && rootScope.allowedData[geoCountry.short_code].length > 0 ) ? true : false;
      } else {
        geoCountry.Selected = (rootScope.allowedData[videoID][geoCountry.short_code] != undefined 
          && rootScope.allowedData[videoID][geoCountry.short_code].length > 0 ) ? true : false;
      }
    };
}];