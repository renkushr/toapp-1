$(window).on("load scroll",function(e){   
    var scroll = $(window).scrollTop();
  
    if (scroll >= 100) {  
        $("body").addClass("scrolling"); 
    } else {
        $("body").removeClass("scrolling");
    } 
});
 
$(document).ready(function(){   
    $("a.target").on('click', function(event) {
        event.preventDefault();
        var hash = this.hash; 

        $('html, body').animate({
            scrollTop: $(hash).offset().top  
            }, 800, function(){ 
        });
    }); 
 
    $(".totop").click(function() {
      $("html, body").animate({scrollTop: 0},1300);
    });

    $("body").removeClass("nav-opened");
    $('<div class="page-blocker"></div>').appendTo('body'); 

    $('.navbar-toggle').click(function () {
        $("html").toggleClass("nav-opened");  
    });

    $(".page-blocker").click(function () { 
        $("html").toggleClass("nav-opened");
      setTimeout($.proxy(function(){
          $('html').removeClass("closing");
      },this),700);
    });
  
    $('.scrollbar-inner').scrollbar();
    
    AOS.init({
        duration: 1200,
       // offset:0,
        once: true
    });

    $('select').on('change', function() {
        if (this.value) {
            $(this).addClass("selected");
        } else {
            $(this).removeClass("selected");
        }
    }).change();

     
    // Toggle Menu
    
    $(".nav-sidebar .nav-toggle").click(function(){
         
        if($(this).hasClass("show")){
            $(this).removeClass("show").next(".accordion-collapse").slideToggle(300);
         
        }else{
            $(".nav-sidebar").find(".accordion-collapse").slideUp(300);
            $(this).next(".accordion-collapse").slideToggle(300);

            $(".nav-sidebar .nav-toggle").removeClass("show");
            $(this).toggleClass("show"); 
        }
      
	});
  
});
 
$(window).on("load", function() {
  
  $(".preload").fadeOut();

  setTimeout(function () {
     $("html").addClass("page-loaded");
  }, 200);

  
  var isMobile = {
      Android: function() {
          return navigator.userAgent.match(/Android/i);
      },
      BlackBerry: function() {
          return navigator.userAgent.match(/BlackBerry/i);
      },
      iOS: function() {
          return navigator.userAgent.match(/iPhone|iPad|iPod/i);
      },
      Opera: function() {
          return navigator.userAgent.match(/Opera Mini/i);
      },
      Windows: function() {
          return navigator.userAgent.match(/IEMobile/i);
      },
      any: function() {
          return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
      }
  };

  if(isMobile.any()) {
      $("html").addClass("device");   
  }else{
      $("html").addClass("pc");    
  }
   
  var ua = window.navigator.userAgent;
  var isIE = /MSIE|Trident/.test(ua);

  if ( isIE ) {
     $("html").addClass("ie");
  }
  
  $('.modal').on("hidden.bs.modal", function (e) {
      if ($('.modal:visible').length) { 
          $('body').addClass('modal-open'); 
      }
  });
 
  $('img.svg-js').each(function() {
      var $img = jQuery(this);
      var imgURL = $img.attr('src');
      var attributes = $img.prop("attributes");

      $.get(imgURL, function(data) {
          // Get the SVG tag, ignore the rest
          var $svg = jQuery(data).find('svg');

          // Remove any invalid XML tags
          $svg = $svg.removeAttr('xmlns:a');

          // Loop through IMG attributes and apply on SVG
          $.each(attributes, function() {
              $svg.attr(this.name, this.value);
          });

          // Replace IMG with SVG
          $img.replaceWith($svg);
      }, 'xml');
  });
});
 
 