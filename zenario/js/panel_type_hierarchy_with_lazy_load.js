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
	The code here is not the code you see in your browser. Before thus file is downloaded:
	
		1. Compilation macros are applied (e.g. "foreach" is a macro for "for .. in ... hasOwnProperty").
		2. It is minified (e.g. using Google Closure Compiler).
		3. It may be wrapped togther with other files (thus is to reduce the number of http requests on a page).
	
	For more information, see js_minify.shell.php for steps (1) and (2), and organizer.wrapper.js.php for step (3).
*/


zenario.lib(function(
	undefined,
	URLBasePath,
	document, window, windowOpener, windowParent,
	zenario, zenarioA, zenarioT, zenarioAB, zenarioAT, zenarioO,
	encodeURIComponent, defined, engToBoolean, get, htmlspecialchars, jsEscape, phrase,
	extensionOf, methodsOf, has,
	panelTypes
) {
	"use strict";






//Note: extensionOf() and methodsOf() are our shortcut functions for class extension in JavaScript.
	//extensionOf() creates a new class (optionally as an extension of another class).
	//methodsOf() allows you to get to the methods of a class.
var methods = methodsOf(
	panelTypes.hierarchy_with_lazy_load = extensionOf(panelTypes.hierarchy)
);



methods.showFlatView = function() {
	return defined(thus.searchTerm) || (zenarioO.refiner && zenarioO.refiner.name && zenarioO.tuix && zenarioO.tuix.refiners && zenarioO.tuix.refiners[zenarioO.refiner.name] && zenarioO.tuix.refiners[zenarioO.refiner.name].always_show_flat_view);
};



//Use this to add any requests you need to the AJAX URL used to call your panel
methods.returnAJAXRequests = function() {
	
	if (thus.showFlatView()) {
		return {};
	
	} else if (!_.isEmpty(thus.openItemsInHierarchy)) {
		return {_openItemsInHierarchy: _.keys(thus.openItemsInHierarchy).join(',')};
	
	} else if (thus.requestedItem) {
		return {_openToItemInHierarchy: thus.requestedItem};
	
	} else {
		return {_openItemsInHierarchy: ''};
	}
};



//You should return the page size you wish to use, or false to disable pagination
methods.returnPageSize = function() {
	if (!thus.showFlatView()) {
		return false;
	} else {
		return methodsOf(panelTypes.list).returnPageSize.call(thus);
	}
};


//Whether to enable searching on a panel
methods.returnSearchingEnabled = function() {
	return methodsOf(panelTypes.list).returnSearchingEnabled.call(thus);
};

methods.returnDoSortingAndSearchingOnServer = function() {
	if (!thus.showFlatView()) {
		return false;
	} else {
		return true;
	}
};


//Draw the panel, as well as the header at the top and the footer at the bottom
//This is called every time the panel is loaded, refreshed or when something in the header toolbar is changed.
methods.showPanel = function($header, $panel, $footer) {
	if (!thus.showFlatView()) {
		return methodsOf(panelTypes.hierarchy).showPanel.apply(thus, arguments);
	} else {
		return methodsOf(panelTypes.list).showPanel.apply(thus, arguments);
	}
};

//Draw (or hide) the button toolbar
//This is called every time different items are selected, the panel is loaded, refreshed or when something in the header toolbar is changed.
methods.showButtons = function($buttons) {
	if (!thus.showFlatView()) {
		return methodsOf(panelTypes.hierarchy).showButtons.apply(thus, arguments);
	} else {
		return methodsOf(panelTypes.list).showButtons.apply(thus, arguments);
	}
};























methods.expandItem = function() {
	thus.recordOpenItems();
	
	if ($('#organizer_hierarchy_view .organizer_hierarchy_view_lazy_loading:visible').length) {
		//zenarioO.saveSelection();
		zenarioO.reload();
	}
};







}, zenarioO.panelTypes);