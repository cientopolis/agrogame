# Extensión que agrega gamificación a una wiki

Cargar extensión en LocalSettings.php: 

```php
wfLoadExtension('MyMWExtension');
```

Actualizar wiki para agregar los cambios a la base de datos:

```bash
$: php wiki/maintenance/update.php
```
