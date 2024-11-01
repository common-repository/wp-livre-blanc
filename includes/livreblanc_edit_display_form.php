<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<form name="livreblanc_add" method="post" action="<?php echo admin_url( 'admin.php' ); ?>" enctype="multipart/form-data">

<div id="titlediv">
    <div id="titlewrap">
        <input name="input-name" autofocus size="30" value="<?php echo $name; ?>" id="title" spellcheck="true" autocomplete="off" type="text" placeholder="<?php _e('Your white paper name', 'olyos-livre-blanc'); ?>">
    </div>
</div>

<?php if ($id !== ''): ?>
    <div id="livreblanc-shortcode">
        <strong><?php _e('Shortcode : ', 'olyos-livre-blanc'); ?></strong>
        <input type="text" readonly value="[livreblanc id=<?php echo $id ?>]"></input>
    </div>
<?php endif; ?>

<?php wp_editor($description, 'description'); ?>

<br />

<section id="social_options" class="postbox">
    <div class="inside">
        <table class="form-table">
            <?php if ($file_content == ''):?>
            <tr>
                <th scope="row"><label for="upload-file"><?php _e('White paper file : ', 'olyos-livre-blanc'); ?></label></th>
                <td><input type='file' id='upload-file' name='upload-file' value="<?php echo $file_content; ?>"/></td>
            </tr>
            <?php else: ?>
            <tr>
                <th scope="row"><label for="upload-file"><?php _e('White paper file : ', 'olyos-livre-blanc'); ?></label></th>
                <td><?php echo $file_content; ?></td>
            </tr>
            <tr>
                <th scope="row"></th>
                <td><input type='file' id='upload-file' name='upload-file' value="<?php echo $file_content; ?>"/></td>
            </tr>
            <?php endif; ?>
        </table>
    </div>
</section>

<section id="form_options" class="postbox">
<div class="inside">
    <table class="form-table">
        <tr>
        <th scope="row"><label for="form-title"><?php _e('Form block title : ', 'olyos-livre-blanc'); ?></label></th>
        <td><input type="text" id="form-title" name="form-title" value="<?php echo $form_title_content; ?>"/></td>
        </tr>

        <tr>
        <th scope="row"><label for="newsletter-chb"><?php _e('Display newsletter checkbox : ', 'olyos-livre-blanc'); ?></label></th>
        <td><input type="checkbox" id="newsletter-chb" name="newsletter-chb" value="checked" <?php echo($newsletter_checkbox == '1' ? 'checked="checked"' : '') ?>/></td>
        </tr>
    </table>
</div>
</section>

<section id="social_options" class="postbox">
<div class="inside">
    <table class="form-table">
        <tr>
            <th scope="row"><label for="social-title"><?php _e('Social block title : ', 'olyos-livre-blanc'); ?></label></th>
            <td><input type="text" id="social-title" name="social-title" value="<?php echo $social_title_content; ?>"/></td>
        </tr>
        <tr>
            <th scope="row"><label for=""><?php _e('Facebook : ', 'olyos-livre-blanc'); ?></label></th>
            <td>
                <textarea name="input-social1" rows="4"><?php echo $social1; ?></textarea>
                <p class="description"><?php _e('Insert the iframe of your Facebook page. ', 'olyos-livre-blanc'); ?><a href="https://developers.facebook.com/docs/plugins/page-plugin" target="_blank"><?php _e('See documentation', 'olyos-livre-blanc'); ?></a></p>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for=""><?php _e('Twitter : ', 'olyos-livre-blanc'); ?></label></th>
            <td>
                <textarea name="input-social2" rows="4"><?php echo $social2; ?></textarea>
                <p class="description"><?php _e('Insert the url of your tweet.', 'olyos-livre-blanc'); ?> ex: https://twitter.com/Olybop/status/827114532921339904</p>
            </td>
        </tr>
        
    </table>
</div>
</section>

<input type="hidden" name="livreblanc_id" value="<?php echo $id; ?>"/>
<input type="hidden" name="insert_type" value="<?php echo $insert_type; ?>"/>
<input type="hidden" name="action" value="process_livreblanc_form"/>

<p class="submit"><input type="submit" name="Save" value="<?php _e('Save white paper', 'olyos-livre-blanc'); ?>" class="button-primary" /></p>
</form>