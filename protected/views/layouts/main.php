<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <?php $cs = Yii::app()->getClientScript();  ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo Yii::app()->name; ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php
        $cs->registerCssFile(Yii::app()->getBaseUrl() . '/css/normalize.css');
        $cs->registerCssFile(Yii::app()->getBaseUrl() . '/css/bootstrap.min.css');
        $cs->registerCssFile(Yii::app()->getBaseUrl() . '/css/main.css');

        $cs->registerScriptFile(Yii::app()->getBaseUrl() . '/js/vendor/custom.modernizr.js', CClientScript::POS_HEAD);
    ?>
</head>

<body>
    <!--[if lt IE 7]>
    <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <div class="container">
        <header class="row">

        </header>

        <div class="row">
            <?php echo $content; ?>
        </div>

        <div class="row">
            <footer class="col-xs-12">
                <p class="copyright text-center">Created by <a href="http://evolution.rs.ba" target="_blank">Evolution Web Studio</a> &copy;2014</p>
            </footer>
        </div>
    </div>

    <?php
        $cs->registerCoreScript('jquery');
        $cs->registerCoreScript('jquery.ui');
//        /cs->registerScriptFile(Yii::app()->getBaseUrl() . '/js/bootstrap.min.js', CClientScript::POS_END);
        $cs->registerScriptFile(Yii::app()->getBaseUrl() . '/js/plugins.js', CClientScript::POS_END);
        $cs->registerScriptFile(Yii::app()->getBaseUrl() . '/js/main.js', CClientScript::POS_END);
    ?>

<!--    <script>-->
<!--        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){-->
<!--            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),-->
<!--            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)-->
<!--        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');-->
<!---->
<!--        ga('create', '//code', '//url'); //TODO Instert tracking code and url-->
<!--        ga('send', 'pageview');-->
<!--    </script>-->
</body>
</html>
