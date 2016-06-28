<?php
/* display message saved in session if any */
echo $this->Session->flash();
?>

<div class="responses form">
<?php echo $this->Form->create('OnlineResource', array('type'=>'file')); ?>
	<fieldset>
		<legend><?php echo __('Admin Add Online Resource'); ?></legend>
	<?php
		
		echo $this->Form->input('name');
		echo $this->Form->input('url');
		echo $this->Form->input('description');
                echo $this->Form->input('Category');
                echo $this->Form->input('image', array('type'=>'file'));
		?>
	</fieldset>
	<fieldset>
		<h3>Suitable for</h3>
		<?php
		echo $this->Form->input('age_lower', array('label'=>'Ages from...'));
		echo $this->Form->input('age_upper', array('label'=>'...to...'));
		echo $this->Form->input('gender_m', array('label'=>'Males'));
		echo $this->Form->input('gender_f', array('label'=>'Females'));
		?>
	<fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
