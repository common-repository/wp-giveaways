<?php
/**
 * Represents the view for the settings.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   WP Giveaways
 * @author    Zoran C. <web@zoranc.co>
 * @license   GPL2
 * @link      http://zoranc.co/wp-giveaways/
 * @copyright 2014 Zoran C.
 */
?>
<style>
.addon {
    background-color:#ADD8E6;
    border: 1px outset #000000;
    display: block;
    height: 165px;
    padding: 20px;
    text-decoration: none;
}
.addon:hover {
    background-color:#FFFFFF;
}
.addon:active {
    border: 1px inset #000000;
}
.addon h2 {
    color:#444444;
}
.addon > img{
    float:left;
    padding:10px;
}
</style>

<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
  <a href="http://store.zoranc.co/downloads/wp-giveaways-addon-mailpoet-integration/" target="_blank" class="addon" title="Full Description">
    <img src="<?php echo plugins_url( 'wp-giveaways/admin/assets/img/mailpoet-addon.png' ); ?>" />
    <div>
      <h2>MailPoet Integration</h2>
      <p>Increase your conversions by encouraging mailpoet subscriptions.</p>
      <p>Use mailpoet lists as a pools from which to pick and reward random winner(s)!</p>
    </div>
	</a>

</div>
