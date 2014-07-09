<?php
$newMsgs = $this->module->getNewMsgs();
$action = $this->getAction()->getId();

if($this->module->authManager)
{
	$authNew = Yii::app()->user->checkAccess("Mailbox.Message.New");
	$authInbox = Yii::app()->user->checkAccess("Mailbox.Message.Inbox");
	$authSent = Yii::app()->user->checkAccess("Mailbox.Message.Sent");
	$authTrash = Yii::app()->user->checkAccess("Mailbox.Message.Trash");
}
else
{
	$authNew = $this->module->sendMsgs && (!$this->module->readOnly || $this->module->isAdmin());
	$authInbox = ( !$this->module->readOnly || $this->module->isAdmin() );
	$authTrash = $this->module->trashbox && (!$this->module->readOnly || $this->module->isAdmin());
	$authSent = $this->module->sentbox && (!$this->module->readOnly || $this->module->isAdmin());
}

?>
<div class="mailbox-menu col-md-2">
    <?php
    if($authNew) :
        ?>
        <div class="mailbox-menu-newmsg">
            <span><a href="<?php echo $this->createUrl('message/new'); ?>" class="btn btn-primary btn-block"><i class="fa fa-edit"></i> New Message</a></span>
        </div>
    <?php endif; ?>
	<div class="mailbox-menu-folders list-group">
		<?php
		if($authInbox):?>
			<a id="mailbox-inbox" class="mailbox-menu-item list-group-item <?php echo ($action=='inbox')? 'active ' : '' ; ?>" href="<?php echo $this->createUrl('message/inbox'); ?>">
                Inbox <span class="mailbox-new-msgs"><?php echo $newMsgs? '('.$newMsgs.')' : null ; ?></span>
            </a>
		<?php endif;
		if($authSent) : ?>
			<a id="mailbox-sent" class="mailbox-menu-item list-group-item <?php if($action=='sent') echo 'active '; ?>" href="<?php echo $this->createUrl('message/sent'); ?>">
                Sent Mail
            </a>
		<?php endif;
		if($authTrash) : ?>
			<a  id="mailbox-trash" class="mailbox-menu-item list-group-item <?php if($action=='trash') echo 'active '; ?>" href="<?php echo $this->createUrl('message/trash'); ?>">
                Trash
            </a>
		<?php endif; ?>
	</div>
</div>