# Marketplace DDD

Aplicación que simula un marketplace siguiendo patrones de DDD y arquitectura hexagonal.

### Tecnologías utilizadas
- Symfony 7
- Mysql
- Docker

### Despliegue del proyecto en entorno local
Teniendo docker instalado, ejecutaremos los siguientes comandos:
- Clonar el repostiorio: `git clone https://github.com/dmaggiof/marketplace.git`
- Instalar dependencias: `composer install`
- Preparar y arrancar contenedores de docker: `docker compose up`. 

Si tenemos disponible el comando `make` en nuestro equipo podemos ejecutar `make up` para levantar los contenedores 

### Ejecución de los tests

Si tenemos disponible el comando `make` en nuestro equipo podemos ejecutar el siguiente comando para ejecutar todos los tests:
`make test`

De lo contrario, hay que acceder al docker: 
`docker compose exec web bash`

Y desde ahí:
`vendor/bin/phpunit`

## Arquitectura

Se separa el dominio de los detalles de infraestructura siguiendo prácticas de DDD y arquitectura hexagonal

#### Patrones de DDD

El proyecto tiene actualmente un único bounded context llamado Marketplace. 

Tratándose de un marketplace, podría crearse uno nuevo para proveedores, de forma que estos puedan gestionar el inventario de los productos que ponen a la venta. El motivo de esta separación es que puede haber proveedores con un gran cantidad de productos que tengan necesidad especiales.

El bounded context de la tienda está separado en distintos módulos: Customer, Cart, Order y Product.

Cada módulo tiene una entidad principal, que coincide con el nombre del módulo. En el caso de los módulos Customer y Order tenemos dos agregados:

Entity                   
├─ CustomerAddress (listado de direcciones del usuario)  
└─ PaymentMethod (listado de métodos de pago del usuario)     

Order                   
└─ OrderLine (listado con cada uno de los productos comprados en una transacción)  

### Arquitectura hexagonal

Por otro lado tenemos una separación entre casos de uso, elementos de dominio e infraestructura. El código contenido en src/Marketplace
está organizado de esa manera, y en cada capa tenemos una carpeta que representa a cada uno de los elementos del dominio:

src/Marketplace
|-- Application
|   |-- Cart
|   |-- Customer
|   `-- Product
|-- Domain
|   |-- Cart
|   |-- Customer
|   |-- Order
|   |-- Product
|   |-- ProductCart
|   `-- Supplier
`-- Infrastructure
|-- Cart
|-- Customer
|-- Order
|-- Product
|-- ProductCart
|-- Supplier
`-- Web


Se siguen patrones de la arquitectura, por ejemplo en la capa de infraestructura cada repositorio implementa una interfaz para facilitar cambios en la infraestructura y el testing del proyecto. 
Las interfaces son del dominio, por lo tanto las encontraremos en la carpeta src/Marketplace/Domain del módulo que corresponda.








