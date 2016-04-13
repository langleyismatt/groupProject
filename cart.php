<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Group Project</title>
    </head>
    <body>
        <?php
            session_start();
        ?>
        <header>
            <h1>Grocery Store</h1>
        </header>
        
        Your shopping cart:
        
        <?php
            if(isset($_POST["clearCart"]))
            {
                $_SESSION["shoppingCart"] = array();
            }
            
            $total = 0;
            echo "<table border=1><tr><th>Product Name</th><th>Quantity Ordered</th><th>Individual Price</th><th>Full Price</th></tr>";
            for($i =0, $j = count($_SESSION["shoppingCart"]); $i < $j; ++$i)
            {
                $full = $_SESSION["shoppingCart"][$i]["quantity"] * $_SESSION["shoppingCart"][$i]["price"];
                
                echo "<tr><td>" . $_SESSION["shoppingCart"][$i]["name"] . "</td>";
                echo "<td>" . $_SESSION["shoppingCart"][$i]["quantity"] . "</td>";
                echo "<td>$" . $_SESSION["shoppingCart"][$i]["price"] . "</td>";
                echo "<td>" . $full . "</td></tr>";
                
                $total += $full;
            }
            echo "<tr><td colspan=2></td><th>Total:</th><th>$$total</th></tr>";
            echo "</table>";
        ?>
        
        <form action="" method="post">
            <input type="submit" name="clearCart" value="Clear Shopping Cart" />
        </form><br />
        <a href="index.php">Go Back</a>
    </body>
</html>