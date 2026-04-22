<?php
    session_start();
    if(!isset($_SESSION['status']) || $_SESSION['role'] != "admin"){
        header('location: login.html');
    }

    // Logic to Add Product with Sequential ID
    if(isset($_POST['add_product'])){
        // Determine the next ID
        $last_id = 0;
        if(isset($_SESSION['products']) && count($_SESSION['products']) > 0){
            // Find the maximum ID currently in the list
            foreach($_SESSION['products'] as $p){
                if($p['id'] > $last_id){
                    $last_id = $p['id'];
                }
            }
        }
        
        $newP = [
            'id' => $last_id + 1, // Next ID is always max + 1
            'name' => $_POST['name'],
            'price' => $_POST['price'],
            'qty' => $_POST['qty']
        ];
        $_SESSION['products'][] = $newP;
    }

    // Logic to Update a Specific Product
    if(isset($_POST['update_product'])){
        $id = $_POST['id'];
        foreach($_SESSION['products'] as $key => $p){
            if($p['id'] == $id){
                $_SESSION['products'][$key]['name'] = $_POST['name'];
                $_SESSION['products'][$key]['price'] = $_POST['price'];
                $_SESSION['products'][$key]['qty'] = $_POST['qty'];
                break; 
            }
        }
    }

    // Logic to Remove a Specific Product
    if(isset($_GET['delete'])){
        $id = $_GET['delete'];
        foreach($_SESSION['products'] as $key => $p){
            if($p['id'] == $id){
                unset($_SESSION['products'][$key]);
                // Re-index to keep the array clean, but IDs remain the same
                $_SESSION['products'] = array_values($_SESSION['products']); 
                break;
            }
        }
    }
?>

<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Admin Dashboard</h2>
    <a href="logout.php">Logout</a>
    <hr>
    
    <h3>Add New Product</h3>
    <form method="post">
        Name: <input type="text" name="name" required> 
        Price: <input type="number" name="price" required> 
        Qty: <input type="number" name="qty" required>
        <input type="submit" name="add_product" value="Add Product">
    </form>

    <h3>Manage Products</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Update</th>
            <th>Remove</th>
        </tr>
        <?php 
        if(isset($_SESSION['products'])){
            foreach($_SESSION['products'] as $p){ 
        ?>
        <tr>
            <form method="post">
                <td>
                    <?php echo $p['id']; ?> 
                    <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                </td>
                <td><input type="text" name="name" value="<?php echo $p['name']; ?>"></td>
                <td><input type="number" name="price" value="<?php echo $p['price']; ?>"></td>
                <td><input type="number" name="qty" value="<?php echo $p['qty']; ?>"></td>
                <td><input type="submit" name="update_product" value="Update"></td>
                <td><a href="adminHome.php?delete=<?php echo $p['id']; ?>">Delete</a></td>
            </form>
        </tr>
        <?php 
            } 
        } 
        ?>
    </table>
</body>
</html>