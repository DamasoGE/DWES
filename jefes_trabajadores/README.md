 # ENUNCIADO

Realizar una aplicación siguiendo el modelo vista controlador y PHP en la que gestionamos un almacén de una empresa:

(1p) Habrá 2 tipos de usuarios. Trabajadores y jefes.

(3p) Los jefes pueden dar de alta y modificar los objetos que se almacenan. De estos objetos queremos saber su ID, descripción, cantidad, ubicación así como las entradas y salidas del almacén.

La ubicación se compone de pasillo, estantería, estante.

Las entradas y salidas del almacén tendrán que guardar cuantos objetos salen o entran y cuándo sucede.

(4p) Los trabajadores pueden generar entradas y salidas de almacén. No se pueden sacar si no hay suficientes objetos de ese tipo en el almacén. Pueden entrar todos los que se quieran.

(2p) La vista principal es un listado de los objetos, ordenados por su ubicación donde se indique cuantos objetos hay de ese tipo y cuantos hay disponibles.

ENDPOINTS:

"/" : listado de objetos (show)
"/item/new": nuevo objeto (ADMIN)
"/item/{id}: cambiar caracteristicas objeto (ADMIN)
"/changestock": lista de entradas/salidas
"/changestock/new": nueva entrada/salida

