classDiagram
direction BT
class cart {
   int customer_id_id
   varchar(25) status
   varchar(255) address
   int id
}
class customer {
   varchar(150) name
   varchar(150) email
   varchar(255) password
   int id
}
class customer_address {
   int customer_id
   varchar(255) address
   tinyint(1) default_address
   int id
}
class order {
   int customer_id_id
   varchar(255) status
   int id
}
class order_line {
   int product_id_id
   int order_id_id
   int quantity
   int price
   int id
}
class product {
   int supplier_id_id
   varchar(255) name
   int price
   varchar(255) description
   int stock_quantity
   int id
}
class product_cart {
   int product_id
   int cart_id
   int quantity
   int price
   int id
}

cart  -->  customer : customer_id_id:id
customer_address  -->  customer : customer_id:id
order  -->  customer : customer_id_id:id
order_line  -->  order : order_id_id:id
order_line  -->  product : product_id_id:id
product_cart  -->  cart : cart_id:id
product_cart  -->  product : product_id:id
