<div class="list-wrapper">
    <h2>Products</h2>
      <div class='card-ticket'>
          <!-- START products -->
          <div class="card" style="width: 18rem;">
            <img width="200" src="http://localhost/~hetu/Sandbox/Eclipse_progs/_AppForShow/images/{product_path}" alt="{product_name} image" class="card-img-top" alt="...">
            <div class="card-body">
              <h5 class="card-title">{product_name}</h5>
              <p class="card-text">Cost: &dollar;{product_price}, number in stock {product_stock}. Weight: {product_weight}Kg.</p>
              <a href="MVC_Final/wishlist/add/{product_path}" title="Add {product_name} to your wishlist" class="btn btn-primary">Add to wishlist.</a>
              <a href="MVC_Final/basket/add-product/{product_path}" method="post">Add to basket</a>
            </div>

          <!-- END products -->
          </div>

          <!-- START filter_attribute_types -->
          <h3>{filter_attr_name} {filter_attr_reference}</h3>
          <div>
          <!-- START attribute_values_{filter_attr_reference} -->
          <p><a href="{attribute_URL_extra}">{attribute_value}</a></p>
          <!-- END attribute_values_{filter_attr_reference} -->
          </div>
          <!-- END filter_attribute_types -->
      </div>
</div><!-- End of the list-wrapper -->