<?php 

function displayAllProducts(){
    include 'includes/database.php';
    
    $conn = dbConnect();

    $sql = "select `name`, `price` from `products` order by `name` ASC";

    $sqldata = $conn->prepare($sql);
    $sqldata->execute(); 
  
          echo "<table>";
          foreach ($sqldata as $data) {
          echo "<tr>"; 
          echo "<td>" . $data['name'] . "</td>"; 
          echo "<td> <form action='landing.php'>";
          echo "<td>" .$data['price'] . "</td>";
          echo "<td> <input type='submit' value='more info'/></td>";
          echo "<td> <input type='submit' value='add to cart'/> </td>";
          echo "<input type='hidden' name='productID' value=" . $data['productID'] . "/></form> </td>";
          echo "</tr>";
        }
        echo "</table>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Group Project</title>
</head>

<body>
  <div>
    <header>
      <h1>Grocery Store</h1>
    </header>

   
    <div>
     <strong> Welcome! - Select and item to learn more or add to cart!</strong>
    
      <br /><br />    
      <?php
    
        displayAllProducts();
       
        moreInfo();
        
      ?>

    </div>
  </div>
</body>
</html>