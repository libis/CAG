<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php if ( $description = option('description')): ?>
        <meta name="description" content="<?php echo $description; ?>" />
        <?php endif; ?>
        <?php
        if (isset($title)) {
            $titleParts[] = strip_formatting($title);
        }
        $titleParts[] = option('site_title');
        ?>
        <title><?php echo implode(' &middot; ', $titleParts); ?></title>

        <!-- Meta -->

        <meta property='og:title' content='<?php echo implode(' &middot; ', $titleParts); ?>'/>
        <meta property='og:url' content='<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>'/>
        <meta property='og:description' content=''/>
        <?php
            //if($og){echo $og;}
        ?>
        <link rel="shortcut icon" href="<?php echo img('favicon.ico') ?>" />

<?php echo auto_discovery_link_tags(); ?>

        <!-- Plugin Stuff -->

<?php fire_plugin_hook('public_head', array('view'=>$this)); ?>

        <!-- Stylesheets -->
        <?php
        queue_css_file('reset');
        queue_css_file('style');
        queue_css_file('ie');
        queue_css_file('skin');
        queue_css_file('main');
        queue_css_file('feature-carousel');
        echo head_css();
        ?>

<?php if ($headerBackground = get_theme_option('Header Background')): ?>

            <style type="text/css" media="screen">
                #header {
                    background:transparent url('<?php echo WEB_THEME_UPLOADS . DIRECTORY_SEPARATOR . $headerBackground; ?>') top left no-repeat;
                }
            </style>
<?php endif; ?>

        <!-- JavaScripts -->
        <?php queue_js_file('jquery.collapser'); ?>
        <?php queue_js_file('jquery.roundabout'); ?>
        <?php queue_js_file('iframe'); ?>
        <?php queue_js_file('selectivizr'); ?>
        <?php echo head_js(); ?>

        <script language="JavaScript">
            if (self !== top) document.write('<style type="text/css">#normal-title{display:none} html{background:#fff;margin:0 0 100px 0;} #footer {display: none;} .info {display:none;} #header {display: none;} #search-wrap {display: none;}</style>');
            var RecaptchaOptions = {
                lang : 'nl'
             };
        </script><!--iframe changes-->
    </head>
    <body<?php echo isset($bodyid) ? ' id="' . $bodyid . '"' : ''; ?><?php echo isset($bodyclass) ? ' class="' . $bodyclass . '"' : ''; ?>>

        <div id="wrap">
            <div id="header">
                <div id="site-title">
                    <div id="header-title">
                        <h1 class="header">Het Virtuele Land</h1>
                        <h2 class="header">Erfgoed van landbouw, voeding en landelijk leven</h2>
                    </div>
                </div>
                <div id="search-wrap" class="group">
                <?php echo search_form(array('submit_value'=>'Zoeken','form_attributes'=> array('id'=>'simple-search')));  ?>
                </div><!-- /#search-wrap -->

                <div id="header-nav_bar">

                    <div id="header-nav">


                            <?php echo public_nav_main(); ?>
                    </div>

                </div>

            </div>

            <div id="content" class="group">
