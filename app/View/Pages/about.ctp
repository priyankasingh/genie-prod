<?php
	$what_is = $this->requestAction('pages/view/1');
	$about = $this->requestAction('pages/view/3');
?>
<div class="main-holder">
	<h1>Learn more about PLANS...</h1>
	<div class="text-box">
		<h2><?php echo $about['name'];?></h2>
		<div class="two-columns page-content"><?php echo $about['content'];?></div>
	</div>
</div>
<div class="options-section">
    <h2>What would you like to do next?</h2>
    <ul class="options-list">
        <li class="mouse"><a id="questionnaire-button" href="/#questionnaire" class="question-button" >Fill in the short PLANS<br/> questionnaire and get<br/> your own tailored,<br/> personal map</a></li>
        <li class="search"><a id="search-button" href="/#postcode-search" class="question-button" >Have a quick look at<br/> what's available in your<br/> area by entering your<br/> postcode</a></li>
        <li class="pencil"><a id="login-button" href="#/login" class="question-button" >Sign in if you already<br/> have an account<br/> with us.</a></li>
    </ul>
</div>