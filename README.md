# Extensión que agrega gamificación a una wiki

## Instalación en la wiki

**1.** Clonar el repositorio en la carpeta `extensions` de la wiki con el nombre Agrogame

```git
git clone https://github.com/cientopolis/agrogame.git Agrogame
```

**2.** Cargar la extensión en el archivo `LocalSettings.php` de la wiki: 

```php
wfLoadExtension('Agrogame');
```

**3.** Actualizar wiki para agregar los cambios a la base de datos:

```bash
$: php wiki/maintenance/update.php
```
