<?php
    include 'includes/database.php';

    function displayAllProducts()
    {
        $conn = dbConnect();
    
        $sql = "select products.name, suppliers.companyName, department.name as deptName, products.price, products.productID from 
            products join suppliers on products.supplierID = suppliers.supplierID
            join department on products.departmentID = department.departmentID ";
        
        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            $sql = $sql . "where ";
            if(isset($_POST["company"]))
            {
                $sql = $sql . "suppliers.supplierID = :company ";
                if(isset($_POST["department"]) || isset($_POST["usingPrice"]))
                {
                    $sql = $sql . "and ";
                }
            }
            if(isset($_POST["department"]))
            {
                $sql = $sql . "department.departmentID = :department ";
                if(isset($_POST["usingPrice"]))
                {
                    $sql = $sql . "and ";
                }
            }
            if(isset($_POST["usingPrice"]))
            {
                $sql = $sql . "products.price between :priceLow and :priceHigh ";
            }
            
            $sql = $sql . "order by products.name asc";
        }
        else
        {
            $sql = $sql . "order by products.name asc";
        }
    
        $sqldata = $conn->prepare($sql);
        
        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            if(isset($_POST["company"]))
            {
                $sqldata->bindParam(":company", $_POST["company"]);
            }
            if(isset($_POST["department"]))
            {
                $sqldata->bindParam(":department", $_POST["department"]);
            }
            if(isset($_POST["usingPrice"]))
            {
                $sqldata->bindParam(":priceLow", $_POST["priceLow"]);
                $sqldata->bindParam(":priceHigh", $_POST["priceHigh"]);
            }
        }
        
        $sqldata->execute(); 
        $sqldata->setFetchMode(PDO::FETCH_ASSOC);
        
        $result = $sqldata->fetchAll();
        
        echo "<table border=1><tr>";
        foreach($result[0] as $a => $b)
        {
            echo "<th>$a</th>";
        }
        echo "<th></th><th></th></tr>";
        foreach($result as $a => $b)
        {
            echo "<tr>";
            foreach($b as $c => $d)
            {
                echo "<td>$d</td>";
            }
            echo "<form action='landing.php' method='post'><td><input type='submit' value='more info' /></td>";
            echo "<td><input type='submit' value='add to cart' /></td><input type='hidden' name='productID' value=" . $b["productID"] . "/></form>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    function populateCompanies()
    {
        $conn = new PDO("mysql:host=localhost;dbname=my_products", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $helper = $conn->prepare("select distinct companyName, supplierID from suppliers");
        $helper->execute();
        $helper->setFetchMode(PDO::FETCH_ASSOC);
        
        $result = $helper->fetchAll();
        
        foreach($result as $a => $b)
        {
            echo "<option value=\"" . $b["supplierID"] . "\">" . $b["companyName"] . "</option>\n";
        }
    }
    function populateDepartments()
    {
        $conn = new PDO("mysql:host=localhost;dbname=my_products", "root", "");
        $conn ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $helper = $conn->prepare("select distinct name, departmentID from department");
        $helper->execute();
        $helper->setFetchMode(PDO::FETCH_ASSOC);
        
        $result = $helper->fetchAll();
        
        foreach($result as $a => $b)
        {
            echo "<option value=\"" . $b["departmentID"] . "\">" . $b["name"] . "</option>\n";
        }
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
      
      <form action="" method="post">
          <label>Company: </label><select name="company">
              <?= populateCompanies(); ?>
          </select>
          <label>Department: </label><select name="department">
              <?= populateDepartments(); ?>
          </select>
          <label>Price: </label><input type="checkbox" name="usingPrice" /><label> between </label>
          <input type="number" step="0.01" name="priceLow" /><label> and </label><input type="number" step="0.01" name="priceHigh" />
          <input type="submit" value="modify table" />
      </form>
      
      <br /><br />
      
      <?php
    
        displayAllProducts();
       
        //moreInfo();
        
      ?>

    </div>
  </div>
</body>
</html>