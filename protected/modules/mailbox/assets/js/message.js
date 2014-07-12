

;(function($) {

	$.fn.yiiMailboxMessage = function(options) {
		return this.each(function(){
			var settings = $.extend({}, $.fn.yiiMailboxMessage.defaults, options || {});
			var $this = $(this);
			var id = $this.attr('id');
			$.fn.yiiMailboxMessage.settings[id] = settings;
			
			message = $(this);
		});
	}

	$.fn.yiiMailboxMessage.defaults = {
		trashbox: 0,
		dragDelete: 0,
		confirmDelete: 0,
		menuPosition: 'top',
		juiThemes: 0,
		juiButtons: 0,
		juiIcons: 0,
		alternateRows: 0,
		highlightRows: 0,
		ajaxerror: 0,
		sortBy:''
	};
	
	$.fn.yiiMailboxMessage.settings = {};
})(jQuery);

$(document).ready(function() {
    bindSummernote(false);
    $('a.goto-reply').on('click', function(){
        bindSummernote(true);
        return false;
    });
});

function bindSummernote(focus)
{
    $('.summernote-small').summernote({
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']]
        ],
        height: 100,
        focus: focus
    });
}