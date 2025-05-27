
# SecureShare

Este proyecto consiste en una plataforma web segura para la compartición de archivos entre usuarios. Ha sido desarrollada como parte del proyecto final del ciclo de **Administración de Sistemas Informáticos en Red (ASIX)**.

## Requisitos previos

Antes de comenzar, asegúrate de tener instalado en tu ordenador:

- [VirtualBox](https://www.virtualbox.org/) o cualquier otro hipervisor compatible.
- Imagen ISO de **Ubuntu Server 22.04 LTS** (u otra versión compatible).
- Conexión a internet en la máquina virtual.

## 1. Crear la máquina virtual Ubuntu Server

1. Abre VirtualBox y crea una nueva máquina virtual.
2. Asigna un nombre (por ejemplo, `secure-server`) y selecciona:
   - Tipo: Linux
   - Versión: Ubuntu (64-bit)
3. Asigna al menos:
   - **2 GB de RAM**
   - **1 CPU**
   - **10 GB de disco duro (dinámico o fijo)**
4. Monta la ISO de Ubuntu Server e inicia la instalación.
5. Durante la instalación:
   - Elige idioma: Español (o Inglés)
   - Nombre del equipo: `secure-server`
   - Crea un usuario y contraseña.
   - **Instala OpenSSH Server** cuando te lo pregunte.
   - No instales servicios adicionales por ahora.
6. Finaliza la instalación y reinicia la máquina.

## 2. Configurar red (modo adaptador puente o red interna)

Para que el servidor pueda ser accedido desde fuera o comunicarse con otras VMs:

- Abre configuración de la VM en VirtualBox.
- Ve a **Red > Adaptador 1**.
- Selecciona "Adaptador puente".

Verifica la IP del servidor con:

```bash
ip a
```

## 3. Conexión por SSH (opcional)

Puedes conectarte a tu servidor vía SSH desde tu equipo host:

```bash
ssh tu_usuario@IP_DEL_SERVIDOR
```

## 4. Actualizar paquetes e instalar dependencias

Una vez dentro del servidor, ejecuta:

```bash
sudo apt update && sudo apt upgrade -y
```

### Instalar Apache, PHP y MySQL:

```bash
sudo apt install apache2 php libapache2-mod-php php-mysql mariadb-server unzip curl git -y
```

### Habilitar servicios:

```bash
sudo systemctl enable apache2
sudo systemctl enable mariadb
```

## 5. Clonar el proyecto

Copia tu carpeta del proyecto a `/var/www/proyecto/`. Si lo haces desde GitHub:

```bash
cd /var/www
sudo git clone https://github.com/arnaurg03/SecureShare.git proyecto
sudo chown -R www-data:www-data /var/www/proyecto
```

## 6. Configurar la base de datos

Inicia sesión en MySQL con el siguiente comando:

```bash
sudo mysql -u root -p
```

Una vez dentro del intérprete de comandos de MySQL, ejecuta los siguientes pasos para crear la base de datos y las tablas necesarias:

### Crear la base de datos

```sql
CREATE DATABASE db_users;
USE db_users;
```

### Crear tabla `departamentos`

```sql
CREATE TABLE departamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);
```

Insertar los departamentos disponibles:

```sql
INSERT INTO departamentos (nombre) VALUES
('ciberseguridad'),
('marketing'),
('integracion social'),
('soporte'),
('administrador');
```

### Crear tabla `usuarios`

```sql
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('usuario', 'admin') DEFAULT 'usuario',
    departamento INT,
    FOREIGN KEY (departamento) REFERENCES departamentos(id)
);
```

Una vez hecho esto, puedes salir del intérprete de MySQL con:

```sql
EXIT;
```

Esto dejará la base de datos lista para usar con el sistema.

## 7. Configurar VirusTotal API

Edita el archivo de configuración para añadir tu clave API de VirusTotal:

```bash
sudo nano /var/www/proyecto/api.txt
```

Ejemplo de contenido:

```php
<?php
define('VT_API_KEY', 'AQUI_TU_API_KEY');
?>
```

Puedes conseguir tu clave gratuita desde: https://www.virustotal.com/gui/join-us

## 8. Comprobar funcionamiento

- Abre tu navegador y accede a: `http://IP_DEL_SERVIDOR`
- Regístrate como nuevo usuario.
- Sube un archivo de prueba.
- Comprueba si se analiza con VirusTotal y se guarda correctamente.
- Accede como administrador para validar usuarios y asignar departamentos.


