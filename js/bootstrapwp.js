// Comment section additions to add classes for bootstrap styles 
jQuery(document).ready(function(){

	// here for each comment reply link of wordpress
	jQuery( '.comment-reply-link' ).addClass( 'btn btn-primary' );

	// here for the submit button of the comment reply form
	jQuery( '#commentsubmit' ).addClass( 'btn btn-primary' );
	
	
	// The WordPress Default Widgets 
	// Now we'll add some classes for the wordpress default widgets - let's go  
	
	// the search widget	
	jQuery( 'input.search-field' ).addClass( 'form-control' );
	jQuery( 'input.search-submit' ).addClass( 'btn btn-default' );
	
	jQuery( '.widget_rss ul' ).addClass( 'media-list' );
	
	jQuery( '.widget_meta ul, .widget_recent_entries ul, .widget_archive ul, .widget_categories ul, .widget_nav_menu ul, .widget_pages ul' ).addClass( 'nav' );

	jQuery( '.widget_recent_comments ul#recentcomments' ).css( 'list-style', 'none').css( 'padding-left', '0' );
	jQuery( '.widget_recent_comments ul#recentcomments li' ).css( 'padding', '5px 15px');
	
	jQuery( 'table#wp-calendar' ).addClass( 'table table-striped');
	
		
});


