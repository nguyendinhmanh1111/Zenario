(function(h){h.plugins.Safari2x=function(d){function n(a,c){var e=d.getDoc(),f,g;c=c||{};e.execCommand("FontName",!1,"_tmp");k(d.dom.select("span"),function(d){"_tmp"==d.style.fontFamily&&(f=p(d,a,c),g||(g=f))});f=f.firstChild;d.selection.getSel().setBaseAndExtent(g,0,f,f.nodeValue.length)}function q(a){var c=d.selection;c.setContent("<"+a+'><li id="_tmp"></li></'+a+">");a=d.dom.get("_tmp");a.id="";c.select(a);c.collapse(1)}function m(a){return d.dom.getParent(a,function(a){return/^(H[1-6]|P|DIV|ADDRESS|PRE|FORM|TABLE|LI|OL|UL|TD|CODE|CAPTION|BLOCKQUOTE|CENTER|DL|DT|DD|DIR|FIELDSET|NOSCRIPT|NOFRAMES|MENU|ISINDEX|SAMP)$/.test(a.nodeName)})}
function p(a,c,e){var f=d.getDoc(),g;e=e||{};g=f.createElement(c);k(a.attributes,function(a){a.specified&&a.nodeValue&&g.setAttribute(a.nodeName,a.nodeValue)});k(e,function(a,c){g.setAttribute(c,a)});k(a.childNodes,function(a){g.appendChild(a.cloneNode(!0))});a.parentNode.replaceChild(g,a);return g}var k=h.each,l=h.Event,s,t;h.isOldWebKit&&(d.selection.getRng=function(){var a=this.getSel(),c=d.getDoc(),e,f,g,h;if(a.anchorNode){e=c.createRange();try{f=c.createRange(),f.setStart(a.anchorNode,a.anchorOffset),
f.collapse(1),g=c.createRange(),g.setStart(a.focusNode,a.focusOffset),g.collapse(1),h=0>f.compareBoundaryPoints(f.START_TO_END,g),e.setStart(h?a.anchorNode:a.focusNode,h?a.anchorOffset:a.focusOffset),e.setEnd(h?a.focusNode:a.anchorNode,h?a.focusOffset:a.anchorOffset)}catch(k){}}return e},s=d.selection.setContent,d.selection.setContent=function(a,c){var e=this.getRng();try{s.call(this,a,c)}catch(f){b=d.dom.create("body"),b.innerHTML=a,k(b.childNodes,function(a){e.insertNode(a.cloneNode(!0))})}},t=
d.selection.collapse,d.selection.collapse=function(a){try{t.call(this,a)}catch(c){}},d.onInit.add(function(){h.DOM.get(d.settings.id+"_r").style.display="none"}),d.onPreInit.add(function(){l.add(d.getDoc(),"click",function(a){if("A"==a.target.nodeName)return d.selection.select(a.target),l.cancel(a)});l.add(d.getDoc(),"keydown",function(a){var c=d.selection,e,f;if(32<a.charCode||13==a.keyCode)if(e=d.dom.getParent(c.getNode(),function(a){return"LI"==a.nodeName}))if(c.getRng(),13==a.keyCode){if(!e.hasChildNodes()){if(!e.nextSibling||
"LI"!=e.nextSibling.nodeName){f=d.dom.getParent(c.getNode(),function(a){return/(UL|OL)/.test(a.nodeName)});e.parentNode.removeChild(e);c.select(f.nextSibling);return}return l.cancel(a)}f=d.getDoc().createTextNode("\u00a0");e.appendChild(f);window.setTimeout(function(){var a=c.getNode();a.firstChild&&"\u00a0"==a.firstChild.nodeValue.charAt(0)&&(a.removeChild(a.firstChild),c.select(a))},1)}else if(f=String.fromCharCode(a.charCode),/^\w$/.test(f))return c.setContent(f),f=c.getRng(),c=c.getSel(),e=c.anchorNode,
"LI"!=e.nodeName&&(e=e.nextSibling),c.setBaseAndExtent(e,1,e,1),l.cancel(a)})}),h.extend(d.commands,{IncreaseFontSize:function(){var a=d.getDoc(),c=parseInt(a.queryCommandValue("FontSize"));a.execCommand("FontSize",!1,c+1+"px")},DecreaseFontSize:function(){var a=d.getDoc(),c=parseInt(a.queryCommandValue("FontSize"));0<c&&a.execCommand("FontSize",!1,c-1+"px")},Strikethrough:function(){n("strike")},CreateLink:function(a,c){n("a",{href:c,mce_href:c})},Unlink:function(){var a=d.selection;a.setContent(a.getContent().replace(/(<a[^>]+>|<\/a>)/,
""))},RemoveFormat:function(){var a=d.selection;a.setContent(a.getContent().replace(/(<(span|b|i|strong|em|strike) [^>]+>|<(span|b|i|strong|em|strike)>|<\/(span|b|i|strong|em|strike)>|)/g,""))},FormatBlock:function(a,c){var e=d.selection,f;(f=m(d.selection.getNode()))&&(r=p(f,c.replace(/<|>/g,"")));e.select(r);e.collapse(1)},InsertUnorderedList:function(){q("ul")},InsertOrderedList:function(){q("ol")},Indent:function(){var a=m(d.selection.getNode()),c;a&&(c=parseInt(a.style.paddingLeft)||0,a.style.paddingLeft=
c+10+"px")},Outdent:function(){var a=m(d.selection.getNode()),c;a&&(c=parseInt(a.style.paddingLeft)||0,10<=c&&(a.style.paddingLeft=c-10+"px"))}}))}})(punymce);
