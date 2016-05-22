##Discusion

###Estructura de datos

Todas las tablas de la base de datos específicas de la prueba están replicadas en las
clases de "App\Models", son las siguientes:

acuerdos                  
acuerdos_relaciones       
empresas                  
empresas_aceptan_acuerdos                
relaciones   

Además, están cargadas en el sistema las siguientes clases, que en algunos casos
se utilizan de hecho en el código:

migrations
password_resets           
permission_role           
permissions                
role_user                 
roles                     
users  


###Idea de la aplicación:

Lo importante del asunto, a mi entender, es que los acuerdos entre empresas
"solo se pueden crear en sentido descendente de relaciones ya existentes". Por otro lado,
entiendo que la relación cliente-proveedor es puramente simétrica: si una empresa A es proveedora de B,
entonces B es cliente de A, y vice versa.

Para representar esto se ha creado una tabla "relaciones" con dos identificadores, que apuntan a dos
empresas como cliente y proveedor.

Que los acuerdos solo se puedan crear en sentido descendente, implica las siguientes propiedades:

Sea la empresa A proveedora de B, y B a su vez de C y cliente de D,
un acuerdo A -> B -> C (de ahora en adelante, 'X -> Y' significa que la empresa X es
proveedora de Y ) es posible, pero un acuerdo [A->B + D->B], donde el acuerdo
consistiría en que las empresas A y D transfieren algún bien a B, no es posible.

Conociendo esto, basta con conocer las relaciones entre empresas que componen un acuerdo
para generar la estructura de datos necesaria para guardarlo,
dado que a partir de un conjunto de relaciones comerciales, podemos determinar si esas
relaciones pueden formar un acuerdo y cuál es la única estructura posible de ese acuerdo:

Por ejemplo las relaciones i = A -> B , ii = B -> C , iii = C -> D , son un conjunto candidato
para formar el acuerdo válido A -> B -> C -> D. Además, de la información obtenida
en el set [i ,ii , iii  ] podemos saber con toda seguridad que el acuerdo debe ser
A -> B -> C -> D , y que ese es el único acuerdo posible formado por ese conjunto de relaciones.

En cambio si un acuerdo como [A -> B , B -> A ] , representando que A provee a B de un cierto bien, B fabrica otro con ello y, al final, lo entrega a A,
fuera legítimo, solo con almacenar el índice de las relaciones mismas no podríamos deducir el orden de éstas dentro del acuerdo.



Como mecanismo para asignar estados a los acuerdos, se ha asumido que, una vez propuesto un acuerdo,
cada empresa puede (o no) aceptarlo. Esta relación de aceptación entre acuerdos y empresas se realiza
a través de la table empresas_aceptan_acuerdos: cada tupla en esa tabla indica que, de hecho, la empresa acepta
el acuerdo; si tal tupla no existe, la empresa no lo acepta.

Un acuerdo se considera aceptado si todas las empresas le han dado su beneplácito.


### Uso

Una vez que se hace login (hay que seedear la db primero, bien con artisan o bien ejecutando los tests ),
se debe loguear con una cuenta de empresa que dirigirá a la página /empresa.

(la cuenta fake con user 'satoshi' y password 'hascash' es la que tiene más relaciones cargadas una vez que se ha seedeado).

Allí se ha alojado
la interfaz HTML para acceder a las relaciones comerciales de la empresa dada. Ordena los datos por nombre
y por el tipo de relación comercial. Todas las interacciones de los filtros van por ajax.

Para hacerse una idea del funcionamiento de /empresa, a parte de ver los modelos bajo /App/Models,
habría que leer el controlador en /app/http/controllers/EmpresaControllers,  /resources/assets/js/app/app.js  
y tal vez los parciales bajo /resources/views/partials/database/

Aunque solo se ha codificado para la interfaz lo que se pedía en el lema del ejercicio, bajo tests/matura hay
varios ficheros de tests unitarios donde se prueban los métodos de los modelos que implican relaciones entre clases
(por ejemplo para obtener las empresas de un acuerdo dado, crear nuevas cuentas de empresa, crear cuentas de administrador
etc. etc. )


### Librerías
Para el backend se ha utilizado Laravel 5.2 y su ORM Eloquent. Para el look and feel he usado materialize.
