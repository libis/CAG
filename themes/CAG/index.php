<?php echo head(array('bodyid'=>'home', 'bodyclass' =>'two-col')); ?>

<div id="frontpage-top">
   
  

        <div class="logo" ><center><img src="<?php echo (img("cag_logo.png"));?>" /></center></div>
        <div id="intro">
            <h1>Welkom op Het Virtuele Land</h1>
            <?php if ($homepageText = get_theme_option('Homepage Text')): ?>
            <p><?php echo $homepageText; ?></p>
            <?php endif; ?>
            </div>
</div>  
        <h2>In de kijker</h2>
        <div id="item-block" class="kijker-block">
            <div class="in_de_kijker nieuws-knoppen">
                <div class='overzicht-link'><a href='<?php echo url('solr-search/results?q=&facet=itemtype:("Nieuwsbericht" OR "Agendapunt")'); ?>'>Nieuwsberichten</a></div>
                <div class='overzicht-link'><a href='<?php echo url('solr-search/results?q=&facet=itemtype:("Nieuwsbericht" OR "Agendapunt")'); ?>'>Agenda</a></div>
            </div>
            <?php
                echo libis_get_featured_news();                
            ?>           
        </div>    
        
        <div id="meer_nieuws"><p><a href='<?php echo url('solr-search/results?q=&facet=itemtype:("Nieuwsbericht" OR "Agendapunt")') ?>'><img src="<?php echo img('arrow-right.png');?>">Meer nieuws</a></p></div>
        
        <div id="carousel-container">
            
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
            
            <div id="item-block">
                <h2><?php echo __('Objecten uit onze erfgoedbank'); ?></h2>		
                <?php
                    $items = Libis_get_random_featured_items('10',true);    
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