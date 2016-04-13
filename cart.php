<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Group Project</title>
        <link type="text/css" rel="stylesheet" href="css/mycss.css" >
    </head>
    <body>
        <?php
            session_start();
        ?>
        <div class="header">
            <h1>Grocery Store</h1>
            <strong>Your shopping cart:</strong>
        </div>
        <div class="centerData">
            <?php
                if(isset($_POST["clearCart"]))
                {
                    $_SESSION["shoppingCart"] = array();
                    header("Location: index.php");
                }
                if(isset($_POST["removeItem"]))
                {
                    $i = 0;
                    $j = count($_SESSION["shoppingCart"]);
                    
                    for(; $i < $j && $_SESSION["shoppingCart"][$i]["productID"] != $_POST["itemID"]; ++$i)
                    {
                    }
                    if($i < $j)
                    {
                        for(; $i < ($j-1); ++$i)
                        {
                            $_SESSION["shoppingCart"][$i] = $_SESSION["shoppingCart"][$i+1];
                        }
                        unset($_SESSION["shoppingCart"][$i]);
                    }
                }
                
                $total = 0;
                echo "<table border=1><tr><th>Product Name</th><th>Quantity Ordered</th><th>Individual Price</th><th>Full Price</th><th></th></tr>";
                for($i =0, $j = count($_SESSION["shoppingCart"]); $i < $j; ++$i)
                {
                    $full = $_SESSION["shoppingCart"][$i]["quantity"] * $_SESSION["shoppingCart"][$i]["price"];
                    
                    echo "<tr><td>" . $_SESSION["shoppingCart"][$i]["name"] . "</td>";
                    echo "<td>" . $_SESSION["shoppingCart"][$i]["quantity"] . "</td>";
                    echo "<td>$" . $_SESSION["shoppingCart"][$i]["price"] . "</td>";
                    echo "<td>" . $full . "</td>";
                    echo "<td><form action=\"\" method=\"post\" ><input type=\"submit\" name=\"removeItem\" value=\"remove\" />";
                    echo "<input type=\"hidden\" name=\"itemID\" value=\"" . $_SESSION["shoppingCart"][$i]["productID"] . "\" /></form></td>";
                    echo "</tr>";
                    
                    $total += $full;
                }
                echo "<tr><td colspan=2></td><th>Total:</th><th>$$total</th><td></td></tr>";
                echo "</table>";
            ?>
            <br /><form action="" method="post">
                <input type="submit" name="clearCart" value="Clear Shopping Cart" />
            </form><br />
            <a href="index.php">Go Back</a>
        </div>
    </body>
</html>