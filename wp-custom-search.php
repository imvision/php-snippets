<?php 
/*
Template Name: Search Result
*/ ?>
<?php
global $wpdb;
$q = "";
if(isset($_GET['q']))
{
  $q = $_GET['q'];
  $city = $_GET['city'];
}

  $querystr = "
    SELECT p.*, pm.meta_value  
    FROM $wpdb->posts p 
    INNER JOIN $wpdb->postmeta pm 
    ON p.ID = pm.post_id 
    WHERE p.post_status = 'publish' 
    AND p.post_type = 'hotels' 
    AND p.post_title LIKE '%$q%' 
    AND pm.meta_value LIKE '%$city%' 
    AND pm.meta_key = 'hotels_city' 
    ORDER BY p.post_date DESC";

 $pageposts = $wpdb->get_results($querystr, OBJECT);
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Search Result</title>

    <!-- Bootstrap -->
  </head>
  <body>

<div class="main_section">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="section_2">
                <h1>Results Found: <?php echo count($pageposts);?> Hotels / Appartments</h1>
                  <?php if ($pageposts): ?>
                 <?php global $post; ?>
                 <?php foreach ($pageposts as $post): ?>
                 <?php setup_postdata($post); ?>
                 <?php $price = get_post_meta ( get_the_ID(), 'hotels_price', true );?>
                    <div class="abu_dhabi">
                      <div class="row">
                        <div class="col-md-3">
                        	<a href="#"><img src="<?php bloginfo('template_url'); ?>/images/1_03.png" class="img-responsive"></a>
                        </div>
                            
                            <div class="col-md-9">
                            	<div class="city">
                                    <div class="row">
                                    <div class="col-md-10">
                                    	<h3><?php the_title();?></h3>
                                    </div>
                                    <div class="col-md-2">
                                    	<img src="<?php bloginfo('template_url'); ?>/images/Search-Result_03.png" class="img-responsive">
                                    </div>
                                    </div>
                                    <div class="row">
                                    <div class="col-md-12">
                                        <h4>City - <?php echo $post->meta_value;?> <span class="graye">---</span><span class="purpul"> Show Map</span></h4>
                                        <div class="borderi">
                                        <div class="row">
                                        <div class="col-md-6">
                                        <h5>Standard Room . SGL</h5>
                                        </div>
                                        <div class="col-md-6">
                                        <h6>BB   |  <span class="green"> Available</span>   |  <span class="purpul"> AED <?php echo $price;?></span></h6>
                                        </div>
                                        </div>
                                        </div>
                                        <div class="book">
                                        <div class="row">
                                            <div class="col-md-8">
                                            <p><a href="#">Show More Room Types</a></p>
                                            </div>
                                            
                                            <div class="col-md-1">
                                            <img src="<?php bloginfo('template_url'); ?>/images/icons_03.png" class="img-responsive">
                                            </div>
                                            
                                            <div class="col-md-3">
                                            <h7><a href="#">BOOK</a></h7>
                                            </div>
                                        </div>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <?php endforeach; ?>
                   <?php else : ?>
                      <h2 class="center">Not Found</h2>
                      <p class="center">Sorry, but you are looking for something that isn't here.</p>
                   <?php endif; ?>

                  <?php //endwhile;?>
                  <?php //endif;?>
                  <?php //wp_reset_postdata(); ?>

                        <div class="blue">
                          <div class="row">
                            <div class="col-md-4">
                              <div class="page_index">
                                <a href="#"><i class="fa fa-angle-double-left"></i></a><a href="#"><i class="fa fa-angle-left"></i></a><span class="purpul">01</span><a href="#"><i class="fa fa-angle-right"></i></a><a href="#"><i class="fa fa-angle-double-right"></i></a>
                              </div>
                            </div>
                                
                            <div class="col-md-2">
                                <div class="graye">
                                  <select name="date" class="day">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                  </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <span class="graye">Items per page</span>
                                
                            </div>
                                
                            <div class="col-md-4">
                                <span class="graye">2 - 2 of 2 items</span>
                                
                            </div>
                          </div>
                        </div>
                      </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <div class="footer">
<div class="container">
<div class="row">
<div class="col-md-4">
<a href="#">Home</a>                     
<a href="#">Tariff’s</a>
<a href="#">Reservation</a>
<a href="#">Contact us</a>
</div>

<div class="col-md-8">
<h1><span class="white">© 2015</span> Mawasim Travel & Holidays</h1>
</div>
</div>
</div>
</div>
        </div>
    </div>
</div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php bloginfo('template_url'); ?>/js/bootstrap.min.js"></script>
  </body>
</html>