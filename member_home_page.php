<?php
/* Template Name: Member Home Page */

get_header();

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );


?>

<div id="main-content">


	<?php if ( ! $is_page_builder_used ) : ?>

		<div class="container">
			<div id="content-area" class="clearfix">
				<div id="left-area">

				<?php endif; ?>

				<?php while ( have_posts() ) : the_post(); ?>

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

						endif; ?>

						<div class="entry-content">


							<!-- pad content -->
							<div style='padding-left:10%; padding-right:10%; padding-top:2%;'>

								<!-- get featured image -->
								<div style='background-image:url("http://localhost:8888/divi_child_pmbus/wp-content/uploads/2018/05/PMBus-Banner.jpeg;"); background-size:100%;'>
									<?php echo get_the_post_thumbnail(); ?>
								</div>

							</div>


							<?php
							the_content();

							//build table based on the input of custom field 'Company' in dashboard
							$company_custom_field = get_post_meta($post->ID, 'Company', true);

							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php

//-----------Check current custom company field value ******* V
//<?php echo get_post_meta($post->ID, 'Company', true);

?>
<br>
<?php

global $wpdb;
$gci_company_id_query = $wpdb->get_results(
	"
	SELECT DISTINCT term_id FROM wp_terms WHERE slug = '$company_custom_field'
	");

	$gci_company_id = $gci_company_id_query[0]->term_id;
	?>
	<center><h3>ID of Company page you are on: <?= $gci_company_id; ?></h3></center><br><?php

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
		?>


		<?php
		for ($pa = 0; $pa < count($parent); $pa++) {?>
			<div style='padding-left:10%; padding-right:10%;'>
				<p>
					<h1><?= $parent[$pa]->name ?></h1>
					<hr width="30%" align="left">
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
					?>

					<?php
					for ($i = 0; $i < count($gci_company_products_query); $i++) {
						?>

						<?php
						echo "<h2>" . $gci_company_products_query[$i]->name . "</h2>";
						echo "<br>";
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
							);?>
							<pre>
								<?php //print_r($pq); ?>
							</pre>
							<table class="table" style="margin-left: -20%;">
								<tbody>
									<?php
									for ($k = 0; $k < count($pq); $k++) {
										$product_loop_id = $pq[$k]->object_id;
										?><tr><?php
										echo do_shortcode("[products ids='$product_loop_id']");
										?></tr><?php
									}
									?>
								</tbody>
							</table>
							<br>
						<?php } ?>
					</div>
					<?php
					continue;
				}
				?></div>
			</div>

			<?php
			//echo do_shortcode("[products category='$company']");
			//echo do_shortcode("[products category='$company_custom_field']");
			?>
		</div>

		<?php
		if ( ! $is_page_builder_used )
		wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
		?>
	</div> <!-- .entry-content -->




	<?php
	if ( ! $is_page_builder_used && comments_open() && 'on' === et_get_option( 'divi_show_pagescomments', 'false' ) ) comments_template( '', true );
	?>

</article> <!-- .et_pb_post -->

<?php endwhile; ?>

<?php if ( ! $is_page_builder_used ) : ?>

</div> <!-- #left-area -->

<?php get_sidebar(); ?>
</div> <!-- #content-area -->
</div> <!-- .container -->

<?php endif; ?>

</div> <!-- #main-content -->

<?php

get_footer();
