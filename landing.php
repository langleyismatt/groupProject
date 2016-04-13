<?php
  include 'includes/database.php';
  
  function moreInfo()
  {
    $sql = "SELECT * from `products` WHERE `productID` = :productID";
            
    $namedParameters[':productID'] = $_POST['productID'];
    
    $conn = dbConnect();
    $statement1 = $conn->prepare($sql);
    $statement1->execute($namedParameters);
    
    $statement1->setFetchMode(PDO::FETCH_ASSOC);
    $result = $statement1->fetchAll();
    
    echo "<table border=1><tr>";
    foreach($result[0] as $a => $b)
    {
      echo "<th>$a</th>";
    }
    echo "</tr>";
    foreach ($result as $a => $b)
    {
      echo "<tr>";
      foreach($b as $c => $d)
      {
        echo "<td>$d</td>";
      }
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
      ?>
      
      

    </div>
  </div>
</body>
</html>