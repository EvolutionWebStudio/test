<?php

$userid = $this->module->getUserId();

if($this->getAction()->getId()=='sent')
{
	$counterUserId = $data->recipient_id;
}
else
{
	if($this->module->getUserId() == $data->initiator_id)
		$counterUserId = $data->interlocutor_id;
	else
		$counterUserId = $data->initiator_id;
}
$username = $this->module->getFromLabel($counterUserId);

if($username && ($this->module->isAdmin() || $this->module->linkUser)) {
	$url = $this->module->getUrl($counterUserId);
	if($url)
		$username = '<a href="'.$url.'">'.$username.'</a>';
}
elseif(!$username)
	$username = '<span class="mailbox-deleted-user">'.$this->module->deletedUser.'</span>';

$viewLink = $this->createUrl('message/view',array('id'=>$data->conversation_id));

if($this->getAction()->getId()=='sent') {
	$received = $this->module->getDate($data->created);
	if($this->module->recipientRead)
		$itemCssClass = ($data->isRead($userid))? 'msg-read' : 'msg-deliver';
	else
		$itemCssClass = 'msg-sent';
}
else{ 
	$received = $this->module->getDate($data->modified);
	$itemCssClass = $data->isNew($userid)? 'msg-new' : 'msg-read';
}
switch($itemCssClass)
{
	case 'msg-read': $status = ($this->getAction()->getId()=='sent')? 'Recipient has read your message' : 'Message has been read' ; break;
	case 'msg-deliver':  $status = 'Recipient has not read your message yet';
	case 'msg-new': $status =  ($this->getAction()->getId()=='sent')? 'Recipient has not read your message yet' : 'You received a new message'; break;
	case 'msg-sent': $status = "You sent message {$username} a message";
}


$subject = '<a class="mailbox-link" title="'.$status.'" href="'.$viewLink.'">';
$subject .= '<span class="mailbox-subject-text">';
$subjectSeperator = ' - ';
if(strlen($data->subject) > $this->module->subjectMaxCharsDisplay)
{
	$subject .= substr($data->subject,0,$this->module->subjectMaxCharsDisplay - strlen($this->module->ellipsis) ). $this->module->ellipsis;
}
else
{
    $subject .= $data->subject;
	/*$subject .= $data->subject .'</span><span class="mailbox-msg-brief">'.$subjectSeperator
		 .substr(strip_tags($data->text),0,$this->module->subjectMaxCharsDisplay - strlen($data->subject) - strlen($subjectSeperator) - strlen($this->module->ellipsis) );
	if(strlen($data->subject) + strlen($data->text) + strlen($subjectSeperator) > $this->module->subjectMaxCharsDisplay)
		$subject .= $this->module->ellipsis;*/
}
$subject = preg_replace('/[\n\r]+/','',$subject);
$subject.= '</span></a>';
?>


<tr class="mailbox-item <?php echo $itemCssClass; ?>">
    <td style="width:20px;">
        <?php if($this->getAction()->getId()!='sent') : ?>
            <label class="" for="conv_<?php echo $data->conversation_id; ?>">
                <input class="mailbox-check " id="conv_<?php echo $data->conversation_id; ?>" type="checkbox" name="convs[]" value="<?php echo $data->conversation_id; ?>" />
            </label>
        <?php endif; ?>
    </td>
    <td style="width: 200px;">
		<div  class="mailbox-item-wrapper mailbox-from mailbox-ellipsis"><?php echo $username; ?></div>
    </td>
    <td class="mailbox-subject-brief">
	    <div class="mailbox-subject mailbox-ellipsis">
			<?php echo $subject; ?>
		</div>
    </td>
    <td style="width: 150px;" class="mailbox-received">
		<?php if($data->is_replied): ?>
            <span class="fa fa-mail-reply"></span>
		<?php endif; ?>
		<?php echo $received; ?>
    </td>
</tr>




