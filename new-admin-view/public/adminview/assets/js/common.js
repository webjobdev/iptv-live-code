angular.element(document).ready(function() {
  var bodyClass = localStorage.getItem('sideMenuCollapse');


  if(document.getElementsByClassName('toggle-menu').length) {
    var collapseMenu  = document.getElementsByClassName('toggle-menu')[0];
    var bodyElem      = document.getElementsByTagName('body')[0];

    bodyElem.className = bodyClass;

    collapseMenu.addEventListener('click', function() {
        var classList = '';
        if(bodyElem.className.indexOf('sidebar-collapse') > -1) {
          classList = bodyElem.className.replace('sidebar-collapse', '');
          classList += ' sidebar-icon';

          localStorage.setItem('sideMenuCollapse','sidebar-icon');
        }
        else {
          classList = bodyElem.className.replace('sidebar-icon', '');
          classList += ' sidebar-collapse';

          localStorage.setItem('sideMenuCollapse', 'sidebar-collapse');
        }

        bodyElem.className = classList;
    });
  }


  $('.ckbox-default').on('click', function() {
  	$('.bulkaction').toggleClass('openaction');
  });

});

/*
  Function for side menu open/ close
*/
$.sidebarMenu = function(menu) {
      var animationSpeed = 300;
      
      var activeMenu = $( "li.treeview" ).find( "a.active" ).next();
      activeMenu.slideDown(animationSpeed, function() {
        activeMenu.addClass('menu-open');
        activeMenu.css('display', 'block');
        activeMenu.parent("li").addClass("active")
      });
     
      $(menu).on('click', 'li a', function(e) {
        var $this = $(this);
        var checkElement = $this.next();

        if (checkElement.is('.treeview-menu') && checkElement.is(':visible')) {
          checkElement.slideUp(animationSpeed, function() {
            checkElement.removeClass('menu-open');
          });
          checkElement.parent("li").removeClass("active");
        }

        //If the menu is not visible
        else if ((checkElement.is('.treeview-menu')) && (!checkElement.is(':visible'))) {
          //Get the parent menu
          var parent = $this.parents('ul').first();
          //Close all open menus within the parent
          var ul = parent.find('ul:visible').slideUp(animationSpeed);
          //Remove the menu-open class from the parent
          ul.removeClass('menu-open');
          //Get the parent li
          var parent_li = $this.parent("li");

          //Open the target menu and add the menu-open class
          checkElement.slideDown(animationSpeed, function() {
            //Add the class active to the parent li
            checkElement.addClass('menu-open');
            parent.find('li.active').removeClass('active');
            parent_li.addClass('active');
          });
        }
        //if this isn't a link, prevent the page from being redirected
        if (checkElement.is('.treeview-menu')) {
          e.preventDefault();
        }
      });
    }
$.sidebarMenu($('.sidebar-menu'));

setTimeout(() => {
  $('.sidepanel-open').on('click', function() {
    if(!$(this).hasClass("disabled")) {
      $(".sidepanel").addClass("in");
    }
  });
  $('.sidepanel .overlay, .sidepanel .save').on('click', function() {
    $(".sidepanel").removeClass("in");
  });
}, 2000);



