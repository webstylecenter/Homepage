!function(e){var t={};function n(o){if(t[o])return t[o].exports;var a=t[o]={i:o,l:!1,exports:{}};return e[o].call(a.exports,a,a.exports,n),a.l=!0,a.exports}n.m=e,n.c=t,n.d=function(e,t,o){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:o})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var o=Object.create(null);if(n.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var a in e)n.d(o,a,function(t){return e[t]}.bind(null,a));return o},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="/build/",n(n.s=26)}([,,,,,,,,,,,,,,,,function(e,t,n){"use strict";$(function(){$('.registerBox input[type="submit"]').on("click",function(e){$('.registerBox input[type="checkbox"]').is(":checked")||(e.preventDefault(),showDialog("Registration can't continue","You must agree if you want to use our services"))})})},function(e,t,n){"use strict";$(function(){$(document).on("click",".widget-custom-item",function(){var e=$(this).data("url");""===e&&(e="/nourl/"),parent.$(".content-frame").attr("src",e),parent.$(".urlbar a").text(e).attr("href",e),parent.$(".js-copy-to-clipboard").attr("data-clipboard-text",e).addClass("show-if-mobile")}),$(".weather--content").load("/weather/detail/")})},function(e,t,n){"use strict";$(function(){var e;$(".js-settings-remove-feed").on("click",function(){var e=$(this).parent().parent().data("feed-id"),t=$(this).parent().parent().data("feed-name"),n=confirm("Are you sure you want to remove "+t+"?"),o=$(this);n&&$.post("/settings/feeds/remove/",{feedId:e}).done(function(){$(o).parent().parent().addClass("removed")}).fail(function(e){showDialog("Error","Cannot remove feed. Please try again later.<br /><br />"+e.toString().substr(0,200))})}),$(".spectrum").spectrum({color:$(this).val(),allowEmpty:!1,preferredFormat:"hex"}),$(".js-settings-add-feed").on("click",function(){var e=$(this).parent().find("[name='url']").val(),t=$(this).parent().find("[name='color']").val(),n=$(this).parent().find("[name='icon']").val(),o=$(this).parent().find("[name='website']").val(),a=$(this).parent().find("[name='autoPin']").prop("checked");0!==e.length||0!==o.length?$.post("/settings/feeds/add/",{url:e,website:o,color:t,icon:n,autoPin:!!a}).done(function(e){"success"===e.status?location.reload():showDialog("Cannot add Feed","An error occured while adding the feed:<br /><br />"+e.message.substr(0,300))}).fail(function(e){showDialog("Cannot add Feed","An error occured while adding the feed:<br /><br />"+e.toString().substr(0,300))}):showDialog("Invalid input","Please enter a website url or RSS feed-url")}),$(".js-update-feed-color").on("change",function(){var e=$(this).val(),t=$(this).parent().parent().parent().data("feed-id");$.post("/settings/feeds/update/",{id:t,color:e}).done(function(){}).fail(function(e){showDialog("Failed to update color","Your color setting cannot be changed at the moment because of a server error. Please try again later.<br /><br /><small>"+e.toString().substr(0,200)+"</small>")})}),$(".js-update-auto-pin").on("click",function(){var e=$(this).parent().parent().data("feed-id"),t="on",n=$(this);$(this).prop("checked")||(t="off"),$(n).hide(),$.post("/settings/feeds/update/",{id:e,autoPin:t}).done(function(){$(n).show()}).fail(function(e){showDialog("Failed to update setting","Your auto pin setting cannot be changed at the moment because of a server error. Please try again later.<br /><br /><small>"+e.toString().substr(0,200)+"</small>"),$(n).show()})}),$(".js-open-icon-selector").on("click",function(){$(".iconSelector input").val($(this).parent().parent().data("feed-id")),$(".iconSelector").modal({fadeDuration:100}),e=$(this)}),$(".iconSelector .fa, .iconSelector button").on("click",function(){var t=$(this).attr("class").replace("fa fa-",""),n=$(this).parent().parent().find("input").val();""===t&&(t="plus emptyIcon"),parseInt(n)>0&&$.post("/settings/feeds/update/",{id:n,icon:t}).done(function(){$(".iconSelector input").val(""),$(".iconSelector").hide(),$(".jquery-modal").hide(),$(e).attr("class","fa fa-"+t)}).fail(function(e){showDialog("Failed to update setting","Your auto pin setting cannot be changed at the moment because of a server error. Please try again later.<br /><br /><small>"+e.toString().substr(0,200)+"</small>")})})})},function(e,t,n){"use strict";$(function(){$(".js-search-feed").on("keyup",function(){o($(this).val())}).on("click",function(){""!==$(this).val()&&$(".js-search-list").slideDown()}).on("blur",function(){$(".js-search-list:not(.doNotHide)").slideUp()}).on("keydown",function(e){13===e.which&&s($(this))}),$(".js-search-list").on("click",".feed-list-item--state-button",function(){r(this)})});var o=function(e){$(".js-search-list").html("").slideDown(),""!==e&&"http"!==e.substring(0,4)&&$.getJSON("/feed/search/?query="+encodeURIComponent(e),function(t){if("success"===t.status){var n=$("#js-search-result").html(),o=Handlebars.compile(n);$(".js-search-list").html(o({results:t.data,query:e}))}})},a=function(e){i({item:e})},i=function(e){$.post("/checklist/add/",e).then(function(e){$(".checklist--list").html(e),$('.checklist--form input[type="text"]').val(""),$(".js-checklist-item").on("click",function(){checkItem(this)})}).catch(function(){return showDialog("Error ","Updating checklist failed. Please try again later."),!1})},r=function(e){var t=$(e).find("b").html();a(t),$(e).html("<b>"+t+" added to checklist!"),$(".s-search-feed").val("")},s=function(e){var t=$(e).val();$(".js-search-list").hide(),"http"===t.substring(0,4)?($(e).val("Fetching meta data..."),$.post("/meta/",{url:t},function(n){if($(e).val("Adding data to feed..."),$(".js-search-list").hide(),!n.data.title)return $(".js-form-feed modal").modal({fadeDuration:100}),$('.js-form-feed [name="title"]').val(t),void $('.js-form-feed [name="description"]').val(n.data.description);$.post("/feed/add-item/",{url:t,title:n.data.title,description:n.data.description},function(n){"success"!==n.status?($(e).val(t),showDialog("Failed to add item!","Failed to add item due to a server error.")):($(e).val(""),requestNewFeedItems(),$.modal.close()),$(".js-search-list").hide()},"json")},"json")):a(t)}},function(e,t,n){"use strict";$(function(){$(".screensaver--newsticker-title").html()&&(setInterval(function(){setTimeout(a,5e3)},18e4),setInterval(i,1e3),setInterval(r,3e5),setInterval(c,2e4),setTimeout(s,3e3),a(),r())});var o=0;function a(){var e=new Image,t=$.now();e.src="/screensaver/images/"+t+".jpg",e.onload=function(){$(".notActive").css("background-image",'url("/screensaver/images/'+t+'.jpg")').fadeIn(3e3),$(".active").fadeOut(3e3),$(".notActive, .active").toggleClass("active notActive")}}function i(){var e=new Date,t=e.getHours(),n=e.getMinutes();t<10&&(t="0"+t),n<10&&(n="0"+n),$(".screensaver--time").html(t+":"+n)}function r(){$(".screensaver .weather").load("/weather/icon/")}function s(){$(".screensaver--newsticker,.screensaver--time,.screensaver--newsticker-source,.screensaver--newsticker-title").fadeToggle("slow")}function c(){$(".screensaver--newsticker-source, .screensaver--newsticker-title").fadeToggle("slow"),setTimeout(l,1e3)}function l(){++o>49&&(o=0),$(".screensaver--newsticker-source").html(newsItems[o][0]).attr("class","screensaver--newsticker-source").fadeToggle("slow").css("backgroundColor",newsItems[o][3]),$(".screensaver--newsticker-title").html(newsItems[o][1]+"<span>"+newsItems[o][2]+"</span>").fadeToggle("slow")}},function(e,t){var n;n=function(){return this}();try{n=n||Function("return this")()||(0,eval)("this")}catch(e){"object"==typeof window&&(n=window)}e.exports=n},function(e,t,n){"use strict";(function(e){function t(e,t){var a=$(".feed-list--type-sidebar").attr("data-is-mobile"),i=$(".feed-list--type-sidebar").attr("data-hideXframe");$(".profileMenu").slideUp(),"1"===a?n(e,t):"1"===i?o(e,t):n(e,t)}function n(e,t){$.post("/feed/check-header/",{url:e}).then(function(n){!0===n.found?function(e){window.open(e),$(".feed-list--type-sidebar").attr("data-is-mobile")||$(".content-frame").attr("src","/feed/opened-in-popup/")}(e):o(e,t)})}function o(e,t){$(".content").removeClass("hide-if-mobile"),$("aside").addClass("hide-if-mobile"),$(".Homepage").addClass("pageOpen"),$("footer .defaultView").hide(),$("footer .pageView").show(),$(".content-frame").attr("src",i(e,!0)),$(".urlbar a").text("https://"+window.location.hostname+"/share/"+t).attr("href",e),$(".js-copy-to-clipboard").attr("data-clipboard-text","https://"+window.location.hostname+"/share/"+t)}function a(e,t){var n=document.createElement("iframe");n.className=e,"https:"===location.protocol&&(t=t.replace("http://","https://")),n.src=t,$(n).attr("sandbox","allow-scripts allow-same-origin allow-forms allow-popups allow-pointer-lock allow-modals"),$(n).attr("allowfullscreen","allowfullscreen"),$(".iFramesContainer").append(n),$(".content-close-pip").show(),$(".content-maximize-pip").show()}function i(e,t){var n=e.replace("https://www.youtube.com/watch?v=","");return e!==n?(t&&$(".feed-list").addClass("darkTheme",2e3,"easeInOutQuad"),$(".header--bar, footer").css("backgroundColor","#1a1a1a"),"https://www.youtube.com/embed/"+n+"?autoplay=true"):(t&&$(".feed-list").removeClass("darkTheme","",2e3,"easeInOutQuad"),"https:"===location.protocol&&(e=e.replace("http://","https://")),e)}$(function(){$(".feed-list").jscroll({padding:150,nextSelector:"a.jscroll-next:last",contentSelector:".feed-list-item",callback:function(){$(".jscroll-added:last-of-type .js-action-feed-list-swipe").each(function(){var e=new Hammer(this),t=$(this);e.on("swiperight",function(e){$(t).find(".pin").trigger("click")})})}}),$(".header--bar").each(function(){new Hammer(this).on("swiperight",function(e){$(".js-return").trigger("click")})}),$(document).on("click",".js-open-url",function(){$(".header--bar, footer").css("backgroundColor","#337dff"),t(""!==$(this).data("url")?$(this).data("url"):"/nourl/",$(this).data("share-id"))}).on("click",".js-action-feed-list-click",function(){$(this).addClass("animated pulse feed-list-item--state-selected"),$(".feed-list-item").removeClass("feed-list-item--state-selected"),$(".header--bar, footer").css("backgroundColor",$(this).css("borderLeftColor")),t(""!==$(this).data("url")?$(this).data("url"):"/nourl/",$(this).data("share-id"))}).on("click",".js-return",function(e){e.preventDefault(),$(".header--bar, footer").css("backgroundColor","#337dff"),$(".content iframe").prop("src","/welcome/"),$(".content").addClass("hide-if-mobile"),$("aside").removeClass("hide-if-mobile"),$(".Homepage").removeClass("pageOpen"),$("footer .pageView").hide(),$("footer .defaultView").show()}).on("click",".js-reload-page",function(){event.preventDefault(),$(".content-frame").attr("src","/welcome/"),$(".urlbar a").text("").attr("data-clipboard-text",""),$(".header--bar, footer").css("backgroundColor","#337dff"),$("aside").scrollTop(0),requestNewFeedItems()}).on("click",".pin",function(e){e.stopImmediatePropagation();var t=this;$.post("/feed/pin/"+$(this).data("pin-id"),function(){$(t).parent().addClass("animated shake"),$(t).parent().toggleClass("feed-list-item--state-pinned")},"json")}).on("click",".pip",function(e){var t;e.stopImmediatePropagation(),t=i($(this).parent().data("url"),!1),$(".content-pictureInPictureFrame").remove(),a("content-pictureInPictureFrame",t)}).on("click",".js-close-pip",function(){$(".content-pictureInPictureFrame").remove(),$(".content-close-pip").hide(),$(".content-maximize-pip").hide()}).on("click",".js-modal-trigger",function(){$($(this).data("modal-target")).modal({fadeDuration:100})}).on("click",".js-form-feed button",function(){$.post("/feed/add-item/",$(".js-form-feed").serialize(),function(e){"success"===e.status?$.modal.close():showDialog("Adding item failed!","Failed to add item due to a server error.")},"json")}).on("click",".js-open-new-window",function(){window.open($(".urlbar a").attr("href"))}).on("click",".js-visbility-toggle",function(){$($(this).data("target")).toggle()}).on("click",".js-send-to-pip",function(){$(".content-pictureInPictureFrame").remove(),$(".content-frame").addClass("content-pictureInPictureFrame").removeClass("content-frame"),a("content-frame","")}).on("click",".js-send-from-pip",function(){$(".content-frame").remove(),$(".content-pictureInPictureFrame").addClass("content-frame").removeClass("content-pictureInPictureFrame"),$(".content-close-pip").hide(),$(".content-maximize-pip").hide()}).on("click",".js-open-profile-menu",function(){$(".profileMenu").slideToggle(),$(".content-overlay").fadeIn()}).on("click",".profileMenu",function(){$(".profileMenu").slideUp(),$(".content-overlay").fadeOut()}),$(".js-action-feed-list-swipe").each(function(){var e=new Hammer(this),t=$(this);e.on("swiperight",function(e){$(t).find(".pin").trigger("click")})}),new ClipboardJS(".js-copy-to-clipboard").on("success",function(e){e.clearSelection()})}),e.requestNewFeedItems=function(){$.getJSON("/feed/refresh/",function(e){$(".feed-list").prepend(e),$(".noFeedItems").html(e).addClass("feed-list").removeClass("noFeedItems"),$(".js-form-feed").find("input[type=text], textarea").val("")})}}).call(this,n(21))},function(e,t,n){"use strict";var o="https://pvd.onl/";function a(){$(".js-screenshot-list").slideUp(),$(".content-overlay").fadeOut()}function i(){new ClipboardJS(".dropCopy"),$(".dropOpen").on("click",function(){var e=$(this).data("image");window.open(o+e,"_blank").focus(),a()}),$(".dropCopy").on("click",function(){$(this).css("color","red")}),$(".dropHide").on("click",function(){r(this,$(this).data("image"),"hide")}),$(".dropRemove").on("click",function(){r(this,$(this).data("image"),"delete")})}function r(e,t,n){$.ajax({method:"GET",url:o+"pages/"+n+".php",data:{file:t}}).done(function(t){$(e).parents(":eq(2)").html(t).css("background","none").css("background-color","rgba(107, 193, 103, 0.7)").css("text-align","center").css("height","auto").css("padding","20px 0")})}$(".js-toggle").on("click",function(e){e.preventDefault();var t=$($(this).data("toggle-element"));t.load(t.data("target"),function(){$(".js-screenshot-list").slideDown(),$(".content-overlay").fadeIn(),i()})}),$(".js-more-droplist").on("click",function(){window.location.href="/droplist/all"}),$(".content-overlay, .feed-list").on("click",function(){a(),$(".profileMenu").slideUp()}),$(function(){i()})},function(e,t,n){"use strict";function o(e){a({id:$(e).data("database-id"),checked:$(e).is(":checked")})}function a(e){$.post("/checklist/add/",e).then(function(e){$(".checklist--list").html(e),$('.checklist--form input[type="text"]').val(""),$(".js-checklist-item").on("click",function(){o(this)})}).catch(function(){return showDialog("Error","Updating checklist has failed! Please try again in a moment."),!1})}$(function(){$('.checklist--form input[type="button"]').on("click",function(){$('.checklist--form input[type="text"]').val()&&a({item:$('.checklist--form input[type="text"]').val()})}),$('.checklist--form input[type="text"]').keypress(function(e){13===e.which&&$('.checklist--form input[type="button"]').click()}),$(".js-checklist-item").on("click",function(){o(this)})})},function(e,t,n){"use strict";function o(){var e=$('.js-form-feed [name="url"]').val();e.length>0&&($('.js-form-feed [name="title"]').val("Loading info..."),$('.js-form-feed [name="description"]').val(""),$.ajax({method:"POST",url:"/meta/",data:{url:e}}).done(function(e){"success"==e.status?($('.js-form-feed [name="title"]').val(e.data.title),$('.js-form-feed [name="description"]').val(e.data.description)):($('.js-form-feed [name="title"]').val(""),$('.js-form-feed [name="description"]').val(""))}))}$(function(){$(".js-button-parse-url").on("click",function(){o()}),$('.js-form-feed [name="url"]').on("blur",function(){o()}),$(".widget-note textarea").on("blur",function(){var e,t,n,o,a;e=$(this),t=e.attr("data-id"),n=e.parent().find("input").val(),o=e.attr("data-position"),a=e.val(),$.ajax({method:"POST",url:"/note/save/",data:{id:t,position:o,name:n,note:a},beforeSend:function(){e.css("color","#303030")}}).done(function(o){e.css("color","black"),$(".note-selector-"+t).text(n),0===t.length&&e.attr("data-id",o.data.id)}).fail(function(){e.css("color","red")})}),$(".widget-note input").on("blur",function(){$(this).parent().find("textarea").trigger("blur")}),setInterval(function(){$(".js-update-weahter-icon").load("/weather/icon/"),$(".js-weather-radar").attr("src","https://api.buienradar.nl/image/1.0/RadarMapNL?w=500&h=512&time="+Math.random())},3e5),$(document).on("click",".js-open-note",function(){$(".widget-note--notes > div").hide(),$(".note-data-"+$(this).data("note-id")).show()}).on("click",".js-remove-note",function(){var e;e=$(this).data("id"),confirm("Are you sure you want to remove this note?")&&$.ajax({method:"POST",url:"/note/remove/",data:{id:e}}).done(function(){$(".note-selector-"+e).hide(),$(".note-data-"+e).hide()})}).on("click",".js-update-weather-icon",function(){$(".content-overlay").fadeIn(),$(".js-show-weather-radar").slideDown()}).on("click",".content-overlay, .feed-list",function(){$(".content-overlay").fadeOut(),$(".js-show-weather-radar").slideUp(),$(".profileMenu").slideUp()}),$(".specialTxt").each(function(){$(this).html('<a href="mailto:peter@petervdam.nl">peter@petervdam.nl</a>')}),$(".page--homepage").on("click",function(){$(".page--homepage .header").animate({height:"30vh"},500),$(".mainContent, .widget").fadeIn()}),$(".js-homepage-showpage").on("click",function(){$(".view").slideUp().delay(100),$(".mainContent nav span").removeClass("active"),$("."+$(this).data("page")).slideDown(),$(this).addClass("active"),$(".page--homepage .feeds").load("/feeds/overview/")}),$(".js-toggle-fullscreen").on("dblclick",function(){var e=$(".container .feed-list").css("width"),t="#337dff";"0px"!==$(".content").css("left")&&(e=0,t="#000"),$(".header--bar-actions").toggle("slow"),$(".header--bar").css("backgroundColor",t),$(".content").animate({left:e},1e3)})})},function(e,t,n){"use strict";n(25),n(24),n(23),n(22),n(20),n(19),n(18),n(17),n(16),window.showDialog=function(e,t){$(".dialog .title").html(e),$(".dialog .description").html(t),$(".dialog").modal({fadeDuration:100})}}]);