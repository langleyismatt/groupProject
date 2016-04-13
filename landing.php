<?php
  include 'includes/database.php';
  
  function moreInfo()
  {
    $sql = "SELECT products.productID, products.name, suppliers.companyName, department.name as dpName, products.price, products.quantity from `products` 
      join suppliers on products.supplierID = suppliers.supplierID
      join department on products.departmentID = department.departmentID
      WHERE `productID` = :productID";
            
    $namedParameters[':productID'] = $_POST['productID'];
    
    $conn = dbConnect();
    $statement1 = $conn->prepare($sql);
    $statement1->execute($namedParameters);
    
    $statement1->setFetchMode(PDO::FETCH_ASSOC);
    $result = $statement1->fetchAll();
    
    echo "<table border=1><tr><th>Product Id</th><th>Product Name</th><th>Company Name</th><th>Department Name</th><th>Price</th><th>Quantity</th></tr>";
    echo "<tr><td>" . $result[0]["productID"] . "</td>";
    echo "<td>" . $result[0]["name"] . "</td>";
    echo "<td>" . $result[0]["companyName"] . "</td>";
    echo "<td>" . $result[0]["dpName"] . "</td>";
    echo "<td>" . $result[0]["price"] . "</td>";
    echo "<td>";
    if(isset($_POST["addToCart"]))
    {
      echo $_POST["quantity"];
    }
    else
    {
      echo $result[0]["quantity"];
    }
    echo "</td></tr></table>";
    
    if(isset($_POST["addToCart"]))
    {
      $temp = array(
        "productID" => $result[0]["productID"],
        "name" => $result[0]["name"],
        "quantity" => $_POST["quantity"],
        "price" => $result[0]["price"]
        );
        
        $_SESSION["shoppingCart"][] = $temp;
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Group Project</title>
</head>

<body>
  <?php
    session_start();
  ?>
  <div>
    <header>
      <h1>Grocery Store</h1>
    </header>

   
    <div>
     <strong> Your Item!</strong>
    
      <br /><br />    
      <?php
        moreInfo();
      ?>
      
      <a href="index.php">Go Back</a>

    </div>
  </div>
</body>
</html>