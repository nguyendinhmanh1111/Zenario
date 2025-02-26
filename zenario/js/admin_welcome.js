/*
 * Copyright (c) 2023, Tribal Limited
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of Zenario, Tribal Limited nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL TRIBAL LTD BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

/*
	This file contains JavaScript source code.
	The code here is not the code you see in your browser. Before this file is downloaded:
	
		1. Compilation macros are applied (e.g. "foreach" is a macro for "for .. in ... hasOwnProperty").
		2. It is minified (e.g. using Google Closure Compiler).
		3. It may be wrapped togther with other files (this is to reduce the number of http requests on a page).
	
	For more information, see js_minify.shell.php for steps (1) and (2), and admin.wrapper.js.php for step (3).
*/

zenario.lib(function(
	undefined,
	URLBasePath,
	document, window, windowOpener, windowParent,
	zenario, zenarioA, zenarioT, zenarioAB, zenarioAT, zenarioO,
	encodeURIComponent, defined, engToBoolean, get, htmlspecialchars, jsEscape, phrase,
	extensionOf, methodsOf, has,
	zenarioAF
) {
	"use strict";
	
	var zenarioAW = window.zenarioAW = new zenarioAF();
	zenarioAW.init('zenarioAW', 'zenario_welcome');


zenarioAW.enableInlineErrors = function() {
	return false;
};


zenarioAW.setData = function(data) {
	
	this.setDataDiff(data);
};

zenarioAW.sendStateToServer = function() {
	
	//Reset any errors first
	//In the FAB/FEA this is done on the server side, but on the admin login the errors don't quite sync properly
	//so need to be specifically reset first
	if (this.tuix
	 && this.tuix.tabs) {
		var t, tab;
		foreach (this.tuix.tabs as t => tab) {
			delete tab.errors;
		}
	}
	
	return this.sendStateToServerDiff();
};




zenarioAW.draw = function() {
	if (zenarioAW.tuix) {
		
		if (zenarioAW.tuix._clear_local_storage) {
			delete zenarioAW.tuix._clear_local_storage;
			zenario.sClear(true);
		}
		
		if (defined(zenarioAW.tuix.go_to_url)) {
			document.location.href = zenario.addBasePath(zenarioAW.tuix.go_to_url);
			return;
		} else if (defined(zenarioAW.tuix._task)) {
			zenarioAW.task = zenarioAW.tuix._task;
			delete zenarioAW.tuix._task;
		}
	}
	
	if (zenarioAW.loaded && zenarioAW.tabHidden) {
		zenarioAW.draw2();
	}
};


zenarioAW.draw2 = function() {
	
	zenarioA.nowDoingSomething(false);
	$('#zenario_abtab input').add('#zenario_abtab select').add('#zenario_abtab textarea').clearQueue();
	
	
	var cb = new zenario.callback,
		html = zenarioAW.drawFields(cb);
	
	//Set a CSS class name around the outer box, depending on what mode this is
	get('welcome_outer').className = 'admin_welcome_' + zenarioAW.tuix.path;
	
	//On the Admin Login screen, drop in the tab if this is the first time we're showing the box
	if (zenarioAW.shownTab === false) {
		zenarioAW.insertHTML(html, cb);
		
		$('#welcome').show({effect: 'drop', direction: 'up', duration: 300, complete: function() {
			zenarioAW.addJQueryElementsToTab();
			zenarioAW.focusFirstField();
		}});
		zenario.addJQueryElements('#zenario_abtab ', true);	
	
	} else {
		zenarioAW.animateInTab(html, cb, $('#welcome'));
	}

	zenarioAW.shownTab = zenarioAW.tuix.tab;
	delete zenarioAW.lastScrollTop;
};


//Get a URL needed for an AJAX request
zenarioAW.returnAJAXURL = function() {
	return URLBasePath +
		'zenario/admin/welcome.ajax.php' +
		'?task=' + encodeURIComponent(zenarioAW.task) +
		'&get=' + encodeURIComponent(JSON.stringify(zenarioAW.getRequest)) +
		zenario.urlRequest(zenarioAW.key || zenarioAW.tuix.key);
};

zenarioAW._nextLoadIsAnInstall = false;
zenarioAW.retryAJAX = function(url, post, json, done, nowDoingSomething, onCancel) {
	
	if (nowDoingSomething === 'loading'
	 && zenarioAW._nextLoadIsAnInstall) {
		nowDoingSomething = 'installing';
		zenarioAW._nextLoadIsAnInstall = false;
	}
	
	methodsOf(zenarioAF).retryAJAX.call(zenarioAW, url, post, json, done, nowDoingSomething, onCancel);
};

	
	



//Quickly add validation for a few things on the Welcome page as the user types.
//Also used for directories in the Site Settings
zenarioAW.quickValidateWelcomePage = function(delay) {
	zenario.actAfterDelayIfNotSuperseded('quickValidateWelcomePage', function() { zenarioAW.quickValidateWelcomePageGo(); }, delay);
};

zenarioAW.quickValidateWelcomePageGo = function() {
	
	var f, field,
		rowClasses = {},
		url = URLBasePath + 'zenario/admin/welcome.ajax.php?quickValidate=1';
	
	foreach (this.tuix.tabs[this.tuix.tab].fields as f => field) {
		rowClasses[f] = field.row_class || '';
	}
	
	//zenario.ajax(url, post, json, useCache, retry, continueAnyway, settings, timeout, AJAXErrorHandler, onRetry, onCancel)
	zenario.ajax(url,
		{
			tab: this.tuix.tab,
			path: this.path || '',
			values: JSON.stringify(this.readTab()),
			row_classes: JSON.stringify(rowClasses)
		},
		true, false, true, true
	).after(function(data) {
	
		if (data && data.row_classes) {
			foreach (data.row_classes as f) {
				if (zenarioAW.tuix.tabs[zenarioAW.tuix.tab].fields[f] && zenarioAW.get('row__' + f)) {
					zenarioAW.tuix.tabs[zenarioAW.tuix.tab].fields[f].row_class = data.row_classes[f];
					zenarioAW.get('row__' + f).className = 'zenario_ab_row zenario_ab_row__' + f + ' ' + data.row_classes[f];
				}
			}
		}
		
		if (data && data.snippets) {
			foreach (data.snippets as f) {
				if (zenarioAW.tuix.tabs[zenarioAW.tuix.tab].fields[f]
				 && zenarioAW.tuix.tabs[zenarioAW.tuix.tab].fields[f].snippet
				 && zenarioAW.tuix.tabs[zenarioAW.tuix.tab].fields[f].snippet.html && zenarioAW.get('snippet__' + f)) {
					zenarioAW.tuix.tabs[zenarioAW.tuix.tab].fields[f].snippet.html = data.snippets[f];
					zenarioAW.get('snippet__' + f).innerHTML = data.snippets[f];
				}
			}
		}
	});
	
	return true;
};


////T9732, Admin login panel, show warning when a redirect from other URL has occurred
zenarioAW.refererHostWarning = function(msg) {
	if (msg) {
		toastr.warning(msg, undefined, {timeOut: 0, extendedTimeOut: 0});
	}
};


//These functions need to be defined to enable file uploads
zenarioAW.getKey = function() {
	return {};
};
zenarioAW.ajaxURL = function() {
	return URLBasePath + 'zenario/admin/welcome.ajax.php?method_call=handleWelcomeAJAX';
};

//Try to disable the ability to navigate away from the page by dragging a file or an image into the browser from the filesystem
if (zenarioT.canDoHTML5Upload()) {
	zenarioT.disableFileDragDrop(document.body);
};





},
	zenarioAF
);