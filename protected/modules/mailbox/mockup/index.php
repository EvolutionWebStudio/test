<?php
    $page = 'inbox';
    $pageTitle = 'Inbox';
    if(isset($_GET['page']))
    {
        $page = $_GET['page'];
        switch($page)
        {
            case 'read-message':
                $pageTitle = 'Message';
                break;
            case 'new-message':
                $pageTitle = 'New Message';
                break;
            case 'reply-message':
                $pageTitle = 'Reply Message';
                break;
            default:
                break;
        }
    }
    $basePath = '/mailbox-module';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Evolution Web Studio">

    <title>Mailbox Module for Yii v0.1</title>

    <link href="<?php echo $basePath; ?>/css/bootstrap.css" rel="stylesheet">

    <link href="<?php echo $basePath; ?>/css/main.css" rel="stylesheet">
    <link href="<?php echo $basePath; ?>/css/main-responsive.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- VENDOR -->
    <link href="<?php echo $basePath; ?>/css/animate.css" rel="stylesheet">
    <link href="<?php echo $basePath; ?>/css/font-awesome.css" rel="stylesheet">
    <link href="<?php echo $basePath; ?>/css/component.css" rel="stylesheet">
    <link href="<?php echo $basePath; ?>/js/vendor/iCheck/skins/minimal/grey.css" rel="stylesheet">
    <link href="<?php echo $basePath; ?>/js/vendor/summernote/summernote.css" rel="stylesheet">
</head>

<body>

    <div class="container">
        <div class="content-page">
            <div class="body content rows scroll-y">
                <div class="page-heading animated fadeInDownBig">
                    <h1><?php echo $pageTitle; ?> <small>lorem ipsum dolor</small></h1>
                </div>
                <div class="box-info box-messages">
                    <div class="row">
                        <div class="col-md-2">
                            <?php include '_side-bar.php'; ?>
                        </div>
                        <div class="col-md-10">
                            <?php include $page . '.php'; ?>
                        </div>
                    </div><!-- End div .row -->
                </div><!-- End div .box-info -->

                <?php include '_footer.php' ?>

            </div> <!-- /content -->
        </div> <!-- /content-page -->

        <?php include '_modal-dialog.php'; ?>
        <div class="md-overlay"></div>

    </div> <!-- /container -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="<?php echo $basePath; ?>/js/bootstrap.min.js"></script>

    <!-- VENDOR -->

    <!-- Nifty modals js -->
    <script src="<?php echo $basePath; ?>/js/classie.js"></script>
    <script src="<?php echo $basePath; ?>/js/modalEffects.js"></script>

    <script src="<?php echo $basePath; ?>/js/vendor/summernote/summernote.js"></script>
    <script src="<?php echo $basePath; ?>/js/vendor/iCheck/icheck.js"></script>

    <script src="js/main.js"></script>

</body>
</html>
