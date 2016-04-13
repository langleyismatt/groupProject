<?php
    include 'includes/database.php';

    function displayAllProducts()
    {
        $conn = dbConnect();
    
        $sql = "select products.name, suppliers.companyName, department.name as deptName, products.price, products.productID from 
            products join suppliers on products.supplierID = suppliers.supplierID
            join department on products.departmentID = department.departmentID ";
        
        if($_SERVER["REQUEST_METHOD"] == "POST" &&
            (isset($_POST["usingCompany"]) || isset($_POST["usingDepartment"]) ||
            isset($_POST["usingPrice"]) || isset($_POST["usingSort"])))
        {
            if(isset($_POST["usingCompany"]) || isset($_POST["usingDepartment"]) ||
                isset($_POST["usingPrice"]))
            {
                $sql = $sql . "where ";
            }
            
            if(isset($_POST["usingCompany"]))
            {
                $sql = $sql . "suppliers.supplierID = :company ";
                if(isset($_POST["usingDepartment"]) || isset($_POST["usingPrice"]))
                {
                    $sql = $sql . "and ";
                }
            }
            if(isset($_POST["usingDepartment"]))
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
            
            if(isset($_POST["usingSort"]))
            {
                if($_POST["sortColumn"] == 1)
                {
                    $sql = $sql . "order by products.name ";
                }
                else if($_POST["sortColumn"] == 2)
                {
                    $sql = $sql . "order by products.price ";
                }
                
                if($_POST["sortOrder"] == "ascending")
                {
                    $sql = $sql . "asc";
                }
                else
                {
                    $sql = $sql . "desc";
                }
            }
            else
            {
                $sql = $sql . "order by products.name asc";
            }
        }
        else
        {
            $sql = $sql . "order by products.name asc";
        }
    
        $sqldata = $conn->prepare($sql);
        
        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            if(isset($_POST["usingCompany"]))
            {
                $sqldata->bindParam(":company", $_POST["company"]);
            }
            if(isset($_POST["usingDepartment"]))
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
        
        echo "<table border=1><tr><th>Product Name</th><th>Company</th><th>Department</th><th>Price</th><th colspan=2>Options</th></tr>";
        foreach($result as $a => $b)
        {
            echo "<tr>";
            echo "<td>" . $b["name"] . "</td>";
            echo "<td>" . $b["companyName"] . "</td>";
            echo "<td>" . $b["deptName"] . "</td>";
            echo "<td>$" . $b["price"] . "</td>";
            echo "<form action='landing.php' method='post'><td><input type='submit' name='justLooking' value='more info' /></td>";
            echo "<td><input type='submit' name='addToCart' value='add to cart' />";
            echo "<input type='number' name='quantity' min='1' value='1' /></td><input type='hidden' name='productID' value=" . $b["productID"] . "/></form>";
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
            echo "<option value=\"" . $b["supplierID"] . "\"";
            if(isset($_POST["usingCompany"]) && $_POST["company"] == $b["supplierID"])
            {
                echo "selected";
            }
            echo ">" . $b["companyName"] . "</option>\n";
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
            echo "<option value=\"" . $b["departmentID"] . "\"";
            if(isset($_POST["usingDepartment"]) && $_POST["department"] == $b["departmentID"])
            {
                echo "selected";
            }
            echo ">" . $b["name"] . "</option>\n";
        }
    }
    
    function checkCompany()
    {
        if(isset($_POST["usingCompany"]))
        {
            echo "checked";
        }
    }
    function checkDepartment()
    {
        if(isset($_POST["usingDepartment"]))
        {
            echo "checked";
        }
    }
    function checkPrice()
    {
        if(isset($_POST["usingPrice"]))
        {
            echo "checked";
        }
    }
    function checkSorting()
    {
        if(isset($_POST["usingSort"]))
        {
            echo "checked";
        }
    }
    function setAscending()
    {
        if(!isset($_POST["usingSort"]) || $_POST["sortOrder"] == "ascending" )
        {
            echo "checked";
        }
    }
    function setDescending()
    {
        if(isset($_POST["usingSort"]) && $_POST["sortOrder"] == "descending")
        {
            echo "checked";
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
        if(!isset($_SESSION["shoppingCart"]))
        {
            $_SESSION["shoppingCart"] = array();
        }
    ?>
  <div>
    <header>
      <h1>Grocery Store</h1>
    </header>

   
    <div>
     <strong> Welcome! - Select and item to learn more or add to cart!</strong>
    
      <br /><br />    
      
      <div style="float:left;">
      <form action="" method="post">
          <input type="checkbox" name="usingCompany" <?= checkCompany(); ?>/><label>Company: </label><select name="company">
              <?= populateCompanies(); ?>
          </select><br />
          <input type="checkbox" name="usingDepartment" <?= checkDepartment(); ?>/><label>Department: </label><select name="department">
              <?= populateDepartments(); ?>
          </select><br />
          <input type="checkbox" name="usingPrice" <?= checkPrice(); ?>/><label>Price between </label>
          <input type="number" step="0.01" name="priceLow" value="<?= $_POST["priceLow"] ?>" /><label> and </label>
              <input type="number" step="0.01" name="priceHigh" value="<?= $_POST["priceHigh"] ?>"/><br />
          <input type="checkbox" name="usingSort" <?= checkSorting(); ?>/><label>Sort using</label>
              <select name="sortColumn">
                  <option value="1">Product Name</option>
                  <option value="2">Price</option>
              </select>
              <label>Ascending: </label><input type="radio" name="sortOrder" value="ascending" <?= setAscending(); ?> />
              <label>Descending: </label><input type="radio" name="sortOrder" value="descending" <?= setDescending(); ?>/><br />
          <input type="submit" value="modify table" />
      </form>
      <a href="cart.php">Go to cart</a>
      </div>
      
      <br /><br />
      
      <?php
    
        displayAllProducts();
       
        //moreInfo();
        
      ?>

    </div>
  </div>
</body>
</html>