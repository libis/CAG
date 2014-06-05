<?php echo head(array('bodyid'=>'home', 'bodyclass' =>'two-col')); ?>

<div id="frontpage">
    <?php if ($homepageText = get_theme_option('Homepage Text')): ?>
    <p><?php echo $homepageText; ?></p>

    <?php endif; ?>

    <div id="carousel-block">
        <h2>Welkom op Het Virtuele Land - Centrum Agrarische Geschiedenis</h2>
        <p>Het Centrum Agrarische Geschiedenis is het expertisecentrum voor het agrarische erfgoed in Vlaanderen.
        We bestuderen de geschiedenis en het erfgoed van landbouw, platteland en voeding, vanaf 1750 tot en met vandaag.</p>
        
        <h2>In de kijker</h2>
        <div id="item-block" class="kijker-block">
            <?php
                echo libis_get_featured_news();                
            ?>           
        </div>    
        
        <div id="meer_nieuws"><p><a href='<?php echo url('solr-search/results?q=&facet=itemtype:("Nieuwsbericht" OR "Agendapunt")') ?>'><img src="<?php echo img('arrow-right.png');?>">Meer nieuws</a></p></div>
        
        <div id="carousel-container">
            <div id="item-block">
                <h2><?php echo __('Objecten uit onze erfgoedbank'); ?></h2>		
                <?php
                    $items = Libis_get_random_featured_items('10');    
                ?>       
                <div class="cycle" id="cycle2">
                    <ul class="rotator2">
                    <?php foreach(loop('items',$items) as $item):?> 
                        <li>
                           <a href="<?php echo url("items/show/".$item->id);?>"><div class="cycle-container"><img class="carousel-image" src="<?php echo digitool_get_thumb_url($item);?>"></div></a>

                        </li>
                    <?php endforeach; ?>
                    </ul>
                    <a href="#" class="prev p2"></a>
                    <a href="#" class="next n2"></a>
                    <div class="description"></div>
                    <div class="thumbnail"></div>
                </div>  
            </div>   

            <div id="item-block">
                <h2><?php echo __('Verhalen'); ?></h2>
                <?php
                    //get the 10 latest exhibits
                    $exhibits = exhibit_builder_recent_exhibits(10);
                ?>            
                <div class="cycle" id="cycle1">
                <ul class="rotator1">
                    <?php foreach($exhibits as $exhibit) {
                        if (libis_get_exhibit_thumb($exhibit)):?>
                            <li>
                                <?php echo exhibit_builder_link_to_exhibit($exhibit,LIBIS_get_exhibit_thumb($exhibit,array('class'=>'thumbnail carousel-image','height'=>'150','width'=>'150'))); ?>
                            </li>
                        <?php endif;?>	
                    <?php } ?>
                </ul>
                <a href="#" class="prev p1"></a>
                <a href="#" class="next n1"></a>
                <div class="description"></div>
                <div class="thumbnail"></div>
                </div>   

                <br>	
            </div>       
        </div>             
    </div>
    
    <div id="wegwijs">
        <div class="logo" ><center><img src="<?php echo (img("cag_logo.jpg"));?>" /></center></div>
        <div class="wegwijs-block">
            <h2>Nieuwsbrief</h2>
            <div id="inschrijven"><a href="<?php echo url("newsletter/index/register")?>">Inschrijven</a></div>
            <div class="lees_meer"><a href="<?php echo url("nieuwsbriefarchief")?>">Nieuwsbrief archief</a></div>
        </div>
        <?php echo libis_get_news(); ?>
        <?php echo libis_get_agenda(); ?>
        <div id="wegwijs-social">
            <?php echo add_this_add(); ?>
        </div>    
    </div>
    
    <script>
          
    jQuery(document).ready(function($) {

        $('.rotator1').roundabout({
          btnNext: ".n1",
          btnPrev: ".p1",
          minScale:0.2,
          maxScale:0.8
        });

         $('.rotator2').roundabout({
          btnNext: ".n2",
          btnPrev: ".p2",
          minScale:0.2,
          maxScale:0.8
         });
    });

    </script>
</div>
<?php echo foot(); ?>