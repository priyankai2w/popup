
<form id="searchform" class="searchform" role="search" method="get" action="<?php echo esc_url(home_url( '/' )); ?>" >
<div>
<label class="screen-reader-text" for="s">Search for:</label>
<input id="s" name="s" type="text"  value="<?php echo get_search_query() ?>" >
<input id="searchsubmit" value="Search" type="submit">
</div>
</form>





