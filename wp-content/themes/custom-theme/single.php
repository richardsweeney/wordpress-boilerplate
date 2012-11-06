<?php get_header(); ?>

<div class="container-fluid wider-page" id="page">
<?php
  while(have_posts()): the_post();
    $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
    rps_print_test_breadcrumbs();
  ?>
  <div class="row-fluid">
    <div class="span3">
      <?php rps_print_main_navigation(true); ?>
    </div>
    <div class="span9">
      <?php if (has_post_thumbnail()): ?>
      <div class="image-border-container">
        <img src="<?php echo $image[0]; ?>" alt="<?php esc_attr(the_title()); ?>" />
      </div>
      <?php endif; ?>
      <section>
        <h1 <?php if (has_post_thumbnail()) { echo 'class="down"'; } ?>><?php the_title(); ?></h1>
        <?php the_content(); ?>
      </section>
    </div>
  </div>
<?php endwhile; ?>
<?php get_footer(); ?>
