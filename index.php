<?php
    include 'includes/database.php';

    function displayAllProducts()
    {
        $conn = dbConnect();
    
        $sql = "select `name`, `price`, `productID` from `products` order by `name` ASC";
    
        $sqldata = $conn->prepare($sql);
        $sqldata->execute(); 
        $sqldata->setFetchMode(PDO::FETCH_ASSOC);
        
        $result = $sqldata->fetchAll();
  
        echo "<table>";
        foreach ($result as $data) {
        echo "<tr>"; 
        echo "<td>" . $data['name'] . "</td>"; 
        echo "<td> <form action='landing.php' method='post'>";
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
       
        //moreInfo();
        
      ?>

    </div>
  </div>
</body>
</html>