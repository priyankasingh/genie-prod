<div id="category-description">
<div id="parent-helper" <?php if(isset($active_nav) && $active_nav == 'my_plans') echo "class='serviceParent'"; ?>></div>
<?php 
if( !empty( $parent_category ) ) echo $parent_category['Category']['description'];
?>
</div>

<?php echo $this->element('category_filter', array(
	'sub_category_list' => isset($sub_category_list)?$sub_category_list:null,
	'categories' => $categories,
	'selected_parent_id' => $selected_parent_id,
	'selected_parent_slug' => $selected_parent_slug,
));?>

<?php echo $this->element('results_box', array(
									'parents' => isset($parents)?$parents:null,
									'categories' => isset($categories)?$categories:null,
									'paginator' => isset($this->Paginator)?$this->Paginator:null,
									'category' => isset($category)?$category:null,
									'service' => isset($service)?$service:null,
							));?>	
<?php echo $this->element('results_pager', array(
		'paginator' => $this->Paginator,
));?>
<div id="parent-id"><?php echo $selected_parent_id;?></div>
<div id="postcode"><?php echo $postcode;?></div>