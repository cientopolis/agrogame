# Extensión que agrega gamificación a una wiki

## Instalación en la wiki

1. Clonar el repositorio en `wiki/extensions/`

2. Cargar la extensión en el archivo `LocalSettings.php` de la wiki: 

```php
wfLoadExtension('MyMWExtension');
```

3. Actualizar wiki para agregar los cambios a la base de datos:

```bash
$: php wiki/maintenance/update.php
```

## Continuar desarrollo de la extensión

Para continuar el desarrollo de la extensión es necesario configurar la carpeta de la extensión dentro de la wiki como un repositorio.

1. Iniciar un repositorio git vacío en `wiki/extensions/`
```git
git init
```

2. Agregar el repositorio como origen:
```git
git remote add -f origin https://github.com/cientopolis/agrogame.git
```

3. configurar **sparse-checkout**:
```git
git config core.sparsecheckout true
```
Creamos un archivo `sparse-checkout` en .git/info/

4. `git pull origin master`
