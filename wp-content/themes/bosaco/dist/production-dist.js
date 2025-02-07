var output = (function (exports) {
    'use strict';

    (function () {
        var elements;
        var windowHeight;

        function init() {
            elements = document.querySelectorAll('.animate, .animate-quick');
            windowHeight = window.innerHeight;
        }

        function checkPosition() {
            for (var i = 0; i < elements.length; i++) {
                var element = elements[i];
                var positionFromTop = elements[i].getBoundingClientRect().top;

                if (positionFromTop - windowHeight <= 0) {
                    if(element.dataset.animate) {
                        element.classList.add(element.dataset.animate);
                    }
                }
            }
        }

        window.addEventListener('scroll', checkPosition);
        window.addEventListener('resize', init);

        init();
        checkPosition();
    })();

    // --------------------------------------------------------------------------------------------------
    // Back to top
    // -- Uses IntersectionObserver API to determine if the window has
    // -- scrolled past the pixel-to-watch (in footer.twig)
    // -- Not supported in IE11 & < Safari 12.0 only
    // --------------------------------------------------------------------------------------------------

    if ("IntersectionObserver" in window) {
    // // Set a variable to identify the back to top button
      var backToTopBtn = document.getElementById("back-top");

      var observer = new IntersectionObserver((entries) => {
        if (entries[0].boundingClientRect.y < 0) {
          // Add the opacity class to bring in the
          backToTopBtn.classList.add("opacity-25");
          backToTopBtn.classList.remove("opacity-0");
          // Bring it into the viewport (removing from the viewport means it isn't clickable)
          backToTopBtn.classList.remove("-mb-20");
        } else {
          backToTopBtn.classList.remove("opacity-25");
          backToTopBtn.classList.add("opacity-0");
          // Move it back out of the viewport
          backToTopBtn.classList.add("-mb-20");
        }
      });
      observer.observe(document.querySelector("#pixel-to-watch"));
      backToTopBtn.onclick = function () {
        window.scroll({
          top: 0,
          left: 0,
          behaviour: "smooth",
        });
      };
    }

    function createCommonjsModule(fn, module) {
    	return module = { exports: {} }, fn(module, module.exports), module.exports;
    }

    var lazysizes_min = createCommonjsModule(function (module) {
    /*! lazysizes - v4.1.5 */
    !function(a,b){var c=b(a,a.document);a.lazySizes=c,module.exports&&(module.exports=c);}(window,function(a,b){if(b.getElementsByClassName){var c,d,e=b.documentElement,f=a.Date,g=a.HTMLPictureElement,h="addEventListener",i="getAttribute",j=a[h],k=a.setTimeout,l=a.requestAnimationFrame||k,m=a.requestIdleCallback,n=/^picture$/i,o=["load","error","lazyincluded","_lazyloaded"],p={},q=Array.prototype.forEach,r=function(a,b){return p[b]||(p[b]=new RegExp("(\\s|^)"+b+"(\\s|$)")),p[b].test(a[i]("class")||"")&&p[b]},s=function(a,b){r(a,b)||a.setAttribute("class",(a[i]("class")||"").trim()+" "+b);},t=function(a,b){var c;(c=r(a,b))&&a.setAttribute("class",(a[i]("class")||"").replace(c," "));},u=function(a,b,c){var d=c?h:"removeEventListener";c&&u(a,b),o.forEach(function(c){a[d](c,b);});},v=function(a,d,e,f,g){var h=b.createEvent("Event");return e||(e={}),e.instance=c,h.initEvent(d,!f,!g),h.detail=e,a.dispatchEvent(h),h},w=function(b,c){var e;!g&&(e=a.picturefill||d.pf)?(c&&c.src&&!b[i]("srcset")&&b.setAttribute("srcset",c.src),e({reevaluate:!0,elements:[b]})):c&&c.src&&(b.src=c.src);},x=function(a,b){return (getComputedStyle(a,null)||{})[b]},y=function(a,b,c){for(c=c||a.offsetWidth;c<d.minSize&&b&&!a._lazysizesWidth;)c=b.offsetWidth,b=b.parentNode;return c},z=function(){var a,c,d=[],e=[],f=d,g=function(){var b=f;for(f=d.length?e:d,a=!0,c=!1;b.length;)b.shift()();a=!1;},h=function(d,e){a&&!e?d.apply(this,arguments):(f.push(d),c||(c=!0,(b.hidden?k:l)(g)));};return h._lsFlush=g,h}(),A=function(a,b){return b?function(){z(a);}:function(){var b=this,c=arguments;z(function(){a.apply(b,c);});}},B=function(a){var b,c=0,e=d.throttleDelay,g=d.ricTimeout,h=function(){b=!1,c=f.now(),a();},i=m&&g>49?function(){m(h,{timeout:g}),g!==d.ricTimeout&&(g=d.ricTimeout);}:A(function(){k(h);},!0);return function(a){var d;(a=!0===a)&&(g=33),b||(b=!0,d=e-(f.now()-c),d<0&&(d=0),a||d<9?i():k(i,d));}},C=function(a){var b,c,d=99,e=function(){b=null,a();},g=function(){var a=f.now()-c;a<d?k(g,d-a):(m||e)(e);};return function(){c=f.now(),b||(b=k(g,d));}};!function(){var b,c={lazyClass:"lazyload",loadedClass:"lazyloaded",loadingClass:"lazyloading",preloadClass:"lazypreload",errorClass:"lazyerror",autosizesClass:"lazyautosizes",srcAttr:"data-src",srcsetAttr:"data-srcset",sizesAttr:"data-sizes",minSize:40,customMedia:{},init:!0,expFactor:1.5,hFac:.8,loadMode:2,loadHidden:!0,ricTimeout:0,throttleDelay:125};d=a.lazySizesConfig||a.lazysizesConfig||{};for(b in c)b in d||(d[b]=c[b]);a.lazySizesConfig=d,k(function(){d.init&&F();});}();var D=function(){var g,l,m,o,p,y,D,F,G,H,I,J,K,L,M=/^img$/i,N=/^iframe$/i,O="onscroll"in a&&!/(gle|ing)bot/.test(navigator.userAgent),P=0,Q=0,R=0,S=-1,T=function(a){R--,a&&a.target&&u(a.target,T),(!a||R<0||!a.target)&&(R=0);},U=function(a,c){var d,f=a,g="hidden"==x(b.body,"visibility")||"hidden"!=x(a.parentNode,"visibility")&&"hidden"!=x(a,"visibility");for(F-=c,I+=c,G-=c,H+=c;g&&(f=f.offsetParent)&&f!=b.body&&f!=e;)(g=(x(f,"opacity")||1)>0)&&"visible"!=x(f,"overflow")&&(d=f.getBoundingClientRect(),g=H>d.left&&G<d.right&&I>d.top-1&&F<d.bottom+1);return g},V=function(){var a,f,h,j,k,m,n,p,q,r=c.elements;if((o=d.loadMode)&&R<8&&(a=r.length)){f=0,S++,null==K&&("expand"in d||(d.expand=e.clientHeight>500&&e.clientWidth>500?500:370),J=d.expand,K=J*d.expFactor),Q<K&&R<1&&S>2&&o>2&&!b.hidden?(Q=K,S=0):Q=o>1&&S>1&&R<6?J:P;for(;f<a;f++)if(r[f]&&!r[f]._lazyRace)if(O)if((p=r[f][i]("data-expand"))&&(m=1*p)||(m=Q),q!==m&&(y=innerWidth+m*L,D=innerHeight+m,n=-1*m,q=m),h=r[f].getBoundingClientRect(),(I=h.bottom)>=n&&(F=h.top)<=D&&(H=h.right)>=n*L&&(G=h.left)<=y&&(I||H||G||F)&&(d.loadHidden||"hidden"!=x(r[f],"visibility"))&&(l&&R<3&&!p&&(o<3||S<4)||U(r[f],m))){if(ba(r[f]),k=!0,R>9)break}else !k&&l&&!j&&R<4&&S<4&&o>2&&(g[0]||d.preloadAfterLoad)&&(g[0]||!p&&(I||H||G||F||"auto"!=r[f][i](d.sizesAttr)))&&(j=g[0]||r[f]);else ba(r[f]);j&&!k&&ba(j);}},W=B(V),X=function(a){s(a.target,d.loadedClass),t(a.target,d.loadingClass),u(a.target,Z),v(a.target,"lazyloaded");},Y=A(X),Z=function(a){Y({target:a.target});},$=function(a,b){try{a.contentWindow.location.replace(b);}catch(c){a.src=b;}},_=function(a){var b,c=a[i](d.srcsetAttr);(b=d.customMedia[a[i]("data-media")||a[i]("media")])&&a.setAttribute("media",b),c&&a.setAttribute("srcset",c);},aa=A(function(a,b,c,e,f){var g,h,j,l,o,p;(o=v(a,"lazybeforeunveil",b)).defaultPrevented||(e&&(c?s(a,d.autosizesClass):a.setAttribute("sizes",e)),h=a[i](d.srcsetAttr),g=a[i](d.srcAttr),f&&(j=a.parentNode,l=j&&n.test(j.nodeName||"")),p=b.firesLoad||"src"in a&&(h||g||l),o={target:a},p&&(u(a,T,!0),clearTimeout(m),m=k(T,2500),s(a,d.loadingClass),u(a,Z,!0)),l&&q.call(j.getElementsByTagName("source"),_),h?a.setAttribute("srcset",h):g&&!l&&(N.test(a.nodeName)?$(a,g):a.src=g),f&&(h||l)&&w(a,{src:g})),a._lazyRace&&delete a._lazyRace,t(a,d.lazyClass),z(function(){(!p||a.complete&&a.naturalWidth>1)&&(p?T(o):R--,X(o));},!0);}),ba=function(a){var b,c=M.test(a.nodeName),e=c&&(a[i](d.sizesAttr)||a[i]("sizes")),f="auto"==e;(!f&&l||!c||!a[i]("src")&&!a.srcset||a.complete||r(a,d.errorClass)||!r(a,d.lazyClass))&&(b=v(a,"lazyunveilread").detail,f&&E.updateElem(a,!0,a.offsetWidth),a._lazyRace=!0,R++,aa(a,b,f,e,c));},ca=function(){if(!l){if(f.now()-p<999)return void k(ca,999);var a=C(function(){d.loadMode=3,W();});l=!0,d.loadMode=3,W(),j("scroll",function(){3==d.loadMode&&(d.loadMode=2),a();},!0);}};return {_:function(){p=f.now(),c.elements=b.getElementsByClassName(d.lazyClass),g=b.getElementsByClassName(d.lazyClass+" "+d.preloadClass),L=d.hFac,j("scroll",W,!0),j("resize",W,!0),a.MutationObserver?new MutationObserver(W).observe(e,{childList:!0,subtree:!0,attributes:!0}):(e[h]("DOMNodeInserted",W,!0),e[h]("DOMAttrModified",W,!0),setInterval(W,999)),j("hashchange",W,!0),["focus","mouseover","click","load","transitionend","animationend","webkitAnimationEnd"].forEach(function(a){b[h](a,W,!0);}),/d$|^c/.test(b.readyState)?ca():(j("load",ca),b[h]("DOMContentLoaded",W),k(ca,2e4)),c.elements.length?(V(),z._lsFlush()):W();},checkElems:W,unveil:ba}}(),E=function(){var a,c=A(function(a,b,c,d){var e,f,g;if(a._lazysizesWidth=d,d+="px",a.setAttribute("sizes",d),n.test(b.nodeName||""))for(e=b.getElementsByTagName("source"),f=0,g=e.length;f<g;f++)e[f].setAttribute("sizes",d);c.detail.dataAttr||w(a,c.detail);}),e=function(a,b,d){var e,f=a.parentNode;f&&(d=y(a,f,d),e=v(a,"lazybeforesizes",{width:d,dataAttr:!!b}),e.defaultPrevented||(d=e.detail.width)&&d!==a._lazysizesWidth&&c(a,f,e,d));},f=function(){var b,c=a.length;if(c)for(b=0;b<c;b++)e(a[b]);},g=C(f);return {_:function(){a=b.getElementsByClassName(d.autosizesClass),j("resize",g);},checkElems:g,updateElem:e}}(),F=function(){F.i||(F.i=!0,E._(),D._());};return c={cfg:d,autoSizer:E,loader:D,init:F,uP:w,aC:s,rC:t,hC:r,fire:v,gW:y,rAF:z}}});
    });

    // Add angle down to all nav items with children
    var allArrowDown = document.querySelectorAll(".menu > ul > .menu-item-has-children > a");
    allArrowDown.forEach(element => {
      var iconDown = document.createElementNS("http://www.w3.org/2000/svg","svg");
      iconDown.setAttribute("class", "icon icon-angle-down");
      iconDown.innerHTML = "<use xlink:href='" + themeURL.themeURL + "/_assets/images/icons-sprite.svg#icon-angle-down'></use>";
      element.insertAdjacentElement("beforeend", iconDown);
    });

    // --------------------------------------------------------------------------------------------------
    // Mobile Menu
    // --------------------------------------------------------------------------------------------------

    // Set some vars for the icons
    var iconAngleUp =
      "<svg class='icon icon-angle-up'><use xlink:href='" +
      themeURL.themeURL +
      "/_assets/images/icons-sprite.svg#icon-angle-up'></use></svg>";
    var iconAngleDown =
      "<svg class='icon icon-angle-down'><use xlink:href='" +
      themeURL.themeURL +
      "/_assets/images/icons-sprite.svg#icon-angle-down'></use></svg>";

    // // Copy primary and secondary menus to .mob-nav element
    var mobNav = document.querySelector(".mob-nav .scroll-container");

    // // Add Close Icon element
    var closeBtn = document.createElement("div");
    closeBtn.setAttribute("class", "mob-nav-close");
    closeBtn.innerHTML =
      "<svg class='icon icon-times'><use xlink:href='" +
      themeURL.themeURL +
      "/_assets/images/icons-sprite.svg#icon-times'></use></svg>";
    mobNav.insertAdjacentElement("beforeend", closeBtn);

    // Add dropdown arrow to links with sub-menus
    var subNavPosition = document.querySelectorAll(
      ".mob-nav .menu-item-has-children > a"
    );
    subNavPosition.forEach((element) => {
      var subArrows = document.createElement("span");
      subArrows.setAttribute("class", "sub-arrow");
      subArrows.innerHTML = iconAngleDown + iconAngleUp;
      element.insertAdjacentElement("afterend", subArrows);
    });

    // Get all the sub nav arrow icons
    var allArrows = document.querySelectorAll(".sub-arrow .icon-angle-down");

    // Add active class to all sub nav arrow icons
    allArrows.forEach((element) => {
      element.classList.add("active");
    });

    // Get all sub navs
    var allSubArrows = document.querySelectorAll(".sub-arrow");

    // For each sub nav, let it toggle a class and show/hide the sibling menu
    allSubArrows.forEach((subArrow) => {
      subArrow.nextElementSibling.style.display = "none";
      subArrow.addEventListener("click", function () {
        this.classList.toggle("active");
        this.previousElementSibling.classList.toggle("active");
        this.nextElementSibling.style.display =
          this.nextElementSibling.style.display === "none" ? "block" : "none";
        var subArrowChildren = [...this.children];
        subArrowChildren.forEach((child) => {
          child.classList.toggle("active");
        });
      });
    });

    // Add underlay element after mobile nav
    var mobNavUnderlay = document.createElement("div");
    mobNavUnderlay.setAttribute("class", "mob-nav-underlay");
    document
      .querySelector(".mob-nav")
      .insertAdjacentElement("afterend", mobNavUnderlay);

    // Show underlay and fix the body scroll when menu button is clicked
    var menuBtn = document.querySelector('[data-mobile-menu-toggle]');

    menuBtn.addEventListener("click", function () {
      document.querySelector(".mob-nav-underlay").classList.add("mob-nav--active");
      document.querySelector(".mob-nav").classList.add("mob-nav--active");
      // document.querySelector('body').classList.add('fixed')
    });

    // Hide menu when close icon or underlay is clicked
    var closeMenuBtn = document.querySelector(".mob-nav-close");
    var menuOverlay = document.querySelector(".mob-nav-underlay");

    closeMenuBtn.addEventListener("click", closeMobileNav);
    menuOverlay.addEventListener("click", closeMobileNav);

    function closeMobileNav() {
      document
        .querySelector(".mob-nav-underlay")
        .classList.remove("mob-nav--active");
      document.querySelector(".mob-nav").classList.remove("mob-nav--active");
      // document.querySelector('body').classList.remove('fixed')
    }

    // binds $ to jquery, requires you to write strict code. Will fail validation if it doesn't match requirements.
    (function ($) {

    	// add all of your code within here, not above or below
    	$(function () {

    		// Smaller header
    		$(window).scroll(function() {
    			if ($(document).scrollTop() > 200) {
    			  $('.nt-sticky').addClass('nt-shrink');
    			} else {
    			  $('.nt-sticky').removeClass('nt-shrink');
    			}
    		});

    		// Smaller header
    		$(window).scroll(function() {
    			if ($(document).scrollTop() > 200) {
    			  $('.sticky-nav').addClass('sticky-nav-show');
    			} else {
    			  $('.sticky-nav').removeClass('sticky-nav-show');
    			}
    		});

    		$('accordion-content');

    		if ($(window).width() > 1000) {
    			$('.accordion-content').slideDown();
    		}

    		$('.accordion-header').click(function() {
    			$(this).siblings('.accordion-content').slideToggle();
    		});


    	});

    }(jQuery));

    /*!
     * @copyright Copyright (c) 2017 IcoMoon.io
     * @license   Licensed under MIT license
     *            See https://github.com/Keyamoon/svgxuse
     * @version   1.2.6
     */
    (function(){if("undefined"!==typeof window&&window.addEventListener){var e=Object.create(null),l,d=function(){clearTimeout(l);l=setTimeout(n,100);},m=function(){},t=function(){window.addEventListener("resize",d,!1);window.addEventListener("orientationchange",d,!1);if(window.MutationObserver){var k=new MutationObserver(d);k.observe(document.documentElement,{childList:!0,subtree:!0,attributes:!0});m=function(){try{k.disconnect(),window.removeEventListener("resize",d,!1),window.removeEventListener("orientationchange",
    d,!1);}catch(v){}};}else document.documentElement.addEventListener("DOMSubtreeModified",d,!1),m=function(){document.documentElement.removeEventListener("DOMSubtreeModified",d,!1);window.removeEventListener("resize",d,!1);window.removeEventListener("orientationchange",d,!1);};},u=function(k){function e(a){if(void 0!==a.protocol)var c=a;else c=document.createElement("a"),c.href=a;return c.protocol.replace(/:/g,"")+c.host}if(window.XMLHttpRequest){var d=new XMLHttpRequest;var m=e(location);k=e(k);d=void 0===
    d.withCredentials&&""!==k&&k!==m?XDomainRequest||void 0:XMLHttpRequest;}return d};var n=function(){function d(){--q;0===q&&(m(),t());}function l(a){return function(){!0!==e[a.base]&&(a.useEl.setAttributeNS("http://www.w3.org/1999/xlink","xlink:href","#"+a.hash),a.useEl.hasAttribute("href")&&a.useEl.setAttribute("href","#"+a.hash));}}function p(a){return function(){var c=document.body,b=document.createElement("x");a.onload=null;b.innerHTML=a.responseText;if(b=b.getElementsByTagName("svg")[0])b.setAttribute("aria-hidden",
    "true"),b.style.position="absolute",b.style.width=0,b.style.height=0,b.style.overflow="hidden",c.insertBefore(b,c.firstChild);d();}}function n(a){return function(){a.onerror=null;a.ontimeout=null;d();}}var a,c,q=0;m();var f=document.getElementsByTagName("use");for(c=0;c<f.length;c+=1){try{var g=f[c].getBoundingClientRect();}catch(w){g=!1;}var h=(a=f[c].getAttribute("href")||f[c].getAttributeNS("http://www.w3.org/1999/xlink","href")||f[c].getAttribute("xlink:href"))&&a.split?a.split("#"):["",""];var b=
    h[0];h=h[1];var r=g&&0===g.left&&0===g.right&&0===g.top&&0===g.bottom;g&&0===g.width&&0===g.height&&!r?(f[c].hasAttribute("href")&&f[c].setAttributeNS("http://www.w3.org/1999/xlink","xlink:href",a),b.length&&(a=e[b],!0!==a&&setTimeout(l({useEl:f[c],base:b,hash:h}),0),void 0===a&&(h=u(b),void 0!==h&&(a=new h,e[b]=a,a.onload=p(a),a.onerror=n(a),a.ontimeout=n(a),a.open("GET",b),a.send(),q+=1)))):r?b.length&&e[b]&&setTimeout(l({useEl:f[c],base:b,hash:h}),0):void 0===e[b]?e[b]=!0:e[b].onload&&(e[b].abort(),
    delete e[b].onload,e[b]=!0);}f="";q+=1;d();};var p=function(){window.removeEventListener("load",p,!1);l=setTimeout(n,0);};"complete"!==document.readyState?window.addEventListener("load",p,!1):p();}})();

    // Below finds any data of "data-ld-toggle" and matches it to a corresponding div class

    var allDataLDToggle = document.querySelectorAll('[data-ld-toggle]');

    allDataLDToggle.forEach(element => {
      var dataName = element.getAttribute('data-ld-toggle');
      element.addEventListener("click",function(){
        document.getElementById(dataName).classList.toggle('hidden');
      });
    });

    exports.__moduleExports = lazysizes_min;

    Object.defineProperty(exports, '__esModule', { value: true });

    return exports;

})({});
