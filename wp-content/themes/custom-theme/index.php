<?php get_header(); ?>

<div class="container-fluid wider-page" id="page">
<?php
  while(have_posts()): the_post();
    $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
    rps_print_secondary_navigation();
    rps_print_breadcrumbs();
  ?>
  <?php if (has_post_thumbnail()): ?>
  <div class="row-fluid">
    <div class="span12 image-border-container">
      <img src="<?php echo $image[0]; ?>" alt="<?php esc_attr(the_title()); ?>" />
    </div>
  </div>
  <?php endif; ?>
  <div class="row-fluid introtext">
    <div class="span12">
      <h1 class="down"><?php the_title(); ?></h1>
      <div class="lead">
        <?php the_content(); ?>
      </div>
    </div>
  </div>

<?php endwhile; ?>

<?php get_footer(); ?>
