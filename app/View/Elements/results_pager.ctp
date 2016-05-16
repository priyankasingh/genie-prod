<div id="results-pager">
	<div class="pager-holder">
		<?php if($paginator->hasPrev()) echo $paginator->prev('Prev',array('tag'=>false, 'class'=>'prev ajax'));?>
		<?php if($paginator->hasNext()) echo $paginator->next('Next',array('tag'=>false, 'class'=>'next ajax'));?>
	</div>
	<ul class="pager-list">
		<?php echo $paginator->numbers(array(
												'tag'=>'li',
												'separator'=>'',
												'class' => 'ajax',
												'currentClass'=>'active',
												'currentTag'=>'a'));?>
	</ul>
</div>