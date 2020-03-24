# PCBasket

### Inicialización del proyecto
Levantar contenedores
```
make run
```
Una vez levantados ejecutar el composer del contenedor
```
make composer-install
```

### Comandos
##### Crear Jugador
```
make create-player
```
##### Borrar Jugador
```
make delete-player
```
##### Listar Jugadores
```
make list-players
```
##### Calcular alineacion
```
make optimize-tactic
```
##### Listar eventos
```
make list-events
```

### Tests

Se pueden lanzar con el siguiente comando:
````
  make php-unit-all
