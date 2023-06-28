<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
};

if(isset($_POST['order'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $address = 'flat no. '. $_POST['flat'] .', '. $_POST['street'] .', '. $_POST['city'] .', '. $_POST['state'] .', '. $_POST['country'] .' - '. $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if($check_cart->rowCount() > 0){

      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);

      $message[] = '¡pedido realizado con éxito!';
   }else{
      $message[] = 'your cart is empty';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="checkout-orders">

   <form action="" method="POST">

   <h3>tus pedidos</h3>

      <div class="display-orders">
      <?php
         $grand_total = 0;
         $cart_items[] = '';
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
               $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].') - ';
               $total_products = implode($cart_items);
               $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
      ?>
         <p> <?= $fetch_cart['name']; ?> <span>(<?= '$'.$fetch_cart['price'].'/- x '. $fetch_cart['quantity']; ?>)</span> </p>
      <?php
            }
         }else{
            echo '<p class="empty">¡Tu carrito esta vacío!</p>';
         }
      ?>
         <input type="hidden" name="total_products" value="<?= $total_products; ?>">
         <input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">
         <div class="grand-total">total : <span>$<?= $grand_total; ?>/-</span></div>
      </div>

      <h3>place your orders</h3>

      <div class="flex">
         <div class="inputBox">
            <span>nombres y apellidos :</span>
            <input type="text" name="name" placeholder="ingresa tus nombres y apellidos" class="box" maxlength="20" required>
         </div>
         <div class="inputBox">
            <span>numero de contacto :</span>
            <input type="number" name="number" placeholder="ingresa tu numero" class="box" min="0" max="9999999999" onkeypress="if(this.value.length == 10) return false;" required>
         </div>
         <div class="inputBox">
            <span>correo :</span>
            <input type="email" name="email" placeholder="eingresa tu correo" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>metodo de pago :</span>
            <select name="method" class="box" required>
               <option value="cash on delivery">pago contra entrega</option>
               <option value="credit card">tarjeta de credito</option>
               <option value="paytm">efectivo</option>
               <option value="paypal">virtual</option>
            </select>
         </div>
         <div class="inputBox">
            <span>direccion :</span>
            <input type="text" name="flat" placeholder="ingresa tu direccion" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>direccion alternativa :</span>
            <input type="text" name="street" placeholder="ingresa tu direccion alterna" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>ciudad :</span>
            <input type="text" name="city" placeholder="ingresa tu ciudad de residencia" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>barrio :</span>
            <input type="text" name="state" placeholder="ingresa tu barrio" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>pais :</span>
            <input type="text" name="country" placeholder="ingresa tu pais de residensia" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>codigo postal :</span>
            <input type="number" min="0" name="pin_code" placeholder="ingresa tu codigo postal" min="0" max="999999" onkeypress="if(this.value.length == 6) return false;" class="box" required>
         </div>
      </div>

      <input type="submit" name="order" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>" value="realizar orden">

   </form>

</section>













<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
