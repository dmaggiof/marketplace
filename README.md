### Marketplace DDD

Aplicación que simula un marketplace siguiendo patrones de DDD y arquitectura hexagonal.

### Tecnologías utilizadas
Symfony
Mysql
Docker

### Ejecución de los tests

Instalar [docker](https://docs.docker.com/compose/gettingstarted/) para ejecutar los tests  
Instalar [composer](https://getcomposer.org/download/)  
Clonar el repositorio: `git clone https://github.com/dmaggiof/marketplace.git`  
Instalar dependencias: `composer install`  
Ejecutar los tests: `vendor/bin/phpunit'

### Arquitectura

Se separa el dominio de los detalles de infraestructura siguiendo prácticas de DDD y arquitectura hexagonal

#### Patrones de DDD

El proyecto tiene actualmente un único bounded context llamado Marketplace.  uno para proveedores (sin desarrollar) y otro para la tienda online. 

La idea del bounded context de proveedores es que estos puedan subir y gestionar sus productos, teniendo en cuenta
que puede haber proveedores con un gran tráfico de productos.

El bounded context de la tienda está separado en distintos módulos: Customer, Cart, Order y Product.

Cada módulo tiene una entidad principal, que coincide con el nombre del módulo. En el caso de los módulos Customer y Order tenemos dos agregados:

Entity                   
├─ CustomerAddress (listado de direcciones del usuario)  
└─ PaymentMethod (listado de métodos de pago del usuario)     

Order                   
└─ OrderLine (listado con cada uno de los productos comprados en una transacción)  

### Arquitectura hexagonal

Por otro lado tenemos una separación entre casos de uso, elementos de dominio e infraestructura. El código contenido en src/Marketplace
está organizado de esa manera, y en cada capa tenemos una carpeta que representa a cada uno de los elementos.

Por otro lado, cada repositorio de la capa de persistencia implementa una interfaz de dominio para facilitar cambios en la infraestructura y el testing del proyecto.








