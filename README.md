# Marketplace DDD

Este proyecto es una aplicación desarrollada con Symfony que sigue los principios de Domain Driven Design (DDD) y arquitectura hexagonal.

### Tecnologías utilizadas
- Symfony 7
- Mysql
- Docker

### Requisitos
- PHP 8.2
- Composer
- Docker

### Despliegue del proyecto en entorno local
Teniendo docker y composer instalados, ejecutaremos los siguientes comandos:

1. Clonar el repostiorio:
   `git clone https://github.com/dmaggiof/marketplace.git`
2. Preparar y arrancar los contenedores de docker:
   `docker compose up`. Si tenemos disponible el comando `make` en nuestro equipo podemos ejecutar `make up` para levantar los contenedores 
3. Instalar dependencias:
  `docker exec -it project_web composer install`. Si tenemos disponible el comando `make` en nuestro equipo podemos ejecutar `make install-deps` para instalar las dependencias. 

### Ejecución de los tests

Si tenemos disponible el comando `make` en nuestro equipo podemos ejecutar el siguiente comando para ejecutar todos los tests:
`make test`

De lo contrario ejecutaremos: 
`docker exec -it project_web vendor/bin/phpunit`

## Estructura del proyecto

Se separa el dominio de los detalles de infraestructura siguiendo prácticas de DDD y arquitectura hexagonal

#### Patrones de DDD

##### Patrones estratégicos

El proyecto tiene actualmente un único bounded context llamado Marketplace, cuyo dominio principal es el de realizar las operaciones habituales de un ecommerce: alta de usuarios, listado de productos a la venta, compras, etc. Dentro de este bounded context se han separado las entidades de dominio de los servicios de aplicación e infraestructura.

Tratándose de un marketplace, podría crearse un nuevo bounded context para los proveedores. El motivo de esta separación es que en esta capa probablemente los conceptos no sean los mismos que en la del marketplace: un entidad como producto podría tener otros atributos, o puede haber conceptos nuevos como almacen, logística o contratos. 

El bounded context Marketplace está dividido en varios módulos (o subdominios) que representan los conceptos básicos de un ecommerce utilizando un lenguaje ubicuo:

- Customer: Gestión de los usuarios y sus datos personales.
- Cart: Manejo del carrito de compras.
- Order: Pedidos y líneas de pedidos, que se utilizarían de cara a generar a facturas.
- Product: Administración del catálogo de productos.

##### Patrones tácticos

Cada módulo tiene una entidad principal, que representa un objeto del dominio, y que coincide con el nombre del módulo. Además, en el caso de los módulos Customer y Order tenemos dos agregados:

- Customer:
  - Agregado raíz: `Customer`
    - Entidades secundarias:
       - `CustomerAddress`: listado de direcciones del usuario.
       - `PaymentMethod`: listado de métodos de pago del usuario.

- Order:
   - Agregado raíz: `Order`
   - Entidades secundarias:
     -   `OrderLine`: representa cada uno de los productos comprados en una transacción.

Las entidades no solo almacenan estado, sino que tienen métodos para gestionar su propia lógica, evitando así tener un modelo de datos _aneḿico_. También se ha intentado llevar a cada entidad las invariantes que debe proteger.

Para aquellas acciones cuya lógica no pertenece a una única entidad, como por ejemplo finalizar una carrito, se utilizan servicios de aplicación. Dichos servicios reciben DTOs con la información necesaria desde los controladores. Aunque no se ha implementado, el tener una capa de servicio y el uso de DTOs en la capa de aplicación podría facilitar la introducción de un command bus como Tactician. 

### Arquitectura hexagonal

Como se comenta en la sección anterior de patrones tácticos, se ha hecho una separación entre casos de uso, elementos de dominio e infraestructura. El código contenido en src/Marketplace
está organizado de esa manera, y en cada capa tenemos una carpeta que representa a cada uno de los elementos de nuestro negocio:

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


Se siguen patrones de diseño y buenas prácticas, por ejemplo en la capa de infraestructura cada repositorio implementa una interfaz para facilitar cambios en la infraestructura y el testing del proyecto. 
Las interfaces pertenecen al dominio, por lo tanto las encontraremos en la carpeta src/Marketplace/Domain del módulo que corresponda.

### Acciones disponibles

