<?php
/*

*/

get_header();
$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );
?>

<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
				<?php
				while ( have_posts() ) : the_post();
					?>

					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<?php if ( ! $is_page_builder_used ) : ?>

						<h1 class="entry-title main_title"><?php the_title(); ?></h1>
						<?php
						$thumb = '';

						$width = (int) apply_filters( 'et_pb_index_blog_image_width', 1080 );

						$height = (int) apply_filters( 'et_pb_index_blog_image_height', 675 );
						$classtext = 'et_featured_image';
						$titletext = get_the_title();
						$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
						$thumb = $thumbnail["thumb"];

						if ( 'on' === et_get_option( 'divi_page_thumbnails', 'false' ) && '' !== $thumb )
							print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height );
						endif;
						?>

						<div class="entry-content">
							<!-- pad content -->
							<div style='padding-top:2%;'>
							<?php
								screen_size();
							?>


								<?php
								function GetImageUrlsByProductId( $productId){

								$product = new WC_product($productId);
								$attachmentIds = $product->get_gallery_attachment_ids();
								$imgUrls = array();
								foreach( $attachmentIds as $attachmentId )
								{
									$imgUrls[] = wp_get_attachment_url( $attachmentId );
								}

								return $imgUrls;
								}

								GetImageUrlsByProductId( 2330 );
								?>
								<!-- get featured image -->
								<div>
									<div style='background-size:100%;'>
										<?php echo get_the_post_thumbnail(); ?>
									</div>
								</div>

								<?php
								the_content();
								//build table based on the input of custom field 'Company' in dashboard
								$company_custom_field = get_post_meta($post->ID, 'Company', true);
								?>
							</div>
							<?php

							global $wpdb;
							//-----------Check current custom company field value ******* V
							//<?php echo get_post_meta($post->ID, 'Company', true);

							$gci_company_id_query = $wpdb->get_results(
							"
							SELECT DISTINCT term_id FROM wp_terms WHERE slug = '$company_custom_field'
							");

							$gci_company_id = $gci_company_id_query[0]->term_id;
							?>
							<br>
							<div style="padding-left:10%; padding-bottom:5%; padding-right:10%">
								<?php $gci_featured_products = $wpdb->get_results("
									SELECT * FROM
											(
												SELECT company.object_id, name, slug, parent, terms.term_id FROM
												(SELECT * FROM wp_term_relationships r WHERE r.term_taxonomy_id =".$gci_company_id.") AS company,
												(SELECT * FROM wp_term_relationships r1) AS category,
												(SELECT * FROM wp_terms) AS terms,
												(SELECT * FROM wp_term_taxonomy) AS taxonomy
												WHERE company.object_id = category.object_id AND terms.term_id = category.term_taxonomy_id
												AND terms.slug NOT LIKE 'http%' AND terms.name != 'simple'
												AND terms.slug NOT LIKE 'Company' AND terms.slug NOT LIKE 'company-%'
												AND terms.name = 'featured'
												AND taxonomy.taxonomy NOT LIKE 'pa_company' AND parent != 0
											) as categories
                                            WHERE parent=667
                                            GROUP BY object_id
									"); ?>
									<?php
										if($gci_featured_products != null){ ?>
											<h1>Featured Products</h1><br> <?php
											echo do_shortcode('[products limit="4" category="'.$company_custom_field.'" visibility="featured"]');
										}?>
							</div>
							<?php


							$parent = $wpdb->get_results(
								"
									SELECT * FROM
									(
										SELECT company.object_id, name, slug, parent, terms.term_id FROM
										(SELECT * FROM wp_term_relationships r WHERE r.term_taxonomy_id = " . $gci_company_id . ") AS company,
										(SELECT * FROM wp_term_relationships r1) AS category,
										(SELECT * FROM wp_terms) AS terms,
										(SELECT * FROM wp_term_taxonomy WHERE taxonomy != 'pa_product_link') AS taxonomy
										WHERE company.object_id = category.object_id AND terms.term_id = category.term_taxonomy_id
										AND terms.slug NOT LIKE 'http%' AND terms.name != 'simple'
										AND terms.slug NOT LIKE 'Company' AND terms.slug NOT LIKE 'company-%'
										AND terms.name != 'featured' AND taxonomy.term_taxonomy_id = terms.term_id
										AND taxonomy.taxonomy NOT LIKE 'pa_company'
									) as categories
									WHERE parent = 0 AND slug != 'generic'
									GROUP BY name
								"
							);

							for ($pa = 0; $pa < count($parent); $pa++) {
								?>
								<div style='padding-left:10%; padding-right:10%;'>
									<p>
										<h1 style='color:#5C2961'><?= $parent[$pa]->name ?></h1>
										<hr style="color:#5C2961" width="30%" align="left">
									</p>

									<?php
									$parent_loop_id = $parent[$pa]->term_id;
									$gci_company_products_query = $wpdb->get_results(
									"
										SELECT * FROM
										(
											SELECT company.object_id, name, slug, parent, terms.term_id FROM
											(SELECT * FROM wp_term_relationships r WHERE r.term_taxonomy_id = " . $gci_company_id . ") AS company,
											(SELECT * FROM wp_term_relationships r1) AS category,
											(SELECT * FROM wp_terms) AS terms,
											(SELECT * FROM wp_term_taxonomy) AS taxonomy
											WHERE company.object_id = category.object_id AND terms.term_id = category.term_taxonomy_id
											AND terms.slug NOT LIKE 'http%' AND terms.name != 'simple'
											AND terms.slug NOT LIKE 'Company' AND terms.slug NOT LIKE 'company-%'
											AND terms.name != 'featured' AND taxonomy.term_taxonomy_id = terms.term_id
											AND taxonomy.taxonomy NOT LIKE 'pa_company'
										) as categories
										WHERE parent = ".$parent_loop_id."
										GROUP BY name
									"
									);

									for ($i = 0; $i < count($gci_company_products_query); $i++) {
										?>

										<h2 style='color:#013087'><?= $gci_company_products_query[$i]->name ?></h2>

										<?php
										$cat_id = $gci_company_products_query[$i]->term_id;
										$pq = $wpdb->get_results(
										"
											SELECT * FROM
											(
												SELECT company.object_id, name, slug, parent, terms.term_id FROM
												(SELECT * FROM wp_term_relationships r WHERE r.term_taxonomy_id = " . $gci_company_id . ") AS company,
												(SELECT * FROM wp_term_relationships r1) AS category,
												(SELECT * FROM wp_terms) AS terms,
												(SELECT * FROM wp_term_taxonomy) AS taxonomy
												WHERE company.object_id = category.object_id AND terms.term_id = category.term_taxonomy_id
												AND terms.slug NOT LIKE 'http%' AND terms.name != 'simple'
												AND terms.slug NOT LIKE 'Company' AND terms.slug NOT LIKE 'company-%'
												AND terms.name != 'featured' AND taxonomy.term_taxonomy_id = terms.term_id
												AND taxonomy.taxonomy NOT LIKE 'pa_company' AND parent != 0
											) as categories
											WHERE term_id = " . $cat_id . "
										"
										);

										//Child Category Loop Run list of product IDs variable
										$pid = '';
										//Running through array of products in child category and adding IDs $pid seperated by a comma
										for ($k = 0; $k < count($pq); $k++) {
											$product_loop_id = $pq[$k]->object_id;
											$pid = $pid . $product_loop_id . ', ';
										}
										//Trimming the last comma in the $pid variable
										$p2id = rtrim($pid,", ");
										//Running shop loop based on IDs variable and retrieving products in child category
										echo do_shortcode("[products ids='$p2id']");
										?>

										<br>

										<?php
									}
									?>

								</div>

								<?php
								continue;
							}
						?>
					</article> <!-- .et_pb_post -->
					<?php
				endwhile;
				?>
			</div> <!-- #left-area -->
			<?php
			get_sidebar();
			?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->

<?php
get_footer();
