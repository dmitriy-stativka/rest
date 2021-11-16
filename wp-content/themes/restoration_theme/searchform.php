<form role="search" method="get" id="searchform" class="searchform" action="<?php echo home_url( '/' ); ?>">
        <label class="screen-reader-text" for="s">Search for:</label>
        <input type="text" value="<?php echo get_search_query() ?>" name="s" id="s" placeholder="Enter your search here"/>
        <input type="submit" id="searchsubmit" value="Search" />
</form>