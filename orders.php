<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>ordenes</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="orders">

   <h1 class="heading">pedidos realizados</h1>

   <div class="box-container">

   <?php
      if($user_id == ''){
         echo '<p class="empty">Inicie sesión para ver sus pedidos</p>';
      }else{
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
         $select_orders->execute([$user_id]);
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p>fecha de pedido : <span><?= $fetch_orders['placed_on']; ?></span></p>
      <p>nombre : <span><?= $fetch_orders['name']; ?></span></p>
      <p>correo : <span><?= $fetch_orders['email']; ?></span></p>
      <p>numero contacto : <span><?= $fetch_orders['number']; ?></span></p>
      <p>direccion : <span><?= $fetch_orders['address']; ?></span></p>
      <p>forma de pago : <span><?= $fetch_orders['method']; ?></span></p>
      <p>tus pedidos : <span><?= $fetch_orders['total_products']; ?></span></p>
      <p>precio total : <span>$<?= $fetch_orders['total_price']; ?>/-</span></p>
      <p>estado de pago : <span style="color:<?php if($fetch_orders['payment_status'] == 'pendiente'){ echo 'red'; }else{ echo 'green'; }; ?>"><?= $fetch_orders['payment_status']; ?></span> </p>
   </div>
   <?php
      }
      }else{
         echo '<p class="empty">¡no hay pedidos realizados todavía!</p>';
      }
      }
   ?>

   </div>

</section>













<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
