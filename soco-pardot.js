/*! iFrame Resizer (iframeSizer.contentWindow.min.js) - v4.2.1 - 2019-09-24
 *  Desc: Include this file in any page being loaded into an iframe
 *        to force the iframe to resize to the content size.
 *  Requires: iframeResizer.min.js on host page.
 *  Copyright: (c) 2019 David J. Bradshaw - dave@bradshaw.net
 *  License: MIT
 */

!function(d){if("undefined"!=typeof window){var n=!0,o=10,i="",r=0,a="",t=null,u="",c=!1,s={resize:1,click:1},l=128,f=!0,m=1,h="bodyOffset",g=h,p=!0,v="",y={},b=32,w=null,T=!1,E="[iFrameSizer]",O=E.length,S="",M={max:1,min:1,bodyScroll:1,documentElementScroll:1},I="child",N=!0,A=window.parent,C="*",z=0,k=!1,e=null,R=16,x=1,L="scroll",F=L,P=window,D=function(){_("onMessage function not defined")},j=function(){},q=function(){},H={height:function(){return _("Custom height calculation function not defined"),document.documentElement.offsetHeight},width:function(){return _("Custom width calculation function not defined"),document.body.scrollWidth}},W={},B=!1;try{var J=Object.create({},{passive:{get:function(){B=!0}}});window.addEventListener("test",X,J),window.removeEventListener("test",X,J)}catch(e){}var U=Date.now||function(){return(new Date).getTime()},V={bodyOffset:function(){return document.body.offsetHeight+de("marginTop")+de("marginBottom")},offset:function(){return V.bodyOffset()},bodyScroll:function(){return document.body.scrollHeight},custom:function(){return H.height()},documentElementOffset:function(){return document.documentElement.offsetHeight},documentElementScroll:function(){return document.documentElement.scrollHeight},max:function(){return Math.max.apply(null,fe(V))},min:function(){return Math.min.apply(null,fe(V))},grow:function(){return V.max()},lowestElement:function(){return Math.max(V.bodyOffset()||V.documentElementOffset(),le("bottom",he()))},taggedElement:function(){return me("bottom","data-iframe-height")}},K={bodyScroll:function(){return document.body.scrollWidth},bodyOffset:function(){return document.body.offsetWidth},custom:function(){return H.width()},documentElementScroll:function(){return document.documentElement.scrollWidth},documentElementOffset:function(){return document.documentElement.offsetWidth},scroll:function(){return Math.max(K.bodyScroll(),K.documentElementScroll())},max:function(){return Math.max.apply(null,fe(K))},min:function(){return Math.min.apply(null,fe(K))},rightMostElement:function(){return le("right",he())},taggedElement:function(){return me("right","data-iframe-width")}},Q=function(n){function o(){c=U(),u=null,a=n.apply(i,r),u||(i=r=null)}var i,r,a,u=null,c=0;return function(){var e=U();c||(c=e);var t=R-(e-c);return i=this,r=arguments,t<=0||R<t?(u&&(clearTimeout(u),u=null),c=e,a=n.apply(i,r),u||(i=r=null)):u||(u=setTimeout(o,t)),a}}(ge);Y(window,"message",function(t){var n={init:function(){v=t.data,A=t.source,ee(),f=!1,setTimeout(function(){p=!1},l)},reset:function(){p?$("Page reset ignored by init"):($("Page size reset by host page"),ye("resetPage"))},resize:function(){pe("resizeParent","Parent window requested size check")},moveToAnchor:function(){y.findTarget(i())},inPageLink:function(){this.moveToAnchor()},pageInfo:function(){var e=i();$("PageInfoFromParent called from parent: "+e),q(JSON.parse(e)),$(" --")},message:function(){var e=i();$("onMessage called from parent: "+e),D(JSON.parse(e)),$(" --")}};function o(){return t.data.split("]")[1].split(":")[0]}function i(){return t.data.substr(t.data.indexOf(":")+1)}function r(){return t.data.split(":")[2]in{true:1,false:1}}function e(){var e=o();e in n?n[e]():("undefined"==typeof module||!module.exports)&&"iFrameResize"in window||"jQuery"in window&&"iFrameResize"in window.jQuery.prototype||r()||_("Unexpected message ("+t.data+")")}E===(""+t.data).substr(0,O)&&(!1===f?e():r()?n.init():$('Ignored message of type "'+o()+'". Received before initialization.'))}),Y(window,"readystatechange",Te),Te()}function X(){}function Y(e,t,n,o){e.addEventListener(t,n,!!B&&(o||{}))}function G(e){return e.charAt(0).toUpperCase()+e.slice(1)}function Z(e){return E+"["+S+"] "+e}function $(e){T&&"object"==typeof window.console&&console.log(Z(e))}function _(e){"object"==typeof window.console&&console.warn(Z(e))}function ee(){!function(){function e(e){return"true"===e}var t=v.substr(O).split(":");S=t[0],r=d!==t[1]?Number(t[1]):r,c=d!==t[2]?e(t[2]):c,T=d!==t[3]?e(t[3]):T,b=d!==t[4]?Number(t[4]):b,n=d!==t[6]?e(t[6]):n,a=t[7],g=d!==t[8]?t[8]:g,i=t[9],u=t[10],z=d!==t[11]?Number(t[11]):z,y.enable=d!==t[12]&&e(t[12]),I=d!==t[13]?t[13]:I,F=d!==t[14]?t[14]:F}(),$("Initialising iFrame ("+location.href+")"),function(){function e(e,t){return"function"==typeof e&&($("Setup custom "+t+"CalcMethod"),H[t]=e,e="custom"),e}"iFrameResizer"in window&&Object===window.iFrameResizer.constructor&&(function(){var e=window.iFrameResizer;$("Reading data from page: "+JSON.stringify(e)),Object.keys(e).forEach(te,e),D="onMessage"in e?e.onMessage:D,j="onReady"in e?e.onReady:j,C="targetOrigin"in e?e.targetOrigin:C,g="heightCalculationMethod"in e?e.heightCalculationMethod:g,F="widthCalculationMethod"in e?e.widthCalculationMethod:F}(),g=e(g,"height"),F=e(F,"width"));$("TargetOrigin for parent set to: "+C)}(),function(){d===a&&(a=r+"px");ne("margin",function(e,t){-1!==t.indexOf("-")&&(_("Negative CSS value ignored for "+e),t="");return t}("margin",a))}(),ne("background",i),ne("padding",u),function(){var e=document.createElement("div");e.style.clear="both",e.style.display="block",e.style.height="0",document.body.appendChild(e)}(),ae(),ue(),document.documentElement.style.height="",document.body.style.height="",$('HTML & body height set to "auto"'),$("Enable public methods"),P.parentIFrame={autoResize:function(e){return!0===e&&!1===n?(n=!0,ce()):!1===e&&!0===n&&(n=!1,ie("remove"),null!==t&&t.disconnect(),clearInterval(w)),we(0,0,"autoResize",JSON.stringify(n)),n},close:function(){we(0,0,"close")},getId:function(){return S},getPageInfo:function(e){"function"==typeof e?(q=e,we(0,0,"pageInfo")):(q=function(){},we(0,0,"pageInfoStop"))},moveToAnchor:function(e){y.findTarget(e)},reset:function(){be("parentIFrame.reset")},scrollTo:function(e,t){we(t,e,"scrollTo")},scrollToOffset:function(e,t){we(t,e,"scrollToOffset")},sendMessage:function(e,t){we(0,0,"message",JSON.stringify(e),t)},setHeightCalculationMethod:function(e){g=e,ae()},setWidthCalculationMethod:function(e){F=e,ue()},setTargetOrigin:function(e){$("Set targetOrigin: "+e),C=e},size:function(e,t){var n=(e||"")+(t?","+t:"");pe("size","parentIFrame.size("+n+")",e,t)}},ce(),y=function(){function i(e){var t=e.getBoundingClientRect(),n={x:window.pageXOffset!==d?window.pageXOffset:document.documentElement.scrollLeft,y:window.pageYOffset!==d?window.pageYOffset:document.documentElement.scrollTop};return{x:parseInt(t.left,10)+parseInt(n.x,10),y:parseInt(t.top,10)+parseInt(n.y,10)}}function n(e){var n=e.split("#")[1]||e,t=decodeURIComponent(n),o=document.getElementById(t)||document.getElementsByName(t)[0];d!==o?function(e){var t=i(e);$("Moving to in page link (#"+n+") at x: "+t.x+" y: "+t.y),we(t.y,t.x,"scrollToOffset")}(o):($("In page link (#"+n+") not found in iFrame, so sending to parent"),we(0,0,"inPageLink","#"+n))}function e(){""!==location.hash&&"#"!==location.hash&&n(location.href)}function t(){Array.prototype.forEach.call(document.querySelectorAll('a[href^="#"]'),function(e){function t(e){e.preventDefault(),n(this.getAttribute("href"))}"#"!==e.getAttribute("href")&&Y(e,"click",t)})}y.enable?Array.prototype.forEach&&document.querySelectorAll?($("Setting up location.hash handlers"),t(),Y(window,"hashchange",e),setTimeout(e,l)):_("In page linking not fully supported in this browser! (See README.md for IE8 workaround)"):$("In page linking not enabled");return{findTarget:n}}(),pe("init","Init message from host page"),j()}function te(e){var t=e.split("Callback");if(2===t.length){var n="on"+t[0].charAt(0).toUpperCase()+t[0].slice(1);this[n]=this[e],delete this[e],_("Deprecated: '"+e+"' has been renamed '"+n+"'. The old method will be removed in the next major version.")}}function ne(e,t){d!==t&&""!==t&&"null"!==t&&$("Body "+e+' set to "'+(document.body.style[e]=t)+'"')}function oe(n){var e={add:function(e){function t(){pe(n.eventName,n.eventType)}W[e]=t,Y(window,e,t,{passive:!0})},remove:function(e){var t=W[e];delete W[e],function(e,t,n){e.removeEventListener(t,n,!1)}(window,e,t)}};n.eventNames&&Array.prototype.map?(n.eventName=n.eventNames[0],n.eventNames.map(e[n.method])):e[n.method](n.eventName),$(G(n.method)+" event listener: "+n.eventType)}function ie(e){oe({method:e,eventType:"Animation Start",eventNames:["animationstart","webkitAnimationStart"]}),oe({method:e,eventType:"Animation Iteration",eventNames:["animationiteration","webkitAnimationIteration"]}),oe({method:e,eventType:"Animation End",eventNames:["animationend","webkitAnimationEnd"]}),oe({method:e,eventType:"Input",eventName:"input"}),oe({method:e,eventType:"Mouse Up",eventName:"mouseup"}),oe({method:e,eventType:"Mouse Down",eventName:"mousedown"}),oe({method:e,eventType:"Orientation Change",eventName:"orientationchange"}),oe({method:e,eventType:"Print",eventName:["afterprint","beforeprint"]}),oe({method:e,eventType:"Ready State Change",eventName:"readystatechange"}),oe({method:e,eventType:"Touch Start",eventName:"touchstart"}),oe({method:e,eventType:"Touch End",eventName:"touchend"}),oe({method:e,eventType:"Touch Cancel",eventName:"touchcancel"}),oe({method:e,eventType:"Transition Start",eventNames:["transitionstart","webkitTransitionStart","MSTransitionStart","oTransitionStart","otransitionstart"]}),oe({method:e,eventType:"Transition Iteration",eventNames:["transitioniteration","webkitTransitionIteration","MSTransitionIteration","oTransitionIteration","otransitioniteration"]}),oe({method:e,eventType:"Transition End",eventNames:["transitionend","webkitTransitionEnd","MSTransitionEnd","oTransitionEnd","otransitionend"]}),"child"===I&&oe({method:e,eventType:"IFrame Resized",eventName:"resize"})}function re(e,t,n,o){return t!==e&&(e in n||(_(e+" is not a valid option for "+o+"CalculationMethod."),e=t),$(o+' calculation method set to "'+e+'"')),e}function ae(){g=re(g,h,V,"height")}function ue(){F=re(F,L,K,"width")}function ce(){!0===n?(ie("add"),function(){var e=b<0;window.MutationObserver||window.WebKitMutationObserver?e?se():t=function(){function t(e){function t(e){!1===e.complete&&($("Attach listeners to "+e.src),e.addEventListener("load",i,!1),e.addEventListener("error",r,!1),u.push(e))}"attributes"===e.type&&"src"===e.attributeName?t(e.target):"childList"===e.type&&Array.prototype.forEach.call(e.target.querySelectorAll("img"),t)}function o(e){$("Remove listeners from "+e.src),e.removeEventListener("load",i,!1),e.removeEventListener("error",r,!1),function(e){u.splice(u.indexOf(e),1)}(e)}function n(e,t,n){o(e.target),pe(t,n+": "+e.target.src,d,d)}function i(e){n(e,"imageLoad","Image loaded")}function r(e){n(e,"imageLoadFailed","Image load failed")}function a(e){pe("mutationObserver","mutationObserver: "+e[0].target+" "+e[0].type),e.forEach(t)}var u=[],c=window.MutationObserver||window.WebKitMutationObserver,s=function(){var e=document.querySelector("body"),t={attributes:!0,attributeOldValue:!1,characterData:!0,characterDataOldValue:!1,childList:!0,subtree:!0};return s=new c(a),$("Create body MutationObserver"),s.observe(e,t),s}();return{disconnect:function(){"disconnect"in s&&($("Disconnect body MutationObserver"),s.disconnect(),u.forEach(o))}}}():($("MutationObserver not supported in this browser!"),se())}()):$("Auto Resize disabled")}function se(){0!==b&&($("setInterval: "+b+"ms"),w=setInterval(function(){pe("interval","setInterval: "+b)},Math.abs(b)))}function de(e,t){var n=0;return t=t||document.body,n=null!==(n=document.defaultView.getComputedStyle(t,null))?n[e]:0,parseInt(n,o)}function le(e,t){for(var n=t.length,o=0,i=0,r=G(e),a=U(),u=0;u<n;u++)i<(o=t[u].getBoundingClientRect()[e]+de("margin"+r,t[u]))&&(i=o);return a=U()-a,$("Parsed "+n+" HTML elements"),$("Element position calculated in "+a+"ms"),function(e){R/2<e&&$("Event throttle increased to "+(R=2*e)+"ms")}(a),i}function fe(e){return[e.bodyOffset(),e.bodyScroll(),e.documentElementOffset(),e.documentElementScroll()]}function me(e,t){var n=document.querySelectorAll("["+t+"]");return 0===n.length&&(_("No tagged elements ("+t+") found on page"),document.querySelectorAll("body *")),le(e,n)}function he(){return document.querySelectorAll("body *")}function ge(e,t,n,o){var i,r;!function(){function e(e,t){return!(Math.abs(e-t)<=z)}return i=d!==n?n:V[g](),r=d!==o?o:K[F](),e(m,i)||c&&e(x,r)}()&&"init"!==e?e in{init:1,interval:1,size:1}||!(g in M||c&&F in M)?e in{interval:1}||$("No change in size detected"):be(t):(ve(),we(m=i,x=r,e))}function pe(e,t,n,o){k&&e in s?$("Trigger event cancelled: "+e):(e in{reset:1,resetPage:1,init:1}||$("Trigger event: "+t),"init"===e?ge(e,t,n,o):Q(e,t,n,o))}function ve(){k||(k=!0,$("Trigger event lock on")),clearTimeout(e),e=setTimeout(function(){k=!1,$("Trigger event lock off"),$("--")},l)}function ye(e){m=V[g](),x=K[F](),we(m,x,e)}function be(e){var t=g;g=h,$("Reset trigger event: "+e),ve(),ye("reset"),g=t}function we(e,t,n,o,i){var r;!0===N&&(d===i?i=C:$("Message targetOrigin: "+i),$("Sending message to host page ("+(r=S+":"+e+":"+t+":"+n+(d!==o?":"+o:""))+")"),A.postMessage(E+r,i))}function Te(){"loading"!==document.readyState&&window.parent.postMessage("[iFrameResizerChild]Ready","*")}}();
//# sourceMappingURL=iframeResizer.contentWindow.map
/*!
 * Bootstrap-select v1.13.9 (https://developer.snapappointments.com/bootstrap-select)
 *
 * Copyright 2012-2019 SnapAppointments, LLC
 * Licensed under MIT (https://github.com/snapappointments/bootstrap-select/blob/master/LICENSE)
 */

(function (root, factory) {
  if (root === undefined && window !== undefined) root = window;
  if (typeof define === 'function' && define.amd) {
    // AMD. Register as an anonymous module unless amdModuleId is set
    define(["jquery"], function (a0) {
      return (factory(a0));
    });
  } else if (typeof module === 'object' && module.exports) {
    // Node. Does not work with strict CommonJS, but
    // only CommonJS-like environments that support module.exports,
    // like Node.
    module.exports = factory(require("jquery"));
  } else {
    factory(root["jQuery"]);
  }
}(this, function (jQuery) {

  (function ($) {
    'use strict';

    var DISALLOWED_ATTRIBUTES = ['sanitize', 'whiteList', 'sanitizeFn'];

    var uriAttrs = [
      'background',
      'cite',
      'href',
      'itemtype',
      'longdesc',
      'poster',
      'src',
      'xlink:href'
    ];

    var ARIA_ATTRIBUTE_PATTERN = /^aria-[\w-]*$/i;

    var DefaultWhitelist = {
      // Global attributes allowed on any supplied element below.
      '*': ['class', 'dir', 'id', 'lang', 'role', 'tabindex', 'style', ARIA_ATTRIBUTE_PATTERN],
      a: ['target', 'href', 'title', 'rel'],
      area: [],
      b: [],
      br: [],
      col: [],
      code: [],
      div: [],
      em: [],
      hr: [],
      h1: [],
      h2: [],
      h3: [],
      h4: [],
      h5: [],
      h6: [],
      i: [],
      img: ['src', 'alt', 'title', 'width', 'height'],
      li: [],
      ol: [],
      p: [],
      pre: [],
      s: [],
      small: [],
      span: [],
      sub: [],
      sup: [],
      strong: [],
      u: [],
      ul: []
    }

    /**
     * A pattern that recognizes a commonly useful subset of URLs that are safe.
     *
     * Shoutout to Angular 7 https://github.com/angular/angular/blob/7.2.4/packages/core/src/sanitization/url_sanitizer.ts
     */
    var SAFE_URL_PATTERN = /^(?:(?:https?|mailto|ftp|tel|file):|[^&:/?#]*(?:[/?#]|$))/gi;

    /**
     * A pattern that matches safe data URLs. Only matches image, video and audio types.
     *
     * Shoutout to Angular 7 https://github.com/angular/angular/blob/7.2.4/packages/core/src/sanitization/url_sanitizer.ts
     */
    var DATA_URL_PATTERN = /^data:(?:image\/(?:bmp|gif|jpeg|jpg|png|tiff|webp)|video\/(?:mpeg|mp4|ogg|webm)|audio\/(?:mp3|oga|ogg|opus));base64,[a-z0-9+/]+=*$/i;

    function allowedAttribute (attr, allowedAttributeList) {
      var attrName = attr.nodeName.toLowerCase()

      if ($.inArray(attrName, allowedAttributeList) !== -1) {
        if ($.inArray(attrName, uriAttrs) !== -1) {
          return Boolean(attr.nodeValue.match(SAFE_URL_PATTERN) || attr.nodeValue.match(DATA_URL_PATTERN))
        }

        return true
      }

      var regExp = $(allowedAttributeList).filter(function (index, value) {
        return value instanceof RegExp
      })

      // Check if a regular expression validates the attribute.
      for (var i = 0, l = regExp.length; i < l; i++) {
        if (attrName.match(regExp[i])) {
          return true
        }
      }

      return false
    }

    function sanitizeHtml (unsafeElements, whiteList, sanitizeFn) {
      if (sanitizeFn && typeof sanitizeFn === 'function') {
        return sanitizeFn(unsafeElements);
      }

      var whitelistKeys = Object.keys(whiteList);

      for (var i = 0, len = unsafeElements.length; i < len; i++) {
        var elements = unsafeElements[i].querySelectorAll('*');

        for (var j = 0, len2 = elements.length; j < len2; j++) {
          var el = elements[j];
          var elName = el.nodeName.toLowerCase();

          if (whitelistKeys.indexOf(elName) === -1) {
            el.parentNode.removeChild(el);

            continue;
          }

          var attributeList = [].slice.call(el.attributes);
          var whitelistedAttributes = [].concat(whiteList['*'] || [], whiteList[elName] || []);

          for (var k = 0, len3 = attributeList.length; k < len3; k++) {
            var attr = attributeList[k];

            if (!allowedAttribute(attr, whitelistedAttributes)) {
              el.removeAttribute(attr.nodeName);
            }
          }
        }
      }
    }

    // Polyfill for browsers with no classList support
    // Remove in v2
    if (!('classList' in document.createElement('_'))) {
      (function (view) {
        if (!('Element' in view)) return;

        var classListProp = 'classList',
          protoProp = 'prototype',
          elemCtrProto = view.Element[protoProp],
          objCtr = Object,
          classListGetter = function () {
            var $elem = $(this);

            return {
              add: function (classes) {
                classes = Array.prototype.slice.call(arguments).join(' ');
                return $elem.addClass(classes);
              },
              remove: function (classes) {
                classes = Array.prototype.slice.call(arguments).join(' ');
                return $elem.removeClass(classes);
              },
              toggle: function (classes, force) {
                return $elem.toggleClass(classes, force);
              },
              contains: function (classes) {
                return $elem.hasClass(classes);
              }
            }
          };

        if (objCtr.defineProperty) {
          var classListPropDesc = {
            get: classListGetter,
            enumerable: true,
            configurable: true
          };
          try {
            objCtr.defineProperty(elemCtrProto, classListProp, classListPropDesc);
          } catch (ex) { // IE 8 doesn't support enumerable:true
            // adding undefined to fight this issue https://github.com/eligrey/classList.js/issues/36
            // modernie IE8-MSW7 machine has IE8 8.0.6001.18702 and is affected
            if (ex.number === undefined || ex.number === -0x7FF5EC54) {
              classListPropDesc.enumerable = false;
              objCtr.defineProperty(elemCtrProto, classListProp, classListPropDesc);
            }
          }
        } else if (objCtr[protoProp].__defineGetter__) {
          elemCtrProto.__defineGetter__(classListProp, classListGetter);
        }
      }(window));
    }

    var testElement = document.createElement('_');

    testElement.classList.add('c1', 'c2');

    if (!testElement.classList.contains('c2')) {
      var _add = DOMTokenList.prototype.add,
        _remove = DOMTokenList.prototype.remove;

      DOMTokenList.prototype.add = function () {
        Array.prototype.forEach.call(arguments, _add.bind(this));
      }

      DOMTokenList.prototype.remove = function () {
        Array.prototype.forEach.call(arguments, _remove.bind(this));
      }
    }

    testElement.classList.toggle('c3', false);

    // Polyfill for IE 10 and Firefox <24, where classList.toggle does not
    // support the second argument.
    if (testElement.classList.contains('c3')) {
      var _toggle = DOMTokenList.prototype.toggle;

      DOMTokenList.prototype.toggle = function (token, force) {
        if (1 in arguments && !this.contains(token) === !force) {
          return force;
        } else {
          return _toggle.call(this, token);
        }
      };
    }

    testElement = null;

    // shallow array comparison
    function isEqual (array1, array2) {
      return array1.length === array2.length && array1.every(function (element, index) {
        return element === array2[index];
      });
    };

    // <editor-fold desc="Shims">
    if (!String.prototype.startsWith) {
      (function () {
        'use strict'; // needed to support `apply`/`call` with `undefined`/`null`
        var defineProperty = (function () {
          // IE 8 only supports `Object.defineProperty` on DOM elements
          try {
            var object = {};
            var $defineProperty = Object.defineProperty;
            var result = $defineProperty(object, object, object) && $defineProperty;
          } catch (error) {
          }
          return result;
        }());
        var toString = {}.toString;
        var startsWith = function (search) {
          if (this == null) {
            throw new TypeError();
          }
          var string = String(this);
          if (search && toString.call(search) == '[object RegExp]') {
            throw new TypeError();
          }
          var stringLength = string.length;
          var searchString = String(search);
          var searchLength = searchString.length;
          var position = arguments.length > 1 ? arguments[1] : undefined;
          // `ToInteger`
          var pos = position ? Number(position) : 0;
          if (pos != pos) { // better `isNaN`
            pos = 0;
          }
          var start = Math.min(Math.max(pos, 0), stringLength);
          // Avoid the `indexOf` call if no match is possible
          if (searchLength + start > stringLength) {
            return false;
          }
          var index = -1;
          while (++index < searchLength) {
            if (string.charCodeAt(start + index) != searchString.charCodeAt(index)) {
              return false;
            }
          }
          return true;
        };
        if (defineProperty) {
          defineProperty(String.prototype, 'startsWith', {
            'value': startsWith,
            'configurable': true,
            'writable': true
          });
        } else {
          String.prototype.startsWith = startsWith;
        }
      }());
    }

    if (!Object.keys) {
      Object.keys = function (
        o, // object
        k, // key
        r  // result array
      ) {
        // initialize object and result
        r = [];
        // iterate over object keys
        for (k in o) {
          // fill result array with non-prototypical keys
          r.hasOwnProperty.call(o, k) && r.push(k);
        }
        // return result
        return r;
      };
    }

    if (HTMLSelectElement && !HTMLSelectElement.prototype.hasOwnProperty('selectedOptions')) {
      Object.defineProperty(HTMLSelectElement.prototype, 'selectedOptions', {
        get: function () {
          return this.querySelectorAll(':checked');
        }
      });
    }

    // much faster than $.val()
    function getSelectValues (select) {
      var result = [];
      var options = select.selectedOptions;
      var opt;

      if (select.multiple) {
        for (var i = 0, len = options.length; i < len; i++) {
          opt = options[i];

          result.push(opt.value || opt.text);
        }
      } else {
        result = select.value;
      }

      return result;
    }

    // set data-selected on select element if the value has been programmatically selected
    // prior to initialization of bootstrap-select
    // * consider removing or replacing an alternative method *
    var valHooks = {
      useDefault: false,
      _set: $.valHooks.select.set
    };

    $.valHooks.select.set = function (elem, value) {
      if (value && !valHooks.useDefault) $(elem).data('selected', true);

      return valHooks._set.apply(this, arguments);
    };

    var changedArguments = null;

    var EventIsSupported = (function () {
      try {
        new Event('change');
        return true;
      } catch (e) {
        return false;
      }
    })();

    $.fn.triggerNative = function (eventName) {
      var el = this[0],
        event;

      if (el.dispatchEvent) { // for modern browsers & IE9+
        if (EventIsSupported) {
          // For modern browsers
          event = new Event(eventName, {
            bubbles: true
          });
        } else {
          // For IE since it doesn't support Event constructor
          event = document.createEvent('Event');
          event.initEvent(eventName, true, false);
        }

        el.dispatchEvent(event);
      } else if (el.fireEvent) { // for IE8
        event = document.createEventObject();
        event.eventType = eventName;
        el.fireEvent('on' + eventName, event);
      } else {
        // fall back to jQuery.trigger
        this.trigger(eventName);
      }
    };
    // </editor-fold>

    function stringSearch (li, searchString, method, normalize) {
      var stringTypes = [
          'display',
          'subtext',
          'tokens'
        ],
        searchSuccess = false;

      for (var i = 0; i < stringTypes.length; i++) {
        var stringType = stringTypes[i],
          string = li[stringType];

        if (string) {
          string = string.toString();

          // Strip HTML tags. This isn't perfect, but it's much faster than any other method
          if (stringType === 'display') {
            string = string.replace(/<[^>]+>/g, '');
          }

          if (normalize) string = normalizeToBase(string);
          string = string.toUpperCase();

          if (method === 'contains') {
            searchSuccess = string.indexOf(searchString) >= 0;
          } else {
            searchSuccess = string.startsWith(searchString);
          }

          if (searchSuccess) break;
        }
      }

      return searchSuccess;
    }

    function toInteger (value) {
      return parseInt(value, 10) || 0;
    }

    // Borrowed from Lodash (_.deburr)
    /** Used to map Latin Unicode letters to basic Latin letters. */
    var deburredLetters = {
      // Latin-1 Supplement block.
      '\xc0': 'A',  '\xc1': 'A', '\xc2': 'A', '\xc3': 'A', '\xc4': 'A', '\xc5': 'A',
      '\xe0': 'a',  '\xe1': 'a', '\xe2': 'a', '\xe3': 'a', '\xe4': 'a', '\xe5': 'a',
      '\xc7': 'C',  '\xe7': 'c',
      '\xd0': 'D',  '\xf0': 'd',
      '\xc8': 'E',  '\xc9': 'E', '\xca': 'E', '\xcb': 'E',
      '\xe8': 'e',  '\xe9': 'e', '\xea': 'e', '\xeb': 'e',
      '\xcc': 'I',  '\xcd': 'I', '\xce': 'I', '\xcf': 'I',
      '\xec': 'i',  '\xed': 'i', '\xee': 'i', '\xef': 'i',
      '\xd1': 'N',  '\xf1': 'n',
      '\xd2': 'O',  '\xd3': 'O', '\xd4': 'O', '\xd5': 'O', '\xd6': 'O', '\xd8': 'O',
      '\xf2': 'o',  '\xf3': 'o', '\xf4': 'o', '\xf5': 'o', '\xf6': 'o', '\xf8': 'o',
      '\xd9': 'U',  '\xda': 'U', '\xdb': 'U', '\xdc': 'U',
      '\xf9': 'u',  '\xfa': 'u', '\xfb': 'u', '\xfc': 'u',
      '\xdd': 'Y',  '\xfd': 'y', '\xff': 'y',
      '\xc6': 'Ae', '\xe6': 'ae',
      '\xde': 'Th', '\xfe': 'th',
      '\xdf': 'ss',
      // Latin Extended-A block.
      '\u0100': 'A',  '\u0102': 'A', '\u0104': 'A',
      '\u0101': 'a',  '\u0103': 'a', '\u0105': 'a',
      '\u0106': 'C',  '\u0108': 'C', '\u010a': 'C', '\u010c': 'C',
      '\u0107': 'c',  '\u0109': 'c', '\u010b': 'c', '\u010d': 'c',
      '\u010e': 'D',  '\u0110': 'D', '\u010f': 'd', '\u0111': 'd',
      '\u0112': 'E',  '\u0114': 'E', '\u0116': 'E', '\u0118': 'E', '\u011a': 'E',
      '\u0113': 'e',  '\u0115': 'e', '\u0117': 'e', '\u0119': 'e', '\u011b': 'e',
      '\u011c': 'G',  '\u011e': 'G', '\u0120': 'G', '\u0122': 'G',
      '\u011d': 'g',  '\u011f': 'g', '\u0121': 'g', '\u0123': 'g',
      '\u0124': 'H',  '\u0126': 'H', '\u0125': 'h', '\u0127': 'h',
      '\u0128': 'I',  '\u012a': 'I', '\u012c': 'I', '\u012e': 'I', '\u0130': 'I',
      '\u0129': 'i',  '\u012b': 'i', '\u012d': 'i', '\u012f': 'i', '\u0131': 'i',
      '\u0134': 'J',  '\u0135': 'j',
      '\u0136': 'K',  '\u0137': 'k', '\u0138': 'k',
      '\u0139': 'L',  '\u013b': 'L', '\u013d': 'L', '\u013f': 'L', '\u0141': 'L',
      '\u013a': 'l',  '\u013c': 'l', '\u013e': 'l', '\u0140': 'l', '\u0142': 'l',
      '\u0143': 'N',  '\u0145': 'N', '\u0147': 'N', '\u014a': 'N',
      '\u0144': 'n',  '\u0146': 'n', '\u0148': 'n', '\u014b': 'n',
      '\u014c': 'O',  '\u014e': 'O', '\u0150': 'O',
      '\u014d': 'o',  '\u014f': 'o', '\u0151': 'o',
      '\u0154': 'R',  '\u0156': 'R', '\u0158': 'R',
      '\u0155': 'r',  '\u0157': 'r', '\u0159': 'r',
      '\u015a': 'S',  '\u015c': 'S', '\u015e': 'S', '\u0160': 'S',
      '\u015b': 's',  '\u015d': 's', '\u015f': 's', '\u0161': 's',
      '\u0162': 'T',  '\u0164': 'T', '\u0166': 'T',
      '\u0163': 't',  '\u0165': 't', '\u0167': 't',
      '\u0168': 'U',  '\u016a': 'U', '\u016c': 'U', '\u016e': 'U', '\u0170': 'U', '\u0172': 'U',
      '\u0169': 'u',  '\u016b': 'u', '\u016d': 'u', '\u016f': 'u', '\u0171': 'u', '\u0173': 'u',
      '\u0174': 'W',  '\u0175': 'w',
      '\u0176': 'Y',  '\u0177': 'y', '\u0178': 'Y',
      '\u0179': 'Z',  '\u017b': 'Z', '\u017d': 'Z',
      '\u017a': 'z',  '\u017c': 'z', '\u017e': 'z',
      '\u0132': 'IJ', '\u0133': 'ij',
      '\u0152': 'Oe', '\u0153': 'oe',
      '\u0149': "'n", '\u017f': 's'
    };

    /** Used to match Latin Unicode letters (excluding mathematical operators). */
    var reLatin = /[\xc0-\xd6\xd8-\xf6\xf8-\xff\u0100-\u017f]/g;

    /** Used to compose unicode character classes. */
    var rsComboMarksRange = '\\u0300-\\u036f',
      reComboHalfMarksRange = '\\ufe20-\\ufe2f',
      rsComboSymbolsRange = '\\u20d0-\\u20ff',
      rsComboMarksExtendedRange = '\\u1ab0-\\u1aff',
      rsComboMarksSupplementRange = '\\u1dc0-\\u1dff',
      rsComboRange = rsComboMarksRange + reComboHalfMarksRange + rsComboSymbolsRange + rsComboMarksExtendedRange + rsComboMarksSupplementRange;

    /** Used to compose unicode capture groups. */
    var rsCombo = '[' + rsComboRange + ']';

    /**
     * Used to match [combining diacritical marks](https://en.wikipedia.org/wiki/Combining_Diacritical_Marks) and
     * [combining diacritical marks for symbols](https://en.wikipedia.org/wiki/Combining_Diacritical_Marks_for_Symbols).
     */
    var reComboMark = RegExp(rsCombo, 'g');

    function deburrLetter (key) {
      return deburredLetters[key];
    };

    function normalizeToBase (string) {
      string = string.toString();
      return string && string.replace(reLatin, deburrLetter).replace(reComboMark, '');
    }

    // List of HTML entities for escaping.
    var escapeMap = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#x27;',
      '`': '&#x60;'
    };

    // Functions for escaping and unescaping strings to/from HTML interpolation.
    var createEscaper = function (map) {
      var escaper = function (match) {
        return map[match];
      };
      // Regexes for identifying a key that needs to be escaped.
      var source = '(?:' + Object.keys(map).join('|') + ')';
      var testRegexp = RegExp(source);
      var replaceRegexp = RegExp(source, 'g');
      return function (string) {
        string = string == null ? '' : '' + string;
        return testRegexp.test(string) ? string.replace(replaceRegexp, escaper) : string;
      };
    };

    var htmlEscape = createEscaper(escapeMap);

    /**
     * ------------------------------------------------------------------------
     * Constants
     * ------------------------------------------------------------------------
     */

    var keyCodeMap = {
      32: ' ',
      48: '0',
      49: '1',
      50: '2',
      51: '3',
      52: '4',
      53: '5',
      54: '6',
      55: '7',
      56: '8',
      57: '9',
      59: ';',
      65: 'A',
      66: 'B',
      67: 'C',
      68: 'D',
      69: 'E',
      70: 'F',
      71: 'G',
      72: 'H',
      73: 'I',
      74: 'J',
      75: 'K',
      76: 'L',
      77: 'M',
      78: 'N',
      79: 'O',
      80: 'P',
      81: 'Q',
      82: 'R',
      83: 'S',
      84: 'T',
      85: 'U',
      86: 'V',
      87: 'W',
      88: 'X',
      89: 'Y',
      90: 'Z',
      96: '0',
      97: '1',
      98: '2',
      99: '3',
      100: '4',
      101: '5',
      102: '6',
      103: '7',
      104: '8',
      105: '9'
    };

    var keyCodes = {
      ESCAPE: 27, // KeyboardEvent.which value for Escape (Esc) key
      ENTER: 13, // KeyboardEvent.which value for Enter key
      SPACE: 32, // KeyboardEvent.which value for space key
      TAB: 9, // KeyboardEvent.which value for tab key
      ARROW_UP: 38, // KeyboardEvent.which value for up arrow key
      ARROW_DOWN: 40 // KeyboardEvent.which value for down arrow key
    }

    var version = {
      success: false,
      major: '3'
    };

    try {
      version.full = ($.fn.dropdown.Constructor.VERSION || '').split(' ')[0].split('.');
      version.major = version.full[0];
      version.success = true;
    } catch (err) {
      // do nothing
    }

    var selectId = 0;

    var EVENT_KEY = '.bs.select';

    var classNames = {
      DISABLED: 'disabled',
      DIVIDER: 'divider',
      SHOW: 'open',
      DROPUP: 'dropup',
      MENU: 'dropdown-menu',
      MENURIGHT: 'dropdown-menu-right',
      MENULEFT: 'dropdown-menu-left',
      // to-do: replace with more advanced template/customization options
      BUTTONCLASS: 'btn-default',
      POPOVERHEADER: 'popover-title',
      ICONBASE: 'glyphicon',
      TICKICON: 'glyphicon-ok'
    }

    var Selector = {
      MENU: '.' + classNames.MENU
    }

    var elementTemplates = {
      span: document.createElement('span'),
      i: document.createElement('i'),
      subtext: document.createElement('small'),
      a: document.createElement('a'),
      li: document.createElement('li'),
      whitespace: document.createTextNode('\u00A0'),
      fragment: document.createDocumentFragment()
    }

    elementTemplates.a.setAttribute('role', 'option');
    elementTemplates.subtext.className = 'text-muted';

    elementTemplates.text = elementTemplates.span.cloneNode(false);
    elementTemplates.text.className = 'text';

    elementTemplates.checkMark = elementTemplates.span.cloneNode(false);

    var REGEXP_ARROW = new RegExp(keyCodes.ARROW_UP + '|' + keyCodes.ARROW_DOWN);
    var REGEXP_TAB_OR_ESCAPE = new RegExp('^' + keyCodes.TAB + '$|' + keyCodes.ESCAPE);

    var generateOption = {
      li: function (content, classes, optgroup) {
        var li = elementTemplates.li.cloneNode(false);

        if (content) {
          if (content.nodeType === 1 || content.nodeType === 11) {
            li.appendChild(content);
          } else {
            li.innerHTML = content;
          }
        }

        if (typeof classes !== 'undefined' && classes !== '') li.className = classes;
        if (typeof optgroup !== 'undefined' && optgroup !== null) li.classList.add('optgroup-' + optgroup);

        return li;
      },

      a: function (text, classes, inline) {
        var a = elementTemplates.a.cloneNode(true);

        if (text) {
          if (text.nodeType === 11) {
            a.appendChild(text);
          } else {
            a.insertAdjacentHTML('beforeend', text);
          }
        }

        if (typeof classes !== 'undefined' && classes !== '') a.className = classes;
        if (version.major === '4') a.classList.add('dropdown-item');
        if (inline) a.setAttribute('style', inline);

        return a;
      },

      text: function (options, useFragment) {
        var textElement = elementTemplates.text.cloneNode(false),
          subtextElement,
          iconElement;

        if (options.content) {
          textElement.innerHTML = options.content;
        } else {
          textElement.textContent = options.text;

          if (options.icon) {
            var whitespace = elementTemplates.whitespace.cloneNode(false);

            // need to use <i> for icons in the button to prevent a breaking change
            // note: switch to span in next major release
            iconElement = (useFragment === true ? elementTemplates.i : elementTemplates.span).cloneNode(false);
            iconElement.className = options.iconBase + ' ' + options.icon;

            elementTemplates.fragment.appendChild(iconElement);
            elementTemplates.fragment.appendChild(whitespace);
          }

          if (options.subtext) {
            subtextElement = elementTemplates.subtext.cloneNode(false);
            subtextElement.textContent = options.subtext;
            textElement.appendChild(subtextElement);
          }
        }

        if (useFragment === true) {
          while (textElement.childNodes.length > 0) {
            elementTemplates.fragment.appendChild(textElement.childNodes[0]);
          }
        } else {
          elementTemplates.fragment.appendChild(textElement);
        }

        return elementTemplates.fragment;
      },

      label: function (options) {
        var textElement = elementTemplates.text.cloneNode(false),
          subtextElement,
          iconElement;

        textElement.innerHTML = options.label;

        if (options.icon) {
          var whitespace = elementTemplates.whitespace.cloneNode(false);

          iconElement = elementTemplates.span.cloneNode(false);
          iconElement.className = options.iconBase + ' ' + options.icon;

          elementTemplates.fragment.appendChild(iconElement);
          elementTemplates.fragment.appendChild(whitespace);
        }

        if (options.subtext) {
          subtextElement = elementTemplates.subtext.cloneNode(false);
          subtextElement.textContent = options.subtext;
          textElement.appendChild(subtextElement);
        }

        elementTemplates.fragment.appendChild(textElement);

        return elementTemplates.fragment;
      }
    }

    var Selectpicker = function (element, options) {
      var that = this;

      // bootstrap-select has been initialized - revert valHooks.select.set back to its original function
      if (!valHooks.useDefault) {
        $.valHooks.select.set = valHooks._set;
        valHooks.useDefault = true;
      }

      this.$element = $(element);
      this.$newElement = null;
      this.$button = null;
      this.$menu = null;
      this.options = options;
      this.selectpicker = {
        main: {},
        current: {}, // current changes if a search is in progress
        search: {},
        view: {},
        keydown: {
          keyHistory: '',
          resetKeyHistory: {
            start: function () {
              return setTimeout(function () {
                that.selectpicker.keydown.keyHistory = '';
              }, 800);
            }
          }
        }
      };
      // If we have no title yet, try to pull it from the html title attribute (jQuery doesnt' pick it up as it's not a
      // data-attribute)
      if (this.options.title === null) {
        this.options.title = this.$element.attr('title');
      }

      // Format window padding
      var winPad = this.options.windowPadding;
      if (typeof winPad === 'number') {
        this.options.windowPadding = [winPad, winPad, winPad, winPad];
      }

      // Expose public methods
      this.val = Selectpicker.prototype.val;
      this.render = Selectpicker.prototype.render;
      this.refresh = Selectpicker.prototype.refresh;
      this.setStyle = Selectpicker.prototype.setStyle;
      this.selectAll = Selectpicker.prototype.selectAll;
      this.deselectAll = Selectpicker.prototype.deselectAll;
      this.destroy = Selectpicker.prototype.destroy;
      this.remove = Selectpicker.prototype.remove;
      this.show = Selectpicker.prototype.show;
      this.hide = Selectpicker.prototype.hide;

      this.init();
    };

    Selectpicker.VERSION = '1.13.9';

    // part of this is duplicated in i18n/defaults-en_US.js. Make sure to update both.
    Selectpicker.DEFAULTS = {
      noneSelectedText: 'Nothing selected',
      noneResultsText: 'No results matched {0}',
      countSelectedText: function (numSelected, numTotal) {
        return (numSelected == 1) ? '{0} item selected' : '{0} items selected';
      },
      maxOptionsText: function (numAll, numGroup) {
        return [
          (numAll == 1) ? 'Limit reached ({n} item max)' : 'Limit reached ({n} items max)',
          (numGroup == 1) ? 'Group limit reached ({n} item max)' : 'Group limit reached ({n} items max)'
        ];
      },
      selectAllText: 'Select All',
      deselectAllText: 'Deselect All',
      doneButton: false,
      doneButtonText: 'Close',
      multipleSeparator: ', ',
      styleBase: 'btn',
      style: classNames.BUTTONCLASS,
      size: 'auto',
      title: null,
      selectedTextFormat: 'values',
      width: false,
      container: false,
      hideDisabled: false,
      showSubtext: false,
      showIcon: true,
      showContent: true,
      dropupAuto: true,
      header: false,
      liveSearch: false,
      liveSearchPlaceholder: null,
      liveSearchNormalize: false,
      liveSearchStyle: 'contains',
      actionsBox: false,
      iconBase: classNames.ICONBASE,
      tickIcon: classNames.TICKICON,
      showTick: false,
      template: {
        caret: '<span class="caret"></span>'
      },
      maxOptions: false,
      mobile: false,
      selectOnTab: false,
      dropdownAlignRight: false,
      windowPadding: 0,
      virtualScroll: 600,
      display: false,
      sanitize: true,
      sanitizeFn: null,
      whiteList: DefaultWhitelist
    };

    Selectpicker.prototype = {

      constructor: Selectpicker,

      init: function () {
        var that = this,
          id = this.$element.attr('id');

        this.selectId = selectId++;

        this.$element[0].classList.add('bs-select-hidden');

        this.multiple = this.$element.prop('multiple');
        this.autofocus = this.$element.prop('autofocus');
        this.options.showTick = this.$element[0].classList.contains('show-tick');

        this.$newElement = this.createDropdown();
        this.$element
          .after(this.$newElement)
          .prependTo(this.$newElement);

        this.$button = this.$newElement.children('button');
        this.$menu = this.$newElement.children(Selector.MENU);
        this.$menuInner = this.$menu.children('.inner');
        this.$searchbox = this.$menu.find('input');

        this.$element[0].classList.remove('bs-select-hidden');

        if (this.options.dropdownAlignRight === true) this.$menu[0].classList.add(classNames.MENURIGHT);

        if (typeof id !== 'undefined') {
          this.$button.attr('data-id', id);
        }

        this.checkDisabled();
        this.clickListener();
        if (this.options.liveSearch) this.liveSearchListener();
        this.setStyle();
        this.render();
        this.setWidth();
        if (this.options.container) {
          this.selectPosition();
        } else {
          this.$element.on('hide' + EVENT_KEY, function () {
            if (that.isVirtual()) {
              // empty menu on close
              var menuInner = that.$menuInner[0],
                emptyMenu = menuInner.firstChild.cloneNode(false);

              // replace the existing UL with an empty one - this is faster than $.empty() or innerHTML = ''
              menuInner.replaceChild(emptyMenu, menuInner.firstChild);
              menuInner.scrollTop = 0;
            }
          });
        }
        this.$menu.data('this', this);
        this.$newElement.data('this', this);
        if (this.options.mobile) this.mobile();

        this.$newElement.on({
          'hide.bs.dropdown': function (e) {
            that.$menuInner.attr('aria-expanded', false);
            that.$element.trigger('hide' + EVENT_KEY, e);
          },
          'hidden.bs.dropdown': function (e) {
            that.$element.trigger('hidden' + EVENT_KEY, e);
          },
          'show.bs.dropdown': function (e) {
            that.$menuInner.attr('aria-expanded', true);
            that.$element.trigger('show' + EVENT_KEY, e);
          },
          'shown.bs.dropdown': function (e) {
            that.$element.trigger('shown' + EVENT_KEY, e);
          }
        });

        if (that.$element[0].hasAttribute('required')) {
          this.$element.on('invalid' + EVENT_KEY, function () {
            that.$button[0].classList.add('bs-invalid');

            that.$element
              .on('shown' + EVENT_KEY + '.invalid', function () {
                that.$element
                  .val(that.$element.val()) // set the value to hide the validation message in Chrome when menu is opened
                  .off('shown' + EVENT_KEY + '.invalid');
              })
              .on('rendered' + EVENT_KEY, function () {
                // if select is no longer invalid, remove the bs-invalid class
                if (this.validity.valid) that.$button[0].classList.remove('bs-invalid');
                that.$element.off('rendered' + EVENT_KEY);
              });

            that.$button.on('blur' + EVENT_KEY, function () {
              that.$element.trigger('focus').trigger('blur');
              that.$button.off('blur' + EVENT_KEY);
            });
          });
        }

        setTimeout(function () {
          that.createLi();
          that.$element.trigger('loaded' + EVENT_KEY);
        });
      },

      createDropdown: function () {
        // Options
        // If we are multiple or showTick option is set, then add the show-tick class
        var showTick = (this.multiple || this.options.showTick) ? ' show-tick' : '',
          inputGroup = '',
          autofocus = this.autofocus ? ' autofocus' : '';

        if (version.major < 4 && this.$element.parent().hasClass('input-group')) {
          inputGroup = ' input-group-btn';
        }

        // Elements
        var drop,
          header = '',
          searchbox = '',
          actionsbox = '',
          donebutton = '';

        if (this.options.header) {
          header =
            '<div class="' + classNames.POPOVERHEADER + '">' +
            '<button type="button" class="close" aria-hidden="true">&times;</button>' +
            this.options.header +
            '</div>';
        }

        if (this.options.liveSearch) {
          searchbox =
            '<div class="bs-searchbox">' +
            '<input type="text" class="form-control" autocomplete="off"' +
            (
              this.options.liveSearchPlaceholder === null ? ''
                :
                ' placeholder="' + htmlEscape(this.options.liveSearchPlaceholder) + '"'
            ) +
            ' role="textbox" aria-label="Search">' +
            '</div>';
        }

        if (this.multiple && this.options.actionsBox) {
          actionsbox =
            '<div class="bs-actionsbox">' +
            '<div class="btn-group btn-group-sm btn-block">' +
            '<button type="button" class="actions-btn bs-select-all btn ' + classNames.BUTTONCLASS + '">' +
            this.options.selectAllText +
            '</button>' +
            '<button type="button" class="actions-btn bs-deselect-all btn ' + classNames.BUTTONCLASS + '">' +
            this.options.deselectAllText +
            '</button>' +
            '</div>' +
            '</div>';
        }

        if (this.multiple && this.options.doneButton) {
          donebutton =
            '<div class="bs-donebutton">' +
            '<div class="btn-group btn-block">' +
            '<button type="button" class="btn btn-sm ' + classNames.BUTTONCLASS + '">' +
            this.options.doneButtonText +
            '</button>' +
            '</div>' +
            '</div>';
        }

        drop =
          '<div class="dropdown bootstrap-select' + showTick + inputGroup + '">' +
          '<button type="button" class="' + this.options.styleBase + ' dropdown-toggle" ' + (this.options.display === 'static' ? 'data-display="static"' : '') + 'data-toggle="dropdown"' + autofocus + ' role="button">' +
          '<div class="filter-option">' +
          '<div class="filter-option-inner">' +
          '<div class="filter-option-inner-inner"></div>' +
          '</div> ' +
          '</div>' +
          (
            version.major === '4' ? ''
              :
              '<span class="bs-caret">' +
              this.options.template.caret +
              '</span>'
          ) +
          '</button>' +
          '<div class="' + classNames.MENU + ' ' + (version.major === '4' ? '' : classNames.SHOW) + '" role="combobox">' +
          header +
          searchbox +
          actionsbox +
          '<div class="inner ' + classNames.SHOW + '" role="listbox" aria-expanded="false" tabindex="-1">' +
          '<ul class="' + classNames.MENU + ' inner ' + (version.major === '4' ? classNames.SHOW : '') + '">' +
          '</ul>' +
          '</div>' +
          donebutton +
          '</div>' +
          '</div>';

        return $(drop);
      },

      setPositionData: function () {
        this.selectpicker.view.canHighlight = [];

        for (var i = 0; i < this.selectpicker.current.data.length; i++) {
          var li = this.selectpicker.current.data[i],
            canHighlight = true;

          if (li.type === 'divider') {
            canHighlight = false;
            li.height = this.sizeInfo.dividerHeight;
          } else if (li.type === 'optgroup-label') {
            canHighlight = false;
            li.height = this.sizeInfo.dropdownHeaderHeight;
          } else {
            li.height = this.sizeInfo.liHeight;
          }

          if (li.disabled) canHighlight = false;

          this.selectpicker.view.canHighlight.push(canHighlight);

          li.position = (i === 0 ? 0 : this.selectpicker.current.data[i - 1].position) + li.height;
        }
      },

      isVirtual: function () {
        return (this.options.virtualScroll !== false) && (this.selectpicker.main.elements.length >= this.options.virtualScroll) || this.options.virtualScroll === true;
      },

      createView: function (isSearching, scrollTop) {
        scrollTop = scrollTop || 0;

        var that = this;

        this.selectpicker.current = isSearching ? this.selectpicker.search : this.selectpicker.main;

        var active = [];
        var selected;
        var prevActive;

        this.setPositionData();

        scroll(scrollTop, true);

        this.$menuInner.off('scroll.createView').on('scroll.createView', function (e, updateValue) {
          if (!that.noScroll) scroll(this.scrollTop, updateValue);
          that.noScroll = false;
        });

        function scroll (scrollTop, init) {
          var size = that.selectpicker.current.elements.length,
            chunks = [],
            chunkSize,
            chunkCount,
            firstChunk,
            lastChunk,
            currentChunk,
            prevPositions,
            positionIsDifferent,
            previousElements,
            menuIsDifferent = true,
            isVirtual = that.isVirtual();

          that.selectpicker.view.scrollTop = scrollTop;

          if (isVirtual === true) {
            // if an option that is encountered that is wider than the current menu width, update the menu width accordingly
            if (that.sizeInfo.hasScrollBar && that.$menu[0].offsetWidth > that.sizeInfo.totalMenuWidth) {
              that.sizeInfo.menuWidth = that.$menu[0].offsetWidth;
              that.sizeInfo.totalMenuWidth = that.sizeInfo.menuWidth + that.sizeInfo.scrollBarWidth;
              that.$menu.css('min-width', that.sizeInfo.menuWidth);
            }
          }

          chunkSize = Math.ceil(that.sizeInfo.menuInnerHeight / that.sizeInfo.liHeight * 1.5); // number of options in a chunk
          chunkCount = Math.round(size / chunkSize) || 1; // number of chunks

          for (var i = 0; i < chunkCount; i++) {
            var endOfChunk = (i + 1) * chunkSize;

            if (i === chunkCount - 1) {
              endOfChunk = size;
            }

            chunks[i] = [
              (i) * chunkSize + (!i ? 0 : 1),
              endOfChunk
            ];

            if (!size) break;

            if (currentChunk === undefined && scrollTop <= that.selectpicker.current.data[endOfChunk - 1].position - that.sizeInfo.menuInnerHeight) {
              currentChunk = i;
            }
          }

          if (currentChunk === undefined) currentChunk = 0;

          prevPositions = [that.selectpicker.view.position0, that.selectpicker.view.position1];

          // always display previous, current, and next chunks
          firstChunk = Math.max(0, currentChunk - 1);
          lastChunk = Math.min(chunkCount - 1, currentChunk + 1);

          that.selectpicker.view.position0 = isVirtual === false ? 0 : (Math.max(0, chunks[firstChunk][0]) || 0);
          that.selectpicker.view.position1 = isVirtual === false ? size : (Math.min(size, chunks[lastChunk][1]) || 0);

          positionIsDifferent = prevPositions[0] !== that.selectpicker.view.position0 || prevPositions[1] !== that.selectpicker.view.position1;

          if (that.activeIndex !== undefined) {
            prevActive = that.selectpicker.main.elements[that.prevActiveIndex];
            active = that.selectpicker.main.elements[that.activeIndex];
            selected = that.selectpicker.main.elements[that.selectedIndex];

            if (init) {
              if (that.activeIndex !== that.selectedIndex && active && active.length) {
                active.classList.remove('active');
                if (active.firstChild) active.firstChild.classList.remove('active');
              }
              that.activeIndex = undefined;
            }

            if (that.activeIndex && that.activeIndex !== that.selectedIndex && selected && selected.length) {
              selected.classList.remove('active');
              if (selected.firstChild) selected.firstChild.classList.remove('active');
            }
          }

          if (that.prevActiveIndex !== undefined && that.prevActiveIndex !== that.activeIndex && that.prevActiveIndex !== that.selectedIndex && prevActive && prevActive.length) {
            prevActive.classList.remove('active');
            if (prevActive.firstChild) prevActive.firstChild.classList.remove('active');
          }

          if (init || positionIsDifferent) {
            previousElements = that.selectpicker.view.visibleElements ? that.selectpicker.view.visibleElements.slice() : [];

            if (isVirtual === false) {
              that.selectpicker.view.visibleElements = that.selectpicker.current.elements;
            } else {
              that.selectpicker.view.visibleElements = that.selectpicker.current.elements.slice(that.selectpicker.view.position0, that.selectpicker.view.position1);
            }

            that.setOptionStatus();

            // if searching, check to make sure the list has actually been updated before updating DOM
            // this prevents unnecessary repaints
            if (isSearching || (isVirtual === false && init)) menuIsDifferent = !isEqual(previousElements, that.selectpicker.view.visibleElements);

            // if virtual scroll is disabled and not searching,
            // menu should never need to be updated more than once
            if ((init || isVirtual === true) && menuIsDifferent) {
              var menuInner = that.$menuInner[0],
                menuFragment = document.createDocumentFragment(),
                emptyMenu = menuInner.firstChild.cloneNode(false),
                marginTop,
                marginBottom,
                elements = that.selectpicker.view.visibleElements,
                toSanitize = [];

              // replace the existing UL with an empty one - this is faster than $.empty()
              menuInner.replaceChild(emptyMenu, menuInner.firstChild);

              for (var i = 0, visibleElementsLen = elements.length; i < visibleElementsLen; i++) {
                var element = elements[i],
                  elText,
                  elementData;

                if (that.options.sanitize) {
                  elText = element.lastChild;

                  if (elText) {
                    elementData = that.selectpicker.current.data[i + that.selectpicker.view.position0];

                    if (elementData && elementData.content && !elementData.sanitized) {
                      toSanitize.push(elText);
                      elementData.sanitized = true;
                    }
                  }
                }

                menuFragment.appendChild(element);
              }

              if (that.options.sanitize && toSanitize.length) {
                sanitizeHtml(toSanitize, that.options.whiteList, that.options.sanitizeFn);
              }

              if (isVirtual === true) {
                marginTop = (that.selectpicker.view.position0 === 0 ? 0 : that.selectpicker.current.data[that.selectpicker.view.position0 - 1].position);
                marginBottom = (that.selectpicker.view.position1 > size - 1 ? 0 : that.selectpicker.current.data[size - 1].position - that.selectpicker.current.data[that.selectpicker.view.position1 - 1].position);

                menuInner.firstChild.style.marginTop = marginTop + 'px';
                menuInner.firstChild.style.marginBottom = marginBottom + 'px';
              }

              menuInner.firstChild.appendChild(menuFragment);
            }
          }

          that.prevActiveIndex = that.activeIndex;

          if (!that.options.liveSearch) {
            that.$menuInner.trigger('focus');
          } else if (isSearching && init) {
            var index = 0,
              newActive;

            if (!that.selectpicker.view.canHighlight[index]) {
              index = 1 + that.selectpicker.view.canHighlight.slice(1).indexOf(true);
            }

            newActive = that.selectpicker.view.visibleElements[index];

            if (that.selectpicker.view.currentActive) {
              that.selectpicker.view.currentActive.classList.remove('active');
              if (that.selectpicker.view.currentActive.firstChild) that.selectpicker.view.currentActive.firstChild.classList.remove('active');
            }

            if (newActive) {
              newActive.classList.add('active');
              if (newActive.firstChild) newActive.firstChild.classList.add('active');
            }

            that.activeIndex = (that.selectpicker.current.data[index] || {}).index;
          }
        }

        $(window)
          .off('resize' + EVENT_KEY + '.' + this.selectId + '.createView')
          .on('resize' + EVENT_KEY + '.' + this.selectId + '.createView', function () {
            var isActive = that.$newElement.hasClass(classNames.SHOW);

            if (isActive) scroll(that.$menuInner[0].scrollTop);
          });
      },

      setPlaceholder: function () {
        var updateIndex = false;

        if (this.options.title && !this.multiple) {
          if (!this.selectpicker.view.titleOption) this.selectpicker.view.titleOption = document.createElement('option');

          // this option doesn't create a new <li> element, but does add a new option at the start,
          // so startIndex should increase to prevent having to check every option for the bs-title-option class
          updateIndex = true;

          var element = this.$element[0],
            isSelected = false,
            titleNotAppended = !this.selectpicker.view.titleOption.parentNode;

          if (titleNotAppended) {
            // Use native JS to prepend option (faster)
            this.selectpicker.view.titleOption.className = 'bs-title-option';
            this.selectpicker.view.titleOption.value = '';

            // Check if selected or data-selected attribute is already set on an option. If not, select the titleOption option.
            // the selected item may have been changed by user or programmatically before the bootstrap select plugin runs,
            // if so, the select will have the data-selected attribute
            var $opt = $(element.options[element.selectedIndex]);
            isSelected = $opt.attr('selected') === undefined && this.$element.data('selected') === undefined;
          }

          if (titleNotAppended || this.selectpicker.view.titleOption.index !== 0) {
            element.insertBefore(this.selectpicker.view.titleOption, element.firstChild);
          }

          // Set selected *after* appending to select,
          // otherwise the option doesn't get selected in IE
          // set using selectedIndex, as setting the selected attr to true here doesn't work in IE11
          if (isSelected) element.selectedIndex = 0;
        }

        return updateIndex;
      },

      createLi: function () {
        var that = this,
          iconBase = this.options.iconBase,
          optionSelector = ':not([hidden]):not([data-hidden="true"])',
          mainElements = [],
          mainData = [],
          widestOptionLength = 0,
          optID = 0,
          startIndex = this.setPlaceholder() ? 1 : 0; // append the titleOption if necessary and skip the first option in the loop

        if (this.options.hideDisabled) optionSelector += ':not(:disabled)';

        if ((that.options.showTick || that.multiple) && !elementTemplates.checkMark.parentNode) {
          elementTemplates.checkMark.className = iconBase + ' ' + that.options.tickIcon + ' check-mark';
          elementTemplates.a.appendChild(elementTemplates.checkMark);
        }

        var selectOptions = this.$element[0].querySelectorAll('select > *' + optionSelector);

        function addDivider (config) {
          var previousData = mainData[mainData.length - 1];

          // ensure optgroup doesn't create back-to-back dividers
          if (
            previousData &&
            previousData.type === 'divider' &&
            (previousData.optID || config.optID)
          ) {
            return;
          }

          config = config || {};
          config.type = 'divider';

          mainElements.push(
            generateOption.li(
              false,
              classNames.DIVIDER,
              (config.optID ? config.optID + 'div' : undefined)
            )
          );

          mainData.push(config);
        }

        function addOption (option, config) {
          config = config || {};

          config.divider = option.getAttribute('data-divider') === 'true';

          if (config.divider) {
            addDivider({
              optID: config.optID
            });
          } else {
            var liIndex = mainData.length,
              cssText = option.style.cssText,
              inlineStyle = cssText ? htmlEscape(cssText) : '',
              optionClass = (option.className || '') + (config.optgroupClass || '');

            if (config.optID) optionClass = 'opt ' + optionClass;

            config.text = option.textContent;

            config.content = option.getAttribute('data-content');
            config.tokens = option.getAttribute('data-tokens');
            config.subtext = option.getAttribute('data-subtext');
            config.icon = option.getAttribute('data-icon');
            config.iconBase = iconBase;

            var textElement = generateOption.text(config);

            mainElements.push(
              generateOption.li(
                generateOption.a(
                  textElement,
                  optionClass,
                  inlineStyle
                ),
                '',
                config.optID
              )
            );

            option.liIndex = liIndex;

            config.display = config.content || config.text;
            config.type = 'option';
            config.index = liIndex;
            config.option = option;
            config.disabled = config.disabled || option.disabled;

            mainData.push(config);

            var combinedLength = 0;

            // count the number of characters in the option - not perfect, but should work in most cases
            if (config.display) combinedLength += config.display.length;
            if (config.subtext) combinedLength += config.subtext.length;
            // if there is an icon, ensure this option's width is checked
            if (config.icon) combinedLength += 1;

            if (combinedLength > widestOptionLength) {
              widestOptionLength = combinedLength;

              // guess which option is the widest
              // use this when calculating menu width
              // not perfect, but it's fast, and the width will be updating accordingly when scrolling
              that.selectpicker.view.widestOption = mainElements[mainElements.length - 1];
            }
          }
        }

        function addOptgroup (index, selectOptions) {
          var optgroup = selectOptions[index],
            previous = selectOptions[index - 1],
            next = selectOptions[index + 1],
            options = optgroup.querySelectorAll('option' + optionSelector);

          if (!options.length) return;

          var config = {
              label: htmlEscape(optgroup.label),
              subtext: optgroup.getAttribute('data-subtext'),
              icon: optgroup.getAttribute('data-icon'),
              iconBase: iconBase
            },
            optgroupClass = ' ' + (optgroup.className || ''),
            headerIndex,
            lastIndex;

          optID++;

          var liIndex = mainData.length,
            cssText = optgroup.style.cssText,
            inlineStyle = cssText ? htmlEscape(cssText) : '',
            optionClass = (optgroup.className || '') + (config.optgroupClass || '');

          if (previous) {
            addDivider({ optID: optID });
          }

          var labelElement = generateOption.label(config);

          mainElements.push(
            generateOption.li(
              generateOption.a(
              labelElement,
              optionClass,
              inlineStyle
            ), 'dropdown-header' + optgroupClass, optID)
          );

          mainData.push({
            display: config.label,
            subtext: config.subtext,
            type: 'optgroup-label',
            optID: optID
          });

          for (var j = 0, len = options.length; j < len; j++) {
            var option = options[j];

            if (j === 0) {
              headerIndex = mainData.length - 1;
              lastIndex = headerIndex + len;
            }

            addOption(option, {
              headerIndex: headerIndex,
              lastIndex: lastIndex,
              optID: optID,
              optgroupClass: optgroupClass,
              disabled: optgroup.disabled
            });
          }

          if (next) {
            addDivider({ optID: optID });
          }
        }

        for (var len = selectOptions.length; startIndex < len; startIndex++) {
          var item = selectOptions[startIndex];

          if (item.tagName !== 'OPTGROUP') {
            addOption(item, {});
          } else {
            addOptgroup(startIndex, selectOptions);
          }
        }

        this.selectpicker.main.elements = mainElements;
        this.selectpicker.main.data = mainData;

        this.selectpicker.current = this.selectpicker.main;
      },

      findLis: function () {
        return this.$menuInner.find('.inner > li');
      },

      render: function () {
        // ensure titleOption is appended and selected (if necessary) before getting selectedOptions
        this.setPlaceholder();

        var that = this,
          selectedOptions = this.$element[0].selectedOptions,
          selectedCount = selectedOptions.length,
          button = this.$button[0],
          buttonInner = button.querySelector('.filter-option-inner-inner'),
          multipleSeparator = document.createTextNode(this.options.multipleSeparator),
          titleFragment = elementTemplates.fragment.cloneNode(false),
          showCount,
          countMax,
          hasContent = false;

        this.togglePlaceholder();

        this.tabIndex();

        if (this.options.selectedTextFormat === 'static') {
          titleFragment = generateOption.text({ text: this.options.title }, true);
        } else {
          showCount = this.multiple && this.options.selectedTextFormat.indexOf('count') !== -1 && selectedCount > 1;

          // determine if the number of selected options will be shown (showCount === true)
          if (showCount) {
            countMax = this.options.selectedTextFormat.split('>');
            showCount = (countMax.length > 1 && selectedCount > countMax[1]) || (countMax.length === 1 && selectedCount >= 2);
          }

          // only loop through all selected options if the count won't be shown
          if (showCount === false) {
            for (var selectedIndex = 0; selectedIndex < selectedCount; selectedIndex++) {
              if (selectedIndex < 50) {
                var option = selectedOptions[selectedIndex],
                  titleOptions = {},
                  thisData = {
                    content: option.getAttribute('data-content'),
                    subtext: option.getAttribute('data-subtext'),
                    icon: option.getAttribute('data-icon')
                  };

                if (this.multiple && selectedIndex > 0) {
                  titleFragment.appendChild(multipleSeparator.cloneNode(false));
                }

                if (option.title) {
                  titleOptions.text = option.title;
                } else if (thisData.content && that.options.showContent) {
                  titleOptions.content = thisData.content.toString();
                  hasContent = true;
                } else {
                  if (that.options.showIcon) {
                    titleOptions.icon = thisData.icon;
                    titleOptions.iconBase = this.options.iconBase;
                  }
                  if (that.options.showSubtext && !that.multiple && thisData.subtext) titleOptions.subtext = ' ' + thisData.subtext;
                  titleOptions.text = option.textContent.trim();
                }

                titleFragment.appendChild(generateOption.text(titleOptions, true));
              } else {
                break;
              }
            }

            // add ellipsis
            if (selectedCount > 49) {
              titleFragment.appendChild(document.createTextNode('...'));
            }
          } else {
            var optionSelector = ':not([hidden]):not([data-hidden="true"]):not([data-divider="true"])';
            if (this.options.hideDisabled) optionSelector += ':not(:disabled)';

            // If this is a multiselect, and selectedTextFormat is count, then show 1 of 2 selected, etc.
            var totalCount = this.$element[0].querySelectorAll('select > option' + optionSelector + ', optgroup' + optionSelector + ' option' + optionSelector).length,
              tr8nText = (typeof this.options.countSelectedText === 'function') ? this.options.countSelectedText(selectedCount, totalCount) : this.options.countSelectedText;

            titleFragment = generateOption.text({
              text: tr8nText.replace('{0}', selectedCount.toString()).replace('{1}', totalCount.toString())
            }, true);
          }
        }

        if (this.options.title == undefined) {
          // use .attr to ensure undefined is returned if title attribute is not set
          this.options.title = this.$element.attr('title');
        }

        // If the select doesn't have a title, then use the default, or if nothing is set at all, use noneSelectedText
        if (!titleFragment.childNodes.length) {
          titleFragment = generateOption.text({
            text: typeof this.options.title !== 'undefined' ? this.options.title : this.options.noneSelectedText
          }, true);
        }

        // strip all HTML tags and trim the result, then unescape any escaped tags
        button.title = titleFragment.textContent.replace(/<[^>]*>?/g, '').trim();

        if (this.options.sanitize && hasContent) {
          sanitizeHtml([titleFragment], that.options.whiteList, that.options.sanitizeFn);
        }

        buttonInner.innerHTML = '';
        buttonInner.appendChild(titleFragment);

        if (version.major < 4 && this.$newElement[0].classList.contains('bs3-has-addon')) {
          var filterExpand = button.querySelector('.filter-expand'),
            clone = buttonInner.cloneNode(true);

          clone.className = 'filter-expand';

          if (filterExpand) {
            button.replaceChild(clone, filterExpand);
          } else {
            button.appendChild(clone);
          }
        }

        this.$element.trigger('rendered' + EVENT_KEY);
      },

      /**
       * @param [style]
       * @param [status]
       */
      setStyle: function (newStyle, status) {
        var button = this.$button[0],
          newElement = this.$newElement[0],
          style = this.options.style.trim(),
          buttonClass;

        if (this.$element.attr('class')) {
          this.$newElement.addClass(this.$element.attr('class').replace(/selectpicker|mobile-device|bs-select-hidden|validate\[.*\]/gi, ''));
        }

        if (version.major < 4) {
          newElement.classList.add('bs3');

          if (newElement.parentNode.classList.contains('input-group') &&
            (newElement.previousElementSibling || newElement.nextElementSibling) &&
            (newElement.previousElementSibling || newElement.nextElementSibling).classList.contains('input-group-addon')
          ) {
            newElement.classList.add('bs3-has-addon');
          }
        }

        if (newStyle) {
          buttonClass = newStyle.trim();
        } else {
          buttonClass = style;
        }

        if (status == 'add') {
          if (buttonClass) button.classList.add.apply(button.classList, buttonClass.split(' '));
        } else if (status == 'remove') {
          if (buttonClass) button.classList.remove.apply(button.classList, buttonClass.split(' '));
        } else {
          if (style) button.classList.remove.apply(button.classList, style.split(' '));
          if (buttonClass) button.classList.add.apply(button.classList, buttonClass.split(' '));
        }
      },

      liHeight: function (refresh) {
        if (!refresh && (this.options.size === false || this.sizeInfo)) return;

        if (!this.sizeInfo) this.sizeInfo = {};

        var newElement = document.createElement('div'),
          menu = document.createElement('div'),
          menuInner = document.createElement('div'),
          menuInnerInner = document.createElement('ul'),
          divider = document.createElement('li'),
          dropdownHeader = document.createElement('li'),
          li = document.createElement('li'),
          a = document.createElement('a'),
          text = document.createElement('span'),
          header = this.options.header && this.$menu.find('.' + classNames.POPOVERHEADER).length > 0 ? this.$menu.find('.' + classNames.POPOVERHEADER)[0].cloneNode(true) : null,
          search = this.options.liveSearch ? document.createElement('div') : null,
          actions = this.options.actionsBox && this.multiple && this.$menu.find('.bs-actionsbox').length > 0 ? this.$menu.find('.bs-actionsbox')[0].cloneNode(true) : null,
          doneButton = this.options.doneButton && this.multiple && this.$menu.find('.bs-donebutton').length > 0 ? this.$menu.find('.bs-donebutton')[0].cloneNode(true) : null,
          firstOption = this.$element.find('option')[0];

        this.sizeInfo.selectWidth = this.$newElement[0].offsetWidth;

        text.className = 'text';
        a.className = 'dropdown-item ' + (firstOption ? firstOption.className : '');
        newElement.className = this.$menu[0].parentNode.className + ' ' + classNames.SHOW;
        newElement.style.width = this.sizeInfo.selectWidth + 'px';
        if (this.options.width === 'auto') menu.style.minWidth = 0;
        menu.className = classNames.MENU + ' ' + classNames.SHOW;
        menuInner.className = 'inner ' + classNames.SHOW;
        menuInnerInner.className = classNames.MENU + ' inner ' + (version.major === '4' ? classNames.SHOW : '');
        divider.className = classNames.DIVIDER;
        dropdownHeader.className = 'dropdown-header';

        text.appendChild(document.createTextNode('\u200b'));
        a.appendChild(text);
        li.appendChild(a);
        dropdownHeader.appendChild(text.cloneNode(true));

        if (this.selectpicker.view.widestOption) {
          menuInnerInner.appendChild(this.selectpicker.view.widestOption.cloneNode(true));
        }

        menuInnerInner.appendChild(li);
        menuInnerInner.appendChild(divider);
        menuInnerInner.appendChild(dropdownHeader);
        if (header) menu.appendChild(header);
        if (search) {
          var input = document.createElement('input');
          search.className = 'bs-searchbox';
          input.className = 'form-control';
          search.appendChild(input);
          menu.appendChild(search);
        }
        if (actions) menu.appendChild(actions);
        menuInner.appendChild(menuInnerInner);
        menu.appendChild(menuInner);
        if (doneButton) menu.appendChild(doneButton);
        newElement.appendChild(menu);

        document.body.appendChild(newElement);

        var liHeight = li.offsetHeight,
          dropdownHeaderHeight = dropdownHeader ? dropdownHeader.offsetHeight : 0,
          headerHeight = header ? header.offsetHeight : 0,
          searchHeight = search ? search.offsetHeight : 0,
          actionsHeight = actions ? actions.offsetHeight : 0,
          doneButtonHeight = doneButton ? doneButton.offsetHeight : 0,
          dividerHeight = $(divider).outerHeight(true),
          // fall back to jQuery if getComputedStyle is not supported
          menuStyle = window.getComputedStyle ? window.getComputedStyle(menu) : false,
          menuWidth = menu.offsetWidth,
          $menu = menuStyle ? null : $(menu),
          menuPadding = {
            vert: toInteger(menuStyle ? menuStyle.paddingTop : $menu.css('paddingTop')) +
              toInteger(menuStyle ? menuStyle.paddingBottom : $menu.css('paddingBottom')) +
              toInteger(menuStyle ? menuStyle.borderTopWidth : $menu.css('borderTopWidth')) +
              toInteger(menuStyle ? menuStyle.borderBottomWidth : $menu.css('borderBottomWidth')),
            horiz: toInteger(menuStyle ? menuStyle.paddingLeft : $menu.css('paddingLeft')) +
              toInteger(menuStyle ? menuStyle.paddingRight : $menu.css('paddingRight')) +
              toInteger(menuStyle ? menuStyle.borderLeftWidth : $menu.css('borderLeftWidth')) +
              toInteger(menuStyle ? menuStyle.borderRightWidth : $menu.css('borderRightWidth'))
          },
          menuExtras = {
            vert: menuPadding.vert +
              toInteger(menuStyle ? menuStyle.marginTop : $menu.css('marginTop')) +
              toInteger(menuStyle ? menuStyle.marginBottom : $menu.css('marginBottom')) + 2,
            horiz: menuPadding.horiz +
              toInteger(menuStyle ? menuStyle.marginLeft : $menu.css('marginLeft')) +
              toInteger(menuStyle ? menuStyle.marginRight : $menu.css('marginRight')) + 2
          },
          scrollBarWidth;

        menuInner.style.overflowY = 'scroll';

        scrollBarWidth = menu.offsetWidth - menuWidth;

        document.body.removeChild(newElement);

        this.sizeInfo.liHeight = liHeight;
        this.sizeInfo.dropdownHeaderHeight = dropdownHeaderHeight;
        this.sizeInfo.headerHeight = headerHeight;
        this.sizeInfo.searchHeight = searchHeight;
        this.sizeInfo.actionsHeight = actionsHeight;
        this.sizeInfo.doneButtonHeight = doneButtonHeight;
        this.sizeInfo.dividerHeight = dividerHeight;
        this.sizeInfo.menuPadding = menuPadding;
        this.sizeInfo.menuExtras = menuExtras;
        this.sizeInfo.menuWidth = menuWidth;
        this.sizeInfo.totalMenuWidth = this.sizeInfo.menuWidth;
        this.sizeInfo.scrollBarWidth = scrollBarWidth;
        this.sizeInfo.selectHeight = this.$newElement[0].offsetHeight;

        this.setPositionData();
      },

      getSelectPosition: function () {
        var that = this,
          $window = $(window),
          pos = that.$newElement.offset(),
          $container = $(that.options.container),
          containerPos;

        if (that.options.container && $container.length && !$container.is('body')) {
          containerPos = $container.offset();
          containerPos.top += parseInt($container.css('borderTopWidth'));
          containerPos.left += parseInt($container.css('borderLeftWidth'));
        } else {
          containerPos = { top: 0, left: 0 };
        }

        var winPad = that.options.windowPadding;

        this.sizeInfo.selectOffsetTop = pos.top - containerPos.top - $window.scrollTop();
        this.sizeInfo.selectOffsetBot = $window.height() - this.sizeInfo.selectOffsetTop - this.sizeInfo.selectHeight - containerPos.top - winPad[2];
        this.sizeInfo.selectOffsetLeft = pos.left - containerPos.left - $window.scrollLeft();
        this.sizeInfo.selectOffsetRight = $window.width() - this.sizeInfo.selectOffsetLeft - this.sizeInfo.selectWidth - containerPos.left - winPad[1];
        this.sizeInfo.selectOffsetTop -= winPad[0];
        this.sizeInfo.selectOffsetLeft -= winPad[3];
      },

      setMenuSize: function (isAuto) {
        this.getSelectPosition();

        var selectWidth = this.sizeInfo.selectWidth,
          liHeight = this.sizeInfo.liHeight,
          headerHeight = this.sizeInfo.headerHeight,
          searchHeight = this.sizeInfo.searchHeight,
          actionsHeight = this.sizeInfo.actionsHeight,
          doneButtonHeight = this.sizeInfo.doneButtonHeight,
          divHeight = this.sizeInfo.dividerHeight,
          menuPadding = this.sizeInfo.menuPadding,
          menuInnerHeight,
          menuHeight,
          divLength = 0,
          minHeight,
          _minHeight,
          maxHeight,
          menuInnerMinHeight,
          estimate;

        if (this.options.dropupAuto) {
          // Get the estimated height of the menu without scrollbars.
          // This is useful for smaller menus, where there might be plenty of room
          // below the button without setting dropup, but we can't know
          // the exact height of the menu until createView is called later
          estimate = liHeight * this.selectpicker.current.elements.length + menuPadding.vert;
          this.$newElement.toggleClass(classNames.DROPUP, this.sizeInfo.selectOffsetTop - this.sizeInfo.selectOffsetBot > this.sizeInfo.menuExtras.vert && estimate + this.sizeInfo.menuExtras.vert + 50 > this.sizeInfo.selectOffsetBot);
        }

        if (this.options.size === 'auto') {
          _minHeight = this.selectpicker.current.elements.length > 3 ? this.sizeInfo.liHeight * 3 + this.sizeInfo.menuExtras.vert - 2 : 0;
          menuHeight = this.sizeInfo.selectOffsetBot - this.sizeInfo.menuExtras.vert;
          minHeight = _minHeight + headerHeight + searchHeight + actionsHeight + doneButtonHeight;
          menuInnerMinHeight = Math.max(_minHeight - menuPadding.vert, 0);

          if (this.$newElement.hasClass(classNames.DROPUP)) {
            menuHeight = this.sizeInfo.selectOffsetTop - this.sizeInfo.menuExtras.vert;
          }

          maxHeight = menuHeight;
          menuInnerHeight = menuHeight - headerHeight - searchHeight - actionsHeight - doneButtonHeight - menuPadding.vert;
        } else if (this.options.size && this.options.size != 'auto' && this.selectpicker.current.elements.length > this.options.size) {
          for (var i = 0; i < this.options.size; i++) {
            if (this.selectpicker.current.data[i].type === 'divider') divLength++;
          }

          menuHeight = liHeight * this.options.size + divLength * divHeight + menuPadding.vert;
          menuInnerHeight = menuHeight - menuPadding.vert;
          maxHeight = menuHeight + headerHeight + searchHeight + actionsHeight + doneButtonHeight;
          minHeight = menuInnerMinHeight = '';
        }

        if (this.options.dropdownAlignRight === 'auto') {
          this.$menu.toggleClass(classNames.MENURIGHT, this.sizeInfo.selectOffsetLeft > this.sizeInfo.selectOffsetRight && this.sizeInfo.selectOffsetRight < (this.sizeInfo.totalMenuWidth - selectWidth));
        }

        this.$menu.css({
          'max-height': maxHeight + 'px',
          'overflow': 'hidden',
          'min-height': minHeight + 'px'
        });

        this.$menuInner.css({
          'max-height': menuInnerHeight + 'px',
          'overflow-y': 'auto',
          'min-height': menuInnerMinHeight + 'px'
        });

        // ensure menuInnerHeight is always a positive number to prevent issues calculating chunkSize in createView
        this.sizeInfo.menuInnerHeight = Math.max(menuInnerHeight, 1);

        if (this.selectpicker.current.data.length && this.selectpicker.current.data[this.selectpicker.current.data.length - 1].position > this.sizeInfo.menuInnerHeight) {
          this.sizeInfo.hasScrollBar = true;
          this.sizeInfo.totalMenuWidth = this.sizeInfo.menuWidth + this.sizeInfo.scrollBarWidth;

          this.$menu.css('min-width', this.sizeInfo.totalMenuWidth);
        }

        if (this.dropdown && this.dropdown._popper) this.dropdown._popper.update();
      },

      setSize: function (refresh) {
        this.liHeight(refresh);

        if (this.options.header) this.$menu.css('padding-top', 0);
        if (this.options.size === false) return;

        var that = this,
          $window = $(window),
          selectedIndex,
          offset = 0;

        this.setMenuSize();

        if (this.options.liveSearch) {
          this.$searchbox
            .off('input.setMenuSize propertychange.setMenuSize')
            .on('input.setMenuSize propertychange.setMenuSize', function () {
              return that.setMenuSize();
            });
        }

        if (this.options.size === 'auto') {
          $window
            .off('resize' + EVENT_KEY + '.' + this.selectId + '.setMenuSize' + ' scroll' + EVENT_KEY + '.' + this.selectId + '.setMenuSize')
            .on('resize' + EVENT_KEY + '.' + this.selectId + '.setMenuSize' + ' scroll' + EVENT_KEY + '.' + this.selectId + '.setMenuSize', function () {
              return that.setMenuSize();
            });
        } else if (this.options.size && this.options.size != 'auto' && this.selectpicker.current.elements.length > this.options.size) {
          $window.off('resize' + EVENT_KEY + '.' + this.selectId + '.setMenuSize' + ' scroll' + EVENT_KEY + '.' + this.selectId + '.setMenuSize');
        }

        if (refresh) {
          offset = this.$menuInner[0].scrollTop;
        } else if (!that.multiple) {
          var element = that.$element[0];
          selectedIndex = (element.options[element.selectedIndex] || {}).liIndex;

          if (typeof selectedIndex === 'number' && that.options.size !== false) {
            offset = that.sizeInfo.liHeight * selectedIndex;
            offset = offset - (that.sizeInfo.menuInnerHeight / 2) + (that.sizeInfo.liHeight / 2);
          }
        }

        that.createView(false, offset);
      },

      setWidth: function () {
        var that = this;

        if (this.options.width === 'auto') {
          requestAnimationFrame(function () {
            that.$menu.css('min-width', '0');

            that.$element.on('loaded' + EVENT_KEY, function () {
              that.liHeight();
              that.setMenuSize();

              // Get correct width if element is hidden
              var $selectClone = that.$newElement.clone().appendTo('body'),
                btnWidth = $selectClone.css('width', 'auto').children('button').outerWidth();

              $selectClone.remove();

              // Set width to whatever's larger, button title or longest option
              that.sizeInfo.selectWidth = Math.max(that.sizeInfo.totalMenuWidth, btnWidth);
              that.$newElement.css('width', that.sizeInfo.selectWidth + 'px');
            });
          });
        } else if (this.options.width === 'fit') {
          // Remove inline min-width so width can be changed from 'auto'
          this.$menu.css('min-width', '');
          this.$newElement.css('width', '').addClass('fit-width');
        } else if (this.options.width) {
          // Remove inline min-width so width can be changed from 'auto'
          this.$menu.css('min-width', '');
          this.$newElement.css('width', this.options.width);
        } else {
          // Remove inline min-width/width so width can be changed
          this.$menu.css('min-width', '');
          this.$newElement.css('width', '');
        }
        // Remove fit-width class if width is changed programmatically
        if (this.$newElement.hasClass('fit-width') && this.options.width !== 'fit') {
          this.$newElement[0].classList.remove('fit-width');
        }
      },

      selectPosition: function () {
        this.$bsContainer = $('<div class="bs-container" />');

        var that = this,
          $container = $(this.options.container),
          pos,
          containerPos,
          actualHeight,
          getPlacement = function ($element) {
            var containerPosition = {},
              // fall back to dropdown's default display setting if display is not manually set
              display = that.options.display || (
                // Bootstrap 3 doesn't have $.fn.dropdown.Constructor.Default
                $.fn.dropdown.Constructor.Default ? $.fn.dropdown.Constructor.Default.display
                  : false
              );

            that.$bsContainer.addClass($element.attr('class').replace(/form-control|fit-width/gi, '')).toggleClass(classNames.DROPUP, $element.hasClass(classNames.DROPUP));
            pos = $element.offset();

            if (!$container.is('body')) {
              containerPos = $container.offset();
              containerPos.top += parseInt($container.css('borderTopWidth')) - $container.scrollTop();
              containerPos.left += parseInt($container.css('borderLeftWidth')) - $container.scrollLeft();
            } else {
              containerPos = { top: 0, left: 0 };
            }

            actualHeight = $element.hasClass(classNames.DROPUP) ? 0 : $element[0].offsetHeight;

            // Bootstrap 4+ uses Popper for menu positioning
            if (version.major < 4 || display === 'static') {
              containerPosition.top = pos.top - containerPos.top + actualHeight;
              containerPosition.left = pos.left - containerPos.left;
            }

            containerPosition.width = $element[0].offsetWidth;

            that.$bsContainer.css(containerPosition);
          };

        this.$button.on('click.bs.dropdown.data-api', function () {
          if (that.isDisabled()) {
            return;
          }

          getPlacement(that.$newElement);

          that.$bsContainer
            .appendTo(that.options.container)
            .toggleClass(classNames.SHOW, !that.$button.hasClass(classNames.SHOW))
            .append(that.$menu);
        });

        $(window)
          .off('resize' + EVENT_KEY + '.' + this.selectId + ' scroll' + EVENT_KEY + '.' + this.selectId)
          .on('resize' + EVENT_KEY + '.' + this.selectId + ' scroll' + EVENT_KEY + '.' + this.selectId, function () {
            var isActive = that.$newElement.hasClass(classNames.SHOW);

            if (isActive) getPlacement(that.$newElement);
          });

        this.$element.on('hide' + EVENT_KEY, function () {
          that.$menu.data('height', that.$menu.height());
          that.$bsContainer.detach();
        });
      },

      setOptionStatus: function () {
        var that = this;

        that.noScroll = false;

        if (that.selectpicker.view.visibleElements && that.selectpicker.view.visibleElements.length) {
          for (var i = 0; i < that.selectpicker.view.visibleElements.length; i++) {
            var liData = that.selectpicker.current.data[i + that.selectpicker.view.position0],
              option = liData.option;

            if (option) {
              that.setDisabled(
                liData.index,
                liData.disabled
              );

              that.setSelected(
                liData.index,
                option.selected
              );
            }
          }
        }
      },

      /**
       * @param {number} index - the index of the option that is being changed
       * @param {boolean} selected - true if the option is being selected, false if being deselected
       */
      setSelected: function (index, selected) {
        var li = this.selectpicker.main.elements[index],
          liData = this.selectpicker.main.data[index],
          activeIndexIsSet = this.activeIndex !== undefined,
          thisIsActive = this.activeIndex === index,
          prevActive,
          a,
          // if current option is already active
          // OR
          // if the current option is being selected, it's NOT multiple, and
          // activeIndex is undefined:
          //  - when the menu is first being opened, OR
          //  - after a search has been performed, OR
          //  - when retainActive is false when selecting a new option (i.e. index of the newly selected option is not the same as the current activeIndex)
          keepActive = thisIsActive || (selected && !this.multiple && !activeIndexIsSet);

        liData.selected = selected;

        a = li.firstChild;

        if (selected) {
          this.selectedIndex = index;
        }

        li.classList.toggle('selected', selected);
        li.classList.toggle('active', keepActive);

        if (keepActive) {
          this.selectpicker.view.currentActive = li;
          this.activeIndex = index;
        }

        if (a) {
          a.classList.toggle('selected', selected);
          a.classList.toggle('active', keepActive);
          a.setAttribute('aria-selected', selected);
        }

        if (!keepActive) {
          if (!activeIndexIsSet && selected && this.prevActiveIndex !== undefined) {
            prevActive = this.selectpicker.main.elements[this.prevActiveIndex];

            prevActive.classList.remove('active');
            if (prevActive.firstChild) {
              prevActive.firstChild.classList.remove('active');
            }
          }
        }
      },

      /**
       * @param {number} index - the index of the option that is being disabled
       * @param {boolean} disabled - true if the option is being disabled, false if being enabled
       */
      setDisabled: function (index, disabled) {
        var li = this.selectpicker.main.elements[index],
          a;

        this.selectpicker.main.data[index].disabled = disabled;

        a = li.firstChild;

        li.classList.toggle(classNames.DISABLED, disabled);

        if (a) {
          if (version.major === '4') a.classList.toggle(classNames.DISABLED, disabled);

          a.setAttribute('aria-disabled', disabled);

          if (disabled) {
            a.setAttribute('tabindex', -1);
          } else {
            a.setAttribute('tabindex', 0);
          }
        }
      },

      isDisabled: function () {
        return this.$element[0].disabled;
      },

      checkDisabled: function () {
        var that = this;

        if (this.isDisabled()) {
          this.$newElement[0].classList.add(classNames.DISABLED);
          this.$button.addClass(classNames.DISABLED).attr('tabindex', -1).attr('aria-disabled', true);
        } else {
          if (this.$button[0].classList.contains(classNames.DISABLED)) {
            this.$newElement[0].classList.remove(classNames.DISABLED);
            this.$button.removeClass(classNames.DISABLED).attr('aria-disabled', false);
          }

          if (this.$button.attr('tabindex') == -1 && !this.$element.data('tabindex')) {
            this.$button.removeAttr('tabindex');
          }
        }

        this.$button.on('click', function () {
          return !that.isDisabled();
        });
      },

      togglePlaceholder: function () {
        // much faster than calling $.val()
        var element = this.$element[0],
          selectedIndex = element.selectedIndex,
          nothingSelected = selectedIndex === -1;

        if (!nothingSelected && !element.options[selectedIndex].value) nothingSelected = true;

        this.$button.toggleClass('bs-placeholder', nothingSelected);
      },

      tabIndex: function () {
        if (this.$element.data('tabindex') !== this.$element.attr('tabindex') &&
          (this.$element.attr('tabindex') !== -98 && this.$element.attr('tabindex') !== '-98')) {
          this.$element.data('tabindex', this.$element.attr('tabindex'));
          this.$button.attr('tabindex', this.$element.data('tabindex'));
        }

        this.$element.attr('tabindex', -98);
      },

      clickListener: function () {
        var that = this,
          $document = $(document);

        $document.data('spaceSelect', false);

        this.$button.on('keyup', function (e) {
          if (/(32)/.test(e.keyCode.toString(10)) && $document.data('spaceSelect')) {
            e.preventDefault();
            $document.data('spaceSelect', false);
          }
        });

        this.$newElement.on('show.bs.dropdown', function () {
          if (version.major > 3 && !that.dropdown) {
            that.dropdown = that.$button.data('bs.dropdown');
            that.dropdown._menu = that.$menu[0];
          }
        });

        this.$button.on('click.bs.dropdown.data-api', function () {
          if (!that.$newElement.hasClass(classNames.SHOW)) {
            that.setSize();
          }
        });

        function setFocus () {
          if (that.options.liveSearch) {
            that.$searchbox.trigger('focus');
          } else {
            that.$menuInner.trigger('focus');
          }
        }

        function checkPopperExists () {
          if (that.dropdown && that.dropdown._popper && that.dropdown._popper.state.isCreated) {
            setFocus();
          } else {
            requestAnimationFrame(checkPopperExists);
          }
        }

        this.$element.on('shown' + EVENT_KEY, function () {
          if (that.$menuInner[0].scrollTop !== that.selectpicker.view.scrollTop) {
            that.$menuInner[0].scrollTop = that.selectpicker.view.scrollTop;
          }

          if (version.major > 3) {
            requestAnimationFrame(checkPopperExists);
          } else {
            setFocus();
          }
        });

        this.$menuInner.on('click', 'li a', function (e, retainActive) {
          var $this = $(this),
            position0 = that.isVirtual() ? that.selectpicker.view.position0 : 0,
            clickedData = that.selectpicker.current.data[$this.parent().index() + position0],
            clickedIndex = clickedData.index,
            prevValue = getSelectValues(that.$element[0]),
            prevIndex = that.$element.prop('selectedIndex'),
            triggerChange = true;

          // Don't close on multi choice menu
          if (that.multiple && that.options.maxOptions !== 1) {
            e.stopPropagation();
          }

          e.preventDefault();

          // Don't run if the select is disabled
          if (!that.isDisabled() && !$this.parent().hasClass(classNames.DISABLED)) {
            var $options = that.$element.find('option'),
              option = clickedData.option,
              $option = $(option),
              state = option.selected,
              $optgroup = $option.parent('optgroup'),
              $optgroupOptions = $optgroup.find('option'),
              maxOptions = that.options.maxOptions,
              maxOptionsGrp = $optgroup.data('maxOptions') || false;

            if (clickedIndex === that.activeIndex) retainActive = true;

            if (!retainActive) {
              that.prevActiveIndex = that.activeIndex;
              that.activeIndex = undefined;
            }

            if (!that.multiple) { // Deselect all others if not multi select box
              $options.prop('selected', false);
              option.selected = true;
              that.setSelected(clickedIndex, true);
            } else { // Toggle the one we have chosen if we are multi select.
              option.selected = !state;

              that.setSelected(clickedIndex, !state);
              $this.trigger('blur');

              if (maxOptions !== false || maxOptionsGrp !== false) {
                var maxReached = maxOptions < $options.filter(':selected').length,
                  maxReachedGrp = maxOptionsGrp < $optgroup.find('option:selected').length;

                if ((maxOptions && maxReached) || (maxOptionsGrp && maxReachedGrp)) {
                  if (maxOptions && maxOptions == 1) {
                    $options.prop('selected', false);
                    $option.prop('selected', true);

                    for (var i = 0; i < $options.length; i++) {
                      that.setSelected(i, false);
                    }

                    that.setSelected(clickedIndex, true);
                  } else if (maxOptionsGrp && maxOptionsGrp == 1) {
                    $optgroup.find('option:selected').prop('selected', false);
                    $option.prop('selected', true);

                    for (var i = 0; i < $optgroupOptions.length; i++) {
                      var option = $optgroupOptions[i];
                      that.setSelected($options.index(option), false);
                    }

                    that.setSelected(clickedIndex, true);
                  } else {
                    var maxOptionsText = typeof that.options.maxOptionsText === 'string' ? [that.options.maxOptionsText, that.options.maxOptionsText] : that.options.maxOptionsText,
                      maxOptionsArr = typeof maxOptionsText === 'function' ? maxOptionsText(maxOptions, maxOptionsGrp) : maxOptionsText,
                      maxTxt = maxOptionsArr[0].replace('{n}', maxOptions),
                      maxTxtGrp = maxOptionsArr[1].replace('{n}', maxOptionsGrp),
                      $notify = $('<div class="notify"></div>');
                    // If {var} is set in array, replace it
                    /** @deprecated */
                    if (maxOptionsArr[2]) {
                      maxTxt = maxTxt.replace('{var}', maxOptionsArr[2][maxOptions > 1 ? 0 : 1]);
                      maxTxtGrp = maxTxtGrp.replace('{var}', maxOptionsArr[2][maxOptionsGrp > 1 ? 0 : 1]);
                    }

                    $option.prop('selected', false);

                    that.$menu.append($notify);

                    if (maxOptions && maxReached) {
                      $notify.append($('<div>' + maxTxt + '</div>'));
                      triggerChange = false;
                      that.$element.trigger('maxReached' + EVENT_KEY);
                    }

                    if (maxOptionsGrp && maxReachedGrp) {
                      $notify.append($('<div>' + maxTxtGrp + '</div>'));
                      triggerChange = false;
                      that.$element.trigger('maxReachedGrp' + EVENT_KEY);
                    }

                    setTimeout(function () {
                      that.setSelected(clickedIndex, false);
                    }, 10);

                    $notify.delay(750).fadeOut(300, function () {
                      $(this).remove();
                    });
                  }
                }
              }
            }

            if (!that.multiple || (that.multiple && that.options.maxOptions === 1)) {
              that.$button.trigger('focus');
            } else if (that.options.liveSearch) {
              that.$searchbox.trigger('focus');
            }

            // Trigger select 'change'
            if (triggerChange) {
              if ((prevValue != getSelectValues(that.$element[0]) && that.multiple) || (prevIndex != that.$element.prop('selectedIndex') && !that.multiple)) {
                // $option.prop('selected') is current option state (selected/unselected). prevValue is the value of the select prior to being changed.
                changedArguments = [option.index, $option.prop('selected'), prevValue];
                that.$element
                  .triggerNative('change');
              }
            }
          }
        });

        this.$menu.on('click', 'li.' + classNames.DISABLED + ' a, .' + classNames.POPOVERHEADER + ', .' + classNames.POPOVERHEADER + ' :not(.close)', function (e) {
          if (e.currentTarget == this) {
            e.preventDefault();
            e.stopPropagation();
            if (that.options.liveSearch && !$(e.target).hasClass('close')) {
              that.$searchbox.trigger('focus');
            } else {
              that.$button.trigger('focus');
            }
          }
        });

        this.$menuInner.on('click', '.divider, .dropdown-header', function (e) {
          e.preventDefault();
          e.stopPropagation();
          if (that.options.liveSearch) {
            that.$searchbox.trigger('focus');
          } else {
            that.$button.trigger('focus');
          }
        });

        this.$menu.on('click', '.' + classNames.POPOVERHEADER + ' .close', function () {
          that.$button.trigger('click');
        });

        this.$searchbox.on('click', function (e) {
          e.stopPropagation();
        });

        this.$menu.on('click', '.actions-btn', function (e) {
          if (that.options.liveSearch) {
            that.$searchbox.trigger('focus');
          } else {
            that.$button.trigger('focus');
          }

          e.preventDefault();
          e.stopPropagation();

          if ($(this).hasClass('bs-select-all')) {
            that.selectAll();
          } else {
            that.deselectAll();
          }
        });

        this.$element
          .on('change' + EVENT_KEY, function () {
            that.render();
            that.$element.trigger('changed' + EVENT_KEY, changedArguments);
            changedArguments = null;
          })
          .on('focus' + EVENT_KEY, function () {
            if (!that.options.mobile) that.$button.trigger('focus');
          });
      },

      liveSearchListener: function () {
        var that = this,
          noResults = document.createElement('li');

        this.$button.on('click.bs.dropdown.data-api', function () {
          if (!!that.$searchbox.val()) {
            that.$searchbox.val('');
          }
        });

        this.$searchbox.on('click.bs.dropdown.data-api focus.bs.dropdown.data-api touchend.bs.dropdown.data-api', function (e) {
          e.stopPropagation();
        });

        this.$searchbox.on('input propertychange', function () {
          var searchValue = that.$searchbox.val();

          that.selectpicker.search.elements = [];
          that.selectpicker.search.data = [];

          if (searchValue) {
            var i,
              searchMatch = [],
              q = searchValue.toUpperCase(),
              cache = {},
              cacheArr = [],
              searchStyle = that._searchStyle(),
              normalizeSearch = that.options.liveSearchNormalize;

            if (normalizeSearch) q = normalizeToBase(q);

            that._$lisSelected = that.$menuInner.find('.selected');

            for (var i = 0; i < that.selectpicker.main.data.length; i++) {
              var li = that.selectpicker.main.data[i];

              if (!cache[i]) {
                cache[i] = stringSearch(li, q, searchStyle, normalizeSearch);
              }

              if (cache[i] && li.headerIndex !== undefined && cacheArr.indexOf(li.headerIndex) === -1) {
                if (li.headerIndex > 0) {
                  cache[li.headerIndex - 1] = true;
                  cacheArr.push(li.headerIndex - 1);
                }

                cache[li.headerIndex] = true;
                cacheArr.push(li.headerIndex);

                cache[li.lastIndex + 1] = true;
              }

              if (cache[i] && li.type !== 'optgroup-label') cacheArr.push(i);
            }

            for (var i = 0, cacheLen = cacheArr.length; i < cacheLen; i++) {
              var index = cacheArr[i],
                prevIndex = cacheArr[i - 1],
                li = that.selectpicker.main.data[index],
                liPrev = that.selectpicker.main.data[prevIndex];

              if (li.type !== 'divider' || (li.type === 'divider' && liPrev && liPrev.type !== 'divider' && cacheLen - 1 !== i)) {
                that.selectpicker.search.data.push(li);
                searchMatch.push(that.selectpicker.main.elements[index]);
              }
            }

            that.activeIndex = undefined;
            that.noScroll = true;
            that.$menuInner.scrollTop(0);
            that.selectpicker.search.elements = searchMatch;
            that.createView(true);

            if (!searchMatch.length) {
              noResults.className = 'no-results';
              noResults.innerHTML = that.options.noneResultsText.replace('{0}', '"' + htmlEscape(searchValue) + '"');
              that.$menuInner[0].firstChild.appendChild(noResults);
            }
          } else {
            that.$menuInner.scrollTop(0);
            that.createView(false);
          }
        });
      },

      _searchStyle: function () {
        return this.options.liveSearchStyle || 'contains';
      },

      val: function (value) {
        if (typeof value !== 'undefined') {
          var prevValue = getSelectValues(this.$element[0]);

          changedArguments = [null, null, prevValue];

          this.$element
            .val(value)
            .trigger('changed' + EVENT_KEY, changedArguments);

          this.render();

          changedArguments = null;

          return this.$element;
        } else {
          return this.$element.val();
        }
      },

      changeAll: function (status) {
        if (!this.multiple) return;
        if (typeof status === 'undefined') status = true;

        var element = this.$element[0],
          previousSelected = 0,
          currentSelected = 0,
          prevValue = getSelectValues(element);

        element.classList.add('bs-select-hidden');

        for (var i = 0, len = this.selectpicker.current.elements.length; i < len; i++) {
          var liData = this.selectpicker.current.data[i],
            option = liData.option;

          if (option && !liData.disabled && liData.type !== 'divider') {
            if (liData.selected) previousSelected++;
            option.selected = status;
            if (status) currentSelected++;
          }
        }

        element.classList.remove('bs-select-hidden');

        if (previousSelected === currentSelected) return;

        this.setOptionStatus();

        this.togglePlaceholder();

        changedArguments = [null, null, prevValue];

        this.$element
          .triggerNative('change');
      },

      selectAll: function () {
        return this.changeAll(true);
      },

      deselectAll: function () {
        return this.changeAll(false);
      },

      toggle: function (e) {
        e = e || window.event;

        if (e) e.stopPropagation();

        this.$button.trigger('click.bs.dropdown.data-api');
      },

      keydown: function (e) {
        var $this = $(this),
          isToggle = $this.hasClass('dropdown-toggle'),
          $parent = isToggle ? $this.closest('.dropdown') : $this.closest(Selector.MENU),
          that = $parent.data('this'),
          $items = that.findLis(),
          index,
          isActive,
          liActive,
          activeLi,
          offset,
          updateScroll = false,
          downOnTab = e.which === keyCodes.TAB && !isToggle && !that.options.selectOnTab,
          isArrowKey = REGEXP_ARROW.test(e.which) || downOnTab,
          scrollTop = that.$menuInner[0].scrollTop,
          isVirtual = that.isVirtual(),
          position0 = isVirtual === true ? that.selectpicker.view.position0 : 0;

        isActive = that.$newElement.hasClass(classNames.SHOW);

        if (
          !isActive &&
          (
            isArrowKey ||
            (e.which >= 48 && e.which <= 57) ||
            (e.which >= 96 && e.which <= 105) ||
            (e.which >= 65 && e.which <= 90)
          )
        ) {
          that.$button.trigger('click.bs.dropdown.data-api');

          if (that.options.liveSearch) {
            that.$searchbox.trigger('focus');
            return;
          }
        }

        if (e.which === keyCodes.ESCAPE && isActive) {
          e.preventDefault();
          that.$button.trigger('click.bs.dropdown.data-api').trigger('focus');
        }

        if (isArrowKey) { // if up or down
          if (!$items.length) return;

          // $items.index/.filter is too slow with a large list and no virtual scroll
          index = isVirtual === true ? $items.index($items.filter('.active')) : that.activeIndex;

          if (index === undefined) index = -1;

          if (index !== -1) {
            liActive = that.selectpicker.current.elements[index + position0];
            liActive.classList.remove('active');
            if (liActive.firstChild) liActive.firstChild.classList.remove('active');
          }

          if (e.which === keyCodes.ARROW_UP) { // up
            if (index !== -1) index--;
            if (index + position0 < 0) index += $items.length;

            if (!that.selectpicker.view.canHighlight[index + position0]) {
              index = that.selectpicker.view.canHighlight.slice(0, index + position0).lastIndexOf(true) - position0;
              if (index === -1) index = $items.length - 1;
            }
          } else if (e.which === keyCodes.ARROW_DOWN || downOnTab) { // down
            index++;
            if (index + position0 >= that.selectpicker.view.canHighlight.length) index = 0;

            if (!that.selectpicker.view.canHighlight[index + position0]) {
              index = index + 1 + that.selectpicker.view.canHighlight.slice(index + position0 + 1).indexOf(true);
            }
          }

          e.preventDefault();

          var liActiveIndex = position0 + index;

          if (e.which === keyCodes.ARROW_UP) { // up
            // scroll to bottom and highlight last option
            if (position0 === 0 && index === $items.length - 1) {
              that.$menuInner[0].scrollTop = that.$menuInner[0].scrollHeight;

              liActiveIndex = that.selectpicker.current.elements.length - 1;
            } else {
              activeLi = that.selectpicker.current.data[liActiveIndex];
              offset = activeLi.position - activeLi.height;

              updateScroll = offset < scrollTop;
            }
          } else if (e.which === keyCodes.ARROW_DOWN || downOnTab) { // down
            // scroll to top and highlight first option
            if (index === 0) {
              that.$menuInner[0].scrollTop = 0;

              liActiveIndex = 0;
            } else {
              activeLi = that.selectpicker.current.data[liActiveIndex];
              offset = activeLi.position - that.sizeInfo.menuInnerHeight;

              updateScroll = offset > scrollTop;
            }
          }

          liActive = that.selectpicker.current.elements[liActiveIndex];

          if (liActive) {
            liActive.classList.add('active');
            if (liActive.firstChild) liActive.firstChild.classList.add('active');
          }

          that.activeIndex = that.selectpicker.current.data[liActiveIndex].index;

          that.selectpicker.view.currentActive = liActive;

          if (updateScroll) that.$menuInner[0].scrollTop = offset;

          if (that.options.liveSearch) {
            that.$searchbox.trigger('focus');
          } else {
            $this.trigger('focus');
          }
        } else if (
          (!$this.is('input') && !REGEXP_TAB_OR_ESCAPE.test(e.which)) ||
          (e.which === keyCodes.SPACE && that.selectpicker.keydown.keyHistory)
        ) {
          var searchMatch,
            matches = [],
            keyHistory;

          e.preventDefault();

          that.selectpicker.keydown.keyHistory += keyCodeMap[e.which];

          if (that.selectpicker.keydown.resetKeyHistory.cancel) clearTimeout(that.selectpicker.keydown.resetKeyHistory.cancel);
          that.selectpicker.keydown.resetKeyHistory.cancel = that.selectpicker.keydown.resetKeyHistory.start();

          keyHistory = that.selectpicker.keydown.keyHistory;

          // if all letters are the same, set keyHistory to just the first character when searching
          if (/^(.)\1+$/.test(keyHistory)) {
            keyHistory = keyHistory.charAt(0);
          }

          // find matches
          for (var i = 0; i < that.selectpicker.current.data.length; i++) {
            var li = that.selectpicker.current.data[i],
              hasMatch;

            hasMatch = stringSearch(li, keyHistory, 'startsWith', true);

            if (hasMatch && that.selectpicker.view.canHighlight[i]) {
              matches.push(li.index);
            }
          }

          if (matches.length) {
            var matchIndex = 0;

            $items.removeClass('active').find('a').removeClass('active');

            // either only one key has been pressed or they are all the same key
            if (keyHistory.length === 1) {
              matchIndex = matches.indexOf(that.activeIndex);

              if (matchIndex === -1 || matchIndex === matches.length - 1) {
                matchIndex = 0;
              } else {
                matchIndex++;
              }
            }

            searchMatch = matches[matchIndex];

            activeLi = that.selectpicker.main.data[searchMatch];

            if (scrollTop - activeLi.position > 0) {
              offset = activeLi.position - activeLi.height;
              updateScroll = true;
            } else {
              offset = activeLi.position - that.sizeInfo.menuInnerHeight;
              // if the option is already visible at the current scroll position, just keep it the same
              updateScroll = activeLi.position > scrollTop + that.sizeInfo.menuInnerHeight;
            }

            liActive = that.selectpicker.main.elements[searchMatch];
            liActive.classList.add('active');
            if (liActive.firstChild) liActive.firstChild.classList.add('active');
            that.activeIndex = matches[matchIndex];

            liActive.firstChild.focus();

            if (updateScroll) that.$menuInner[0].scrollTop = offset;

            $this.trigger('focus');
          }
        }

        // Select focused option if "Enter", "Spacebar" or "Tab" (when selectOnTab is true) are pressed inside the menu.
        if (
          isActive &&
          (
            (e.which === keyCodes.SPACE && !that.selectpicker.keydown.keyHistory) ||
            e.which === keyCodes.ENTER ||
            (e.which === keyCodes.TAB && that.options.selectOnTab)
          )
        ) {
          if (e.which !== keyCodes.SPACE) e.preventDefault();

          if (!that.options.liveSearch || e.which !== keyCodes.SPACE) {
            that.$menuInner.find('.active a').trigger('click', true); // retain active class
            $this.trigger('focus');

            if (!that.options.liveSearch) {
              // Prevent screen from scrolling if the user hits the spacebar
              e.preventDefault();
              // Fixes spacebar selection of dropdown items in FF & IE
              $(document).data('spaceSelect', true);
            }
          }
        }
      },

      mobile: function () {
        this.$element[0].classList.add('mobile-device');
      },

      refresh: function () {
        // update options if data attributes have been changed
        var config = $.extend({}, this.options, this.$element.data());
        this.options = config;

        this.checkDisabled();
        this.setStyle();
        this.render();
        this.createLi();
        this.setWidth();

        this.setSize(true);

        this.$element.trigger('refreshed' + EVENT_KEY);
      },

      hide: function () {
        this.$newElement.hide();
      },

      show: function () {
        this.$newElement.show();
      },

      remove: function () {
        this.$newElement.remove();
        this.$element.remove();
      },

      destroy: function () {
        this.$newElement.before(this.$element).remove();

        if (this.$bsContainer) {
          this.$bsContainer.remove();
        } else {
          this.$menu.remove();
        }

        this.$element
          .off(EVENT_KEY)
          .removeData('selectpicker')
          .removeClass('bs-select-hidden selectpicker');

        $(window).off(EVENT_KEY + '.' + this.selectId);
      }
    };

    // SELECTPICKER PLUGIN DEFINITION
    // ==============================
    function Plugin (option) {
      // get the args of the outer function..
      var args = arguments;
      // The arguments of the function are explicitly re-defined from the argument list, because the shift causes them
      // to get lost/corrupted in android 2.3 and IE9 #715 #775
      var _option = option;

      [].shift.apply(args);

      // if the version was not set successfully
      if (!version.success) {
        // try to retreive it again
        try {
          version.full = ($.fn.dropdown.Constructor.VERSION || '').split(' ')[0].split('.');
        } catch (err) {
          // fall back to use BootstrapVersion if set
          if (Selectpicker.BootstrapVersion) {
            version.full = Selectpicker.BootstrapVersion.split(' ')[0].split('.');
          } else {
            version.full = [version.major, '0', '0'];

            console.warn(
              'There was an issue retrieving Bootstrap\'s version. ' +
              'Ensure Bootstrap is being loaded before bootstrap-select and there is no namespace collision. ' +
              'If loading Bootstrap asynchronously, the version may need to be manually specified via $.fn.selectpicker.Constructor.BootstrapVersion.',
              err
            );
          }
        }

        version.major = version.full[0];
        version.success = true;
      }

      if (version.major === '4') {
        // some defaults need to be changed if using Bootstrap 4
        // check to see if they have already been manually changed before forcing them to update
        var toUpdate = [];

        if (Selectpicker.DEFAULTS.style === classNames.BUTTONCLASS) toUpdate.push({ name: 'style', className: 'BUTTONCLASS' });
        if (Selectpicker.DEFAULTS.iconBase === classNames.ICONBASE) toUpdate.push({ name: 'iconBase', className: 'ICONBASE' });
        if (Selectpicker.DEFAULTS.tickIcon === classNames.TICKICON) toUpdate.push({ name: 'tickIcon', className: 'TICKICON' });

        classNames.DIVIDER = 'dropdown-divider';
        classNames.SHOW = 'show';
        classNames.BUTTONCLASS = 'btn-light';
        classNames.POPOVERHEADER = 'popover-header';
        classNames.ICONBASE = '';
        classNames.TICKICON = 'bs-ok-default';

        for (var i = 0; i < toUpdate.length; i++) {
          var option = toUpdate[i];
          Selectpicker.DEFAULTS[option.name] = classNames[option.className];
        }
      }

      var value;
      var chain = this.each(function () {
        var $this = $(this);
        if ($this.is('select')) {
          var data = $this.data('selectpicker'),
            options = typeof _option == 'object' && _option;

          if (!data) {
            var dataAttributes = $this.data();

            for (var dataAttr in dataAttributes) {
              if (dataAttributes.hasOwnProperty(dataAttr) && $.inArray(dataAttr, DISALLOWED_ATTRIBUTES) !== -1) {
                delete dataAttributes[dataAttr];
              }
            }

            var config = $.extend({}, Selectpicker.DEFAULTS, $.fn.selectpicker.defaults || {}, dataAttributes, options);
            config.template = $.extend({}, Selectpicker.DEFAULTS.template, ($.fn.selectpicker.defaults ? $.fn.selectpicker.defaults.template : {}), dataAttributes.template, options.template);
            $this.data('selectpicker', (data = new Selectpicker(this, config)));
          } else if (options) {
            for (var i in options) {
              if (options.hasOwnProperty(i)) {
                data.options[i] = options[i];
              }
            }
          }

          if (typeof _option == 'string') {
            if (data[_option] instanceof Function) {
              value = data[_option].apply(data, args);
            } else {
              value = data.options[_option];
            }
          }
        }
      });

      if (typeof value !== 'undefined') {
        // noinspection JSUnusedAssignment
        return value;
      } else {
        return chain;
      }
    }

    var old = $.fn.selectpicker;
    $.fn.selectpicker = Plugin;
    $.fn.selectpicker.Constructor = Selectpicker;

    // SELECTPICKER NO CONFLICT
    // ========================
    $.fn.selectpicker.noConflict = function () {
      $.fn.selectpicker = old;
      return this;
    };

    $(document)
      .off('keydown.bs.dropdown.data-api')
      .on('keydown' + EVENT_KEY, '.bootstrap-select [data-toggle="dropdown"], .bootstrap-select [role="listbox"], .bootstrap-select .bs-searchbox input', Selectpicker.prototype.keydown)
      .on('focusin.modal', '.bootstrap-select [data-toggle="dropdown"], .bootstrap-select [role="listbox"], .bootstrap-select .bs-searchbox input', function (e) {
        e.stopPropagation();
      });

    // SELECTPICKER DATA-API
    // =====================
    $(window).on('load' + EVENT_KEY + '.data-api', function () {
      $('.selectpicker').each(function () {
        var $selectpicker = $(this);
        Plugin.call($selectpicker, $selectpicker.data());
      })
    });
  })(jQuery);


}));
//# sourceMappingURL=bootstrap-select.js.map

/* == jquery mousewheel plugin == Version: 3.1.13, License: MIT License (MIT) */
!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):"object"==typeof exports?module.exports=a:a(jQuery)}(function(a){function b(b){var g=b||window.event,h=i.call(arguments,1),j=0,l=0,m=0,n=0,o=0,p=0;if(b=a.event.fix(g),b.type="mousewheel","detail"in g&&(m=-1*g.detail),"wheelDelta"in g&&(m=g.wheelDelta),"wheelDeltaY"in g&&(m=g.wheelDeltaY),"wheelDeltaX"in g&&(l=-1*g.wheelDeltaX),"axis"in g&&g.axis===g.HORIZONTAL_AXIS&&(l=-1*m,m=0),j=0===m?l:m,"deltaY"in g&&(m=-1*g.deltaY,j=m),"deltaX"in g&&(l=g.deltaX,0===m&&(j=-1*l)),0!==m||0!==l){if(1===g.deltaMode){var q=a.data(this,"mousewheel-line-height");j*=q,m*=q,l*=q}else if(2===g.deltaMode){var r=a.data(this,"mousewheel-page-height");j*=r,m*=r,l*=r}if(n=Math.max(Math.abs(m),Math.abs(l)),(!f||f>n)&&(f=n,d(g,n)&&(f/=40)),d(g,n)&&(j/=40,l/=40,m/=40),j=Math[j>=1?"floor":"ceil"](j/f),l=Math[l>=1?"floor":"ceil"](l/f),m=Math[m>=1?"floor":"ceil"](m/f),k.settings.normalizeOffset&&this.getBoundingClientRect){var s=this.getBoundingClientRect();o=b.clientX-s.left,p=b.clientY-s.top}return b.deltaX=l,b.deltaY=m,b.deltaFactor=f,b.offsetX=o,b.offsetY=p,b.deltaMode=0,h.unshift(b,j,l,m),e&&clearTimeout(e),e=setTimeout(c,200),(a.event.dispatch||a.event.handle).apply(this,h)}}function c(){f=null}function d(a,b){return k.settings.adjustOldDeltas&&"mousewheel"===a.type&&b%120===0}var e,f,g=["wheel","mousewheel","DOMMouseScroll","MozMousePixelScroll"],h="onwheel"in document||document.documentMode>=9?["wheel"]:["mousewheel","DomMouseScroll","MozMousePixelScroll"],i=Array.prototype.slice;if(a.event.fixHooks)for(var j=g.length;j;)a.event.fixHooks[g[--j]]=a.event.mouseHooks;var k=a.event.special.mousewheel={version:"3.1.12",setup:function(){if(this.addEventListener)for(var c=h.length;c;)this.addEventListener(h[--c],b,!1);else this.onmousewheel=b;a.data(this,"mousewheel-line-height",k.getLineHeight(this)),a.data(this,"mousewheel-page-height",k.getPageHeight(this))},teardown:function(){if(this.removeEventListener)for(var c=h.length;c;)this.removeEventListener(h[--c],b,!1);else this.onmousewheel=null;a.removeData(this,"mousewheel-line-height"),a.removeData(this,"mousewheel-page-height")},getLineHeight:function(b){var c=a(b),d=c["offsetParent"in a.fn?"offsetParent":"parent"]();return d.length||(d=a("body")),parseInt(d.css("fontSize"),10)||parseInt(c.css("fontSize"),10)||16},getPageHeight:function(b){return a(b).height()},settings:{adjustOldDeltas:!0,normalizeOffset:!0}};a.fn.extend({mousewheel:function(a){return a?this.bind("mousewheel",a):this.trigger("mousewheel")},unmousewheel:function(a){return this.unbind("mousewheel",a)}})});!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):"object"==typeof exports?module.exports=a:a(jQuery)}(function(a){function b(b){var g=b||window.event,h=i.call(arguments,1),j=0,l=0,m=0,n=0,o=0,p=0;if(b=a.event.fix(g),b.type="mousewheel","detail"in g&&(m=-1*g.detail),"wheelDelta"in g&&(m=g.wheelDelta),"wheelDeltaY"in g&&(m=g.wheelDeltaY),"wheelDeltaX"in g&&(l=-1*g.wheelDeltaX),"axis"in g&&g.axis===g.HORIZONTAL_AXIS&&(l=-1*m,m=0),j=0===m?l:m,"deltaY"in g&&(m=-1*g.deltaY,j=m),"deltaX"in g&&(l=g.deltaX,0===m&&(j=-1*l)),0!==m||0!==l){if(1===g.deltaMode){var q=a.data(this,"mousewheel-line-height");j*=q,m*=q,l*=q}else if(2===g.deltaMode){var r=a.data(this,"mousewheel-page-height");j*=r,m*=r,l*=r}if(n=Math.max(Math.abs(m),Math.abs(l)),(!f||f>n)&&(f=n,d(g,n)&&(f/=40)),d(g,n)&&(j/=40,l/=40,m/=40),j=Math[j>=1?"floor":"ceil"](j/f),l=Math[l>=1?"floor":"ceil"](l/f),m=Math[m>=1?"floor":"ceil"](m/f),k.settings.normalizeOffset&&this.getBoundingClientRect){var s=this.getBoundingClientRect();o=b.clientX-s.left,p=b.clientY-s.top}return b.deltaX=l,b.deltaY=m,b.deltaFactor=f,b.offsetX=o,b.offsetY=p,b.deltaMode=0,h.unshift(b,j,l,m),e&&clearTimeout(e),e=setTimeout(c,200),(a.event.dispatch||a.event.handle).apply(this,h)}}function c(){f=null}function d(a,b){return k.settings.adjustOldDeltas&&"mousewheel"===a.type&&b%120===0}var e,f,g=["wheel","mousewheel","DOMMouseScroll","MozMousePixelScroll"],h="onwheel"in document||document.documentMode>=9?["wheel"]:["mousewheel","DomMouseScroll","MozMousePixelScroll"],i=Array.prototype.slice;if(a.event.fixHooks)for(var j=g.length;j;)a.event.fixHooks[g[--j]]=a.event.mouseHooks;var k=a.event.special.mousewheel={version:"3.1.12",setup:function(){if(this.addEventListener)for(var c=h.length;c;)this.addEventListener(h[--c],b,!1);else this.onmousewheel=b;a.data(this,"mousewheel-line-height",k.getLineHeight(this)),a.data(this,"mousewheel-page-height",k.getPageHeight(this))},teardown:function(){if(this.removeEventListener)for(var c=h.length;c;)this.removeEventListener(h[--c],b,!1);else this.onmousewheel=null;a.removeData(this,"mousewheel-line-height"),a.removeData(this,"mousewheel-page-height")},getLineHeight:function(b){var c=a(b),d=c["offsetParent"in a.fn?"offsetParent":"parent"]();return d.length||(d=a("body")),parseInt(d.css("fontSize"),10)||parseInt(c.css("fontSize"),10)||16},getPageHeight:function(b){return a(b).height()},settings:{adjustOldDeltas:!0,normalizeOffset:!0}};a.fn.extend({mousewheel:function(a){return a?this.bind("mousewheel",a):this.trigger("mousewheel")},unmousewheel:function(a){return this.unbind("mousewheel",a)}})});
/* == malihu jquery custom scrollbar plugin == Version: 3.1.5, License: MIT License (MIT) */
!function(e){"function"==typeof define&&define.amd?define(["jquery"],e):"undefined"!=typeof module&&module.exports?module.exports=e:e(jQuery,window,document)}(function(e){!function(t){var o="function"==typeof define&&define.amd,a="undefined"!=typeof module&&module.exports,n="https:"==document.location.protocol?"https:":"http:",i="cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js";o||(a?require("jquery-mousewheel")(e):e.event.special.mousewheel||e("head").append(decodeURI("%3Cscript src="+n+"//"+i+"%3E%3C/script%3E"))),t()}(function(){var t,o="mCustomScrollbar",a="mCS",n=".mCustomScrollbar",i={setTop:0,setLeft:0,axis:"y",scrollbarPosition:"inside",scrollInertia:950,autoDraggerLength:!0,alwaysShowScrollbar:0,snapOffset:0,mouseWheel:{enable:!0,scrollAmount:"auto",axis:"y",deltaFactor:"auto",disableOver:["select","option","keygen","datalist","textarea"]},scrollButtons:{scrollType:"stepless",scrollAmount:"auto"},keyboard:{enable:!0,scrollType:"stepless",scrollAmount:"auto"},contentTouchScroll:25,documentTouchScroll:!0,advanced:{autoScrollOnFocus:"input,textarea,select,button,datalist,keygen,a[tabindex],area,object,[contenteditable='true']",updateOnContentResize:!0,updateOnImageLoad:"auto",autoUpdateTimeout:60},theme:"light",callbacks:{onTotalScrollOffset:0,onTotalScrollBackOffset:0,alwaysTriggerOffsets:!0}},r=0,l={},s=window.attachEvent&&!window.addEventListener?1:0,c=!1,d=["mCSB_dragger_onDrag","mCSB_scrollTools_onDrag","mCS_img_loaded","mCS_disabled","mCS_destroyed","mCS_no_scrollbar","mCS-autoHide","mCS-dir-rtl","mCS_no_scrollbar_y","mCS_no_scrollbar_x","mCS_y_hidden","mCS_x_hidden","mCSB_draggerContainer","mCSB_buttonUp","mCSB_buttonDown","mCSB_buttonLeft","mCSB_buttonRight"],u={init:function(t){var t=e.extend(!0,{},i,t),o=f.call(this);if(t.live){var s=t.liveSelector||this.selector||n,c=e(s);if("off"===t.live)return void m(s);l[s]=setTimeout(function(){c.mCustomScrollbar(t),"once"===t.live&&c.length&&m(s)},500)}else m(s);return t.setWidth=t.set_width?t.set_width:t.setWidth,t.setHeight=t.set_height?t.set_height:t.setHeight,t.axis=t.horizontalScroll?"x":p(t.axis),t.scrollInertia=t.scrollInertia>0&&t.scrollInertia<17?17:t.scrollInertia,"object"!=typeof t.mouseWheel&&1==t.mouseWheel&&(t.mouseWheel={enable:!0,scrollAmount:"auto",axis:"y",preventDefault:!1,deltaFactor:"auto",normalizeDelta:!1,invert:!1}),t.mouseWheel.scrollAmount=t.mouseWheelPixels?t.mouseWheelPixels:t.mouseWheel.scrollAmount,t.mouseWheel.normalizeDelta=t.advanced.normalizeMouseWheelDelta?t.advanced.normalizeMouseWheelDelta:t.mouseWheel.normalizeDelta,t.scrollButtons.scrollType=g(t.scrollButtons.scrollType),h(t),e(o).each(function(){var o=e(this);if(!o.data(a)){o.data(a,{idx:++r,opt:t,scrollRatio:{y:null,x:null},overflowed:null,contentReset:{y:null,x:null},bindEvents:!1,tweenRunning:!1,sequential:{},langDir:o.css("direction"),cbOffsets:null,trigger:null,poll:{size:{o:0,n:0},img:{o:0,n:0},change:{o:0,n:0}}});var n=o.data(a),i=n.opt,l=o.data("mcs-axis"),s=o.data("mcs-scrollbar-position"),c=o.data("mcs-theme");l&&(i.axis=l),s&&(i.scrollbarPosition=s),c&&(i.theme=c,h(i)),v.call(this),n&&i.callbacks.onCreate&&"function"==typeof i.callbacks.onCreate&&i.callbacks.onCreate.call(this),e("#mCSB_"+n.idx+"_container img:not(."+d[2]+")").addClass(d[2]),u.update.call(null,o)}})},update:function(t,o){var n=t||f.call(this);return e(n).each(function(){var t=e(this);if(t.data(a)){var n=t.data(a),i=n.opt,r=e("#mCSB_"+n.idx+"_container"),l=e("#mCSB_"+n.idx),s=[e("#mCSB_"+n.idx+"_dragger_vertical"),e("#mCSB_"+n.idx+"_dragger_horizontal")];if(!r.length)return;n.tweenRunning&&Q(t),o&&n&&i.callbacks.onBeforeUpdate&&"function"==typeof i.callbacks.onBeforeUpdate&&i.callbacks.onBeforeUpdate.call(this),t.hasClass(d[3])&&t.removeClass(d[3]),t.hasClass(d[4])&&t.removeClass(d[4]),l.css("max-height","none"),l.height()!==t.height()&&l.css("max-height",t.height()),_.call(this),"y"===i.axis||i.advanced.autoExpandHorizontalScroll||r.css("width",x(r)),n.overflowed=y.call(this),M.call(this),i.autoDraggerLength&&S.call(this),b.call(this),T.call(this);var c=[Math.abs(r[0].offsetTop),Math.abs(r[0].offsetLeft)];"x"!==i.axis&&(n.overflowed[0]?s[0].height()>s[0].parent().height()?B.call(this):(G(t,c[0].toString(),{dir:"y",dur:0,overwrite:"none"}),n.contentReset.y=null):(B.call(this),"y"===i.axis?k.call(this):"yx"===i.axis&&n.overflowed[1]&&G(t,c[1].toString(),{dir:"x",dur:0,overwrite:"none"}))),"y"!==i.axis&&(n.overflowed[1]?s[1].width()>s[1].parent().width()?B.call(this):(G(t,c[1].toString(),{dir:"x",dur:0,overwrite:"none"}),n.contentReset.x=null):(B.call(this),"x"===i.axis?k.call(this):"yx"===i.axis&&n.overflowed[0]&&G(t,c[0].toString(),{dir:"y",dur:0,overwrite:"none"}))),o&&n&&(2===o&&i.callbacks.onImageLoad&&"function"==typeof i.callbacks.onImageLoad?i.callbacks.onImageLoad.call(this):3===o&&i.callbacks.onSelectorChange&&"function"==typeof i.callbacks.onSelectorChange?i.callbacks.onSelectorChange.call(this):i.callbacks.onUpdate&&"function"==typeof i.callbacks.onUpdate&&i.callbacks.onUpdate.call(this)),N.call(this)}})},scrollTo:function(t,o){if("undefined"!=typeof t&&null!=t){var n=f.call(this);return e(n).each(function(){var n=e(this);if(n.data(a)){var i=n.data(a),r=i.opt,l={trigger:"external",scrollInertia:r.scrollInertia,scrollEasing:"mcsEaseInOut",moveDragger:!1,timeout:60,callbacks:!0,onStart:!0,onUpdate:!0,onComplete:!0},s=e.extend(!0,{},l,o),c=Y.call(this,t),d=s.scrollInertia>0&&s.scrollInertia<17?17:s.scrollInertia;c[0]=X.call(this,c[0],"y"),c[1]=X.call(this,c[1],"x"),s.moveDragger&&(c[0]*=i.scrollRatio.y,c[1]*=i.scrollRatio.x),s.dur=ne()?0:d,setTimeout(function(){null!==c[0]&&"undefined"!=typeof c[0]&&"x"!==r.axis&&i.overflowed[0]&&(s.dir="y",s.overwrite="all",G(n,c[0].toString(),s)),null!==c[1]&&"undefined"!=typeof c[1]&&"y"!==r.axis&&i.overflowed[1]&&(s.dir="x",s.overwrite="none",G(n,c[1].toString(),s))},s.timeout)}})}},stop:function(){var t=f.call(this);return e(t).each(function(){var t=e(this);t.data(a)&&Q(t)})},disable:function(t){var o=f.call(this);return e(o).each(function(){var o=e(this);if(o.data(a)){o.data(a);N.call(this,"remove"),k.call(this),t&&B.call(this),M.call(this,!0),o.addClass(d[3])}})},destroy:function(){var t=f.call(this);return e(t).each(function(){var n=e(this);if(n.data(a)){var i=n.data(a),r=i.opt,l=e("#mCSB_"+i.idx),s=e("#mCSB_"+i.idx+"_container"),c=e(".mCSB_"+i.idx+"_scrollbar");r.live&&m(r.liveSelector||e(t).selector),N.call(this,"remove"),k.call(this),B.call(this),n.removeData(a),$(this,"mcs"),c.remove(),s.find("img."+d[2]).removeClass(d[2]),l.replaceWith(s.contents()),n.removeClass(o+" _"+a+"_"+i.idx+" "+d[6]+" "+d[7]+" "+d[5]+" "+d[3]).addClass(d[4])}})}},f=function(){return"object"!=typeof e(this)||e(this).length<1?n:this},h=function(t){var o=["rounded","rounded-dark","rounded-dots","rounded-dots-dark"],a=["rounded-dots","rounded-dots-dark","3d","3d-dark","3d-thick","3d-thick-dark","inset","inset-dark","inset-2","inset-2-dark","inset-3","inset-3-dark"],n=["minimal","minimal-dark"],i=["minimal","minimal-dark"],r=["minimal","minimal-dark"];t.autoDraggerLength=e.inArray(t.theme,o)>-1?!1:t.autoDraggerLength,t.autoExpandScrollbar=e.inArray(t.theme,a)>-1?!1:t.autoExpandScrollbar,t.scrollButtons.enable=e.inArray(t.theme,n)>-1?!1:t.scrollButtons.enable,t.autoHideScrollbar=e.inArray(t.theme,i)>-1?!0:t.autoHideScrollbar,t.scrollbarPosition=e.inArray(t.theme,r)>-1?"outside":t.scrollbarPosition},m=function(e){l[e]&&(clearTimeout(l[e]),$(l,e))},p=function(e){return"yx"===e||"xy"===e||"auto"===e?"yx":"x"===e||"horizontal"===e?"x":"y"},g=function(e){return"stepped"===e||"pixels"===e||"step"===e||"click"===e?"stepped":"stepless"},v=function(){var t=e(this),n=t.data(a),i=n.opt,r=i.autoExpandScrollbar?" "+d[1]+"_expand":"",l=["<div id='mCSB_"+n.idx+"_scrollbar_vertical' class='mCSB_scrollTools mCSB_"+n.idx+"_scrollbar mCS-"+i.theme+" mCSB_scrollTools_vertical"+r+"'><div class='"+d[12]+"'><div id='mCSB_"+n.idx+"_dragger_vertical' class='mCSB_dragger' style='position:absolute;'><div class='mCSB_dragger_bar' /></div><div class='mCSB_draggerRail' /></div></div>","<div id='mCSB_"+n.idx+"_scrollbar_horizontal' class='mCSB_scrollTools mCSB_"+n.idx+"_scrollbar mCS-"+i.theme+" mCSB_scrollTools_horizontal"+r+"'><div class='"+d[12]+"'><div id='mCSB_"+n.idx+"_dragger_horizontal' class='mCSB_dragger' style='position:absolute;'><div class='mCSB_dragger_bar' /></div><div class='mCSB_draggerRail' /></div></div>"],s="yx"===i.axis?"mCSB_vertical_horizontal":"x"===i.axis?"mCSB_horizontal":"mCSB_vertical",c="yx"===i.axis?l[0]+l[1]:"x"===i.axis?l[1]:l[0],u="yx"===i.axis?"<div id='mCSB_"+n.idx+"_container_wrapper' class='mCSB_container_wrapper' />":"",f=i.autoHideScrollbar?" "+d[6]:"",h="x"!==i.axis&&"rtl"===n.langDir?" "+d[7]:"";i.setWidth&&t.css("width",i.setWidth),i.setHeight&&t.css("height",i.setHeight),i.setLeft="y"!==i.axis&&"rtl"===n.langDir?"989999px":i.setLeft,t.addClass(o+" _"+a+"_"+n.idx+f+h).wrapInner("<div id='mCSB_"+n.idx+"' class='mCustomScrollBox mCS-"+i.theme+" "+s+"'><div id='mCSB_"+n.idx+"_container' class='mCSB_container' style='position:relative; top:"+i.setTop+"; left:"+i.setLeft+";' dir='"+n.langDir+"' /></div>");var m=e("#mCSB_"+n.idx),p=e("#mCSB_"+n.idx+"_container");"y"===i.axis||i.advanced.autoExpandHorizontalScroll||p.css("width",x(p)),"outside"===i.scrollbarPosition?("static"===t.css("position")&&t.css("position","relative"),t.css("overflow","visible"),m.addClass("mCSB_outside").after(c)):(m.addClass("mCSB_inside").append(c),p.wrap(u)),w.call(this);var g=[e("#mCSB_"+n.idx+"_dragger_vertical"),e("#mCSB_"+n.idx+"_dragger_horizontal")];g[0].css("min-height",g[0].height()),g[1].css("min-width",g[1].width())},x=function(t){var o=[t[0].scrollWidth,Math.max.apply(Math,t.children().map(function(){return e(this).outerWidth(!0)}).get())],a=t.parent().width();return o[0]>a?o[0]:o[1]>a?o[1]:"100%"},_=function(){var t=e(this),o=t.data(a),n=o.opt,i=e("#mCSB_"+o.idx+"_container");if(n.advanced.autoExpandHorizontalScroll&&"y"!==n.axis){i.css({width:"auto","min-width":0,"overflow-x":"scroll"});var r=Math.ceil(i[0].scrollWidth);3===n.advanced.autoExpandHorizontalScroll||2!==n.advanced.autoExpandHorizontalScroll&&r>i.parent().width()?i.css({width:r,"min-width":"100%","overflow-x":"inherit"}):i.css({"overflow-x":"inherit",position:"absolute"}).wrap("<div class='mCSB_h_wrapper' style='position:relative; left:0; width:999999px;' />").css({width:Math.ceil(i[0].getBoundingClientRect().right+.4)-Math.floor(i[0].getBoundingClientRect().left),"min-width":"100%",position:"relative"}).unwrap()}},w=function(){var t=e(this),o=t.data(a),n=o.opt,i=e(".mCSB_"+o.idx+"_scrollbar:first"),r=oe(n.scrollButtons.tabindex)?"tabindex='"+n.scrollButtons.tabindex+"'":"",l=["<a href='#' class='"+d[13]+"' "+r+" />","<a href='#' class='"+d[14]+"' "+r+" />","<a href='#' class='"+d[15]+"' "+r+" />","<a href='#' class='"+d[16]+"' "+r+" />"],s=["x"===n.axis?l[2]:l[0],"x"===n.axis?l[3]:l[1],l[2],l[3]];n.scrollButtons.enable&&i.prepend(s[0]).append(s[1]).next(".mCSB_scrollTools").prepend(s[2]).append(s[3])},S=function(){var t=e(this),o=t.data(a),n=e("#mCSB_"+o.idx),i=e("#mCSB_"+o.idx+"_container"),r=[e("#mCSB_"+o.idx+"_dragger_vertical"),e("#mCSB_"+o.idx+"_dragger_horizontal")],l=[n.height()/i.outerHeight(!1),n.width()/i.outerWidth(!1)],c=[parseInt(r[0].css("min-height")),Math.round(l[0]*r[0].parent().height()),parseInt(r[1].css("min-width")),Math.round(l[1]*r[1].parent().width())],d=s&&c[1]<c[0]?c[0]:c[1],u=s&&c[3]<c[2]?c[2]:c[3];r[0].css({height:d,"max-height":r[0].parent().height()-10}).find(".mCSB_dragger_bar").css({"line-height":c[0]+"px"}),r[1].css({width:u,"max-width":r[1].parent().width()-10})},b=function(){var t=e(this),o=t.data(a),n=e("#mCSB_"+o.idx),i=e("#mCSB_"+o.idx+"_container"),r=[e("#mCSB_"+o.idx+"_dragger_vertical"),e("#mCSB_"+o.idx+"_dragger_horizontal")],l=[i.outerHeight(!1)-n.height(),i.outerWidth(!1)-n.width()],s=[l[0]/(r[0].parent().height()-r[0].height()),l[1]/(r[1].parent().width()-r[1].width())];o.scrollRatio={y:s[0],x:s[1]}},C=function(e,t,o){var a=o?d[0]+"_expanded":"",n=e.closest(".mCSB_scrollTools");"active"===t?(e.toggleClass(d[0]+" "+a),n.toggleClass(d[1]),e[0]._draggable=e[0]._draggable?0:1):e[0]._draggable||("hide"===t?(e.removeClass(d[0]),n.removeClass(d[1])):(e.addClass(d[0]),n.addClass(d[1])))},y=function(){var t=e(this),o=t.data(a),n=e("#mCSB_"+o.idx),i=e("#mCSB_"+o.idx+"_container"),r=null==o.overflowed?i.height():i.outerHeight(!1),l=null==o.overflowed?i.width():i.outerWidth(!1),s=i[0].scrollHeight,c=i[0].scrollWidth;return s>r&&(r=s),c>l&&(l=c),[r>n.height(),l>n.width()]},B=function(){var t=e(this),o=t.data(a),n=o.opt,i=e("#mCSB_"+o.idx),r=e("#mCSB_"+o.idx+"_container"),l=[e("#mCSB_"+o.idx+"_dragger_vertical"),e("#mCSB_"+o.idx+"_dragger_horizontal")];if(Q(t),("x"!==n.axis&&!o.overflowed[0]||"y"===n.axis&&o.overflowed[0])&&(l[0].add(r).css("top",0),G(t,"_resetY")),"y"!==n.axis&&!o.overflowed[1]||"x"===n.axis&&o.overflowed[1]){var s=dx=0;"rtl"===o.langDir&&(s=i.width()-r.outerWidth(!1),dx=Math.abs(s/o.scrollRatio.x)),r.css("left",s),l[1].css("left",dx),G(t,"_resetX")}},T=function(){function t(){r=setTimeout(function(){e.event.special.mousewheel?(clearTimeout(r),W.call(o[0])):t()},100)}var o=e(this),n=o.data(a),i=n.opt;if(!n.bindEvents){if(I.call(this),i.contentTouchScroll&&D.call(this),E.call(this),i.mouseWheel.enable){var r;t()}P.call(this),U.call(this),i.advanced.autoScrollOnFocus&&H.call(this),i.scrollButtons.enable&&F.call(this),i.keyboard.enable&&q.call(this),n.bindEvents=!0}},k=function(){var t=e(this),o=t.data(a),n=o.opt,i=a+"_"+o.idx,r=".mCSB_"+o.idx+"_scrollbar",l=e("#mCSB_"+o.idx+",#mCSB_"+o.idx+"_container,#mCSB_"+o.idx+"_container_wrapper,"+r+" ."+d[12]+",#mCSB_"+o.idx+"_dragger_vertical,#mCSB_"+o.idx+"_dragger_horizontal,"+r+">a"),s=e("#mCSB_"+o.idx+"_container");n.advanced.releaseDraggableSelectors&&l.add(e(n.advanced.releaseDraggableSelectors)),n.advanced.extraDraggableSelectors&&l.add(e(n.advanced.extraDraggableSelectors)),o.bindEvents&&(e(document).add(e(!A()||top.document)).unbind("."+i),l.each(function(){e(this).unbind("."+i)}),clearTimeout(t[0]._focusTimeout),$(t[0],"_focusTimeout"),clearTimeout(o.sequential.step),$(o.sequential,"step"),clearTimeout(s[0].onCompleteTimeout),$(s[0],"onCompleteTimeout"),o.bindEvents=!1)},M=function(t){var o=e(this),n=o.data(a),i=n.opt,r=e("#mCSB_"+n.idx+"_container_wrapper"),l=r.length?r:e("#mCSB_"+n.idx+"_container"),s=[e("#mCSB_"+n.idx+"_scrollbar_vertical"),e("#mCSB_"+n.idx+"_scrollbar_horizontal")],c=[s[0].find(".mCSB_dragger"),s[1].find(".mCSB_dragger")];"x"!==i.axis&&(n.overflowed[0]&&!t?(s[0].add(c[0]).add(s[0].children("a")).css("display","block"),l.removeClass(d[8]+" "+d[10])):(i.alwaysShowScrollbar?(2!==i.alwaysShowScrollbar&&c[0].css("display","none"),l.removeClass(d[10])):(s[0].css("display","none"),l.addClass(d[10])),l.addClass(d[8]))),"y"!==i.axis&&(n.overflowed[1]&&!t?(s[1].add(c[1]).add(s[1].children("a")).css("display","block"),l.removeClass(d[9]+" "+d[11])):(i.alwaysShowScrollbar?(2!==i.alwaysShowScrollbar&&c[1].css("display","none"),l.removeClass(d[11])):(s[1].css("display","none"),l.addClass(d[11])),l.addClass(d[9]))),n.overflowed[0]||n.overflowed[1]?o.removeClass(d[5]):o.addClass(d[5])},O=function(t){var o=t.type,a=t.target.ownerDocument!==document&&null!==frameElement?[e(frameElement).offset().top,e(frameElement).offset().left]:null,n=A()&&t.target.ownerDocument!==top.document&&null!==frameElement?[e(t.view.frameElement).offset().top,e(t.view.frameElement).offset().left]:[0,0];switch(o){case"pointerdown":case"MSPointerDown":case"pointermove":case"MSPointerMove":case"pointerup":case"MSPointerUp":return a?[t.originalEvent.pageY-a[0]+n[0],t.originalEvent.pageX-a[1]+n[1],!1]:[t.originalEvent.pageY,t.originalEvent.pageX,!1];case"touchstart":case"touchmove":case"touchend":var i=t.originalEvent.touches[0]||t.originalEvent.changedTouches[0],r=t.originalEvent.touches.length||t.originalEvent.changedTouches.length;return t.target.ownerDocument!==document?[i.screenY,i.screenX,r>1]:[i.pageY,i.pageX,r>1];default:return a?[t.pageY-a[0]+n[0],t.pageX-a[1]+n[1],!1]:[t.pageY,t.pageX,!1]}},I=function(){function t(e,t,a,n){if(h[0].idleTimer=d.scrollInertia<233?250:0,o.attr("id")===f[1])var i="x",s=(o[0].offsetLeft-t+n)*l.scrollRatio.x;else var i="y",s=(o[0].offsetTop-e+a)*l.scrollRatio.y;G(r,s.toString(),{dir:i,drag:!0})}var o,n,i,r=e(this),l=r.data(a),d=l.opt,u=a+"_"+l.idx,f=["mCSB_"+l.idx+"_dragger_vertical","mCSB_"+l.idx+"_dragger_horizontal"],h=e("#mCSB_"+l.idx+"_container"),m=e("#"+f[0]+",#"+f[1]),p=d.advanced.releaseDraggableSelectors?m.add(e(d.advanced.releaseDraggableSelectors)):m,g=d.advanced.extraDraggableSelectors?e(!A()||top.document).add(e(d.advanced.extraDraggableSelectors)):e(!A()||top.document);m.bind("contextmenu."+u,function(e){e.preventDefault()}).bind("mousedown."+u+" touchstart."+u+" pointerdown."+u+" MSPointerDown."+u,function(t){if(t.stopImmediatePropagation(),t.preventDefault(),ee(t)){c=!0,s&&(document.onselectstart=function(){return!1}),L.call(h,!1),Q(r),o=e(this);var a=o.offset(),l=O(t)[0]-a.top,u=O(t)[1]-a.left,f=o.height()+a.top,m=o.width()+a.left;f>l&&l>0&&m>u&&u>0&&(n=l,i=u),C(o,"active",d.autoExpandScrollbar)}}).bind("touchmove."+u,function(e){e.stopImmediatePropagation(),e.preventDefault();var a=o.offset(),r=O(e)[0]-a.top,l=O(e)[1]-a.left;t(n,i,r,l)}),e(document).add(g).bind("mousemove."+u+" pointermove."+u+" MSPointerMove."+u,function(e){if(o){var a=o.offset(),r=O(e)[0]-a.top,l=O(e)[1]-a.left;if(n===r&&i===l)return;t(n,i,r,l)}}).add(p).bind("mouseup."+u+" touchend."+u+" pointerup."+u+" MSPointerUp."+u,function(){o&&(C(o,"active",d.autoExpandScrollbar),o=null),c=!1,s&&(document.onselectstart=null),L.call(h,!0)})},D=function(){function o(e){if(!te(e)||c||O(e)[2])return void(t=0);t=1,b=0,C=0,d=1,y.removeClass("mCS_touch_action");var o=I.offset();u=O(e)[0]-o.top,f=O(e)[1]-o.left,z=[O(e)[0],O(e)[1]]}function n(e){if(te(e)&&!c&&!O(e)[2]&&(T.documentTouchScroll||e.preventDefault(),e.stopImmediatePropagation(),(!C||b)&&d)){g=K();var t=M.offset(),o=O(e)[0]-t.top,a=O(e)[1]-t.left,n="mcsLinearOut";if(E.push(o),W.push(a),z[2]=Math.abs(O(e)[0]-z[0]),z[3]=Math.abs(O(e)[1]-z[1]),B.overflowed[0])var i=D[0].parent().height()-D[0].height(),r=u-o>0&&o-u>-(i*B.scrollRatio.y)&&(2*z[3]<z[2]||"yx"===T.axis);if(B.overflowed[1])var l=D[1].parent().width()-D[1].width(),h=f-a>0&&a-f>-(l*B.scrollRatio.x)&&(2*z[2]<z[3]||"yx"===T.axis);r||h?(U||e.preventDefault(),b=1):(C=1,y.addClass("mCS_touch_action")),U&&e.preventDefault(),w="yx"===T.axis?[u-o,f-a]:"x"===T.axis?[null,f-a]:[u-o,null],I[0].idleTimer=250,B.overflowed[0]&&s(w[0],R,n,"y","all",!0),B.overflowed[1]&&s(w[1],R,n,"x",L,!0)}}function i(e){if(!te(e)||c||O(e)[2])return void(t=0);t=1,e.stopImmediatePropagation(),Q(y),p=K();var o=M.offset();h=O(e)[0]-o.top,m=O(e)[1]-o.left,E=[],W=[]}function r(e){if(te(e)&&!c&&!O(e)[2]){d=0,e.stopImmediatePropagation(),b=0,C=0,v=K();var t=M.offset(),o=O(e)[0]-t.top,a=O(e)[1]-t.left;if(!(v-g>30)){_=1e3/(v-p);var n="mcsEaseOut",i=2.5>_,r=i?[E[E.length-2],W[W.length-2]]:[0,0];x=i?[o-r[0],a-r[1]]:[o-h,a-m];var u=[Math.abs(x[0]),Math.abs(x[1])];_=i?[Math.abs(x[0]/4),Math.abs(x[1]/4)]:[_,_];var f=[Math.abs(I[0].offsetTop)-x[0]*l(u[0]/_[0],_[0]),Math.abs(I[0].offsetLeft)-x[1]*l(u[1]/_[1],_[1])];w="yx"===T.axis?[f[0],f[1]]:"x"===T.axis?[null,f[1]]:[f[0],null],S=[4*u[0]+T.scrollInertia,4*u[1]+T.scrollInertia];var y=parseInt(T.contentTouchScroll)||0;w[0]=u[0]>y?w[0]:0,w[1]=u[1]>y?w[1]:0,B.overflowed[0]&&s(w[0],S[0],n,"y",L,!1),B.overflowed[1]&&s(w[1],S[1],n,"x",L,!1)}}}function l(e,t){var o=[1.5*t,2*t,t/1.5,t/2];return e>90?t>4?o[0]:o[3]:e>60?t>3?o[3]:o[2]:e>30?t>8?o[1]:t>6?o[0]:t>4?t:o[2]:t>8?t:o[3]}function s(e,t,o,a,n,i){e&&G(y,e.toString(),{dur:t,scrollEasing:o,dir:a,overwrite:n,drag:i})}var d,u,f,h,m,p,g,v,x,_,w,S,b,C,y=e(this),B=y.data(a),T=B.opt,k=a+"_"+B.idx,M=e("#mCSB_"+B.idx),I=e("#mCSB_"+B.idx+"_container"),D=[e("#mCSB_"+B.idx+"_dragger_vertical"),e("#mCSB_"+B.idx+"_dragger_horizontal")],E=[],W=[],R=0,L="yx"===T.axis?"none":"all",z=[],P=I.find("iframe"),H=["touchstart."+k+" pointerdown."+k+" MSPointerDown."+k,"touchmove."+k+" pointermove."+k+" MSPointerMove."+k,"touchend."+k+" pointerup."+k+" MSPointerUp."+k],U=void 0!==document.body.style.touchAction&&""!==document.body.style.touchAction;I.bind(H[0],function(e){o(e)}).bind(H[1],function(e){n(e)}),M.bind(H[0],function(e){i(e)}).bind(H[2],function(e){r(e)}),P.length&&P.each(function(){e(this).bind("load",function(){A(this)&&e(this.contentDocument||this.contentWindow.document).bind(H[0],function(e){o(e),i(e)}).bind(H[1],function(e){n(e)}).bind(H[2],function(e){r(e)})})})},E=function(){function o(){return window.getSelection?window.getSelection().toString():document.selection&&"Control"!=document.selection.type?document.selection.createRange().text:0}function n(e,t,o){d.type=o&&i?"stepped":"stepless",d.scrollAmount=10,j(r,e,t,"mcsLinearOut",o?60:null)}var i,r=e(this),l=r.data(a),s=l.opt,d=l.sequential,u=a+"_"+l.idx,f=e("#mCSB_"+l.idx+"_container"),h=f.parent();f.bind("mousedown."+u,function(){t||i||(i=1,c=!0)}).add(document).bind("mousemove."+u,function(e){if(!t&&i&&o()){var a=f.offset(),r=O(e)[0]-a.top+f[0].offsetTop,c=O(e)[1]-a.left+f[0].offsetLeft;r>0&&r<h.height()&&c>0&&c<h.width()?d.step&&n("off",null,"stepped"):("x"!==s.axis&&l.overflowed[0]&&(0>r?n("on",38):r>h.height()&&n("on",40)),"y"!==s.axis&&l.overflowed[1]&&(0>c?n("on",37):c>h.width()&&n("on",39)))}}).bind("mouseup."+u+" dragend."+u,function(){t||(i&&(i=0,n("off",null)),c=!1)})},W=function(){function t(t,a){if(Q(o),!z(o,t.target)){var r="auto"!==i.mouseWheel.deltaFactor?parseInt(i.mouseWheel.deltaFactor):s&&t.deltaFactor<100?100:t.deltaFactor||100,d=i.scrollInertia;if("x"===i.axis||"x"===i.mouseWheel.axis)var u="x",f=[Math.round(r*n.scrollRatio.x),parseInt(i.mouseWheel.scrollAmount)],h="auto"!==i.mouseWheel.scrollAmount?f[1]:f[0]>=l.width()?.9*l.width():f[0],m=Math.abs(e("#mCSB_"+n.idx+"_container")[0].offsetLeft),p=c[1][0].offsetLeft,g=c[1].parent().width()-c[1].width(),v="y"===i.mouseWheel.axis?t.deltaY||a:t.deltaX;else var u="y",f=[Math.round(r*n.scrollRatio.y),parseInt(i.mouseWheel.scrollAmount)],h="auto"!==i.mouseWheel.scrollAmount?f[1]:f[0]>=l.height()?.9*l.height():f[0],m=Math.abs(e("#mCSB_"+n.idx+"_container")[0].offsetTop),p=c[0][0].offsetTop,g=c[0].parent().height()-c[0].height(),v=t.deltaY||a;"y"===u&&!n.overflowed[0]||"x"===u&&!n.overflowed[1]||((i.mouseWheel.invert||t.webkitDirectionInvertedFromDevice)&&(v=-v),i.mouseWheel.normalizeDelta&&(v=0>v?-1:1),(v>0&&0!==p||0>v&&p!==g||i.mouseWheel.preventDefault)&&(t.stopImmediatePropagation(),t.preventDefault()),t.deltaFactor<5&&!i.mouseWheel.normalizeDelta&&(h=t.deltaFactor,d=17),G(o,(m-v*h).toString(),{dir:u,dur:d}))}}if(e(this).data(a)){var o=e(this),n=o.data(a),i=n.opt,r=a+"_"+n.idx,l=e("#mCSB_"+n.idx),c=[e("#mCSB_"+n.idx+"_dragger_vertical"),e("#mCSB_"+n.idx+"_dragger_horizontal")],d=e("#mCSB_"+n.idx+"_container").find("iframe");d.length&&d.each(function(){e(this).bind("load",function(){A(this)&&e(this.contentDocument||this.contentWindow.document).bind("mousewheel."+r,function(e,o){t(e,o)})})}),l.bind("mousewheel."+r,function(e,o){t(e,o)})}},R=new Object,A=function(t){var o=!1,a=!1,n=null;if(void 0===t?a="#empty":void 0!==e(t).attr("id")&&(a=e(t).attr("id")),a!==!1&&void 0!==R[a])return R[a];if(t){try{var i=t.contentDocument||t.contentWindow.document;n=i.body.innerHTML}catch(r){}o=null!==n}else{try{var i=top.document;n=i.body.innerHTML}catch(r){}o=null!==n}return a!==!1&&(R[a]=o),o},L=function(e){var t=this.find("iframe");if(t.length){var o=e?"auto":"none";t.css("pointer-events",o)}},z=function(t,o){var n=o.nodeName.toLowerCase(),i=t.data(a).opt.mouseWheel.disableOver,r=["select","textarea"];return e.inArray(n,i)>-1&&!(e.inArray(n,r)>-1&&!e(o).is(":focus"))},P=function(){var t,o=e(this),n=o.data(a),i=a+"_"+n.idx,r=e("#mCSB_"+n.idx+"_container"),l=r.parent(),s=e(".mCSB_"+n.idx+"_scrollbar ."+d[12]);s.bind("mousedown."+i+" touchstart."+i+" pointerdown."+i+" MSPointerDown."+i,function(o){c=!0,e(o.target).hasClass("mCSB_dragger")||(t=1)}).bind("touchend."+i+" pointerup."+i+" MSPointerUp."+i,function(){c=!1}).bind("click."+i,function(a){if(t&&(t=0,e(a.target).hasClass(d[12])||e(a.target).hasClass("mCSB_draggerRail"))){Q(o);var i=e(this),s=i.find(".mCSB_dragger");if(i.parent(".mCSB_scrollTools_horizontal").length>0){if(!n.overflowed[1])return;var c="x",u=a.pageX>s.offset().left?-1:1,f=Math.abs(r[0].offsetLeft)-u*(.9*l.width())}else{if(!n.overflowed[0])return;var c="y",u=a.pageY>s.offset().top?-1:1,f=Math.abs(r[0].offsetTop)-u*(.9*l.height())}G(o,f.toString(),{dir:c,scrollEasing:"mcsEaseInOut"})}})},H=function(){var t=e(this),o=t.data(a),n=o.opt,i=a+"_"+o.idx,r=e("#mCSB_"+o.idx+"_container"),l=r.parent();r.bind("focusin."+i,function(){var o=e(document.activeElement),a=r.find(".mCustomScrollBox").length,i=0;o.is(n.advanced.autoScrollOnFocus)&&(Q(t),clearTimeout(t[0]._focusTimeout),t[0]._focusTimer=a?(i+17)*a:0,t[0]._focusTimeout=setTimeout(function(){var e=[ae(o)[0],ae(o)[1]],a=[r[0].offsetTop,r[0].offsetLeft],s=[a[0]+e[0]>=0&&a[0]+e[0]<l.height()-o.outerHeight(!1),a[1]+e[1]>=0&&a[0]+e[1]<l.width()-o.outerWidth(!1)],c="yx"!==n.axis||s[0]||s[1]?"all":"none";"x"===n.axis||s[0]||G(t,e[0].toString(),{dir:"y",scrollEasing:"mcsEaseInOut",overwrite:c,dur:i}),"y"===n.axis||s[1]||G(t,e[1].toString(),{dir:"x",scrollEasing:"mcsEaseInOut",overwrite:c,dur:i})},t[0]._focusTimer))})},U=function(){var t=e(this),o=t.data(a),n=a+"_"+o.idx,i=e("#mCSB_"+o.idx+"_container").parent();i.bind("scroll."+n,function(){0===i.scrollTop()&&0===i.scrollLeft()||e(".mCSB_"+o.idx+"_scrollbar").css("visibility","hidden")})},F=function(){var t=e(this),o=t.data(a),n=o.opt,i=o.sequential,r=a+"_"+o.idx,l=".mCSB_"+o.idx+"_scrollbar",s=e(l+">a");s.bind("contextmenu."+r,function(e){e.preventDefault()}).bind("mousedown."+r+" touchstart."+r+" pointerdown."+r+" MSPointerDown."+r+" mouseup."+r+" touchend."+r+" pointerup."+r+" MSPointerUp."+r+" mouseout."+r+" pointerout."+r+" MSPointerOut."+r+" click."+r,function(a){function r(e,o){i.scrollAmount=n.scrollButtons.scrollAmount,j(t,e,o)}if(a.preventDefault(),ee(a)){var l=e(this).attr("class");switch(i.type=n.scrollButtons.scrollType,a.type){case"mousedown":case"touchstart":case"pointerdown":case"MSPointerDown":if("stepped"===i.type)return;c=!0,o.tweenRunning=!1,r("on",l);break;case"mouseup":case"touchend":case"pointerup":case"MSPointerUp":case"mouseout":case"pointerout":case"MSPointerOut":if("stepped"===i.type)return;c=!1,i.dir&&r("off",l);break;case"click":if("stepped"!==i.type||o.tweenRunning)return;r("on",l)}}})},q=function(){function t(t){function a(e,t){r.type=i.keyboard.scrollType,r.scrollAmount=i.keyboard.scrollAmount,"stepped"===r.type&&n.tweenRunning||j(o,e,t)}switch(t.type){case"blur":n.tweenRunning&&r.dir&&a("off",null);break;case"keydown":case"keyup":var l=t.keyCode?t.keyCode:t.which,s="on";if("x"!==i.axis&&(38===l||40===l)||"y"!==i.axis&&(37===l||39===l)){if((38===l||40===l)&&!n.overflowed[0]||(37===l||39===l)&&!n.overflowed[1])return;"keyup"===t.type&&(s="off"),e(document.activeElement).is(u)||(t.preventDefault(),t.stopImmediatePropagation(),a(s,l))}else if(33===l||34===l){if((n.overflowed[0]||n.overflowed[1])&&(t.preventDefault(),t.stopImmediatePropagation()),"keyup"===t.type){Q(o);var f=34===l?-1:1;if("x"===i.axis||"yx"===i.axis&&n.overflowed[1]&&!n.overflowed[0])var h="x",m=Math.abs(c[0].offsetLeft)-f*(.9*d.width());else var h="y",m=Math.abs(c[0].offsetTop)-f*(.9*d.height());G(o,m.toString(),{dir:h,scrollEasing:"mcsEaseInOut"})}}else if((35===l||36===l)&&!e(document.activeElement).is(u)&&((n.overflowed[0]||n.overflowed[1])&&(t.preventDefault(),t.stopImmediatePropagation()),"keyup"===t.type)){if("x"===i.axis||"yx"===i.axis&&n.overflowed[1]&&!n.overflowed[0])var h="x",m=35===l?Math.abs(d.width()-c.outerWidth(!1)):0;else var h="y",m=35===l?Math.abs(d.height()-c.outerHeight(!1)):0;G(o,m.toString(),{dir:h,scrollEasing:"mcsEaseInOut"})}}}var o=e(this),n=o.data(a),i=n.opt,r=n.sequential,l=a+"_"+n.idx,s=e("#mCSB_"+n.idx),c=e("#mCSB_"+n.idx+"_container"),d=c.parent(),u="input,textarea,select,datalist,keygen,[contenteditable='true']",f=c.find("iframe"),h=["blur."+l+" keydown."+l+" keyup."+l];f.length&&f.each(function(){e(this).bind("load",function(){A(this)&&e(this.contentDocument||this.contentWindow.document).bind(h[0],function(e){t(e)})})}),s.attr("tabindex","0").bind(h[0],function(e){t(e)})},j=function(t,o,n,i,r){function l(e){u.snapAmount&&(f.scrollAmount=u.snapAmount instanceof Array?"x"===f.dir[0]?u.snapAmount[1]:u.snapAmount[0]:u.snapAmount);var o="stepped"!==f.type,a=r?r:e?o?p/1.5:g:1e3/60,n=e?o?7.5:40:2.5,s=[Math.abs(h[0].offsetTop),Math.abs(h[0].offsetLeft)],d=[c.scrollRatio.y>10?10:c.scrollRatio.y,c.scrollRatio.x>10?10:c.scrollRatio.x],m="x"===f.dir[0]?s[1]+f.dir[1]*(d[1]*n):s[0]+f.dir[1]*(d[0]*n),v="x"===f.dir[0]?s[1]+f.dir[1]*parseInt(f.scrollAmount):s[0]+f.dir[1]*parseInt(f.scrollAmount),x="auto"!==f.scrollAmount?v:m,_=i?i:e?o?"mcsLinearOut":"mcsEaseInOut":"mcsLinear",w=!!e;return e&&17>a&&(x="x"===f.dir[0]?s[1]:s[0]),G(t,x.toString(),{dir:f.dir[0],scrollEasing:_,dur:a,onComplete:w}),e?void(f.dir=!1):(clearTimeout(f.step),void(f.step=setTimeout(function(){l()},a)))}function s(){clearTimeout(f.step),$(f,"step"),Q(t)}var c=t.data(a),u=c.opt,f=c.sequential,h=e("#mCSB_"+c.idx+"_container"),m="stepped"===f.type,p=u.scrollInertia<26?26:u.scrollInertia,g=u.scrollInertia<1?17:u.scrollInertia;switch(o){case"on":if(f.dir=[n===d[16]||n===d[15]||39===n||37===n?"x":"y",n===d[13]||n===d[15]||38===n||37===n?-1:1],Q(t),oe(n)&&"stepped"===f.type)return;l(m);break;case"off":s(),(m||c.tweenRunning&&f.dir)&&l(!0)}},Y=function(t){var o=e(this).data(a).opt,n=[];return"function"==typeof t&&(t=t()),t instanceof Array?n=t.length>1?[t[0],t[1]]:"x"===o.axis?[null,t[0]]:[t[0],null]:(n[0]=t.y?t.y:t.x||"x"===o.axis?null:t,n[1]=t.x?t.x:t.y||"y"===o.axis?null:t),"function"==typeof n[0]&&(n[0]=n[0]()),"function"==typeof n[1]&&(n[1]=n[1]()),n},X=function(t,o){if(null!=t&&"undefined"!=typeof t){var n=e(this),i=n.data(a),r=i.opt,l=e("#mCSB_"+i.idx+"_container"),s=l.parent(),c=typeof t;o||(o="x"===r.axis?"x":"y");var d="x"===o?l.outerWidth(!1)-s.width():l.outerHeight(!1)-s.height(),f="x"===o?l[0].offsetLeft:l[0].offsetTop,h="x"===o?"left":"top";switch(c){case"function":return t();case"object":var m=t.jquery?t:e(t);if(!m.length)return;return"x"===o?ae(m)[1]:ae(m)[0];case"string":case"number":if(oe(t))return Math.abs(t);if(-1!==t.indexOf("%"))return Math.abs(d*parseInt(t)/100);if(-1!==t.indexOf("-="))return Math.abs(f-parseInt(t.split("-=")[1]));if(-1!==t.indexOf("+=")){var p=f+parseInt(t.split("+=")[1]);return p>=0?0:Math.abs(p)}if(-1!==t.indexOf("px")&&oe(t.split("px")[0]))return Math.abs(t.split("px")[0]);if("top"===t||"left"===t)return 0;if("bottom"===t)return Math.abs(s.height()-l.outerHeight(!1));if("right"===t)return Math.abs(s.width()-l.outerWidth(!1));if("first"===t||"last"===t){var m=l.find(":"+t);return"x"===o?ae(m)[1]:ae(m)[0]}return e(t).length?"x"===o?ae(e(t))[1]:ae(e(t))[0]:(l.css(h,t),void u.update.call(null,n[0]))}}},N=function(t){function o(){return clearTimeout(f[0].autoUpdate),0===l.parents("html").length?void(l=null):void(f[0].autoUpdate=setTimeout(function(){return c.advanced.updateOnSelectorChange&&(s.poll.change.n=i(),s.poll.change.n!==s.poll.change.o)?(s.poll.change.o=s.poll.change.n,void r(3)):c.advanced.updateOnContentResize&&(s.poll.size.n=l[0].scrollHeight+l[0].scrollWidth+f[0].offsetHeight+l[0].offsetHeight+l[0].offsetWidth,s.poll.size.n!==s.poll.size.o)?(s.poll.size.o=s.poll.size.n,void r(1)):!c.advanced.updateOnImageLoad||"auto"===c.advanced.updateOnImageLoad&&"y"===c.axis||(s.poll.img.n=f.find("img").length,s.poll.img.n===s.poll.img.o)?void((c.advanced.updateOnSelectorChange||c.advanced.updateOnContentResize||c.advanced.updateOnImageLoad)&&o()):(s.poll.img.o=s.poll.img.n,void f.find("img").each(function(){n(this)}))},c.advanced.autoUpdateTimeout))}function n(t){function o(e,t){return function(){
return t.apply(e,arguments)}}function a(){this.onload=null,e(t).addClass(d[2]),r(2)}if(e(t).hasClass(d[2]))return void r();var n=new Image;n.onload=o(n,a),n.src=t.src}function i(){c.advanced.updateOnSelectorChange===!0&&(c.advanced.updateOnSelectorChange="*");var e=0,t=f.find(c.advanced.updateOnSelectorChange);return c.advanced.updateOnSelectorChange&&t.length>0&&t.each(function(){e+=this.offsetHeight+this.offsetWidth}),e}function r(e){clearTimeout(f[0].autoUpdate),u.update.call(null,l[0],e)}var l=e(this),s=l.data(a),c=s.opt,f=e("#mCSB_"+s.idx+"_container");return t?(clearTimeout(f[0].autoUpdate),void $(f[0],"autoUpdate")):void o()},V=function(e,t,o){return Math.round(e/t)*t-o},Q=function(t){var o=t.data(a),n=e("#mCSB_"+o.idx+"_container,#mCSB_"+o.idx+"_container_wrapper,#mCSB_"+o.idx+"_dragger_vertical,#mCSB_"+o.idx+"_dragger_horizontal");n.each(function(){Z.call(this)})},G=function(t,o,n){function i(e){return s&&c.callbacks[e]&&"function"==typeof c.callbacks[e]}function r(){return[c.callbacks.alwaysTriggerOffsets||w>=S[0]+y,c.callbacks.alwaysTriggerOffsets||-B>=w]}function l(){var e=[h[0].offsetTop,h[0].offsetLeft],o=[x[0].offsetTop,x[0].offsetLeft],a=[h.outerHeight(!1),h.outerWidth(!1)],i=[f.height(),f.width()];t[0].mcs={content:h,top:e[0],left:e[1],draggerTop:o[0],draggerLeft:o[1],topPct:Math.round(100*Math.abs(e[0])/(Math.abs(a[0])-i[0])),leftPct:Math.round(100*Math.abs(e[1])/(Math.abs(a[1])-i[1])),direction:n.dir}}var s=t.data(a),c=s.opt,d={trigger:"internal",dir:"y",scrollEasing:"mcsEaseOut",drag:!1,dur:c.scrollInertia,overwrite:"all",callbacks:!0,onStart:!0,onUpdate:!0,onComplete:!0},n=e.extend(d,n),u=[n.dur,n.drag?0:n.dur],f=e("#mCSB_"+s.idx),h=e("#mCSB_"+s.idx+"_container"),m=h.parent(),p=c.callbacks.onTotalScrollOffset?Y.call(t,c.callbacks.onTotalScrollOffset):[0,0],g=c.callbacks.onTotalScrollBackOffset?Y.call(t,c.callbacks.onTotalScrollBackOffset):[0,0];if(s.trigger=n.trigger,0===m.scrollTop()&&0===m.scrollLeft()||(e(".mCSB_"+s.idx+"_scrollbar").css("visibility","visible"),m.scrollTop(0).scrollLeft(0)),"_resetY"!==o||s.contentReset.y||(i("onOverflowYNone")&&c.callbacks.onOverflowYNone.call(t[0]),s.contentReset.y=1),"_resetX"!==o||s.contentReset.x||(i("onOverflowXNone")&&c.callbacks.onOverflowXNone.call(t[0]),s.contentReset.x=1),"_resetY"!==o&&"_resetX"!==o){if(!s.contentReset.y&&t[0].mcs||!s.overflowed[0]||(i("onOverflowY")&&c.callbacks.onOverflowY.call(t[0]),s.contentReset.x=null),!s.contentReset.x&&t[0].mcs||!s.overflowed[1]||(i("onOverflowX")&&c.callbacks.onOverflowX.call(t[0]),s.contentReset.x=null),c.snapAmount){var v=c.snapAmount instanceof Array?"x"===n.dir?c.snapAmount[1]:c.snapAmount[0]:c.snapAmount;o=V(o,v,c.snapOffset)}switch(n.dir){case"x":var x=e("#mCSB_"+s.idx+"_dragger_horizontal"),_="left",w=h[0].offsetLeft,S=[f.width()-h.outerWidth(!1),x.parent().width()-x.width()],b=[o,0===o?0:o/s.scrollRatio.x],y=p[1],B=g[1],T=y>0?y/s.scrollRatio.x:0,k=B>0?B/s.scrollRatio.x:0;break;case"y":var x=e("#mCSB_"+s.idx+"_dragger_vertical"),_="top",w=h[0].offsetTop,S=[f.height()-h.outerHeight(!1),x.parent().height()-x.height()],b=[o,0===o?0:o/s.scrollRatio.y],y=p[0],B=g[0],T=y>0?y/s.scrollRatio.y:0,k=B>0?B/s.scrollRatio.y:0}b[1]<0||0===b[0]&&0===b[1]?b=[0,0]:b[1]>=S[1]?b=[S[0],S[1]]:b[0]=-b[0],t[0].mcs||(l(),i("onInit")&&c.callbacks.onInit.call(t[0])),clearTimeout(h[0].onCompleteTimeout),J(x[0],_,Math.round(b[1]),u[1],n.scrollEasing),!s.tweenRunning&&(0===w&&b[0]>=0||w===S[0]&&b[0]<=S[0])||J(h[0],_,Math.round(b[0]),u[0],n.scrollEasing,n.overwrite,{onStart:function(){n.callbacks&&n.onStart&&!s.tweenRunning&&(i("onScrollStart")&&(l(),c.callbacks.onScrollStart.call(t[0])),s.tweenRunning=!0,C(x),s.cbOffsets=r())},onUpdate:function(){n.callbacks&&n.onUpdate&&i("whileScrolling")&&(l(),c.callbacks.whileScrolling.call(t[0]))},onComplete:function(){if(n.callbacks&&n.onComplete){"yx"===c.axis&&clearTimeout(h[0].onCompleteTimeout);var e=h[0].idleTimer||0;h[0].onCompleteTimeout=setTimeout(function(){i("onScroll")&&(l(),c.callbacks.onScroll.call(t[0])),i("onTotalScroll")&&b[1]>=S[1]-T&&s.cbOffsets[0]&&(l(),c.callbacks.onTotalScroll.call(t[0])),i("onTotalScrollBack")&&b[1]<=k&&s.cbOffsets[1]&&(l(),c.callbacks.onTotalScrollBack.call(t[0])),s.tweenRunning=!1,h[0].idleTimer=0,C(x,"hide")},e)}}})}},J=function(e,t,o,a,n,i,r){function l(){S.stop||(x||m.call(),x=K()-v,s(),x>=S.time&&(S.time=x>S.time?x+f-(x-S.time):x+f-1,S.time<x+1&&(S.time=x+1)),S.time<a?S.id=h(l):g.call())}function s(){a>0?(S.currVal=u(S.time,_,b,a,n),w[t]=Math.round(S.currVal)+"px"):w[t]=o+"px",p.call()}function c(){f=1e3/60,S.time=x+f,h=window.requestAnimationFrame?window.requestAnimationFrame:function(e){return s(),setTimeout(e,.01)},S.id=h(l)}function d(){null!=S.id&&(window.requestAnimationFrame?window.cancelAnimationFrame(S.id):clearTimeout(S.id),S.id=null)}function u(e,t,o,a,n){switch(n){case"linear":case"mcsLinear":return o*e/a+t;case"mcsLinearOut":return e/=a,e--,o*Math.sqrt(1-e*e)+t;case"easeInOutSmooth":return e/=a/2,1>e?o/2*e*e+t:(e--,-o/2*(e*(e-2)-1)+t);case"easeInOutStrong":return e/=a/2,1>e?o/2*Math.pow(2,10*(e-1))+t:(e--,o/2*(-Math.pow(2,-10*e)+2)+t);case"easeInOut":case"mcsEaseInOut":return e/=a/2,1>e?o/2*e*e*e+t:(e-=2,o/2*(e*e*e+2)+t);case"easeOutSmooth":return e/=a,e--,-o*(e*e*e*e-1)+t;case"easeOutStrong":return o*(-Math.pow(2,-10*e/a)+1)+t;case"easeOut":case"mcsEaseOut":default:var i=(e/=a)*e,r=i*e;return t+o*(.499999999999997*r*i+-2.5*i*i+5.5*r+-6.5*i+4*e)}}e._mTween||(e._mTween={top:{},left:{}});var f,h,r=r||{},m=r.onStart||function(){},p=r.onUpdate||function(){},g=r.onComplete||function(){},v=K(),x=0,_=e.offsetTop,w=e.style,S=e._mTween[t];"left"===t&&(_=e.offsetLeft);var b=o-_;S.stop=0,"none"!==i&&d(),c()},K=function(){return window.performance&&window.performance.now?window.performance.now():window.performance&&window.performance.webkitNow?window.performance.webkitNow():Date.now?Date.now():(new Date).getTime()},Z=function(){var e=this;e._mTween||(e._mTween={top:{},left:{}});for(var t=["top","left"],o=0;o<t.length;o++){var a=t[o];e._mTween[a].id&&(window.requestAnimationFrame?window.cancelAnimationFrame(e._mTween[a].id):clearTimeout(e._mTween[a].id),e._mTween[a].id=null,e._mTween[a].stop=1)}},$=function(e,t){try{delete e[t]}catch(o){e[t]=null}},ee=function(e){return!(e.which&&1!==e.which)},te=function(e){var t=e.originalEvent.pointerType;return!(t&&"touch"!==t&&2!==t)},oe=function(e){return!isNaN(parseFloat(e))&&isFinite(e)},ae=function(e){var t=e.parents(".mCSB_container");return[e.offset().top-t.offset().top,e.offset().left-t.offset().left]},ne=function(){function e(){var e=["webkit","moz","ms","o"];if("hidden"in document)return"hidden";for(var t=0;t<e.length;t++)if(e[t]+"Hidden"in document)return e[t]+"Hidden";return null}var t=e();return t?document[t]:!1};e.fn[o]=function(t){return u[t]?u[t].apply(this,Array.prototype.slice.call(arguments,1)):"object"!=typeof t&&t?void e.error("Method "+t+" does not exist"):u.init.apply(this,arguments)},e[o]=function(t){return u[t]?u[t].apply(this,Array.prototype.slice.call(arguments,1)):"object"!=typeof t&&t?void e.error("Method "+t+" does not exist"):u.init.apply(this,arguments)},e[o].defaults=i,window[o]=!0,e(window).bind("load",function(){e(n)[o](),e.extend(e.expr[":"],{mcsInView:e.expr[":"].mcsInView||function(t){var o,a,n=e(t),i=n.parents(".mCSB_container");if(i.length)return o=i.parent(),a=[i[0].offsetTop,i[0].offsetLeft],a[0]+ae(n)[0]>=0&&a[0]+ae(n)[0]<o.height()-n.outerHeight(!1)&&a[1]+ae(n)[1]>=0&&a[1]+ae(n)[1]<o.width()-n.outerWidth(!1)},mcsInSight:e.expr[":"].mcsInSight||function(t,o,a){var n,i,r,l,s=e(t),c=s.parents(".mCSB_container"),d="exact"===a[3]?[[1,0],[1,0]]:[[.9,.1],[.6,.4]];if(c.length)return n=[s.outerHeight(!1),s.outerWidth(!1)],r=[c[0].offsetTop+ae(s)[0],c[0].offsetLeft+ae(s)[1]],i=[c.parent()[0].offsetHeight,c.parent()[0].offsetWidth],l=[n[0]<i[0]?d[0]:d[1],n[1]<i[1]?d[0]:d[1]],r[0]-i[0]*l[0][0]<0&&r[0]+n[0]-i[0]*l[0][1]>=0&&r[1]-i[1]*l[1][0]<0&&r[1]+n[1]-i[1]*l[1][1]>=0},mcsOverflow:e.expr[":"].mcsOverflow||function(t){var o=e(t).data(a);if(o)return o.overflowed[0]||o.overflowed[1]}})})})});
(function(){
  $(document).ready(function() {
    $('select').each(function () {
      $(this).selectpicker();
      $(this).parents(".bootstrap-select").find("div.dropdown-menu").first().mCustomScrollbar({
        theme:"minimal-dark",
        mouseWheel:{ preventDefault:true }
      });
    });

    // FORM
    $(".pd-text input").each(function() {
      $(this).on("input", function () {
        if ($(this).val() !== "") {
          $(this).closest('.pd-text').addClass('filled')
        } else {
          $(this).closest('.pd-text').removeClass('filled')
        }
      });
    });

    $('.bootstrap-select').on('changed.bs.select', function (e) {
      $(this).closest('fieldset').addClass("filled");
    });

    // $('input').each(function () {
    //   $(this).on('blur input', function() {
    //     if ($(this).val()) {
    //       $(this).parent().addClass('filled');
    //     }
    //     else {
    //       $(this).parent().removeClass('filled');
    //     }
    //   });
    //   $(this).focus(function(){
    //     $(this).parent().addClass('focused');
    //   }).blur(function(){
    //     $(this).parent().removeClass('focused');
    //   });
    // });

    // $("select").change(function(){
    //   // 866572 is default empty choice.
    //   if ( $(this).val() && $(this).val() != "866572" ) {
    //     $(this).parent().addClass('filled');
    //   }
    //   else {
    //     $(this).parent().removeClass('filled');
    //   }
    // });
  });
  $(window).on('load', function() {
    $('body').show();
  });
})();

