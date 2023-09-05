<!DOCTYPE html>
<html>
    <head>
        <title><?php Main::showTitle(); ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="description" content="<?php Main::showProperty('description'); ?>">
        <link href="/template/assets/css/layout.css" rel="stylesheet" type="text/css" media="all">
        <!-- JAVASCRIPTS -->
        <script defer="" src="/template/assets/js/jquery/jquery.min_old.js"></script>
        <script defer="" src="/template/assets/js/jquery/jquery.backtotop.js"></script>
        <script defer="" src="/template/assets/js/jquery/jquery.mobilemenu.js"></script>
        <!-- IE9 Placeholder Support -->
        <script defer="" src="/template/assets/js/jquery/jquery.placeholder.min.js"></script>
        <!-- / IE9 Placeholder Support -->
        <!-- Custom scripts -->
        <script defer="" src="/template/assets/js/main.js"></script>
    </head>
    <body id="top">
        <div class="wrapper row1">
            <header id="header" class="hoc clear">
                <div id="logo" class="fl_left">
                    <h1><a <?php if (Main::getPath() != '/'): ?>href="/"<?php endif; ?>><?php Main::includeData('/include/site_name.php');?></a></h1>
                    <i class="fa fa-brands fa-codepen"></i>
                    <p><?php Main::includeData('/include/motto.php');?></p>
                </div>
                <nav id="mainav" class="fl_right">
                    <ul class="clear">
                        <?php Main::includeTemplateFile('/include/top_nav.php'); ?>
                    </ul>
                </nav>

            </header>
        </div>
        <div class="wrapper row0">
            <main class="hoc container clear">
                <!-- main body -->
