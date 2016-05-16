<?php echo $emailName; ?>,

<?php echo __('A password reset request has been received for your account. If this is correct please click the following link to generate a new password:'); ?>

<?php echo Configure::read('Site.url'); ?>users/forgot_password/<?php echo $emailId; ?>/<?php echo $emailKey; ?>

<?php echo __('If you did not request a password reset, please delete this email and do not click the link.'); ?>


<?php echo __('Thanks,'); ?>

<?php echo Configure::read('Site.name'); ?>