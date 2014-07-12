<?php
    foreach(Yii::app()->user->getFlashes() as $key => $message):
        if($key == 'info'): ?>
            <div class="alert alert-<?php echo $key; ?>">
                <?php echo $message; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-<?php echo $key; ?> alert-dismissible">
                <?php echo $message; ?>
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
            </div>
<?php
        endif;
    endforeach;
?>
