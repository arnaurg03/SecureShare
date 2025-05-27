
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
- Selecciona "Adaptador puente" o "Red interna" según tu entorno.

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

Inicia sesión en MySQL:

```bash
sudo mysql
```

Copia y pega las siguientes instrucciones para crear la base de datos y el usuario:

```sql
CREATE DATABASE db_users;
CREATE USER 'root'@'localhost' IDENTIFIED BY 'admin';
GRANT ALL PRIVILEGES ON db.* TO 'root'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Luego importa el esquema de base de datos:

```bash
mysql -u secureuser -p securefiles < /var/www/proyecto/database/schema.sql
```

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


