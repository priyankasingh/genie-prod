<?php $contact = $this->requestAction('pages/view/4'); ?>
<div class="first-box text-box contact">
    <h1><?php echo $contact['name'];?></h1>
    <div class="col">
        <div class="contact-part formatted" id="write">
            <?php echo $contact['content'];?>
        </div>
        <!--<div class="contact-part" id="email">
            <br />
            <strong>Email:</strong> <a href="mailto:info@plans.org">info@plans.org</a>
        </div>
        <div class="contact-part" id="tel">
            <br />
            <strong>Tel:</strong> 0161 494 0571
        </div>-->
    </div>
    <div class="col">
        <strong><?php echo __('Ask us a question'); ?>:</strong>

<?php echo $this->Form->create('Contact', array( 'action' => 'add', 'model'=> 'Contact')); ?>
    <fieldset>
        <?php
            echo $this->Form->input('name', array('id' => 'contact-name', 'placeholder' => __('Your Name'), 'label' => false));
            echo $this->Form->input('email', array('id' => 'contact-email', 'placeholder' =>__('Your Email Address'), 'label' => false));
            echo $this->Form->input('question', array('id' => 'contact-question', 'placeholder' => __('Your Question'), 'label' => false));
            echo $this->Form->submit(__('Submit'), array('class' => 'btn'));
        ?>
    </fieldset>
<?php echo $this->Form->end(); ?>
    </div>
</div>