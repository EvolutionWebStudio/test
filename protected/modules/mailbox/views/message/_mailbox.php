<?php

if(isset($_GET['Message_sort']))
	$sortby = $_GET['Message_sort'];
elseif(isset($_GET['Mailbox_sort']))
	$sortby = $_GET['Mailbox_sort'];
else
	$sortby = '';

$ie6br = <<<EOD
<!--[if lt IE 6]>
<br clear="all" />
<![endif]-->
EOD;
$checkallButtons = '';
$additionalActions = '';

?>

<div id="mailbox-list" class="mailbox-list col-md-10">
    <?php
    $this->renderpartial('_flash');

    if($dataProvider->getItemCount() > 0):
        ?>
        <form id="message-list-form" action="<?php echo $this->createUrl($this->getId().'/'.$this->getAction()->getId()); ?>" method="post">
            <input type="hidden" class="mailbox-count" name="ui[]" value="<?php echo $dataProvider->getItemCount(); ?>" />
            <input type="hidden" class="mailbox-sortby" name="ui[]" value="<?php echo $sortby; ?>" />

            <div class="mailbox-clistview-container">
                <?php
                /* Creating checkall buttons */
                if($dataProvider->getItemCount() > 1 && $this->getAction()->getId() != 'sent') :
                    $checkallButtons = '
                        <button class="checkall btn btn-default">Check All</button>
                        <button class="uncheckall btn btn-default">Uncheck All</button>';
                endif;

                /* Creating additional actions buttons */
                if($this->getAction()->getId()!='sent'):
                    $additionalActions = '
                    <div class="mailbox-additional-actions col-md-6">
                        <span class="mailbox-buttons-label">With selected:</span>';
                    if($this->getAction()->getId()=="trash"):
                        $additionalActions .= '
                            <input type="submit" id="mailbox-action-restore" class="btn btn-default mailbox-button" name="button[restore]" value="restore" />
                            <input type="submit" id="mailbox-action-delete" class="btn btn-default mailbox-button" name="button[delete]" value="delete forever" />';
                    else:
                        if(!$this->module->readOnly || ( $this->module->readOnly && !$this->module->isAdmin())):
                            $additionalActions .= '
                                <input type="submit" id="mailbox-action-delete" class="btn btn-default mailbox-button" name="button[delete]" value="delete" />';
                        endif;
                        $additionalActions .= '
                                <input type="submit" id="mailbox-action-read" class="btn btn-default mailbox-button" name="button[read]" value="read" />
                                <input type="submit" id="mailbox-action-unread" class="btn btn-default mailbox-button" name="button[unread]" value="unread" />';
                    endif;
                    $additionalActions .= '</div>';
                endif;

                $this->widget('zii.widgets.CListView', array(
                    'id'=>'mailbox',
                    'dataProvider'=>$dataProvider,
                    'itemView'=>'_list',
                    'itemsTagName'=>'table',
                    'template'=>'
                        <div class="mailbox-toolbar row">
                            <div class="btn-group mailbox-checkall-buttons col-md-6">'.
                        $checkallButtons
                        .'&nbsp;</div>
                            <div class="mailbox-summary col-md-6">{summary}</div>
                        </div>
                        <div id="mailbox-items" class="table-responsive">{items}</div>
                        <div class="mailbox-toolbar row">'.
                        $additionalActions.'<div class="mailbox-pagination col-md-6">{pager}</div>
                        </div>',
                    'sortableAttributes'=>$this->getAction()->getId()=='sent'?
                            array('created'=>'Date Sent') :
                            array('modified'=>'Date Received'),
                    'loadingCssClass'=>'mailbox-loading',
                    'ajaxUpdate'=>'mailbox-list',
                    'afterAjaxUpdate'=>'$.yiimailbox.updateMailbox',
                    'emptyText'=>'<div class="alert alert-warning"><h3>You have no mail in your '.$this->getAction()->getId().' folder.</h3></div>',
                    //'htmlOptions'=>array('class'=>'ui-helper-clearfix'),
                    'sorterHeader'=>'',
                    'sorterCssClass'=>'mailbox-sorter',
                    'itemsCssClass'=>'mailbox-items-tbl table table-hover',
                    'pagerCssClass'=>'mailbox-pager',
                    'pager' => array(
                        'class' => 'CLinkPager',
                        'header' => '',
                        'htmlOptions' => array(
                            'class' => 'pagination'
                        ),
                        'firstPageLabel' => '<span class="fa fa-angle-double-left"></span>',
                        'prevPageLabel' => '<span class="fa fa-angle-left"></span>',
                        'nextPageLabel' => '<span class="fa fa-angle-right"></span>',
                        'lastPageLabel' => '<span class="fa fa-angle-double-right"></span>',

                    )
                    //'updateSelector'=>'.inbox',
                ));?>
            </div>
        </form>
    <?php
    else:
        $this->renderpartial('_empty');
    endif;
    echo '<div class="msgs-count hide">' . $this->module->getNewMsgs() . '</div>';
    ?>
</div>

<?php $this->renderPartial('_modal');