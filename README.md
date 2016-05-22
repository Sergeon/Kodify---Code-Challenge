##Code Challenge

Code Challenge for [Dokify] (https://dokify.net)


##Instalación

`composer update`  

`php artisan migrate`

`php artisan db:seed`


##Test

`vendor/bin/mat test tests/matura/test_index.php`

Para modificar vistas o scripts y/o utilizar gulp, es necesario ejecutar:

`npm install`

Es necesario modificar el fichero /.env para indicar el usuario y el password de la base de datos
y la database que se va a utilizar.

La aplicación debe correr sin problemas en una máquina vagrant/homestead.

##discusion
Hay un archivo discusion.md con información sobre la estructura de la aplicación .
