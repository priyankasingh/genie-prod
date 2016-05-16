<?php echo $emailName; ?>,

<?php echo __('Your password has recently been changed.'); ?>

<?php echo sprintf( __('You can log in at %s with your new details.'), Configure::read('Site.url') ); ?>

<?php echo __('If you did not recently request a password reset, please contact us straight away.'); ?>


<?php echo __('Thanks,'); ?>

<?php echo Configure::read('Site.name'); ?>