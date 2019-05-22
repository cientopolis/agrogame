# Extensión que agrega gamificación a una wiki

## Instalación en la wiki

**1.** Clonar el repositorio en la carpeta `extensions` de la wiki con el nombre MyMWExtension

```git
git clone https://github.com/cientopolis/agrogame.git MyMWExtension
```

**2.** Cargar la extensión en el archivo `LocalSettings.php` de la wiki: 

```php
wfLoadExtension('MyMWExtension');
```

**3.** Actualizar wiki para agregar los cambios a la base de datos:

```bash
$: php wiki/maintenance/update.php
```
**4.** Instalar dependencia: [Extensión: Create User Page](https://www.mediawiki.org/wiki/Extension:Create_User_Page)

Descargar la extensión, agregarla y configurarla en el archivo `LocalSettings.php` de la wiki:

```php
wfLoadExtension( 'CreateUserPage' );
$wgCreateUserPage_PageContent = "{{#infoGamUser: {{PAGENAME}} }}";
```

## Servidor Web Arduino

La extension cuenta con la posiblidad de enviar las acciones realizadas dentro de la wiki a un servidor web. 

Para configurar el servidor web:

 - Activar en la extension `$wgArduinoWebServerOn = true`.
 - Agregar la IP del servidor `$wgArduinoWebServerIP = 'dirección ip'`.
