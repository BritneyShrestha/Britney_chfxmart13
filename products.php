<?php
    session_start();
    include('connection.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CHFX Mart | Products Page </title>
    <!--Link to CSS-->
    <link rel="stylesheet" href="css/prod.css">
    <!--Box Icons-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>


</head>
<body>

    <!--Header-->
    <header>
        <!--Nav-->
        <div class="nav container">
            
            <a href="hom.php" class="logo">CHFX Mart</a>
            <!--Cart Icon-->
            <div class="icon">
                <i class='bx bx-cart' id = "cart-icon"></i>
                <!--Wishlist Icon-->
                <i class='bx bx-heart' id = "wishlist-icon"></i>
            </div>
            <!--Wishlist-->
            <div class="wishlist">
                <h2 class="wishlist-title">Wishlist</h2>
                <div class="wishlist-content">
                    <!--Wishlist close-->
                    <i class='bx bx-x' id="close-wishlist"></i>
                </div>
            </div>
            <!--Cart-->
            <div class="cart">
                <h2 class="cart-title">Your Cart</h2>
                <!--Content-->
                <div class="cart-content">
                    
                </div>
                <!--Total-->
                <div class="total">
                    <div class="total-title">Total</div>
                    <div class="total-price">$0</div>
                </div>
                <!--Checkout Button-->
                
                <?php
                    if(isset($_SESSION['user_id'])){
                       echo "<button type='button' class='btn-buy'>Checkout With PayPal</button>";
                    }
                    else{
                       echo "<button type='button' class='btn-buy'>Checkout With PayPal</button>";

                    }
                ?>

                <!--Cart Close-->
                <i class='bx bx-x' id="close-cart"></i>
            </div>
        </div>  
    </header>
    <!--Shop-->
    <section class="shop container">
        <h2 class="selection-title">Products</h2> 
        <!--Content-->

        <!--Category-->
        <!--Seach Bar Functionality-->
        <div class="searchwrapper">
            <div id="search-container">
               
                <input
                    type="search"
                    id="search-input"
                    placeholder="Search Product Name.."
                />
                <button id="search" onclick='searchterm()'>Search</button>
            
            </div>
        </div>

        <script>
            function searchterm(){
                var itemname = document.getElementById('search-input').value;
                document.location.href= "products.php?p_name="+itemname;
            }
        </script>

        <!--Button Categories-->
        <div id="categories">
            <div id="buttons">
                <button class="button-value" onclick='filterall()'>All</button>
            <?php 
                $sql = "SELECT * FROM CATEGORY";
                $stid = oci_parse($conn,$sql);
                oci_execute($stid);
                while($row = oci_fetch_array($stid)){
                    $category_id = $row['CATEGORY_ID'];
                    $category_name = $row['CATEGORY_NAME'];
                    echo "<button class='button-value' onclick='filterCategory($category_id)'>$category_name</button>";
                }
            ?>  
            </div>

        </div>


        <div class="shop-content">
            <!--Box 1-->
            <?php
                if(isset($_GET['p_name'])){
                    $sql = "SELECT * FROM PRODUCT WHERE PRODUCT_NAME = :p_name";
                    $stid = oci_parse($conn,$sql);
                    oci_bind_by_name($stid, ":p_name" , $_GET['p_name']);
                }
                if(isset($_GET['cat_id'])){
                    $sql = "SELECT * FROM PRODUCT WHERE FK1_CATEGORY_ID = :cat_id";
                    $stid = oci_parse($conn,$sql);
                    oci_bind_by_name($stid, ":cat_id" , $_GET['cat_id']);
                }
                else{
                    $sql = "SELECT * FROM PRODUCT";
                    $stid = oci_parse($conn,$sql);
                }
                
                
                oci_execute($stid);
                while($row=oci_fetch_array($stid,OCI_ASSOC))
                {  
                    $pid = $row['PRODUCT_ID']; 
            ?>
            <div class="product-box dairy">
                <img src="upload/products/<?php echo $row['PRODUCT_IMG'];?>" alt="<?php echo $row['PRODUCT_NAME']; ?>" class="product-img" onclick="viewproduct(<?php echo $pid; ?>)">
                <h2 class="product-title"><?php echo $row['PRODUCT_NAME']; ?>  </h2>
                <span class="product-title">STOCK : <?php echo $row['PRODUCT_INSTOCK']; ?></span>
                <span class="price">$ <?php echo $row['PRODUCT_PRICE']; ?></span>
                <i class='bx bx-cart-add add-cart' ></i>
                <i class='bx bxs-heart add-wishlist' ></i>
            </div>

            <?php
                }
            ?>
        </div>
        </div>
    </section>
    <!--Link to JS File-->
    <script src="js/main.js"></script>
    
    <script>
        function viewproduct(pid){
            alert("You selected product id : "+pid);
            document.location.href = "productview.php?pid="+pid;
        }
        
            function filterCategory(category_id){
                
                document.location.href = 'products.php?cat_id='+category_id;
            }

            function filterall(){
                document.location.href = 'products.php';
            }
      
    </script>
</body>
</html>
