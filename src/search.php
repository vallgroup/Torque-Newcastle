
<?php TQ::get_template_parts( array( 'parts/shared/html-header', 'parts/shared/header' ) ); ?>

<main class="tq-search-results">
<?php
if ( have_posts() ) {
	?>
	<header class="page-header alignwide">
		<h1 class="page-title">
			<?php
			printf(
				/* translators: %s: Search term. */
				esc_html__( 'Results for "%s"', 'newcastlechild' ),
				'<span class="page-description search-term">' . esc_html( get_search_query() ) . '</span>'
			);
			?>
		</h1>
	</header><!-- .page-header -->

	<div class="search-result-count default-max-width">
		<?php
		printf(
			/* translators: %d: The number of search results. */
			_n(
				'We found <b>%d result</b> for your search.',
				'We found <b>%d results</b> for your search.',
				(int) $wp_query->found_posts,
				'newcastlechild'
			),
			(int) $wp_query->found_posts
		);
		?>
	</div><!-- .search-result-count -->
	<?php
	// Start the Loop.
	while ( have_posts() ) {
		the_post();
		?>
		<article>
			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<div class="excerpt">
				<?php
				$post_excerpt = apply_filters('the_excerpt', $post->post_excerpt);
				echo $post_excerpt;
				//the_excerpt();
				?>
			</div>
			<a class="read-more" href="<?php the_permalink(); ?>">Read More</a>
		</article>
		<?php
	} // End the loop.

	paginateSearch();
	
	// If no content, include the "No posts found" template.
} else {
	?>
	<p>Sorry, but nothing matched your search terms. Please try again with some different keywords</p>
	<?php
	get_search_form();
}

?>

</main>

<?php TQ::get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer' ) ); ?>
