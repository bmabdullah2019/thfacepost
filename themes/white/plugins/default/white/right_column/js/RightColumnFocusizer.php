//<script>
$(document).ready(function() {	
	var comRightColumnFocusizerPosition = $(window).scrollTop(); 
	var comRightColumnFocusizerWidth    = $(window).width();
	var comRightColumnFocusizerOldFix   = 0;
	var comRightColumnFocusizerPushed   = false;
	var themeAwesome = false;
	
	if($('#navbarNavDropdown').length) {
		themeAwesome = true;
	}

	$(window).on('load resize scroll', function (e) {
		var windowHeight;
		var adsHeight;
		var adsWidth;
		var adsTop;
		var scrollPosition;
		var newsColumn;
		var newsColumnHeight;
		var adsColumn;
		var focusColumn = false;
		var focusColumnOffset;
		var topbarHeight;
		var fixedTopAdjust;
		const FLOATING_BOTTOM_MARGIN = 20;
		const CONTENT_TOP_MARGIN = 10;
		const CORE_FOOTER_HEIGHT = 48;
		
		// verify if the current page has a matching center-column class
		var feed_columns = ['.newsfeed-middle', '.ossn-profile-wall', '.group-wall', '.user-activity'];
		var side_columns = ['.newsfeed-right', '.ossn-profile-sidebar', '.page-sidebar', '.business-page-right-bottom'];
		for(var i = 0; i < feed_columns.length; i++) {
			if($(feed_columns[i]).length) {
				newsColumn       = $(feed_columns[i]).parent("[class^=col-md-]");
				newsColumnHeight = $(newsColumn).innerHeight();
				// get right neighbour column of newsfeed (= ads column)
				adsColumn        = $(side_columns[i]).parent("[class^=col-md-]");
				focusColumn      = $(adsColumn).children();
				break;
			}
		}

		if(!focusColumn) {
			return;
		}
		// give bootstrap a chance to take control of re-ordering columns
		if(e.type == 'resize') {
			if($(adsColumn).css('flex') == '0 0 auto' && comRightColumnFocusizerPushed) {
				// on resizing -> wider and right column has been moved below the newsfeed by former resizing
				// restore old fixed top
				$(focusColumn).css("top", comRightColumnFocusizerOldFix);
				comRightColumnFocusizerPushed = false;
			}
			if($(adsColumn).css('flex') == 1) {
				if(!comRightColumnFocusizerPushed) {
					// on resizing -> smaller 
					// remember old fixed top position before first bootstrap re-ordering
					comRightColumnFocusizerOldFix = parseFloat($(focusColumn).css("top"));
					comRightColumnFocusizerPushed = true;
				}
				// release fixed top - otherwise bootstrap would fail with re-ordering
				$(focusColumn).css('position', 'relative');
				$(focusColumn).css('top', 0);
				$(focusColumn).css('width', 'auto');
				return;
			}
		}
		// if we found a right column, make sure it's located horizontally side-by-side and not below the newsfeed (= mobile mode)
		// bootstrap column col-md-x set to float=left means: column appears horizontally in line with other columns
		// float=none means: column appears vertically BELOW other columns (in this case wall) and we MUST NOT fix the ads
		// unfortunately Theme Awesome is different and we can't rely on only one recognizing mechanism :(
		if(focusColumn && (!themeAwesome && $(adsColumn).css("flex") == '0 0 auto') || (themeAwesome && $(focusColumn).css('display') != 'none')) {
			// watch out for fixed topbars !
			if($('.topbar').css('position') == 'fixed') {
				topbarHeight = $('.topbar').height() + CONTENT_TOP_MARGIN;
				fixedTopAdjust = topbarHeight - CONTENT_TOP_MARGIN;
			}
			else {
				topbarHeight = CONTENT_TOP_MARGIN;
				fixedTopAdjust = 0;
			}
				
			// calculate scroll direction and position
			var scroll_top = $(window).scrollTop();
			var scroll_difference;
			var scroll_down;
			if(scroll_top > comRightColumnFocusizerPosition) {
				scroll_difference = scroll_top - comRightColumnFocusizerPosition;
				scroll_down = true;
			} else {
				scroll_difference = comRightColumnFocusizerPosition - scroll_top;
				scroll_down = false;
			}
			comRightColumnFocusizerPosition = scroll_top;

			// reset some css here, otherwise the following calculations
			// might return values of the formerly fixed column
			$(focusColumn).css('width', 'auto');
			$(focusColumn).css('height', 'auto');
			$(focusColumn).css('position', 'static');
				

			windowHeight      = $(window).height();
			focusColumnOffset = $(focusColumn).offset();
			adsTop            = focusColumnOffset.top;
			adsHeight         = $(focusColumn).outerHeight() + adsTop + FLOATING_BOTTOM_MARGIN + fixedTopAdjust;
			adsWidth          = $(focusColumn).outerWidth();
			e.stopPropagation();

			if (adsHeight - adsTop < newsColumnHeight) {
				// if add column is of smaller height than news column
				// we have to take care about the scrolling position
				// and calculate a fixed bottom
				scrollPosition = comRightColumnFocusizerPosition;
				if (scrollPosition > 0) {
					$(focusColumn).css("position", "fixed");
					$(focusColumn).css("width", adsWidth);

					var new_top = 0;
					if(scroll_down) {
						new_top = parseFloat($(focusColumn).css("top")) - scroll_difference + $(focusColumn).data('fixed-top-adjust');
						//fixed-top-adjust must be added only ONCE!
						$(focusColumn).data('fixed-top-adjust', 0);

						if($(focusColumn).outerHeight() > windowHeight - fixedTopAdjust) {
							if(new_top < topbarHeight - adsHeight + windowHeight + adsTop && $(window).scrollTop() + CORE_FOOTER_HEIGHT < $(document).height() - $(window).height()) {
								new_top = topbarHeight - adsHeight + windowHeight + adsTop;
							}
						}
						if($(focusColumn).outerHeight() < windowHeight - fixedTopAdjust && new_top < topbarHeight) {
							new_top = topbarHeight - CONTENT_TOP_MARGIN;
						}
					}
					else {
						new_top = parseFloat($(focusColumn).css("top")) + scroll_difference;
						if(new_top > topbarHeight) {
							new_top = topbarHeight;
							// calculation for pages with extra space (like cover image) between topbar and start of newsfeed
							var cover_gap = parseFloat(newsColumn.offset().top) - scrollPosition;
							if(cover_gap > topbarHeight) {
								new_top = cover_gap;
							}
						}
					}

					// set new top of sidebar
					$(focusColumn).css("top", new_top);

					// take care of extra sidebar footer
					if(newsColumnHeight - adsHeight + adsTop > windowHeight) {
						if(comRightColumnFocusizerCoreFooterIsVisible($('.footer-contents'))) {
							$('.sidebar-footer-content').fadeOut();
						}
						else {
							$('.sidebar-footer-content').fadeIn();
						}
					} 
				}
				else {
					$(focusColumn).css("position", "relative");
					$(focusColumn).css("top", 0);
					$(focusColumn).data('fixed-top-adjust', adsTop);
				}
			}
		}
		else {
			$(focusColumn).css("position", "relative");
		}
	});
}); 

comRightColumnFocusizerCoreFooterIsVisible = function(el){
	const CORE_FOOTER_HEIGHT = 48;
	if(el.length) {
		var win = $(window);
		var bounds = el.offset();
		var viewport = {
			top : win.scrollTop()
		};
		viewport.bottom = viewport.top + win.height() + CORE_FOOTER_HEIGHT;
		bounds.bottom = bounds.top + el.outerHeight();
		return (!(viewport.bottom < bounds.top || viewport.bottom < bounds.bottom || viewport.top > bounds.bottom || viewport.top > bounds.top));
	}
	return false;
};

