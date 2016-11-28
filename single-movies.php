<?php
/**
 * Gold MOVIES is simple and awesome movies template for Wordpress.
 *
 * @package WordPress
 * @subpackage Gold_MOVIES
 * @since Gold MOVIES 1.0
 */

include('lib/TMDb.php');

define('TMDB_API_KEY', ot_get_option('tmdb_api_key'));

get_header();

setPostViews(get_the_ID());

$searchMovieImage = '';

// TMDb API Connection
  if(TMDB_API_KEY != '' && ot_get_option('movie_cover') == 'on') {
    $tmdb = new TMDb(TMDB_API_KEY);
    $tmdbConfig['config'] = $tmdb->getConfiguration();
    $title = get_the_title();
    $row = $tmdb->searchMovie($title, '1', FALSE, get_post_meta(get_the_ID(), 'movies_year', true));
	foreach ($row['results'] as $key => $movie) {
	      if($key >= 1){
	        break; // Get out after 10 movies.
	      }
	      $searchMovieId = $movie['id'];
  	}
  	if(!isset($searchMovieId) == '') {
	  	$poster = $tmdb->getMovieImages($searchMovieId);
		foreach ($poster['posters'] as $key_2 => $posters) {
		      if($key_2 >= 1){
		        break; // Get out after 10 movies.
		      }
		      $searchMoviePoster = $tmdb->getImageUrl($posters['file_path'], 'poster', "original");
	  	}

	  	$image = $tmdb->getMovieImages($searchMovieId);
		foreach ($image['backdrops'] as $key_3 => $images) {
		      if($key_3 >= 1){
		        break; // Get out after 10 movies.
		      }
		      $searchMovieImage .= $tmdb->getImageUrl($images['file_path'], 'poster', "original");
	  	}

	  	$trailer = $tmdb->getMovieTrailers($searchMovieId);
		foreach ($trailer['youtube'] as $key_3 => $trailers) {
		      if($key_3 >= 1){
		        break; // Get out after 10 movies.
		      }
		      $searchMovieTrailer = "http://www.youtube.com/embed/".$trailers['source'];
	  	}
	}
  }
?>

<div id="waterfall-container" class="waterfall-container" style="margin: 64px auto 0;">
	<div class="post_preview" style="background: #565558 url('http://wpmovies.themesgold.com/wp-content/themes/goldmovies/assets/images/theatre.jpg'); background-size: 960px;">
	<?php if(TMDB_API_KEY != '' && ot_get_option('movie_cover') == 'on' && $searchMovieImage != '') { ?>
		<div id="left-wrapper"></div>
		<div id="right-wrapper"></div>
	<?php } ?>

		<div class="player">

			<?php
			if(get_post_meta(get_the_ID(), 'movies_link', true) == '') {
			?>
				<iframe src="<?php echo get_post_meta(get_the_ID(), 'movie_iframe', true); ?>" frameborder="0" style="width: 920px; height: 510px;" allowfullscreen></iframe>
			<?php
			} else {
			?>
			<video class="video-js vjs-big-play-centered" controls preload="auto" width="100%" height="100%" id="player" poster="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id( ), 'large' )[0]; ?>" data-setup="{}">
			    <source src="<?php echo get_post_meta(get_the_ID(), 'movies_link', true); ?>" type='video/mp4'>
			    <p class="vjs-no-js">
			      To view this video please enable JavaScript, and consider upgrading to a web browser that
			      <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
			    </p>
  			</video>
			<?php
			}
			?>
		</div>
	</div>

	<div class="wrap cf wrap-width">

		<div class="full_wrap cf">
			<div class="wrap_high">
	  			<div class="wrap_normal">

				<div class="post_description">

				<div class="movie_container">
					<div id="movie_title"><a href="<?php echo get_permalink(); ?>" class="movie_title"><?php the_title( '', '' ); ?></a></div>
					<div class="movie_image">
						<img src="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id( ), 'large' )[0]; ?>" alt="<?php the_title( '', '' ); ?>" title="<?php the_title( '', '' ); ?>" class="image" height="323" width="220">
					</div>
					<div class="movie_information">
						<div class="movie_description">
							<?php
										$categories        = wp_get_post_terms( get_the_ID(), 'genre', array("fields" => "all") );
										$categories_count  = count($categories);
										$categories_val    = 1;
										$categories_string = '';
										foreach ($categories as $value) {
											if ( $categories_val == $categories_count ) {
												$categories_string .= '<a href="'.home_url().'/genre/'.$value->slug.'/">'.$value->name.'</a>';
											} else {
												$categories_string .= '<a href="'.home_url().'/genre/'.$value->slug.'/">'.$value->name.'</a>, ';
											}
											$categories_val++;
										}
										$directed_by = get_post_meta(get_the_ID(), 'directed_by', true);
										$directed = explode(", ", $directed_by);
										foreach($directed as &$rejisori) {
											$rejisori = "<a href='".home_url()."/?s=".$rejisori."'>".str_replace(".", "", $rejisori)."</a>";
										}
										$rejisorebi = implode(", ", $directed);
										
										$casts_by = get_post_meta(get_the_ID(), 'actors', true);
										$casts_explode = explode(", ", $casts_by);
										foreach($casts_explode as &$casts_to) {
											$casts_to = "<a href='".home_url()."/?s=".$casts_to."'>".str_replace(".", "", $casts_to)."</a>";
										}
										$casts = implode(", ", $casts_explode);
							?>
							<?php if ( get_post_meta( get_the_ID(), 'movies_year', true ) != '' ) { ?>
							<div class="movie_desc_row"><div class="movie_desc_title"><?php echo __( 'Year:', 'goldmovies' ); ?></div><div class="movie_desc_content"><a href="<?php echo home_url(); ?>/?s=<?php echo get_post_meta(get_the_ID(), 'movies_year', true); ?>"><?php echo get_post_meta(get_the_ID(), 'movies_year', true); ?></a></div></div>
							<?php } ?>
							<?php if ( $categories_string != '' ) { ?>
							<div class="movie_desc_row">
								<div class="movie_desc_title"><?php echo __( 'Genre:', 'goldmovies' ); ?></div>
								<div class="movie_desc_content">
									<?php
										echo $categories_string;
									?>
								</div>
							</div>
							<?php } ?>
							<?php if ( get_post_meta( get_the_ID(), 'directed_by', true ) != '' ) { ?>
							<div class="movie_desc_row"><div class="movie_desc_title"><?php echo __( 'Producer:', 'goldmovies' ); ?></div><div class="movie_desc_content"><?php echo $rejisorebi; ?></div></div>
							<?php } ?>
							<?php if ( get_post_meta( get_the_ID(), 'actors', true ) != '' ) { ?>
							<div class="movie_desc_row">
								<div class="movie_desc_title"><?php echo __( 'Actors:', 'goldmovies' ); ?></div>
								<span class="movie_desc_content float-none desc-actors">
									<?php echo $casts; ?>
								</span>
							</div>
							<?php } ?>
							<div class="movie_desc_row desc-info"><div class="movie_desc_title"><?php echo __( 'Description:', 'goldmovies' ); ?></div> <div class="movie_desc_content float-none description-of-movie"><?php echo mb_substr(strip_tags($post->post_content), 0, 250); ?></div></div>
							
							<div class="imdb-badge arrow"><span><?php echo get_post_meta(get_the_ID(), 'imdb_rating', true); ?></span></div>

						</div>
					</div>
				</div>


				<aside class="shares social">

				<div class="share-buttons v2" style="float: left;"><div class="share-container">
				  <div class="primary-shares nowhatsapp" style="margin-left: -5px;">
				    <a target="_blank" href="http://www.facebook.com/share.php?u=<?php echo get_permalink(); ?>" class="social-share facebook" style="text-decoration: none;">
					  <svg style="fill: #fff; width: 23px; position: relative; top: 9px; margin: 0px 7px 0px 5px;" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="28px" height="28px" viewBox="0 0 28 28" enable-background="new 0 0 28 28" xml:space="preserve">
				                    <path d="M27.825,4.783c0-2.427-2.182-4.608-4.608-4.608H4.783c-2.422,0-4.608,2.182-4.608,4.608v18.434
				                        c0,2.427,2.181,4.608,4.608,4.608H14V17.379h-3.379v-4.608H14v-1.795c0-3.089,2.335-5.885,5.192-5.885h3.718v4.608h-3.726
				                        c-0.408,0-0.884,0.492-0.884,1.236v1.836h4.609v4.608h-4.609v10.446h4.916c2.422,0,4.608-2.188,4.608-4.608V4.783z"></path>
				      </svg>
				      <span class="expanded-text">Facebook</span>
				    </a>
				    <a target="_blank" href="http://twitter.com/share?url=<?php echo get_permalink(); ?>&amp;text=Now You See Me" class="social-share twitter" style="text-decoration: none;">
					  <svg style="fill: #fff; width: 23px; position: relative; top: 9px; margin: 0px 7px 0px 5px;" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="28px" height="28px" viewBox="0 0 28 28" enable-background="new 0 0 28 28" xml:space="preserve">
				                <path d="M24.253,8.756C24.689,17.08,18.297,24.182,9.97,24.62c-3.122,0.162-6.219-0.646-8.861-2.32
				                    c2.703,0.179,5.376-0.648,7.508-2.321c-2.072-0.247-3.818-1.661-4.489-3.638c0.801,0.128,1.62,0.076,2.399-0.155
				                    C4.045,15.72,2.215,13.6,2.115,11.077c0.688,0.275,1.426,0.407,2.168,0.386c-2.135-1.65-2.729-4.621-1.394-6.965
				                    C5.575,7.816,9.54,9.84,13.803,10.071c-0.842-2.739,0.694-5.64,3.434-6.482c2.018-0.623,4.212,0.044,5.546,1.683
				                    c1.186-0.213,2.318-0.662,3.329-1.317c-0.385,1.256-1.247,2.312-2.399,2.942c1.048-0.106,2.069-0.394,3.019-0.851
				                    C26.275,7.229,25.39,8.196,24.253,8.756z"></path>
				      </svg>
				      <span class="expanded-text">Twitter</span>
				    </a>
				    <a target="_blank" href="https://plus.google.com/share?url=<?php echo get_permalink(); ?>" class="social-share google" style="text-decoration: none;">
				        <svg style="fill: #fff; width: 23px; position: relative; top: 9px; margin: 0px 5px;" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="28px" height="28px" viewBox="0 0 28 28" enable-background="new 0 0 28 28" xml:space="preserve">
				                                    <g>
				                                        <g>
				                                            <path d="M14.703,15.854l-1.219-0.948c-0.372-0.308-0.88-0.715-0.88-1.459c0-0.748,0.508-1.223,0.95-1.663
				                                                c1.42-1.119,2.839-2.309,2.839-4.817c0-2.58-1.621-3.937-2.399-4.581h2.097l2.202-1.383h-6.67c-1.83,0-4.467,0.433-6.398,2.027
				                                                C3.768,4.287,3.059,6.018,3.059,7.576c0,2.634,2.022,5.328,5.604,5.328c0.339,0,0.71-0.033,1.083-0.068
				                                                c-0.167,0.408-0.336,0.748-0.336,1.324c0,1.04,0.551,1.685,1.011,2.297c-1.524,0.104-4.37,0.273-6.467,1.562
				                                                c-1.998,1.188-2.605,2.916-2.605,4.137c0,2.512,2.358,4.84,7.289,4.84c5.822,0,8.904-3.223,8.904-6.41
				                                                c0.008-2.327-1.359-3.489-2.829-4.731H14.703z M10.269,11.951c-2.912,0-4.231-3.765-4.231-6.037c0-0.884,0.168-1.797,0.744-2.511
				                                                c0.543-0.679,1.489-1.12,2.372-1.12c2.807,0,4.256,3.798,4.256,6.242c0,0.612-0.067,1.694-0.845,2.478
				                                                c-0.537,0.55-1.438,0.948-2.295,0.951V11.951z M10.302,25.609c-3.621,0-5.957-1.732-5.957-4.142c0-2.408,2.165-3.223,2.911-3.492
				                                                c1.421-0.479,3.25-0.545,3.555-0.545c0.338,0,0.52,0,0.766,0.034c2.574,1.838,3.706,2.757,3.706,4.479
				                                                c-0.002,2.073-1.736,3.665-4.982,3.649L10.302,25.609z"></path>
				                                            <polygon points="23.254,11.89 23.254,8.521 21.569,8.521 21.569,11.89 18.202,11.89 18.202,13.604 21.569,13.604 21.569,17.004
				                                                23.254,17.004 23.254,13.604 26.653,13.604 26.653,11.89"></polygon>
				                                        </g>
				                                    </g>
				        </svg>
				      <span class="expanded-text">Google</span>
				    </a>
				  </div>
				  
				</div></div>

				</aside>
				<div style="float: right; display: inline-block;">
				    <span class="" style="color: #333; font-size: 27px; font-weight: bold; max-width: 90px; overflow: hidden; text-align: center; display: block;"><?php echo getPostViews(get_the_ID()); ?></span>
				    <div class="caption" style="font-weight: bold; padding-top: 2px;"><?php echo __( 'Views', 'goldmovies' ); ?></div>
				</div>

				</div>

				</div>
				
		<div id="advert"><?php if(ot_get_option('post_advert') != '') { echo ot_get_option('post_advert'); } ?></div>

		<div style="background: #fff; border: 1px solid #E7E7E7; border-radius: 3px;">
		    <ul class="comments_switch">
		        <li><a class="comments-switch active" data-comments="#tab_fb_comments" href="javascript:void(0);">
					<svg style="fill: #fff; width: 23px; position: relative; top: 5px; margin: 0px 10px 0px 0px;" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="28px" height="28px" viewBox="0 0 28 28" enable-background="new 0 0 28 28" xml:space="preserve">
		                    <path d="M27.825,4.783c0-2.427-2.182-4.608-4.608-4.608H4.783c-2.422,0-4.608,2.182-4.608,4.608v18.434
		                        c0,2.427,2.181,4.608,4.608,4.608H14V17.379h-3.379v-4.608H14v-1.795c0-3.089,2.335-5.885,5.192-5.885h3.718v4.608h-3.726
		                        c-0.408,0-0.884,0.492-0.884,1.236v1.836h4.609v4.608h-4.609v10.446h4.916c2.422,0,4.608-2.188,4.608-4.608V4.783z"></path>
		      		</svg>
					<span style="position: relative; top: -3px;">Comments</span>
				</a></li>
		    </ul>
			<div class="comments_tab">
				<section id="tab_fb_comments" class="comments_section" >
					<div class="fb-comments" data-href="<?php echo get_permalink(); ?>" data-numposts="20" data-width="570"></div>
				</section>
		    </div>
		</div>

	</div>

<div class="sidebar_main" style="float: left;"><div class="post_sidebar">

			<div class="side_box" style="margin-bottom: 20px;width: 144px;float: left;margin-right: 10px;">
				<span class="title" style="width: 144px;">MOVIE GENRES</span>
				<span class="content" style="margin-top: 5px; margin-bottom: 0px;width: 144px;">
					<div style="margin-bottom: -1px;">
						<ul class="categories">
							<?php 
							$args=array(
							  'taxonomy' => 'genre',
							  'show_option_all' => '1'
							);
							$output = 'objects';
							$genres = get_categories($args,$output);
							if  ($genres) {
							  foreach ($genres  as $genre ) {
							    echo "<li><a href='".home_url()."/genre/".$genre->slug."'>".$genre->name."</a></li>";
							  }
							}
						    ?>
						</ul>
					</div>
				</span>
			</div>

			<div class="side_box" style="margin-bottom: 20px;width: 144px;float: left;">
				<span class="title" style="width: 144px;">RANDOM MOVIES</span>
				<span class="content" style="margin-top: 5px; margin-bottom: 0px;width: auto;">
					<?php
					$randoms = new WP_Query( array ( 'orderby' => 'rand', 'posts_per_page' => '3', 'post_type' => 'movies' ) );
					// output the random post
					while ( $randoms->have_posts() ) : $randoms->the_post();
					?>
					<div class="pop_img">
						<a href="<?php the_permalink(); ?>">
							<img src="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id( ), 'large' )[0]; ?>" alt="<?php the_title() ?>" title="<?php the_title() ?>" style="width: 130px; height: 185px;">
						</a>
					</div>
					<?php
					endwhile;

					// Reset Post Data
					wp_reset_postdata();
					?>
				</span>
			</div>

			<?php echo get_template_part( 'sidebar' ); ?>

</div>
</div>
</div></div>

<?php get_footer(); ?>