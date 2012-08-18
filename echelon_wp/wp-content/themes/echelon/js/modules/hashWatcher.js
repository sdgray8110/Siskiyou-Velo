/*!
 * URL HASH WATCHER
 *
 * REQUISITES:
 * -- No explicit dependencies but the event binding method described below assumes jQuery is available.
 *
 * COMMENTS:
 * -- Register hash change events as follows
 * -- $(window).bind('hashChange',function(){ yourEvent(); });
 * -- Events should set UrlHashWatcher.ajaxActive=1 during an ajax request and should call UrlHashWatcher.setNewPageHash(newHash) when complete
 *
 * Authored by Spencer Gray
 *
 */

var UrlHashWatcher = {
	
	intervalTime: 500,
	ajaxActive: 0,
	currentHash:'',	
	lastHash:'',		
	settingHash: false,
	init: function(){
		if( this.initialized ) return; 
	
		if( $.browser.msie && $.browser.version.substr(0,1)<7 ) return;
		this.timeout = setTimeout( function (){ UrlHashWatcher.changeCheck(); }, this.intervalTime);
		this.initialized=1;
	},
		
	changeCheck: function(){ 
		var hash = window.location.hash;
		hash = this.stripHash(hash);
		
		if (hash!=this.lastHash && !this.ajaxProcessing()) {

			this.currentHash=hash;
			
		    	$(window).trigger('hashChange');
		    	this.lastHash=hash;
		}
		
		this.timeout = setTimeout( function(){ UrlHashWatcher.changeCheck(); }, this.intervalTime);
	}, 
	
	setNewPageHash: function(newHash){
		newHash = this.stripHash(newHash);
		
		if(this.lastHash !== newHash) {
			this.lastHash=newHash; 
			window.location.hash = newHash;
		}
		
		this.ajaxActive = false;
	},
	
	stripHash : function(hash){
		if(hash.indexOf('#') == 0) {
			hash = hash.split('#')[1];
		}
		return hash;
	},
	
	ajaxProcessing: function(){
		if(typeof(facetData) == 'undefined') {
			return this.ajaxActive;
		} else {
			return facetData.processing;
		}
	}
		
};

UrlHashWatcher.init();