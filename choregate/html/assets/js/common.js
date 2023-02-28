$(function() {
  new ScrollHint('.mod_cont .table_scroll', {
    i18n: {
      scrollable: 'スクロールできます'
    }
  });
  $(".page_top a").click(function(){
    $('html,body').animate({ scrollTop: $($(this).attr("href")).offset().top }, 500,'swing');
    return false;
  })
  var topBtn = $('.page_top');
  topBtn.hide();
  $(window).on('scroll',function(){
    if ($(this).scrollTop() > 100) {
      $('#header').addClass('scrolled');
      topBtn.fadeIn();
    } else {
      $('#header').removeClass('scrolled');
      topBtn.fadeOut();
    }
  });
  $('a[href^="#"]').on('click', function() {
    var href= $(this).attr("href");
    var target = $(href == "#" || href == "" ? 'html' : href);
    var position = target.offset().top;
    $("html, body").animate({scrollTop:position}, 550, "swing");
    return false;
  });
  var sp = '_sp.';
  var pc = '_pc.';
  var replaceWidth = 900;
  function imageSwitch() {
    var windowWidth = parseInt($(window).width());
    var $this =  $('#bg_video .switch');
    if(windowWidth >= replaceWidth) {
      $this.attr('src', $this.attr('src').replace(sp, pc));
    } else {
      $this.attr('src', $this.attr('src').replace(pc, sp));
    }
  }
  imageSwitch();
  var resizeTimer;
  var window_width = window.innerWidth;
  $(window).on('load resize', function() {
    var resize_width = window.innerWidth;
    if (window_width != resize_width) {
      imageSwitch();
      var video = $('#bg_video video').get(0);
      video.load();
      video.play();
    }
  });
  $('#gnavi').on('click', function() {
    $('body').addClass('menu_open');
    $('#gnavi_wrap').addClass('open');
  });
  $(document).on('click','#gnavi_close', function() {
    $('body').removeClass('menu_open');
    $('#gnavi_wrap').removeClass('open');
  });
  $(window).on('load scroll', function() {
    $('.move_up').each(function(){
      var position = $(this).offset().top;
      var scroll = $(window).scrollTop();
      var windowHeight = $(window).height();
      if (scroll > position - windowHeight + 100){
        $(this).addClass('up');
      }
    });
  });
});
