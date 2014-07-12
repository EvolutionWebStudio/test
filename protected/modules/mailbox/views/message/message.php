<?php
$this->breadcrumbs = array(
    ucfirst($this->module->id) => array('mailbox/inbox'),
    'Message',
);

$this->renderPartial('_menu');

$subject = ($conv->subject) ? $conv->subject : $this->module->defaultSubject;

if (strlen($subject) > 100) {
    $subject = substr($subject, 0, 100);
}

?>

<div class="mailbox-message-list col-md-10">

    <?php $this->renderPartial('_flash'); ?>


    <div class="mailbox-message-toolbar clearfix">
        <div class="col-md-10  mailbox-ellipsis">
            <h2 class="mailbox-message-subject mailbox-ellipsis"><?php echo $subject; ?></h2>
        </div>
        <div class="col-md-2 text-right">
            <a class="btn btn-secondary btn-sm goto-reply" href="#reply"><span class="fa fa-mail-reply"></span> Reply</a>
        </div>

    </div>

    <?php
    $first_message = 1;
    foreach ($conv->messages as $msg):
        $sender = $this->module->getUserName($msg->sender_id);
        if (!$sender)
            $sender = $this->module->deletedUser;
        ?>

        <div class="mailbox-message-header clearfix">
            <div class="message-sender col-md-8">
                <?php echo ($msg->sender_id == Yii::app()->user->id) ? 'You' : ucfirst($sender);
                echo ($first_message) ? ' said' : ' replied'; ?>
            </div>
            <div class="message-date col-md-4">
                <?php echo date("F j, Y \a\\t g:i a", $msg->created); ?>
            </div>
        </div>
        <div class="mailbox-message-text"><?php echo $msg->text; ?></div>

        <?php $first_message = 0;
    endforeach;

    if ($this->module->authManager)
        $authReply = Yii::app()->user->checkAccess("Mailbox.Message.Reply");
    else
        $authReply = $this->module->sendMsgs;

    if ($authReply)
    {
        $form = $this->beginWidget('CActiveForm', array(
            'action' => $this->createUrl('message/reply', array('id' => $_GET['id'])),
            'id' => 'message-reply-form',
            'enableAjaxValidation' => false,
        )); ?>
            <div class="mailbox-message-reply">
                <div class="form-group">
                    <p><a name="reply" class="reply-anchor">Reply to this conversation:</a></p>
                    <textarea class="form-control summernote-small" name="text" placeholder="Reply here..."></textarea>
                </div>
                <input type="submit" class="btn btn-primary mailbox-input" value="Send Reply"/>
            </div>
            <?php echo $form->error($reply, 'text'); ?>
        <?php $this->endWidget();
    }
    ?>
</div>

