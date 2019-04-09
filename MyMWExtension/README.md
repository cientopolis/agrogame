# Extensión que agrega gamificación a una wiki

1. Clonar el repositorio en wiki/extensions/

2. Cargar extensión en LocalSettings.php: 

```php
wfLoadExtension('MyMWExtension');
```

3. Actualizar wiki para agregar los cambios a la base de datos:

```bash
$: php wiki/maintenance/update.php
```
