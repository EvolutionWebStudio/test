
(function($) {
	// track selected button
	$.yiimailbox.targetinput=null;
	$.yiimailbox.ajaxerror=0;
	// how much to shift the alternating rows background color by (if alternateRows is enabled by the module).
	$.yiimailbox.altRowsColorShift = 15;

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
			alert('No items selected!');
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
                    $.fn.yiiListView.update("mailbox");

                autocloseAlert('.alert-dismissible', 5000);
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
        autocloseAlert('.alert-dismissible', 5000);
	}

	$.yiimailbox.confirm = function(url)
	{
		var title;
		var message;

		/*if($.yiimailbox.getConversations().length == 0) {
			alert('No items selected!');
			return false;
		}*/

		if( ($.yiimailbox.confirmDelete==1 && $.yiimailbox.currentFolder=='trash') || $.yiimailbox.confirmDelete==2)
		{
			if($.yiimailbox.currentFolder=='trash' || $.yiimailbox.trashbox==0)
			{
                title = 'Delete item(s) permanently?';
                message = 'These item(s) will be permanently deleted. Are you sure?';
            }
			else {
                title = 'Send item(s) to trash?';
                message = 'Are you sure you want to mark these item(s) as deleted?';
			}

            $.yiimailbox.showConfirmationModal(title, message);

            $('#confirmation-modal').on('hidden.bs.modal', function(e){
                if($(this).attr('data-answer') == 'yes')
                {
                    $.yiimailbox.submitAjax(url);
                }
            });

			return true;
		}
		else
			return false;
	}

    $.yiimailbox.showConfirmationModal = function(title, message)
    {
        dialog = $('#confirmation-modal');

        dialog.find('.modal-title').text(title);
        dialog.find('.modal-body').text(message);

        dialog.modal('show')
            .on('shown.bs.modal', function(e) {

                $(this).find('.modal-btn-no').on('click', function() {
                    $('#confirmation-modal').attr('data-answer', 'no').modal('hide');
                });
                $(this).find('.modal-btn-yes').on('click', function(){
                    $('#confirmation-modal').attr('data-answer', 'yes').modal('hide');
                });
            });
    }

})(jQuery); // jQuery

function autocloseAlert(selector, delay) {
    window.setTimeout(function() { $(selector).fadeOut(); }, delay);
}

jQuery(window).load(function(){
    $.yiimailbox.init();
});
