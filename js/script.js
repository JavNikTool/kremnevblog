$(document).ready(function(){
   $('.button_aside').click(function (){
       $('.hiden_nav').addClass('hiden_nav_active');
       $(this).addClass('button_aside_active');
   });
    $('.exit').click(function (){
        $('.hiden_nav').removeClass('hiden_nav_active');
        $('.button_aside').removeClass('button_aside_active');
    });
    $('.hiden_nav ul li a').click(function (){
        $('.hiden_nav').removeClass('hiden_nav_active');
        $('.button_aside').removeClass('button_aside_active');
    });
});