<?php get_header(); ?>
<?php
  while(have_posts()): the_post();
  $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'medium');
  if (has_post_thumbnail()):
  ?>
    <img src="<?php echo $image[0]; ?>" alt="<?php esc_attr(the_title()); ?>" />
  <?php endif; ?>
  <section>
    <header>
      <h1><?php the_title(); ?></h1>
    </header>
    <div class="content-container">
      <?php the_content(); ?>
    </div>
  </section>
<?php endwhile; ?>

<?php get_footer(); ?>
