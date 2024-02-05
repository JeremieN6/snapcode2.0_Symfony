/* MENU MOBILE */
function openMenu(){
    var containerNavMenu = document.getElementById("containerNavMenu");
    var iconMenu = document.getElementById("fa-bars");
    var close = document.getElementById("closeIcon");
    var containerSwitchTheme = document.getElementById("containerSwitchTheme");

    containerNavMenu.style.display = "flex";
    iconMenu.style.display = "none";
    close.style.display = "block";
    close.style.marginLeft = "-1em";
    containerSwitchTheme.style.display = "block";
}
function closeMenu(){
    var containerNavMenu = document.getElementById("containerNavMenu");
    var iconMenu = document.getElementById("fa-bars");
    var close = document.getElementById("closeIcon");
    var containerSwitchTheme = document.getElementById("containerSwitchTheme");

    containerNavMenu.style.display = "none";
    iconMenu.style.display = "block";
    close.style.display = "none";
    containerSwitchTheme.style.display = "none";
}
/* MENU MOBILE */

/* SWITCH THEME */
function switchDark(){
    var moonDark = document.getElementById("moonDark");
    var moonLight = document.getElementById("moonLight");
    var containerMoon = document.getElementById("containerMoon");
    var containerSun = document.getElementById("containerSun");
    var contentSun = document.getElementById("contentSun");
    var switchTheme = document.getElementById("switchTheme");
    var contentLogo = document.getElementById("contentLogo");
    var containerNavMenu = document.getElementById("containerNavMenu");
    var menuUl = document.getElementById("menuUl");
    var active = document.getElementById("active");
    var navMenu = document.getElementById("navMenu");
    var bgColorDarkMode = getComputedStyle(document.documentElement).getPropertyValue('--bg-color-dark-mode');

    //Display and hide elements
    containerMoon.classList.add('isActive');
    moonLight.style.display = "none";
    moonDark.style.display = "block";
    switchTheme.style.cssText = "background-color: #223344; border: 1px solid #223344";

    containerSun.classList.remove('isActive');
    contentSun.style.filter = "invert(99%) sepia(1%) saturate(2%) hue-rotate(259deg) brightness(109%) contrast(100%)"; //white
    contentLogo.style.filter = "none"; //white
    moonDark.style.filter = "none";


    //change body color
    document.body.style.backgroundColor = "#111729";
    document.body.style.color = "#FFF";

    //change menu color and font weight
    containerNavMenu.style.backgroundColor = "#111729";
    menuUl.style.color = "#FFF";
    menuUl.style.fontWeight = "400";
    navMenu.style.backgroundColor = bgColorDarkMode;

}

function switchLight(){
    var moonDark = document.getElementById("moonDark");
    var moonLight = document.getElementById("moonLight");
    var containerMoon = document.getElementById("containerMoon");
    var containerSun = document.getElementById("containerSun");
    var contentSun = document.getElementById("contentSun");
    var contentLogo = document.getElementById("contentLogo");
    var switchTheme = document.getElementById("switchTheme");
    var containerNavMenu = document.getElementById("containerNavMenu");
    var menuUl = document.getElementById("menuUl");
    var navMenu = document.getElementById("navMenu");
    var bgColorLightMode = getComputedStyle(document.documentElement).getPropertyValue('--bg-color-light-mode');


    //Display and hide elements
    containerMoon.classList.remove('isActive');
    moonLight.style.display = "block";
    moonDark.style.display = "none";
    switchTheme.style.cssText = "background-color: #111729; border: 1px solid #111729";

    containerSun.classList.add('isActive');
    contentSun.style.filter = "none";
    contentLogo.style.filter = "invert(1)";
    moonLight.style.filter = "none";

    //change body color
    document.body.style.backgroundColor = "#F2F9FE";
    document.body.style.color = "#111729";

    //change menu color and font weight
    containerNavMenu.style.backgroundColor = "#F2F9FE";
    menuUl.style.color = "#909193";
    menuUl.style.fontWeight = "600";
    // active.style.color = "#111729";
    // active.style.fontWeight = "700";
    navMenu.style.backgroundColor = bgColorLightMode;

    if (navMenu.classList.contains("sticky-dark")) {
        // Supprime la classe à supprimer
        navMenu.classList.remove("sticky-dark");
        
        // Ajoute la nouvelle classe
        navMenu.classList.add("sticky-light");
      } else {
        // Si la classe à supprimer n'existe pas, ajoute la classe par défaut
        navMenu.classList.add("sticky-dark");
        // Et enlève la nouvelle classe si elle était présente
        navMenu.classList.remove("sticky-light");
      }

}
/* SWITCH THEME */


/* Testimonials swipe */
function nextTestimonial00(){
    var testimonial00  = document.getElementById("w-slider-mask-0");
    var testimonial01  = document.getElementById("w-slider-mask-1");

    // testimonial00.style.opacity = "0";
    // testimonial01.style.opacity = "1";
    // testimonial01.style.transition = "transform 500ms ease 0s";
    // testimonial01.style.transform = "translateX(-1689.6px)";

    testimonial00.style.display = "none";
    testimonial01.style.display = "block";
}
function prevTestimonial00(){
    var testimonial00  = document.getElementById("w-slider-mask-0");
    var testimonial03  = document.getElementById("w-slider-mask-3");

    // testimonial00.style.opacity = "0";
    // testimonial02.style.opacity = "1";
    // testimonial02.style.transition = "transform 500ms ease 0s";
    // testimonial02.style.transform = "translateX(-1689.6px)";

    testimonial00.style.display = "none";
    testimonial03.style.display = "block";
}

function prevTestimonial01(){
    var testimonial00  = document.getElementById("w-slider-mask-0");
    var testimonial01  = document.getElementById("w-slider-mask-1");

    // testimonial01.style.opacity ="0";
    // testimonial00.style.opacity = "1";
    // testimonial00.style.transition = "transform 500ms ease 0s";
    // testimonial00.style.transform = "translateX(-1689.6px)";
    testimonial01.style.display = "none";
    testimonial00.style.display = "block";

}
function nextTestimonial01(){
    var testimonial01  = document.getElementById("w-slider-mask-1");
    var testimonial02  = document.getElementById("w-slider-mask-2");

    // testimonial01.style.opacity = "0";
    // testimonial02.style.opacity = "1";
    // testimonial02.style.transition = "transform 500ms ease 0s";
    // testimonial02.style.transform = "translateX(-1689.6px)";
    testimonial01.style.display = "none";
    testimonial02.style.display = "block";
}

function prevTestimonial02(){
    var testimonial01  = document.getElementById("w-slider-mask-1");
    var testimonial02  = document.getElementById("w-slider-mask-2");

    // testimonial02.style.opacity = "0";
    // testimonial01.style.opacity = "1";
    // testimonial01.style.transition = "transform 500ms ease 0s";
    // testimonial01.style.transform = "translateX(-1689.6px)";
    testimonial02.style.display = "none";
    testimonial01.style.display = "block";

}
function nextTestimonial02(){
    var testimonial03  = document.getElementById("w-slider-mask-3");
    var testimonial02  = document.getElementById("w-slider-mask-2");

    // testimonial02.style.opacity = "0";
    // testimonial00.style.opacity = "1";
    // testimonial00.style.transition = "transform 500ms ease 0s";
    // testimonial00.style.transform = "translateX(-1689.6px)";
    testimonial02.style.display = "none";
    testimonial03.style.display = "block";
}

function prevTestimonial03(){
    var testimonial02  = document.getElementById("w-slider-mask-2");
    var testimonial03  = document.getElementById("w-slider-mask-3");

    // testimonial03.style.opacity = "0";
    // testimonial02.style.opacity = "1";
    // testimonial02.style.transition = "transform 500ms ease 0s";
    // testimonial02.style.transform = "translateX(-1689.6px)";
    testimonial03.style.display = "none";
    testimonial02.style.display = "block";

}
function nextTestimonial03(){
    var testimonial00  = document.getElementById("w-slider-mask-0");
    var testimonial03  = document.getElementById("w-slider-mask-3");

    // testimonial02.style.opacity = "0";
    // testimonial00.style.opacity = "1";
    // testimonial00.style.transition = "transform 500ms ease 0s";
    // testimonial00.style.transform = "translateX(-1689.6px)";
    testimonial03.style.display = "none";
    testimonial00.style.display = "block";
}
/* Testimonials swipe */


/* FAQ */
function seeMoreFAQ0(){
    var faq_answer = document.getElementById("faq_answer");
    var faq_icon =document.getElementById("faq_icon");

    if (window.screen.width < 768) {
        if(faq_answer.style.width != "100%"){
            faq_answer.style.width = "100%";
            faq_answer.style.height = "auto";
            faq_icon.style.transform = "rotate(45deg)";
    
        }else{
            faq_answer.style.width = "inherit";
            faq_answer.style.height = "0";
            faq_icon.style.transform = "rotate(0deg)";
        }
     }
     else {
        if(faq_answer.style.width != "640px"){
            faq_answer.style.width = "640px";
            faq_answer.style.height = "auto";
            faq_icon.style.transform = "rotate(45deg)";
    
        }else{
            faq_answer.style.width = "inherit";
            faq_answer.style.height = "0";
            faq_icon.style.transform = "rotate(0deg)";
        }
     }
}
function seeMoreFAQ1(){
    var faq_answer1 = document.getElementById("faq_answer1");
    var faq_icon1 =document.getElementById("faq_icon1");

    if (window.screen.width < 768) {
        if(faq_answer1.style.width != "100%"){
            faq_answer1.style.width = "100%";
            faq_answer1.style.height = "auto";
            faq_icon1.style.transform = "rotate(45deg)";
    
        }else{
            faq_answer1.style.width = "inherit";
            faq_answer1.style.height = "0";
            faq_icon1.style.transform = "rotate(0deg)";
        }
     }
     else {
        if(faq_answer1.style.width != "640px"){
            faq_answer1.style.width = "640px";
            faq_answer1.style.height = "auto";
            faq_icon1.style.transform = "rotate(45deg)";
    
        }else{
            faq_answer1.style.width = "inherit";
            faq_answer1.style.height = "0";
            faq_icon1.style.transform = "rotate(0deg)";
        }
     }
}
function seeMoreFAQ2(){
    var faq_answer2 = document.getElementById("faq_answer2");
    var faq_icon2 =document.getElementById("faq_icon2");

    if (window.screen.width < 768) {
        if(faq_answer2.style.width != "100%"){
            faq_answer2.style.width = "100%";
            faq_answer2.style.height = "auto";
            faq_icon2.style.transform = "rotate(45deg)";
    
        }else{
            faq_answer2.style.width = "inherit";
            faq_answer2.style.height = "0";
            faq_icon2.style.transform = "rotate(0deg)";
        }
     }
     else {
        if(faq_answer2.style.width != "640px"){
            faq_answer2.style.width = "640px";
            faq_answer2.style.height = "auto";
            faq_icon2.style.transform = "rotate(45deg)";
    
        }else{
            faq_answer2.style.width = "inherit";
            faq_answer2.style.height = "0";
            faq_icon2.style.transform = "rotate(0deg)";
        }
     }
}
function seeMoreFAQ3(){
    var faq_answer3 = document.getElementById("faq_answer3");
    var faq_icon3 =document.getElementById("faq_icon3");

    if (window.screen.width < 768) {
        if(faq_answer3.style.width != "100%"){
            faq_answer3.style.width = "100%";
            faq_answer3.style.height = "auto";
            faq_icon3.style.transform = "rotate(45deg)";
    
        }else{
            faq_answer3.style.width = "inherit";
            faq_answer3.style.height = "0";
            faq_icon3.style.transform = "rotate(0deg)";
        }
     }
     else {
        if(faq_answer3.style.width != "640px"){
            faq_answer3.style.width = "640px";
            faq_answer3.style.height = "auto";
            faq_icon3.style.transform = "rotate(45deg)";
    
        }else{
            faq_answer3.style.width = "inherit";
            faq_answer3.style.height = "0";
            faq_icon3.style.transform = "rotate(0deg)";
        }
     }
}
function seeMoreFAQ4(){
    var faq_answer4 = document.getElementById("faq_answer4");
    var faq_icon4 =document.getElementById("faq_icon4");

    if (window.screen.width < 768) {
        if(faq_answer4.style.width != "100%"){
            faq_answer4.style.width = "100%";
            faq_answer4.style.height = "auto";
            faq_icon4.style.transform = "rotate(45deg)";
    
        }else{
            faq_answer4.style.width = "inherit";
            faq_answer4.style.height = "0";
            faq_icon4.style.transform = "rotate(0deg)";
        }
     }
     else {
        if(faq_answer4.style.width != "640px"){
            faq_answer4.style.width = "640px";
            faq_answer4.style.height = "auto";
            faq_icon4.style.transform = "rotate(45deg)";
    
        }else{
            faq_answer4.style.width = "inherit";
            faq_answer4.style.height = "0";
            faq_icon4.style.transform = "rotate(0deg)";
        }
     }
}
function seeMoreFAQ5(){
    var faq_answer5 = document.getElementById("faq_answer5");
    var faq_icon5 =document.getElementById("faq_icon5");

    if (window.screen.width < 768) {
        if(faq_answer5.style.width != "100%"){
            faq_answer5.style.width = "100%";
            faq_answer5.style.height = "auto";
            faq_icon5.style.transform = "rotate(45deg)";
    
        }else{
            faq_answer5.style.width = "inherit";
            faq_answer5.style.height = "0";
            faq_icon5.style.transform = "rotate(0deg)";
        }
     }
     else {
        if(faq_answer5.style.width != "640px"){
            faq_answer5.style.width = "640px";
            faq_answer5.style.height = "auto";
            faq_icon5.style.transform = "rotate(45deg)";
    
        }else{
            faq_answer5.style.width = "inherit";
            faq_answer5.style.height = "0";
            faq_icon5.style.transform = "rotate(0deg)";
        }
     }
}
function seeMoreFAQ6(){
    var faq_answer6 = document.getElementById("faq_answer6");
    var faq_icon6 =document.getElementById("faq_icon6");

    if (window.screen.width < 768) {
        if(faq_answer6.style.width != "100%"){
            faq_answer6.style.width = "100%";
            faq_answer6.style.height = "auto";
            faq_icon6.style.transform = "rotate(45deg)";
    
        }else{
            faq_answer6.style.width = "inherit";
            faq_answer6.style.height = "0";
            faq_icon6.style.transform = "rotate(0deg)";
        }
     }
     else {
        if(faq_answer6.style.width != "640px"){
            faq_answer6.style.width = "640px";
            faq_answer6.style.height = "auto";
            faq_icon6.style.transform = "rotate(45deg)";
    
        }else{
            faq_answer6.style.width = "inherit";
            faq_answer6.style.height = "0";
            faq_icon6.style.transform = "rotate(0deg)";
        }
     }
}
function seeMoreFAQ7(){
    var faq_answer7 = document.getElementById("faq_answer7");
    var faq_icon7 =document.getElementById("faq_icon7");

    if (window.screen.width < 768) {
        if(faq_answer7.style.width != "100%"){
            faq_answer7.style.width = "100%";
            faq_answer7.style.height = "auto";
            faq_icon7.style.transform = "rotate(45deg)";
    
        }else{
            faq_answer7.style.width = "inherit";
            faq_answer7.style.height = "0";
            faq_icon7.style.transform = "rotate(0deg)";
        }
     }
     else {
        if(faq_answer7.style.width != "640px"){
            faq_answer7.style.width = "640px";
            faq_answer7.style.height = "auto";
            faq_icon7.style.transform = "rotate(45deg)";
    
        }else{
            faq_answer7.style.width = "inherit";
            faq_answer7.style.height = "0";
            faq_icon7.style.transform = "rotate(0deg)";
        }
     }
}
function seeMoreFAQ8(){
    var faq_answer8 = document.getElementById("faq_answer8");
    var faq_icon8 =document.getElementById("faq_icon8");

    if (window.screen.width < 768) {
        if(faq_answer8.style.width != "100%"){
            faq_answer8.style.width = "100%";
            faq_answer8.style.height = "auto";
            faq_icon8.style.transform = "rotate(45deg)";
    
        }else{
            faq_answer8.style.width = "inherit";
            faq_answer8.style.height = "0";
            faq_icon8.style.transform = "rotate(0deg)";
        }
     }
     else {
        if(faq_answer8.style.width != "640px"){
            faq_answer8.style.width = "640px";
            faq_answer8.style.height = "auto";
            faq_icon8.style.transform = "rotate(45deg)";
    
        }else{
            faq_answer8.style.width = "inherit";
            faq_answer8.style.height = "0";
            faq_icon8.style.transform = "rotate(0deg)";
        }
     }
}
function seeMoreFAQ9(){
    var faq_answer9 = document.getElementById("faq_answer9");
    var faq_icon9 =document.getElementById("faq_icon9");

    if (window.screen.width < 768) {
        if(faq_answer9.style.width != "100%"){
            faq_answer9.style.width = "100%";
            faq_answer9.style.height = "auto";
            faq_icon9.style.transform = "rotate(45deg)";
    
        }else{
            faq_answer9.style.width = "inherit";
            faq_answer9.style.height = "0";
            faq_icon9.style.transform = "rotate(0deg)";
        }
     }
     else {
        if(faq_answer9.style.width != "640px"){
            faq_answer9.style.width = "640px";
            faq_answer9.style.height = "auto";
            faq_icon9.style.transform = "rotate(45deg)";
    
        }else{
            faq_answer9.style.width = "inherit";
            faq_answer9.style.height = "0";
            faq_icon9.style.transform = "rotate(0deg)";
        }
     }
}
/* FAQ */

/* Formulaire Contact */
function nextStepForm1(){
    var slide1 = document.getElementById('one');
    var slide2 = document.getElementById('two');

    slide1.style.display = "none";
    slide2.style.display = "block";
}
function nextStepForm2(){
    var slide2 = document.getElementById('two');
    var slide3 = document.getElementById('three');

    slide2.style.display = "none";
    slide3.style.display = "block";
}
function prevStepForm1(){
    var slide1 = document.getElementById('one');
    var slide2 = document.getElementById('two');

    slide1.style.display = "block";
    slide2.style.display = "none";
}
function prevStepForm2(){
    var slide2 = document.getElementById('two');
    var slide3 = document.getElementById('three');

    slide2.style.display = "block";
    slide3.style.display = "none";
}


/* ANIMATIONS JQUERY */
document.addEventListener("DOMContentLoaded", () => {
    $(window).scroll(function() {
        $('.fade-in-left, .fade-in-right').each(function() {
          var position = $(this).offset().top;
          var scroll = $(window).scrollTop();
          var windowHeight = $(window).height();
          
          if (scroll > position - windowHeight + 350) {
            $(this).addClass('visible');
          }
        });
      });
      
      $(window).scroll(function() {
        $('.fade-in').each(function() {
          var position = $(this).offset().top;
          var scroll = $(window).scrollTop();
          var windowHeight = $(window).height();
          
          if (scroll + windowHeight > position && scroll < position + $(this).height()) {
            $(this).addClass('visible');
          }
        });
      });
    
    $(window).scroll(function() {
        $('.fade-in-bottom, .fade-in-top').each(function() {
            var position = $(this).offset().top;
            var scroll = $(window).scrollTop();
            var windowHeight = $(window).height();
    
            if (scroll > position - windowHeight + 250) {
            $(this).addClass('visible');
            }
        });
    });
    
/* ANIMATIONS JQUERY */
    const navLinks = document.querySelectorAll('.menuLi');
    var containerNavMenu = document.getElementById("containerNavMenu");
    var containerSwitchTheme = document.getElementById("containerSwitchTheme");
    var closeIcon = document.getElementById("closeIcon");
    var iconMenu = document.getElementById("fa-bars");



    navLinks.forEach(function(link) {
        link.addEventListener('click', function(event) {

            // Remove 'active' class from all links
            navLinks.forEach(function(link) {
                link.classList.remove('li-active');
            });

            // Add 'active' class to the clicked link
            link.classList.add('li-active');

            if($(window).width() < 768){
                containerNavMenu.style.display = "none";
                closeIcon.style.display = "none";
                containerSwitchTheme.style.display = "none";
                iconMenu.style.display = "block";
            }
        });

    })
});
/* ANIMATIONS JQUERY */