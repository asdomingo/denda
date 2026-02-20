# K.O. BOXING - Guía de Instalación en AWS

## Pasos para hacer funcionar en AWS

### 1. **Verificar requisitos del servidor**
```
http://tu-dominio.com/diagnostico.php
```
Este archivo te dirá qué está faltando.

### 2. **Inicializar la Base de Datos**
```
http://tu-dominio.com/init_database.php
```
Esto creará las tablas necesarias automáticamente.

### 3. **Verificar permisos**
La carpeta debe tener permisos de lectura/escritura:
```bash
chmod -R 755 /ruta/a/denda_sqlite
chmod -R 755 /ruta/a/denda_sqlite/produktuak.db
```

### 4. **Configuración Apache (si necesario)**

Crear o editar `.htaccess` en la raíz del proyecto con:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
</IfModule>
```

### 5. **Problemas comunes**

#### Error: "No such file or table"
- Ejecuta `init_database.php`
- Verifica que `produktuak.db` exista en la raíz

#### Error: "Failed to load response" (404)
- Revisa que los archivos .php existan
- Usa `diagnostico.php` para verificar la estructura

#### Error de JSON en carrito
- Es normal si el servidor devuelve HTML error en vez de JSON
- Ejecuta `init_database.php` para que la BD tenga las tablas necesarias

### 6. **Archivos clave**

- `index.php` - Página de inicio
- `diagnostico.php` - Verificar estado del servidor
- `init_database.php` - Crear/inicializar BD
- `produktuak.db` - Base de datos SQLite
- `api/` - Endpoints de AJAX
- `klaseak/` - Clases PHP del sistema

### 7. **Notas sobre AWS**

El código ha sido actualizado para detectar automáticamente si está en:
- **Local (XAMPP Windows)**: Usa rutas con `C:\\xampp\\`
- **AWS (Linux)**: Busca `produktuak.db` en la raíz del proyecto

Todos los archivos de clases (`DB.php`, `*_db.php`) ahora usan esta detección automática.

### 8. **Contacto y soporte**

Si después de seguir estos pasos sigue sin funcionar:
1. Verifica `diagnostico.php`
2. Revisa los logs del servidor (si están disponibles)
3. Asegúrate que PHP >= 7.4
4. Verifica que la extensión `pdo_sqlite` esté habilitada

---
Última actualización: 20 de febrero de 2026
