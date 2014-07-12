
;(function($) {

	$.fn.yiiMailboxCompose = function(options) {
		return this.each(function(){
			var settings = $.extend({}, $.fn.yiiMailboxCompose.defaults, options || {});
			var $this = $(this);
			var id = $this.attr('id');

			$.fn.yiiMailboxCompose.settings[id] = settings;
			/*
			* Autocomplete
			*/
			var cache = {},
			lastXhr;

			$this.find( "#message-to" ).combobox();

            $this.find(".ui-combobox").addClass("input-group");
            $this.find(".ui-autocomplete-input")
                .addClass("form-control input-lg")
                .removeClass("ui-state-default ui-widget ui-widget-content ui-corner-left")
                .attr('placeholder', $('#message-to').attr('placeholder'));
            $this.find(".ui-combobox-btn")
                .addClass("btn btn-lg btn-default")
                .removeClass("ui-corner-right ui-button-icon")
                .html('<span class="fa fa-caret-down"></span>');

			/*
			* Prevent wrong value for To field from being submitted
			* because autocomplete uses setTimeOut for the change event.
			*/
			$this.find("#message-form").submit(function (event) {
				var form = this;
				//Wait for autocomplete to change value
				setTimeout(function() {
					form.submit();
				}, 250 ); //Make sure delay is greater than 150 ms, as in autocomplete widget.
				event.preventDefault();
			});
		})
	}

	$.fn.yiiMailboxCompose.defaults = {
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

	$.fn.yiiMailboxCompose.settings = {};
})(jQuery);

$(document).ready(function(){

    $('.summernote-small').summernote({
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']]
        ],
        height: 200
    });
});