
(function($) {
	// track selected button
	$.yiimailbox.targetinput=null;
	$.yiimailbox.ajaxerror=0;
	// how much to shift the alternating rows background color by (if alternateRows is enabled by the module).
	$.yiimailbox.altRowsColorShift = 15;
	/*
	var $_GET = {};

	document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
	function decode(s) {
		return decodeURIComponent(s.split("+").join(" "));
	}

	$_GET[decode(arguments[1])] = decode(arguments[2]);
	});
	*/

	$.yiimailbox.updateMailbox = function(){
		//console.log('mailbox updated');

        //ICHECK
        $('input').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue',
            increaseArea: '20%' // optional
        });

		/*
		* Check/Uncheck All
		*/
		$('.checkall').unbind('click').click(function(e){
			$('#message-list-form').find(':checkbox').iCheck('check');
            $('#message-list-form').find(':checkbox').attr('checked','checked');
			return false;
		})
		$('.uncheckall').unbind('click').click(function(e){
            $('#message-list-form').find(':checkbox').iCheck('uncheck');
            $('#message-list-form').find(':checkbox').attr('checked',false);
			return false;
		})
		/*
		* Mailbox buttons (delete|restore|read|unread)
		*/
		$('#message-list-form :input').focus(function() {
			$.yiimailbox.targetinput = $(this);
		});
		$('#message-list-form').submit(function(e){
			if($.yiimailbox.ajaxerror==1){
				// ajax failed, submit form without ajax
				return true;
			}
			return false;
		});
		$('.mailbox-button').unbind('click').click(function(e){
			// recurses on ajax fail
			if($.yiimailbox.ajaxerror==1){
				// ajax failed, submit form without ajax
				return true;
			}
			// build URL
			var url = $('#message-list-form').attr('action');
			url = jQuery.param.querystring(url, 'ajax=1&'+$(this).attr('name')+'=1');
			
			if($(this).attr('id') == 'mailbox-action-delete')
			{
				if($.yiimailbox.confirm(url))
					return true;
			}
			return $.yiimailbox.submitAjax(url);
		});

        /*
         * Change number of unread messages in menu
         */
        var count = $('.msgs-count').html();
        if(count == 0 || count == undefined)
        {
            $('.mailbox-empty')
                .addClass('ui-widget')
                .css({'background':'none'});
            $('.mailbox-new-msgs').text('');
        }
        else{
            $('.mailbox-new-msgs').text('('+count+')');
        }
	}

	/**
	* Return an array of the selected (ie. checked) conversations
	*/
	$.yiimailbox.getConversations = function()
	{
		return $('#message-list-form input:checked').map(function(i,n) {
			return $(n).val();
		}).get(); //get converts it to an array
	}

	/**
	* Submit the ajax form for clicked buttons/drag-n-drop delete.
	*/
	$.yiimailbox.submitAjax = function(url){

		// gather input
		var convs = $.yiimailbox.getConversations();
		if(convs.length == 0) {
			alert('no items selected!');
			return false;
		}
		
		var buttonname = $.yiimailbox.targetinput.attr('name');
		var data = {'convs[]': convs};
		data[buttonname] = 1;
		//console.log(data)
		$.ajax({type: "POST",
			url: url,
			dataType: 'json',
			data: data,
			success: function(response){
				if(response.success) {

					// Tiny desc is used by drag-n-drop only
					if(response.tinydesc)
						$("#"+response.dragdrop+"-tinydesc").text(response.tinydesc).show().fadeOut(4000);
					ajaxGrowl(response.success,response.title);
					// refresh folder
					$.fn.yiiListView.update("mailbox");
					// check for empty folder
					var convs = $.yiimailbox.getConversations();
					if(convs.length == 0) {
						// reload page to display empty folder message
						location.reload();
					}
				}
				else
					ajaxGrowl(response.error, buttonname);
				return false;
			},
			error:
				// submit form without ajax
				function(response){
					//return false;
					$.yiimailbox.ajaxerror=1;
					return false;
					$.yiimailbox.targetinput.click();
					return false;
					//rebind submit button function to not do ajax call
					$.yiimailbox.targetinput.click(new function(){return true;});
					//then call submit button
					$.yiimailbox.targetinput.click();
					return false;
				}
		});
		return false;
	}

	$.yiimailbox.init = function(){

		$.yiimailbox.updateMailbox();

		/*
		* Drag-n-drop droppable area (ie. menu item labeled "trash")
		*/
		if($.yiimailbox.dragDelete==1)
		{
			$( "#mailbox-trash" ).droppable({
				greedy:true,
				tolerance:'pointer',
				activeClass: " ui-state-error",
				//hoverClass: "mailbox-trash-hover",
				drop: function( event, ui ) {
					var url = $('#message-list-form').attr('action');
					url = jQuery.param.querystring(url, 'ajax=1&dragdrop=mailbox-trash');
					$.yiimailbox.targetinput = $('#mailbox-action-delete');
					if($.yiimailbox.confirm(url)) return true;
					$.yiimailbox.submitAjax(url);
				}
			});
		}

		/*
		* Bind themeswitcher event. This is only used in the demo.
		* If you are also using the JUI themeswitcher in your application
		* you can uncomment the lines below to make sure the widget 
		* styles get reapplied to the mailbox when the theme is switched.
		* NOTE: You must also call the event from within the themeswitcher.js
		* For example in the demo's themeswitcher.js we add the following right
		* before the return statement (above the cookie plugin)...
		* $('#content').trigger('switchtheme');
		*/
		/* Uncomment below for themeswitcher */
		$('#content').bind('switchtheme', function(event) {
			if($.yiimailbox.updateMailbox!='undefined')
				$.yiimailbox.updateMailbox();

		});
	}
	/*
	setTimeout(function() {
		$.yiimailbox.shiftBg($('.mailbox-items-tbl tr:even > td'),15);
	}, 950 ); */

	$.yiimailbox.shiftBg = function(elem,amount)
	{
		amount = Number(amount);
		if(elem.length==0)
			return;
		//$('.mailbox-items-tbl tr:even').addClass(' ui-state-active ui-priority-secondary');
		var rgb = elem//('.mailbox-items-tbl tr')
				.parent().parent()
				.css('backgroundColor')
				.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
		//console.log(rgb)
		if(!rgb)
			return;
		rgb.shift()
		var rgbtotal = Number(rgb[0]) + Number(rgb[1]) + Number(rgb[2]);
		if(rgbtotal < 100)
		// lighten colors
		{
			for(var i in rgb)
			{
				rgb[i] = Number(rgb[i]);
				if(rgb[i] < 255 - amount)
					rgb[i] += amount;
				else
					rgb[i] = 255;
			}
		}
		else
		// shade colors
		{
			for(var i in rgb)
			{
				rgb[i] = Number(rgb[i]);
				if(rgb[i] > amount)
					rgb[i] -= amount;
				else
					rgb[i] = 0;
			}
		}
		var rgbnew = "rgba(" + rgb[0] + "," + rgb[1] + "," + rgb[2] +", 0.5)";
		//console.log(rgbnew);

		elem.animate({"backgroundColor": rgbnew}, 900);
			//.css('backgroundColor',rgbnew)
			//.fadeTo(600,0.2);
	}
	
	$.yiimailbox.confirm = function(url)
	{
		var html;
		var buttons;
		
		if($.yiimailbox.getConversations().length == 0) {
			alert('no items selected!');
			return false;
		}
		
		if( ($.yiimailbox.confirmDelete==1 && $.yiimailbox.currentFolder=='trash')
			|| $.yiimailbox.confirmDelete==2)
		{
			if($.yiimailbox.currentFolder=='trash' || $.yiimailbox.trashbox==0)
			{
				buttons = {
					"Delete forever": function() {
						$.yiimailbox.submitAjax(url);
						$( this ).dialog( "close" );
					},
					Cancel: function() {
						$( this ).dialog( "close" );
					}
				}
				html = '<div id="dialog-confirm" title="Delete items permanently?"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>These items will be permanently deleted. Are you sure?</p></div>';
			}
			else {
				buttons = {
					"Delete": function() {
						$.yiimailbox.submitAjax(url);
						$( this ).dialog( "close" );
					},
					Cancel: function() {
						$( this ).dialog( "close" );
					}
				}
				html = '<div id="dialog-confirm" title="Send items to trash?"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you want to mark these items as deleted?</p></div>';
			}
			$( html ).dialog({
				resizable: false,
				height:180,
				modal: true,
				buttons: buttons
			});
			return true;
		}
		else 
			return false;
	}

	$.ui.draggable.prototype._mouseStop = function(event) {
		//If we are using droppables, inform the manager about the drop
		var dropped = false;
		if ($.ui.ddmanager && !this.options.dropBehaviour)
		dropped = $.ui.ddmanager.drop(this, event);

		//if a drop comes from outside (a sortable)
		if(this.dropped) {
		dropped = this.dropped;
		this.dropped = false;
		}

		if((this.options.revert == "invalid" && !dropped) || (this.options.revert == "valid" && dropped) || this.options.revert === true || ($.isFunction(this.options.revert) && this.options.revert.call(this.element, dropped))) {
		var self = this;
		self._trigger("reverting", event);
		$(this.helper).animate(this.originalPosition, parseInt(this.options.revertDuration, 10), function() {
			event.reverted = true;
			self._trigger("stop", event);
			self._clear();
		});
		} else {
		this._trigger("stop", event);
		this._clear();
		}

		return false;
	}


})(jQuery); // jQuery
$.yiimailbox.init();
// CSS files must be loaded in order for shiftBg to apply change to the correct CSS class
jQuery(window).load(function(){
	/* Alternating row colors for widget styles */
	if($.yiimailbox.juiThemes == 'widget'
		&& $.yiimailbox.alternateRows == 1
		&& $('.mailbox-items-tbl tr').length != 0)
	{
		$.yiimailbox.shiftBg($('.mailbox-items-tbl tr:even > td').find('.mailbox-item-wrapper'),$.yiimailbox.altRowsColorShift);
	}

    //ICHECK
    $('input').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue',
        increaseArea: '20%' // optional
    });
});