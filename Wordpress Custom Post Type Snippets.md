Custom Post Type Snippets to make you smile
17 June, 2011 by Joost de Valk - 20 Comments

So it’s friday, I’ve been coding all day and I thought I’d share some of the cool snippets I’ve come across and/or developed today. I’ve mostly been working with Custom Post Types and Taxonomies, so let me share some of that goodness. Let’s geek out in a bit, but first let me show you why this is cool, be sure to click the image, so you can see which functionality I’ve added to the otherwise boring custom posts overview screen:
Custom Post Type goodnessClick for large version
Add columns to the overview page for a Custom Post Type

So you’ll want to add some columns to your post type’s overview page, or remove some. Don’t forget to replace <CPT> with your own custom post type in all these examples:
1
// Change the columns for the edit CPT screen
2
function change_columns( $cols ) {
3
  $cols = array(
4
    'cb'       => '<input type="checkbox" />',
5
    'url'      => __( 'URL',      'trans' ),
6
    'referrer' => __( 'Referrer', 'trans' ),
7
    'host'     => __( 'Host', 'trans' ),
8
  );
9
  return $cols;
10
}
11
add_filter( "manage_<CPT>_posts_columns", "change_columns" );
Be sure to always leave the cb column in there or your mass edit / delete functionality will not work.
Give these new columns some content

Now let’s fill these new columns with some content from the custom post type:
1
function custom_columns( $column, $post_id ) {
2
  switch ( $column ) {
3
    case "url":
4
      $url = get_post_meta( $post_id, 'url', true);
5
      echo '<a href="' . $url . '">' . $url. '</a>';
6
      break;
7
    case "referrer":
8
      $refer = get_post_meta( $post_id, 'referrer', true);
9
      echo '<a href="' . $refer . '">' . $refer. '</a>';
10
      break;
11
    case "host":
12
      echo get_post_meta( $post_id, 'host', true);
13
      break;
14
  }
15
}
16
 
17
add_action( "manage_posts_custom_column", "custom_columns", 10, 2 );
Make these new columns sortable

Now this extra info is cool, I bet you want to sort by it, that’s as simple as this:
1
// Make these columns sortable
2
function sortable_columns() {
3
  return array(
4
    'url'      => 'url',
5
    'referrer' => 'referrer',
6
    'host'     => 'host'
7
  );
8
}
9
 
10
add_filter( "manage_edit-<CPT>_sortable_columns", "sortable_columns" );
Filter Custom Posts by Custom Taxonomy

Ok so far this is all fairly simple. Now let’s go a bit more advanced. Let’s say you have a custom taxonomy attached to that custom post type and you want to show a filter for that custom taxonomy on the custom post types overview page, just like you have a categories drop down on the posts overview page. This code was taken (though slightly modified) from this thread.
Let’s first add that dropdown / select box to the interface:
1
// Filter the request to just give posts for the given taxonomy, if applicable.
2
function taxonomy_filter_restrict_manage_posts() {
3
    global $typenow;
4
 
5
    // If you only want this to work for your specific post type,
6
    // check for that $type here and then return.
7
    // This function, if unmodified, will add the dropdown for each
8
    // post type / taxonomy combination.
9
 
10
    $post_types = get_post_types( array( '_builtin' => false ) );
11
 
12
    if ( in_array( $typenow, $post_types ) ) {
13
      $filters = get_object_taxonomies( $typenow );
14
 
15
        foreach ( $filters as $tax_slug ) {
16
            $tax_obj = get_taxonomy( $tax_slug );
17
            wp_dropdown_categories( array(
18
                'show_option_all' => __('Show All '.$tax_obj->label ),
19
                'taxonomy'    => $tax_slug,
20
                'name'      => $tax_obj->name,
21
                'orderby'     => 'name',
22
                'selected'    => $_GET[$tax_slug],
23
                'hierarchical'    => $tax_obj->hierarchical,
24
                'show_count'    => false,
25
                'hide_empty'    => true
26
            ) );
27
        }
28
    }
29
}
30
 
31
add_action( 'restrict_manage_posts', 'taxonomy_filter_restrict_manage_posts' );
And then, we add a filter to the query so the dropdown will actually work:
view sourceprint?
1
function taxonomy_filter_post_type_request( $query ) {
2
  global $pagenow, $typenow;
3
 
4
  if ( 'edit.php' == $pagenow ) {
5
    $filters = get_object_taxonomies( $typenow );
6
    foreach ( $filters as $tax_slug ) {
7
      $var = &$query->query_vars[$tax_slug];
8
      if ( isset( $var ) ) {
9
        $term = get_term_by( 'id', $var, $tax_slug );
10
        $var = $term->slug;
11
      }
12
    }
13
  }
14
}
15
 
16
add_filter( 'parse_query', 'taxonomy_filter_post_type_request' );
Note that for these last two snippets to work, query_var must have been set to true when registering the custom taxonomy, otherwise this’ll never work.
Bonus: Add Custom Post Type to feed

This one came courtesy of Remkus de Vries a while back and was helpful today, adding a custom post type to your site’s main feed, don’t forget to replace <CPT> with your own custom post type:
1
// Add a Custom Post Type to a feed
2
function add_cpt_to_feed( $qv ) {
3
  if ( isset($qv['feed']) && !isset($qv['post_type']) )
4
    $qv['post_type'] = array('post', '<CPT>');
5
  return $qv;
6
}
7
 
8
add_filter( 'request', 'add_cpt_to_feed' );

source: http://yoast.com/custom-post-type-snippets/
