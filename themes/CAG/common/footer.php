</div>
<div id="footer" class="group">
	<div class="column nieuwsbrief">            
            <h2>Nieuwsbrief</h2>
                <div id="inschrijven"><a href="<?php echo url("newsletter/index/register")?>">Inschrijven</a></div>                
                <div class="archief-link"><a href="<?php echo url("nieuwsbriefarchief")?>">Nieuwsbrief archief</a></div>
                <div class="archief-link"><a href="<?php echo url("newsletter/index/unsubscribe")?>">Uitschrijven nieuwsbrief</a></div>
                
	</div><!-- /.column -->
	<div class="column links">
		<div class="column left">
                    <h2>Website</h2>
                    <ul>
                    <li><a href="<?php echo url('overwebsite')?>">Over deze website</a></li>
                    <li><a href="mailto:diantha.osseweijer@cagnet.be">Technische problemen</a></li> <!-- link CAG-->
                    <li><a href="<?php echo url('copyright/');?>">Copyright en disclaimer</a></li>
                    </ul>                   					
		</div><!-- /.column -->
		<div class="column right">
                    <h2>Communicatie</h2>
                    <ul>
                    <li><a href="<?php echo url('bronnen/eigenpublicaties/'); ?>">Publicaties</a></li>
                    <li><a href="<?php echo url('vacatures/'); ?>">Vacatures</a></li>
                    <li><a href="<?php echo url("contact/");?>">Contact</a></li>
                    <li><a href="<?php echo url('wegbeschrijving/'); ?>">Wegbeschrijving</a></li>
                    
                    <li><a href="<?php echo url('english'); ?>">English</a></li>
                    <li><a href="<?php echo url('francais'); ?>">Français</a></li>
                    </ul>
		</div><!-- /.column -->
		<div class="column  right ">
                    	
                     <h2>Over ons</h2>
                    <ul>
                    <li><a href="<?php echo url('over/missie/'); ?>">Missie</a></li>
                    <li><a href="<?php echo url('werking/basiswerking/'); ?>">Basiswerking</a></li>
                    <li><a href="<?php echo url('over/team/'); ?>">Team</a></li>
                    <li><a href="<?php echo url('werking/projecten/'); ?>">Projecten</a></li>                       
                    <li><a href="<?php echo url('over/partners/'); ?>">Partners</a></li>
                    </ul>	
		</div>
	</div><!-- /.column -->
	<div class="column contact">
            <?php echo add_this_add(); ?>
            <div id="logo_vla"><a href="http://www.vlaanderen.be/nl"><img width="200" alt="Met steun van de Vlaamse overheid" src="<?php echo img("leeuwsteunVO-Gr.png");?>"></a></div>
	</div><!-- /.column -->
	<?php fire_plugin_hook('public_footer'); ?>
</div><!-- end footer -->
</div><!-- end wrap -->
<p class="info">Inhoud: <a href="<?php echo url('contact'); ?>">Centrum Agrarische Geschiedenis</a> - Ontwerp en realisatie: <a href="http://www.libis.be">LIBIS</a></p>
</body>
</html>
