<?php
session_start();
include("connection.php");

    $product_id = $_GET['id'];
    
    if (!empty($_GET['quantity'])) {
        $quantity = $_GET['quantity'];
    }

    $sql = "SELECT CART.CART_ID, WISHLIST.WISHLIST_ID 
    FROM CART 
    JOIN WISHLIST ON CART.FK1_CUST_ID = WISHLIST.FK1_CUST_ID 
    WHERE CART.FK1_CUST_ID = :cust_id";

    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ':cust_id', $_SESSION['cust_id']);

    oci_execute($stmt);

    while ($row = oci_fetch_assoc($stmt)) {
        $cart_id = $row['CART_ID'];
        $wishlist_id = $row['WISHLIST_ID'];
    }

    $sum =  'SELECT COUNT(*) AS NUM_OF_ROWS FROM "PROD_CART" WHERE CART_ID= :c_id ';

                        $data_qu = oci_parse($conn,$sum);
                       oci_bind_by_name($data_qu, ':c_id',$cart_id);
                       oci_define_by_name($data_qu, "NUM_OF_ROWS",  $total_sum);
                    
                        oci_execute($data_qu);
                        oci_fetch($data_qu);
                       
                        // echo $total_sum;
                    
                        if($total_sum >= 20)
                        {
                            echo "You can excess maximum of 20 items per cart";
                        }
                        else
                        {

                            if ($_GET['action'] == 'addcart') {
                                $find = "SELECT * FROM PROD_CART WHERE CART_ID = :cart_id AND PRODUCT_ID = :product_id";
                                $findstid = oci_parse($conn,$find);
                                oci_bind_by_name($findstid,":product_id", $product_id);
                                oci_bind_by_name($findstid,":cart_id", $cart_id);
                                oci_execute($findstid);
                                if(oci_fetch_array($findstid)){
                                    echo "Already Added to Cart";
                                }
                                
                                else{
                                    $sql = "INSERT INTO PROD_CART (CART_ID,PRODUCT_ID,CART_QUANTITY) VALUES (:cart_id,:product_id,:quantity)";
                                    $stid = oci_parse($conn, $sql);
                                    oci_bind_by_name($stid, ":cart_id", $cart_id);
                                    oci_bind_by_name($stid, ":product_id", $product_id);
                                    oci_bind_by_name($stid, ":quantity", $quantity);
                                    if(oci_execute($stid)){
                                        echo "Added Successfully";
                                    }
                                }
                            }
                        }
    
    //  add to cart

    
    // update cart
    if ($_GET['action'] == 'updatecart') {
        $sql = "UPDATE PROD_CART SET CART_QUANTITY = :quantity  WHERE PRODUCT_ID = :product_id";
        $stid = oci_parse($conn, $sql);
        oci_bind_by_name($stid, ":quantity", $quantity);
        oci_bind_by_name($stid, ":product_id", $product_id);
        oci_execute($stid);
    }
    // add to wishlist

    if ($_GET['action'] == 'addwishlist') {

        $find = "SELECT * FROM PROD_WISH WHERE WISHLIST_ID = :wishlist_id AND PRODUCT_ID = :product_id";
        $findstid = oci_parse($conn,$find);
        oci_bind_by_name($findstid,":wishlist_id", $wishlist_id);
        oci_bind_by_name($findstid,":product_id", $product_id);
        oci_execute($findstid);
        if(oci_fetch_array($findstid)){
            echo "Already Added to Wishlist";
        }
        else{
            $sql = "INSERT INTO PROD_WISH (WISHLIST_ID,PRODUCT_ID) VALUES (:wishlist_id,:product_id)";
            $stid = oci_parse($conn, $sql);
            oci_bind_by_name($stid, ":wishlist_id", $wishlist_id);
            oci_bind_by_name($stid, ":product_id", $product_id);
            if(oci_execute($stid)){
                echo "Added to Wishlist";
            }
        }
    }
 

?>