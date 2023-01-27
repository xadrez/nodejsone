<div class="card" style="width: 18rem;">
  <img src="http://localhost/~hetu/Sandbox/Eclipse_progs/_AppForShow/images/{product_image}" alt="{product_name} image" class="card-img-top" alt="...">
  <div class="card-body">
    <h5 class="card-title">{product_name}</h5>
    <p class="card-text">Cost: &dollar;{product_price}, number in stock {product_stock}. Weight: {product_weight}Kg.</p>
    <a href="wishlist/add/{product_path}" title="Add {product_name} to your wishlist" class="btn btn-primary">Add to wishlist.</a>
    <a href="basket/add-product/{product_path}" method="post">Add to basket</a>
    <h2>Related products</h2>
<!-- START relatedproducts -->
<div class="floatingbox">
<a href="products/view/{product_path}">{product_name}</a>
<!-- END relatedproducts -->

  </div>

</div>
</div>