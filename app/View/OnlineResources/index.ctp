<div class="content-box <?php if( !empty( $favourites ) ) echo 'favourites '; ?>">
	<div id="category-description">
	
            <?php
            if( !empty( $parent_category )) echo $parent_category['Category']['description'];
           
            ?>
	</div>
    
       
        <?php
        echo $this->element('category_filter_os',
                array(
                        'sub_category_list' => isset($sub_category_list)?$sub_category_list:null,
                        'categories' => isset($categories)?$categories:null,
                        'selected_parent_id' => isset($selected_parent_id)?$selected_parent_id:null,
                        'selected_parent_slug' => isset($selected_parent_slug)?$selected_parent_slug:null,
                )
        );
        ?> 

<div class="aside">
    <a class="print" href="#"><?php echo __('Print Your results'); ?></a>
    <div class="link-holder">
        <a class="results" href="#"><?php echo __('My Results'); ?></a>
      <!--  <a class="favourites" href="#"><?php echo __('My Favourites'); ?></a> -->
    </div>

    <div id="results_box">
        <ul id="results-list">
            <li class="category-42">
                <h2 class="results-list">Online Resources</h2>
                
                <ul class="aside-list">
                
                    <?php foreach ($onlineResource as $on): ?>

                        <?php foreach ($on['OnlineResource'] as $online_resource): ?>
                            <li id="service-<?php echo h($online_resource['id']); ?>" class="results-list">

                            <div class="text-section">

                                <h3><a href="<?php echo $online_resource['url'];?>" target="_blank"><?php echo $online_resource['name'] ;?></a> -
                                    <span class="mark"><?php echo $cat;?></span></h3>
                                <?php if( !empty($online_resource['url']) ):?>
                                    <p><strong><?php echo __('URL:'); ?> </strong><a href="<?php echo $online_resource['url'];?>" target="_blank"><?php echo $online_resource['url'];?></a></p>
                                <?php endif;?>
                                <?php if( !empty($online_resource['description']) ):?>
                                    <p><strong><?php echo __('Description:'); ?> </strong><?php echo ($online_resource['description']);?></p>
                                <?php endif;?>

                                <?php if( !empty($online_resource['age_lower']) ):?>
                                    <p><strong><?php echo __('Age:'); ?> </strong><?php echo ($online_resource['age_lower']);?> - <?php echo ($online_resource['age_upper']);?></p>
                                <?php endif;?>
                            </div>
                            </li>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                   
                </ul>
            </li>
        </ul>
    </div>
</div>

