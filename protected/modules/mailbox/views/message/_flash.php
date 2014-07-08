<?php
    foreach(Yii::app()->user->getFlashes() as $key => $message) {
        echo '<div class="alert alert-'. $key .'">' . $message . "</div>";
    }
?>
