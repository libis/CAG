</div>
<div id="footer" class="group">
	<div class="column nieuwsbrief">
           
                <h2>Nieuwsbrief</h2>
                <div id="inschrijven"><a href="<?php echo url("newsletter/index/register")?>">Inschrijven</a></div>
                <div class="archief-link"><a href="<?php echo url("nieuwsbriefarchief")?>">Nieuwsbrief archief</a></div>
           
	</div><!-- /.column -->
	<div class="column links">
		<div class="column left">
						<h2>Website</h2>
							<ul>
							<li><a href="<?php echo url('over/')?>">Over deze website</a></li>
							<li><a href="mailto:diantha.osseweijer@cagnet.be">Technische problemen</a></li> <!-- link CAG-->
							<li><a href="<?php echo url('copyright/');?>">Copyright en disclaimer</a></li>

							<!-- <li><a href="<?php echo url("sitemap/");?>">Sitemap</a></li> -->
							</ul>
		</div><!-- /.column -->
		<div class="column right">
						<h2>Organisatie</h2>
							<ul>
							<li><a href="<?php echo url('over/missie/'); ?>">Missie en visie</a></li>
							<li><a href="<?php echo url('werking/'); ?>">Diensten</a></li>
							<li><a href="<?php echo url('team/'); ?>">Team</a></li>
							<li><a href="<?php echo url('werking/projecten/'); ?>">Projecten</a></li>
							<li><a href="<?php echo url('contact/'); ?>">Wegbeschrijving</a></li>
							<li><a href="<?php echo url('over/partners/'); ?>">Partners</a></li>
							</ul>
		</div><!-- /.column -->
		<div class="column  right ">
		<h2>Communicatie</h2>
							<ul>
							<li><a href="<?php echo url("contact/");?>">Contact</a></li>
							<li><a href="<?php echo url('nieuwsbriefarchief/'); ?>">Nieuwsbrief</a></li>
							<li><a href="<?php echo url('bronnen/eigenpublicaties/'); ?>">Publicaties</a></li>
							<li><a href="http://www.cagnet.be/showpage.php?pageID=26">English</a></li><!-- link CAG-->
							<li><a href="http://www.cagnet.be/showpage.php?pageID=25 ">français</a></li><!-- link CAG-->
							</ul>
		</div>
	</div><!-- /.column -->
	<div class="column contact">
               
		<p>Atrechtcollege<br/>
		Naamsestraat 63<br/>
		3000 Leuven<br />
		Belgi&euml;<br/>
		</p>
		<p>
		telefoon : +32 (0)16 32 35 25 <br />
		email : <a href="mailto:contact@cagnet.be">contact@cagnet.be</a>
		</p>
                 <?php echo add_this_add(); ?>
	</div><!-- /.column -->
	<?php fire_plugin_hook('public_footer'); ?>
</div><!-- end footer -->
</div><!-- end wrap -->
<p class="info">Inhoud: <a href="http://www.cagnet.be">Centrum Agrarische Geschiedenis</a> - Ontwerp en realisatie: <a href="http://www.libis.be">LIBIS</a> - Vormgeving: <a href="http://www.blau.be">Blau</a></p>
</body>
</html>
