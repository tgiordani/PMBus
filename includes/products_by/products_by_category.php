<?php
function products_by_category(){

$gci_category_contents = '';

ob_start();
?>

<div>
  <br><?php

  //print all categories and cubcategories
  $args = array(
    'taxonomy' => 'product_cat',
    'hide_empty' => false,
    'parent'   => 0
  );

  $product_cat = get_terms( $args );

  foreach ($product_cat as $parent_product_cat)
  {
    if ($parent_product_cat->name != 'Company' && $parent_product_cat->name != 'Uncategorized'){
    ?>

    <ul>
      <style>
        li{
          list-style:none;
          background-image:none;
          background-repeat:none;
          background-position:0;
        }
      </style>
      <li><h2><a href='<?= get_term_link($parent_product_cat->term_id) ?>'><?= $parent_product_cat->name ?></a></h2>
        <hr align='left' width='50%'><br>

        <ul>

          <?php
          $child_args = array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'parent'   => $parent_product_cat->term_id
          );

          $child_product_cats = get_terms( $child_args );
          foreach ($child_product_cats as $child_product_cat)
          { ?>
            <li>
              <h3><a href='<?= get_term_link($child_product_cat->term_id) ?>'><?= $child_product_cat->name?></a></h3>
            </li>
            <div>
              <?php
              echo do_shortcode("[products category='$child_product_cat->term_id']");
              ?><br>



            </div>
            <?php
          }
          ?>

        </ul>
      </li>
    </ul>

    <?php
    }
  } ?>
</div>
<?php
$gci_category_contents = ob_get_clean();
return $gci_category_contents;

}

function products_by_company(){

  $gci_company_contents = '';

  ob_start();

?>
<div style='padding-left:10%;'>
  <br><?php

  //print all categories and cubcategories
  $args = array(
    'taxonomy' => 'product_cat',
    'hide_empty' => false,
    'parent'   => 0
  );

  $product_cat = get_terms( $args );

  foreach ($product_cat as $parent_product_cat)
  {
    if ($parent_product_cat->name == 'Company' && $parent_product_cat->name != 'Uncategorized'){
    ?>

    <ul>
      <style>
        li{
          list-style:none;
          background-image:none;
          background-repeat:none;
          background-position:0;
        }
      </style>
      <li><h2><a href='<?= get_term_link($parent_product_cat->term_id) ?>'><?= $parent_product_cat->name ?></a></h2>
        <hr align='left' width='50%'><br>
        <ul>

          <?php
          $child_args = array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'parent'   => $parent_product_cat->term_id
          );

          $child_product_cats = get_terms( $child_args );
          foreach ($child_product_cats as $child_product_cat)
          { ?>
            <li style='padding-left: 2%;'>
              <h3><a href='<?= get_term_link($child_product_cat->term_id) ?>'><?= $child_product_cat->name?></a></h3>
            </li>
            <div style='margin-left: -8%;'>
              <?php
              echo do_shortcode("[products category='$child_product_cat->term_id']");
              ?><br>



            </div>
            <?php
          }
          ?>

        </ul>
      </li>
    </ul>

    <?php
    }
  } ?>
</div>
<?php
$gci_company_contents = ob_get_clean();
return $gci_company_contents;
}


add_shortcode('gci_products_by_category', 'products_by_category');
add_shortcode('gci_products_by_company', 'products_by_company');
