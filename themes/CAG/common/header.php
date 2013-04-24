<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#">
    <head>

        <?php
//Aangepast door Sam. Deze wordt al geladen door de javascript hieronder
//echo js('jquery');
        ?>

        <title><?php echo settings('site_title');
        echo isset($title) ? ' | ' . $title : ''; ?></title>

        <!-- Meta -->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="description" content="<?php echo settings('description'); ?>" />
        
        <meta property='og:title' content='<?php echo $title;?>'/>
        <meta property='og:url' content='<?php echo 'http://www.hetvirtueleland.be/cag_test/items/show/34753/';?>'/>
        <meta property='og:description' content=''/>    
        <?php 
            if($og){echo $og;}
        ?>
        <link rel="shortcut icon" href="<?php echo img('favicon.ico') ?>" />       

<?php echo auto_discovery_link_tag(); ?>

        <!-- Plugin Stuff -->

<?php plugin_header(); ?>

        <!-- Stylesheets -->
        <?php
        queue_css('reset');
        queue_css('style');
        queue_css('ie');
        queue_css('styleDropdown');        
        queue_css('skin');
        queue_css('main');

        display_css();
        ?>

<?php if ($headerBackground = get_theme_option('Header Background')): ?>

            <style type="text/css" media="screen">
                #header {
                    background:transparent url('<?php echo WEB_THEME_UPLOADS . DIRECTORY_SEPARATOR . $headerBackground; ?>') top left no-repeat;
                }
            </style>
<?php endif; ?>

        <!-- JavaScripts -->
        <?php echo queue_js('jquery.collapser'); ?>
        <?php echo queue_js('jquery.dropdown'); ?>
        <?php echo queue_js('jquery.roundabout.min'); ?>
        <?php echo queue_js('iframe'); ?>
        <?php echo queue_js('selectivizr'); ?>
        

<?php echo display_js(); ?>
        <script language="JavaScript">
            if (self !== top) document.write('<style type="text/css">#normal-title{display:none} html{background:#fff;margin:0 0 100px 0;} #footer {display: none;} .info {display:none;} #header {display: none;} #search-wrap {display: none;}</style>');
        </script><!--iframe changes-->

    </head>
    <body<?php echo isset($bodyid) ? ' id="' . $bodyid . '"' : ''; ?><?php echo isset($bodyclass) ? ' class="' . $bodyclass . '"' : ''; ?>>

        <div id="wrap">
            <div id="header">
                <div id="site-title">
                    <div id="header-title">
                        <h1 class="header">Het Virtuele Land</h1>
                        <h2 class="header">Erfgoedbank landbouw, platteland en voeding</h2>
                        <a class="cag-link" href="http://www.cagnet.be/"><h3 class="header">Centrum<br/>Agrarische<br/>Geschiedenis</h3></a>
                    </div>
                </div>
                <div id="search-wrap" class="group">
<?php echo SolrSearch_ViewHelpers::createSearchForm('Zoeken'); //echo simple_search("Zoeken",array('id'=>'simple-search'),uri("items/browse/?sort_field=id"));  ?>
                </div><!-- /#search-wrap -->

                <div id="header-nav_bar">

                    <div id="header-nav">

                        <ul class="header_nav dropdown">
                            <li><a href="<?php echo uri('#'); ?>">Home</a></li>
                            <li><a href="<?php echo uri('beeldbank/'); ?>">Beeldbank</a>
                                <ul class="sub_menu">
                                    <li><a href="<?php echo uri('beeldbank/'); ?>">Zoeken</a></li>
                                    <!--<li><a href="<?php echo uri('items/advanced-search/'); ?>">Geavanceerd Zoeken</a></li>-->
                                    <li><a href="<?php echo uri('items/map/'); ?>">Kaart</a></li>
                                    <!-- <li><a href="<?php echo uri('beeldbank/tijdlijn/'); ?>">Tijdlijn</a></li>-->
                                    <!--<li><a href="<?php echo uri('#'); ?>">Albums</a></li>-->
                                </ul>
                            </li>
                            <!--<li><a href="<?php echo uri('werktuigen/'); ?>">Werktuigen</a></li>-->
                            <li><a href="<?php echo uri('verhalen/'); ?>">Verhalen</a>
                                <ul class="sub_menu">
                                    <li><a href="<?php echo uri('verhalen/'); ?>">Per tijdvak of per thema</a></li>
                                    <li><a href="<?php echo uri('verhalen/mensen/'); ?>">    Boer &amp; Co</a></li>
                                    <li><a href="<?php echo uri('verhalen/middenveld/'); ?>">Middenveld en beleid</a></li>
                                    <li><a href="<?php echo uri('verhalen/oogst/'); ?>">Een rijke oogst</a></li>
                                    <li><a href="<?php echo uri('verhalen/landschap/'); ?>">Boerderij en landschap</a></li>
                                    <li><a href="<?php echo uri('verhalen/industrie/'); ?>">Industrie en wetenschap</a></li>
                                    <li><a href="<?php echo uri('verhalen/eetcultuur/'); ?>">Eetcultuur</a></li>
                                    <li><a href="<?php echo uri('verhalen/identiteit/'); ?>">Identiteit en beeldvorming</a></li>
                                    <li><a href="<?php echo uri('alle-verhalen/'); ?>">Alle verhalen</a></li>

                                </ul>
                            </li>
                            <li><a href="<?php echo uri('bronnen/'); ?>">Onderzoeksbronnen</a>
                                <ul class="sub_menu">
                                    <li><a href="<?php echo uri('bronnen/bibliografie/'); ?>">Bibliografie</a></li>
                                    <li><a href="<?php echo uri('bronnen/archiefgids/'); ?>">Archiefgids</a></li>
                                    <li><a href="<?php echo uri('bronnen/rapporten/'); ?>">Rapporten</a></li>
                                   <!--   <li><a href="<?php echo uri('#'); ?>">Tijdschriften</a></li>
                                    <li><a href="<?php echo uri('#'); ?>">Rekeningen</a></li>
                                    <li><a href="<?php echo uri('#'); ?>">Beleidsmakers</a></li>-->
                                </ul>
                            </li>
                            <li><a href="<?php echo uri('actoren/'); ?>">Actoren</a></li>
                            <li><a href="<?php echo uri('contact/'); ?>">Contact</a></li>
                        </ul>

                    </div>
                </div>

            </div>
            <div id="content" class="group">