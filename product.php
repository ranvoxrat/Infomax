<?php
  $page_title = 'All Product';
  require_once('includes/load.php');
  // Checking what level user has permission to view this page
  page_require_level(2);
  $products = join_product_table();
?>
<?php include_once('layouts/header.php'); ?>
<style>
    .status-success {
        background-color: #12B01E; /* Greenish background for success */
        color:white; /* Dark green text color */
    }

    .status-danger {
        background-color: #FEA200; /* Reddish background for danger */
        color: white; /* Dark red text color */
    }

    .status-sold {
        background-color: #B02812; /* Light reddish background for sold */
        color: white; /* Dark red text color */
    }
</style>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <!-- <div class="panel-heading clearfix">
                <div class="pull-right">
                    <a href="add_product.php" class="btn btn-primary">Add New</a>
                </div>
            </div> -->
            <div class="panel-body">
                <table id="product-table" class="table">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th>Product-Code</th>
                            <th>Photo</th>
                            <th>Product Title</th>
                            <th class="text-center" style="width: 10%;">Categories</th>
                            <th class="text-center" style="width: 10%;">In-Stock</th>
                            <th class="text-center" style="width: 10%;">Buying Price</th>
                            <th class="text-center" style="width: 10%;">Selling Price</th>
                            <th class="text-center" style="width: 10%;">Product Added</th>
                            <th>Status</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Your PHP code to populate the table rows goes here -->
                        

<?php foreach ($products as $product):?>
            <tr>
              <td class="text-center"><?php echo count_id();?></td>
              <td><?php echo remove_junk($product['product_code']); ?></td>
              <td>
                <?php if($product['media_id'] === '0'): ?>
                  <img class="img-avatar img-circle" src="uploads/products/no_image.png" alt="">
                <?php else: ?>
                  <img class="img-avatar img-circle" src="uploads/products/<?php echo $product['image']; ?>" alt="">
                <?php endif; ?>
              </td>
              <td><?php echo remove_junk($product['name']); ?></td>
              <td class="text-center"><?php echo remove_junk($product['categorie']); ?></td>
              <td class="text-center"><?php echo remove_junk($product['quantity']); ?></td>
              <td class="text-center"><?php echo remove_junk($product['buy_price']); ?></td>
              <td class="text-center"><?php echo remove_junk($product['sale_price']); ?></td>
              <td class="text-center"><?php echo read_date($product['date']); ?></td>
              <td>
                <!-- Safety Stock, Critical Level, Sold -->
                <?php
                    $statusClass = '';
                    $statusText = '';

                    if ($product['quantity'] >= 20) {
                        $statusText = 'Safety Stock';
                        echo '<span class="badge status-success">'.$statusText.'</span>';
                    } else if ($product['quantity'] >=1 || $product['quantity'] <=20) {
                        $statusText = 'Critical';
                        echo '<span class="badge status-danger">'.$statusText.'</span>';
                    } else {
                        $statusText = 'Out of Stock';
                        echo '<span class="badge status-sold">'.$statusText.'</span>';
                    }
                ?>
              </td>
              <td class="text-center">
                <div class="btn-group">
                  <a href="edit_product.php?id=<?php echo (int)$product['id'];?>" class="btn btn-info btn-xs" title="Edit" data-toggle="tooltip">
                    <span class="glyphicon glyphicon-edit"></span>
                  </a>
                  <a href="delete_product.php?id=<?php echo (int)$product['id'];?>" class="btn btn-danger btn-xs" title="Delete" data-toggle="tooltip">
                    <span class="glyphicon glyphicon-trash"></span>
                  </a>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

  <!-- Initialize DataTables -->
  <script>
      $(document).ready(function() {
          $('#product-table').DataTable({
              responsive: true,
            search:true // Enable responsiveness
              // Add any other DataTables options you need here
          });
      });
  </script>
<?php include_once('layouts/footer.php'); ?>
