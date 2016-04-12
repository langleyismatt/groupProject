<?php

function moreInfo(){
    
        include 'includes/database.php';
        
        $sql = "SELECT * from `products`
                WHERE `productID` = :productID";
                
        $namedParameters[':productID'] = $_POST['productID'];
        
        $conn = dbConnect();
        $statement1 = $conn->prepare($sql);
        $statement1->execute($namedParameters);
        
        echo "<table>";
              foreach ($statement1 as $data) {
              echo "<tr>"; 
              echo "<td>" . $data['productID'] . "</td>"; 
              echo "<td>" . $data['name'] . "</td>";
              echo "<td>" . $data['supplierID'] . "</td>"; 
              echo "<td>" . $data['departmentID'] . "</td>";
              echo "<td>" . $data['quantity'] . "</td>"; 
              echo "<td>" . $data['price'] . "</td>";
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
     <strong> Your Item!</strong>
    
      <br /><br />    
      <?php
        moreInfo();
        
        var_dump(moreInfo());
      ?>
      
      

    </div>
  </div>
</body>
</html>