<?php

include('header.php');
include_once("includes/config.php");

?>

<section class="content">
  <!-- Small boxes (Stat box) -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <!-- Sales Amount Box -->
      <div class="small-box bg-green">
        <div class="inner">
          <h3>
            <?php 
              // Query to sum the total_amount column from the sales_invoices table where payment_status is 'paid'
              $result = mysqli_query($mysqli, 'SELECT SUM(total_amount) AS value_sum FROM sales_invoices WHERE payment_status = "paid"'); 
              $row = mysqli_fetch_assoc($result); 
              // Check if the value is null and set it to 0 if so
              $sum = $row['value_sum'] ? $row['value_sum'] : 0;
              echo number_format($sum, 2); // Format the sum to 2 decimal places
            ?>
          </h3>
          <p>Sales Amount</p>
        </div>
        <div class="icon">
          <i class="ion ion-social-usd"></i>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-xs-6">
      <!-- Total Invoices Box -->
      <div class="small-box bg-purple">
        <div class="inner">
          <h3>
            <?php
              $sql = "SELECT COUNT(*) AS total_invoices FROM sales_invoices";
              $query = $mysqli->query($sql);
              $row = mysqli_fetch_assoc($query);
              echo $row['total_invoices'];
            ?>
          </h3>
          <p>Total Invoices</p>
        </div>
        <div class="icon">
          <i class="ion ion-printer"></i>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-xs-6">
      <!-- Pending Bills Box -->
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>
            <?php
              // Query to count pending invoices in sales_invoices table where payment_status is 'open'
              $sql_sales_invoices = "SELECT COUNT(*) AS pending_bills_sales FROM sales_invoices WHERE payment_status = 'open'";
              $query_sales_invoices = $mysqli->query($sql_sales_invoices);
              $row_sales_invoices = mysqli_fetch_assoc($query_sales_invoices);
              $pending_sales_invoices = $row_sales_invoices['pending_bills_sales'];

              // Query to count pending expenses where status is 'pending'
              $sql_expenses = "SELECT COUNT(*) AS pending_bills_expenses FROM expenses WHERE status = 'pending'";
              $query_expenses = $mysqli->query($sql_expenses);
              $row_expenses = mysqli_fetch_assoc($query_expenses);
              $pending_expenses = $row_expenses['pending_bills_expenses'];

              // Combine both counts: count all pending bills (either from sales invoices or expenses)
              $total_pending_bills = $pending_sales_invoices + $pending_expenses;

              // Show the total number of pending bills (sales invoices + expenses)
              echo $total_pending_bills;
            ?>
          </h3>
          <p>Pending Bills</p>
        </div>
        <div class="icon">
          <i class="ion ion-load-a"></i>
        </div>
      </div>
    </div>
  </div> <!-- Closing the first row -->

  <!-- 2nd row -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <!-- Total Products Box -->
      <div class="small-box bg-primary">
        <div class="inner">
        <h3>
  <?php
    $sql = "SELECT COUNT(*) AS total_products FROM sales_invoices WHERE products IS NOT NULL AND products != ''";
    $query = $mysqli->query($sql);
    $row = mysqli_fetch_assoc($query);
    echo $row['total_products'];
  ?>
</h3>

          <p>Total Products</p>
        </div>
        <div class="icon">
          <i class="ion ion-social-dropbox"></i>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-xs-6">
      <!-- Total Customers Box -->
      <div class="small-box bg-maroon">
        <div class="inner">
          <h3>
            <?php
              // Query to count distinct customers in the sales_invoices table
              $sql = "SELECT COUNT(DISTINCT customer_id) AS total_customers FROM sales_invoices";
              $query = $mysqli->query($sql);
              $row = mysqli_fetch_assoc($query);
              echo $row['total_customers'];
            ?>
          </h3>
          <p>Total Customers</p>
        </div>
        <div class="icon">
          <i class="ion ion-ios-people"></i>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-xs-6">
      <!-- Paid Bills Box -->
      <div class="small-box bg-olive">
        <div class="inner">
          <h3>
            <?php
              // Query to count the paid invoices in the sales_invoices table based on payment_status
              $sql = "SELECT COUNT(*) AS paid_bills FROM sales_invoices WHERE payment_status = 'paid'";
              $query = $mysqli->query($sql);
              $row = mysqli_fetch_assoc($query);
              echo $row['paid_bills'];
            ?>
          </h3>
          <p>Paid Bills</p>
        </div>
        <div class="icon">
          <i class="ion ion-ios-paper"></i>
        </div>
      </div>
    </div>
  </div> <!-- Closing the second row -->
</section>
<!-- /.content -->

<?php
  include('footer.php');
?>
