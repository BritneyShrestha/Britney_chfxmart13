
<?php
session_start();
include('connection.php');
// echo $_SESSION['cust_id'];
$id=(int)$_SESSION['trader_id'];
var_dump($id);

$sql = "SELECT * FROM SHOP WHERE TRADER_ID= :id" ;
$stid = oci_parse($conn, $sql);
oci_bind_by_name($stid, ':id', $id);
oci_execute($stid);

    while($row = oci_fetch_array($stid, OCI_ASSOC))
    {
    $ssid=$row['SHOP_ID'];
}

// -- echo  $shop_id;
echo $ssid;
?>