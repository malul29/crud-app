---

### 1. **Persiapkan Lingkungan di Ubuntu**
#### a. **Update dan Upgrade Sistem**
```bash
sudo apt update && sudo apt upgrade -y
```

#### b. **Install Komponen yang Dibutuhkan**
```bash
sudo apt install nginx mysql-server php-fpm php-mysql unzip -y
```

#### c. **Konfigurasi MySQL**
1. Jalankan MySQL:
   ```bash
   sudo systemctl start mysql
   ```
2. Amankan instalasi:
   ```bash
   sudo mysql_secure_installation
   ```
   - Pilih konfigurasi sesuai kebutuhan, misalnya membuat password root untuk MySQL.
3. Masuk ke MySQL:
   ```bash
   sudo mysql -u root -p
   ```
4. Buat database dan user untuk aplikasi CRUD:
   ```sql
   CREATE DATABASE crud_app;
   CREATE USER 'crud_user'@'localhost' IDENTIFIED BY 'password123';
   GRANT ALL PRIVILEGES ON crud_app.* TO 'crud_user'@'localhost';
   FLUSH PRIVILEGES;
   EXIT;
   ```

---

### 2. **Siapkan Aplikasi PHP**
#### a. **Struktur Direktori**
Buat direktori untuk menyimpan file aplikasi:
```bash
sudo mkdir -p /var/www/crud-app
sudo chown -R $USER:$USER /var/www/crud-app
```

#### b. **Contoh Aplikasi CRUD**
1. **Unduh atau buat aplikasi CRUD sederhana.**
   Untuk contoh, gunakan [CRUD Simple di GitHub](https://github.com/) atau buat sendiri. 
2. **Pindahkan file ke direktori proyek:**
   Misalnya, Anda memiliki file aplikasi CRUD dalam zip:
   ```bash
   unzip crud-app.zip -d /var/www/crud-app/
   ```
3. **Sesuaikan koneksi database di file konfigurasi aplikasi (misalnya `db.php`)**:
   ```php
   <?php
   $host = "localhost";
   $dbname = "crud_app";
   $username = "crud_user";
   $password = "password123";

   try {
       $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
       $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   } catch (PDOException $e) {
       echo "Connection failed: " . $e->getMessage();
   }
   ?>
   ```

---

### 3. **Konfigurasi Nginx**
#### a. **Buat Konfigurasi untuk Proyek**
1. Buat file konfigurasi baru:
   ```bash
   sudo nano /etc/nginx/sites-available/crud-app
   ```
2. Isi file dengan konfigurasi berikut:
   ```nginx
   server {
       listen 80;
       server_name your_domain_or_ip;

       root /var/www/crud-app;
       index index.php index.html index.htm;

       location / {
           try_files $uri $uri/ =404;
       }

       location ~ \.php$ {
           include snippets/fastcgi-php.conf;
           fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
           fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
           include fastcgi_params;
       }

       location ~ /\.ht {
           deny all;
       }
   }
   ```
3. Aktifkan konfigurasi:
   ```bash
   sudo ln -s /etc/nginx/sites-available/crud-app /etc/nginx/sites-enabled/
   ```

#### b. **Uji dan Reload Nginx**
```bash
sudo nginx -t
sudo systemctl reload nginx
```

---

### 4. **Tes Aplikasi**
1. Buka browser dan akses:
   ```
   http://your_server_ip/
   ```
2. Pastikan aplikasi CRUD dapat diakses.

---

### 5. **Keamanan Tambahan**
#### a. **Aktifkan Firewall**
Izinkan Nginx:
```bash
sudo ufw allow 'Nginx Full'
```

#### b. **Aktifkan HTTPS (Opsional)**
Jika Anda memiliki domain, gunakan **Certbot** untuk HTTPS:
```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx
```

#### c. **Batasi Akses ke MySQL**
Konfigurasi MySQL agar hanya dapat diakses oleh localhost.

---

Jika ada kesulitan dalam salah satu langkah, beri tahu saya untuk penjelasan lebih detail! 😊
