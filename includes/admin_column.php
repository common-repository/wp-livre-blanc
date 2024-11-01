<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div id="side-sortables">
	<div class="postbox voter">
		<h3>Noter ce plugin</h3>
		<p class="stars">★★★★★</p>
		<p>Merci de noter ce plugin pour nous remercier ! Il est gratuit et nous avons besoin de vous !</p>
		<a id="rate-plugin" href="https://wordpress.org/plugins/wp-livre-blanc/" target="_blank" title="Voter">
			Voter
		</a>
	</div>

	<div class="postbox">
		<h3><?php _e('About us', 'olyos-livre-blanc'); ?></h3>
		<div id="olyosfr">
			<a href="https://www.olyos.fr/?utm_source=WPLB&utm_campaign=pluginlivreblanc&utm_medium=adminlink" target="_blank" title="<?php _e('Wordpress plugins creation - Nantes Web Agency', 'olyos-livre-blanc'); ?>">
				<img src="<?php echo esc_url(plugins_url('img/icon_olyos.png', dirname(__FILE__))); ?>"/>
				<div>
					<h4>olyos.fr</h4>
					<p><strong><?php _e('Nantes Web Agency', 'olyos-livre-blanc'); ?></strong> : <?php _e('Digital strategy, custom creation of corporate and e-commerce websites.', 'olyos-livre-blanc');?></p>
				</div>
			</a>
			<ul>
				<li><?php _e('Expertise and support for your <strong>digital strategy</strong>', 'olyos-livre-blanc'); ?></li>
				<li><?php _e('Creation of custom <strong>corporate</strong> and <strong>e-commerce</strong> websites', 'olyos-livre-blanc'); ?></li>
				<li><?php _e('Wordpress & Prestashop <strong>Plugins</strong> Development', 'olyos-livre-blanc'); ?></li>
				<li><?php _e('Social networks & SEO <strong>support</strong>', 'olyos-livre-blanc'); ?></li>
			</ul>
			<a id="contact-us" href="https://www.olyos.fr/contact/?utm_source=WPLB&utm_campaign=pluginlivreblanc&utm_medium=adminlink" target="_blank" title="<?php _e('Contact us', 'olyos-livre-blanc'); ?>">
				<?php _e('Contact us', 'olyos-livre-blanc'); ?>
			</a>
		</div>
		<hr>
		<div id="olybopfr">
			<a href="//olybop.fr/?utm_source=WPLB&utm_campaign=pluginlivreblanc&utm_medium=adminlink" target="_blank" title="<?php _e('News web, webdesign, graphic design', 'olyos-livre-blanc'); ?>">
				<img src="<?php echo esc_url(plugins_url('img/icon_olybop.png', dirname(__FILE__))); ?>"/>
				<div>
					<h4>olybop.fr</h4>
					<p>Découvrez l’actualités Webdesign / Graphisme et notre expertise corporate sur les tendances web.</p>
				</div>
			</a>
		</div>
	</div>

	<div class="postbox community">
		<h3><?php _e('Community', 'olyos-livre-blanc'); ?></h3>
		<p>Rejoignez la communauté de plus de 20 000 personnes !</p>
		<p>
			<a class="facebook" href="https://www.facebook.com/Olybop" target="_blank" title="<?php _e('', 'olyos-livre-blanc'); ?>">Devenez fan !</a>
			<a class="twitter" href="https://twitter.com/Olybop" target="_blank" title="<?php _e('', 'olyos-livre-blanc'); ?>">Follow !</a>
		</p>
	</div>

	<div class="postbox">
		<h3><?php _e('Informations', 'olyos-livre-blanc'); ?></h3>
		<p>Développé et testé à partir de la version WP : 4.7 et supérieur</p>
		<p><strong>Attention</strong> : Si vous décidez de supprimer le plugin, il supprimera tous vos livres blancs de votre site web.</p>
		<p><span class="olyos-green">PREMIUM</span> : Une version avancée du plugin sera bientôt disponible. Nous ne manquerons pas de vous en informer.</p>

	</div>
</div>