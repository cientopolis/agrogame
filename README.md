# Extensión que agrega gamificación a una wiki

## Instalación de la extensión Agrogame en la wiki

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

## Instalación de dependencias

**4.** Como dependencia tenemos la extensión RatePage 0.2.0. Clonar el repositorio y checkoutear a la versión 0.2.0 en la carpeta `extensions` de la wiki con el nombre RatePage:

```bash
git clone https://gitlab.com/nonsensopedia/ratepage/commit/a64abc508094dbda2bd4c6b682993153accaa200 RatePage
cd RatePage
git checkout a64abc508094dbda2bd4c6b682993153accaa200
```


**5.** Cargar la extensión en el archivo `LocalSettings.php` de la wiki y configurarla: 

```php
wfLoadExtension( 'RatePage' );
/* El espacio de nombres que no tiene prefijo separado por dos puntos es el 0. Se quitan todas las páginas Special:*, User:*, Template:*, etc. */
$wgRPRatingAllowedNamespaces = [0]; 
/* La página principal se encuentra dentro del namespace 0 pero no la queremos. */
$wgRPRatingPageBlacklist = ["Página_principal"];
/*RatePage no funciona correctamente con el cache de la barra lateral habilitado.*/
$wgEnableSidebarCache = false;
```

**6.** Actualizar wiki para agregar los cambios a la base de datos:

```bash
$: php wiki/maintenance/update.php
```

