(function() {
	
	var acePath = URLBasePath + 'zenario/libs/manually_maintained/bsd/ace/src-min-noconflict/';

	ace.config.set('basePath', acePath);
	ace.config.set('modePath', acePath);
	ace.config.set('workerPath', acePath);
	ace.config.set('themePath', acePath);

})();