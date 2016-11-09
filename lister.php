<?php 
/*Plugin Name:	Lister
Description:	Shortcode to list any post type on a page.
Version:		0.1
Author:			Josiah Altschuler
License:		GPL2
License URI:	https://www.gnu.org/licenses/gpl-2.0.html
*/

function lister_list_posts( $attributes ) {
	
	ob_start();

	extract(
		shortcode_atts(
			array(
				'type' => 'post',
				'order' => 'ASC',
				'orderby' => 'title',
				'posts' => -1,
				'category' => '',
			),
			$attributes
		)
	);

	$options = array(
		'post_type' => $type,
		'order' => $order,
		'orderby' => $orderby,
		'posts_per_page' => $posts,
		'category_name' => $category,
	);

	$query = new WP_Query( $options );

	if ( $query->have_posts() ) { ?>
		<?php while ( $query->have_posts() ) : $query->the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
				</header><!-- .entry-header -->

				<?php the_excerpt(); ?>

				<?php the_post_thumbnail(); ?>

				<div class="entry-content">
					<?php
						/* translators: %s: Name of current post */
						the_content( sprintf(
							__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentysixteen' ),
							get_the_title()
						) );

						wp_link_pages( array(
							'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentysixteen' ) . '</span>',
							'after'       => '</div>',
							'link_before' => '<span>',
							'link_after'  => '</span>',
							'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>%',
							'separator'   => '<span class="screen-reader-text">, </span>',
						) );
					?>
				</div><!-- .entry-content -->

				<footer class="entry-footer">
					<?php the_meta(); ?>
					<?php
						edit_post_link(
							sprintf(
								/* translators: %s: Name of current post */
								__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'twentysixteen' ),
								get_the_title()
							),
							'<span class="edit-link">',
							'</span>'
						);
					?>
				</footer><!-- .entry-footer -->
			</article><!-- #post-## -->

		<?php endwhile;

		wp_reset_postdata();
	
	}

	$myvariable = ob_get_clean();
	return $myvariable;
}
add_shortcode( 'lister', 'lister_list_posts' );

