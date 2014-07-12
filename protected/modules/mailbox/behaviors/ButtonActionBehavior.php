<?php

class ButtonActionBehavior extends CBehavior
{
	
	public $controller;
	public $module;
	public $buttons = array();
	public $arclass;
	public function buttonAction($box='inbox' , $buttonset='default')
	{
		if(!$_POST['button']) {
			if($_GET['ajax'])
            {
                Yii::app()->user->setFlash('danger', "Action not found?");
                die('{"error":"Action not found?"}');
            }
            Yii::app()->user->setFlash('danger', "Action not found?");
            $this->controller->redirect(array($this->controller->getId().'/'.$box));
        }
		$action = key($_POST['button']);
		if(!array_key_exists($action,$this->buttons[$buttonset]))
			throw new Exception('Button action not found?');
		
		$partialmsg = $this->buttons[$buttonset][$action];
		
		$count=0;
		foreach($_POST['convs'] as &$conversation_id)
		{
			/*
			* None of the following errors should happen unless the user 
			* tampers with the input vars, so we ignore them and continue
			*/
			if(!is_int($conversation_id=(int)$conversation_id))
				continue;
			$conv = call_user_func(array($this->arclass, 'model'))->findByPk($conversation_id);

			if(!$conv->belongsTo($this->module->getUserId()))
				continue;
			if(!$conv->$action($this->module->getUserId()) || !$conv->validate())
				continue;
			if($conv->save())
				$count++;
		}
		
		if($count)
		{
			$title = ucfirst($partialmsg);
			$message = $count." message(s) have been {$partialmsg}!";
			if(isset($_GET['ajax']))
			{
                Yii::app()->user->setFlash('success', $message);
                die('{"success":"'.$message.'"}');
            }
            Yii::app()->user->setFlash('success', $message);
            $this->controller->redirect(array($this->controller->getId().'/'.$box));
        }
		else
		{
			$title = "Error occured?";
			$message = "Message could not be {$partialmsg}!";
			if(isset($_GET['ajax']))
			{
                Yii::app()->user->setFlash('danger', $message);
				die('{"error":"'.$message.'"}');
			}
			Yii::app()->user->setFlash('danger', $message);
			$this->controller->redirect(array($this->controller->getId().'/'.$box));
		}
	}
}