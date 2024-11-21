Langkah 1 – Menginstal Server Web Nginx
Untuk menampilkan halaman web bagi pengunjung situs Anda, Anda akan menggunakan Nginx, server web yang modern dan efisien.

Semua perangkat lunak yang digunakan dalam prosedur ini akan berasal dari repositori paket bawaan Ubuntu. Ini berarti Anda akan menggunakan aptperangkat manajemen paket untuk menyelesaikan instalasi yang diperlukan.

Karena ini adalah pertama kalinya Anda menggunakan aptsesi ini, mulailah dengan memperbarui indeks paket server Anda:

sudo apt update
Berikutnya, instal server:

sudo apt install nginx
Pada Ubuntu 18.04, Nginx dikonfigurasi untuk mulai berjalan setelah instalasi.

Jika Anda ufwmenjalankan firewall, seperti yang dijelaskan dalam panduan pengaturan awal, Anda perlu mengizinkan koneksi ke Nginx. Nginx mendaftarkan dirinya sendiri ufwsetelah instalasi, jadi prosedurnya cukup mudah.

Sebaiknya Anda mengaktifkan profil yang paling ketat yang masih mengizinkan lalu lintas yang Anda inginkan. Karena Anda belum mengonfigurasi SSL untuk server Anda dalam panduan ini, Anda hanya perlu mengizinkan lalu lintas pada port 80.

Aktifkan ini dengan mengetik yang berikut ini:

sudo ufw allow 'Nginx HTTP'
Anda dapat memverifikasi perubahan dengan memeriksa status:

sudo ufw status
Output perintah ini akan menunjukkan bahwa lalu lintas HTTP diizinkan:

Output
Status: active

To                         Action      From
--                         ------      ----
OpenSSH                    ALLOW       Anywhere
Nginx HTTP                 ALLOW       Anywhere
OpenSSH (v6)               ALLOW       Anywhere (v6)
Nginx HTTP (v6)            ALLOW       Anywhere (v6)
Dengan menambahkan aturan firewall baru, Anda dapat menguji apakah server aktif dan berjalan dengan mengakses nama domain atau alamat IP publik server di peramban web Anda.

Jika Anda tidak memiliki nama domain yang diarahkan ke server Anda dan Anda tidak mengetahui alamat IP publik server Anda, Anda dapat menemukannya dengan menjalankan perintah berikut:

ip addr show eth0 | grep inet | awk '{ print $2; }' | sed 's/\/.*$//'
Ini akan mencetak beberapa alamat IP. Anda dapat mencoba masing-masing alamat di peramban web Anda.

Sebagai alternatif, Anda dapat memeriksa alamat IP mana yang dapat diakses, seperti yang dilihat dari lokasi lain di internet:

curl -4 icanhazip.com
Ketik alamat yang Anda terima di peramban web dan Anda akan dibawa ke halaman arahan default Nginx:

http://server_domain_or_IP
Halaman bawaan Nginx

Jika Anda menerima halaman web yang menyatakan ”Selamat datang di nginx” maka Anda telah berhasil menginstal Nginx.

Langkah 2 – Menginstal MySQL untuk Mengelola Data Situs
Sekarang setelah Anda memiliki server web, Anda perlu menginstal MySQL (sistem manajemen basis data) untuk menyimpan dan mengelola data untuk situs Anda.

Instal MySQL dengan mengetik perintah berikut:

sudo apt install mysql-server
Perangkat lunak basis data MySQL sekarang terinstal, tetapi konfigurasinya belum selesai.

Untuk mengamankan instalasi, MySQL dilengkapi dengan skrip yang akan menanyakan apakah Anda ingin mengubah beberapa pengaturan default yang tidak aman. Jalankan skrip dengan mengetikkan perintah berikut:

sudo mysql_secure_installation
Skrip ini akan menanyakan apakah Anda ingin mengonfigurasi VALIDATE PASSWORD PLUGIN.

Peringatan: Mengaktifkan fitur ini merupakan keputusan yang harus diambil. Jika diaktifkan, kata sandi yang tidak sesuai dengan kriteria yang ditentukan akan ditolak oleh MySQL dengan kesalahan. Hal ini akan menimbulkan masalah jika Anda menggunakan kata sandi yang lemah bersamaan dengan perangkat lunak yang secara otomatis mengonfigurasi kredensial pengguna MySQL, seperti paket Ubuntu untuk phpMyAdmin. Validasi dapat dinonaktifkan, tetapi Anda harus selalu menggunakan kata sandi yang kuat dan unik untuk kredensial basis data.

Jawab Yya, atau jawaban lainnya untuk melanjutkan tanpa mengaktifkan.

VALIDATE PASSWORD PLUGIN can be used to test passwords
and improve security. It checks the strength of password
and allows the users to set only those passwords which are
secure enough. Would you like to setup VALIDATE PASSWORD plugin?

Press y|Y for Yes, any other key for No:
Jika Anda telah mengaktifkan validasi, skrip tersebut juga akan meminta Anda untuk memilih tingkat validasi kata sandi. Perlu diingat bahwa jika Anda memasukkan 2 – untuk tingkat yang terkuat – Anda akan menerima kesalahan saat mencoba mengatur kata sandi yang tidak mengandung angka, huruf besar dan kecil, serta karakter khusus, atau yang didasarkan pada kata-kata umum dalam kamus.

There are three levels of password validation policy:

LOW    Length >= 8
MEDIUM Length >= 8, numeric, mixed case, and special characters
STRONG Length >= 8, numeric, mixed case, special characters and dictionary file

Please enter 0 = LOW, 1 = MEDIUM and 2 = STRONG: 1
Berikutnya, Anda akan diminta untuk mengirimkan dan mengonfirmasi kata sandi root:

Please set the password for root here.

New password:

Re-enter new password:
Untuk pertanyaan lainnya, Anda harus menekan Ydan memencet ENTERtombol pada setiap perintah. Ini akan menghapus beberapa pengguna anonim dan basis data pengujian, menonaktifkan login root jarak jauh, dan memuat aturan baru ini sehingga MySQL segera mematuhi perubahan yang telah kita buat.

Perhatikan bahwa dalam sistem Ubuntu yang menjalankan MySQL 5.7 (dan versi yang lebih baru), pengguna MySQL root diatur untuk mengautentikasi menggunakan auth_socketplugin secara default, bukan dengan kata sandi. Hal ini memungkinkan keamanan dan kegunaan yang lebih baik dalam banyak kasus, tetapi juga dapat mempersulit keadaan saat Anda perlu mengizinkan program eksternal (misalnya, phpMyAdmin) untuk mengakses pengguna.

Jika penggunaan auth_socketplugin untuk mengakses MySQL sesuai dengan alur kerja Anda, Anda dapat melanjutkan ke Langkah 3. Namun, jika Anda lebih suka menggunakan kata sandi saat menghubungkan ke MySQL sebagai root , Anda perlu mengganti metode autentikasinya dari auth_socketke mysql_native_password. Untuk melakukannya, buka prompt MySQL dari terminal Anda:

sudo mysql
Berikutnya, periksa metode autentikasi yang digunakan setiap akun pengguna MySQL Anda dengan perintah berikut:

SELECT user,authentication_string,plugin,host FROM mysql.user;
Output
+------------------+-------------------------------------------+-----------------------+-----------+
| user             | authentication_string                     | plugin                | host      |
+------------------+-------------------------------------------+-----------------------+-----------+
| root             |                                           | auth_socket           | localhost |
| mysql.session    | *THISISNOTAVALIDPASSWORDTHATCANBEUSEDHERE | mysql_native_password | localhost |
| mysql.sys        | *THISISNOTAVALIDPASSWORDTHATCANBEUSEDHERE | mysql_native_password | localhost |
| debian-sys-maint | *CC744277A401A7D25BE1CA89AFF17BF607F876FF | mysql_native_password | localhost |
+------------------+-------------------------------------------+-----------------------+-----------+
4 rows in set (0.00 sec)
Contoh ini menunjukkan bahwa pengguna root benar-benar melakukan autentikasi menggunakan auth_socketplugin. Untuk mengonfigurasi akun root agar diautentikasi dengan kata sandi, jalankan ALTER USERperintah berikut. Pastikan untuk mengubah passwordkata sandi yang kuat sesuai pilihan Anda:

ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'password';
Kemudian, jalankan FLUSH PRIVILEGESuntuk memberi tahu server agar memuat ulang tabel hibah dan menerapkan perubahan baru Anda:

FLUSH PRIVILEGES;
Periksa kembali metode autentikasi yang digunakan oleh setiap pengguna Anda untuk mengonfirmasi bahwa root tidak lagi melakukan autentikasi menggunakan auth_socketplugin:

SELECT user,authentication_string,plugin,host FROM mysql.user;
Output
+------------------+-------------------------------------------+-----------------------+-----------+
| user             | authentication_string                     | plugin                | host      |
+------------------+-------------------------------------------+-----------------------+-----------+
| root             | *3636DACC8616D997782ADD0839F92C1571D6D78F | mysql_native_password | localhost |
| mysql.session    | *THISISNOTAVALIDPASSWORDTHATCANBEUSEDHERE | mysql_native_password | localhost |
| mysql.sys        | *THISISNOTAVALIDPASSWORDTHATCANBEUSEDHERE | mysql_native_password | localhost |
| debian-sys-maint | *CC744277A401A7D25BE1CA89AFF17BF607F876FF | mysql_native_password | localhost |
+------------------+-------------------------------------------+-----------------------+-----------+
4 rows in set (0.00 sec)
Contoh keluaran ini menunjukkan bahwa pengguna MySQL root sekarang mengautentikasi menggunakan kata sandi. Setelah Anda mengonfirmasi hal ini di server Anda sendiri, Anda dapat keluar dari shell MySQL:

exit
Catatan : Setelah mengonfigurasi pengguna MySQL root untuk mengautentikasi dengan kata sandi, Anda tidak akan dapat lagi mengakses MySQL dengan sudo mysqlperintah yang digunakan sebelumnya. Sebagai gantinya, Anda harus menjalankan perintah berikut:

mysql -u root -p
Setelah memasukkan kata sandi yang Anda atur, Anda akan menerima perintah MySQL.

Pada titik ini, sistem basis data Anda telah disiapkan dan Anda dapat melanjutkan ke instalasi PHP.

Langkah 3 – Menginstal PHP dan Mengonfigurasi Nginx untuk Menggunakan Prosesor PHP
Nginx kini telah terinstal untuk melayani halaman Anda dan MySQL telah terinstal untuk menyimpan dan mengelola data Anda. Namun, Anda masih belum memiliki apa pun yang dapat menghasilkan konten dinamis. Di sinilah PHP berperan.

Karena Nginx tidak mengandung pemrosesan PHP asli seperti beberapa server web lainnya, Anda perlu menginstal php-fpm, yang merupakan singkatan dari “fastCGI process manager”. Setelah itu, Anda akan memberi tahu Nginx untuk meneruskan permintaan PHP ke perangkat lunak ini untuk diproses.

Catatan : Bergantung pada penyedia cloud Anda, Anda mungkin perlu menambahkan repositori Ubuntu universe, yang mencakup perangkat lunak gratis dan sumber terbuka yang dikelola oleh komunitas Ubuntu, sebelum menginstal php-fpmpaket tersebut. Anda dapat melakukannya dengan mengetik perintah berikut:

sudo add-apt-repository universe
Instal php-fpmmodul tersebut bersama dengan paket pembantu tambahan, php-mysql, yang akan memungkinkan PHP untuk berkomunikasi dengan backend basis data Anda. Instalasi tersebut akan mengambil file inti PHP yang diperlukan. Lakukan ini dengan mengetik perintah berikut:

sudo apt install php-fpm php-mysql
Bahkan dengan semua komponen tumpukan LEMP yang diperlukan sudah terpasang, Anda masih perlu membuat beberapa perubahan konfigurasi untuk memberi tahu Nginx agar menggunakan prosesor PHP untuk konten dinamis.

Hal ini dilakukan pada level blok server (blok server mirip dengan host virtual Apache). Untuk melakukannya, buat berkas konfigurasi blok server baru menggunakan editor teks pilihan Anda di dalam /etc/nginx/sites-available/direktori. Dalam contoh ini, kita akan menggunakan nanodan berkas konfigurasi blok server baru akan bertuliskan your_domain, jadi Anda dapat menggantinya dengan informasi Anda sendiri:

sudo nano /etc/nginx/sites-available/your_domain
Dengan membuat berkas konfigurasi blok server baru, alih-alih mengedit berkas konfigurasi default, Anda akan dapat memulihkan konfigurasi default jika diperlukan.

Tambahkan konten berikut, yang diambil dan sedikit dimodifikasi dari berkas konfigurasi blok server default, ke berkas konfigurasi blok server baru Anda:

/etc/nginx/situs-tersedia/domain_anda
server {
        listen 80;
        root /var/www/html;
        index index.php index.html index.htm index.nginx-debian.html;
        server_name your_domain;

        location / {
                try_files $uri $uri/ =404;
        }

        location ~ \.php$ {
                include snippets/fastcgi-php.conf;
                fastcgi_pass unix:/var/run/php/php7.2-fpm.sock;
        }

        location ~ /\.ht {
                deny all;
        }
}
Berikut ini adalah apa yang dilakukan oleh masing-masing arahan dan blok lokasi:

listen— Menentukan port yang akan didengarkan Nginx. Dalam kasus ini, ia akan mendengarkan pada port 80, port default untuk HTTP.
root— Menentukan akar dokumen tempat file yang disajikan oleh situs web disimpan.
index— Mengonfigurasi Nginx untuk memprioritaskan penyajian file yang diberi nama index.phpsaat file indeks diminta jika tersedia.
server_name— Menentukan blok server mana yang harus digunakan untuk permintaan tertentu ke server Anda. Arahkan perintah ini ke nama domain atau alamat IP publik server Anda.
location /— Blok lokasi pertama mencakup try_filesarahan, yang memeriksa keberadaan file yang cocok dengan permintaan URI. Jika Nginx tidak dapat menemukan file yang sesuai, maka akan muncul kesalahan 404.
location ~ \.php$— Blok lokasi ini menangani pemrosesan PHP sebenarnya dengan mengarahkan Nginx ke fastcgi-php.confberkas konfigurasi dan php7.2-fpm.sockberkas yang mendeklarasikan soket mana yang dikaitkan php-fpm.
location ~ /\.ht— Blok lokasi terakhir menangani .htaccessberkas yang tidak diproses oleh Nginx. Dengan menambahkan deny allperintah tersebut, jika ada .htaccessberkas yang berhasil masuk ke root dokumen, berkas tersebut tidak akan ditampilkan kepada pengunjung.
Setelah menambahkan konten ini, simpan dan tutup berkas. Jika Anda menggunakan nano, Anda dapat melakukannya dengan menekan CTRL + Xlalu Ydan ENTER. Aktifkan blok server baru Anda dengan membuat tautan simbolik dari berkas konfigurasi blok server baru Anda (di /etc/nginx/sites-available/direktori) ke /etc/nginx/sites-enabled/direktori:

sudo ln -s /etc/nginx/sites-available/your_domain /etc/nginx/sites-enabled/
Kemudian, hapus tautan file konfigurasi default dari /sites-enabled/direktori:

sudo unlink /etc/nginx/sites-enabled/default
Catatan : Jika Anda perlu mengembalikan konfigurasi default, Anda dapat melakukannya dengan membuat ulang tautan simbolik menggunakan perintah seperti berikut:

sudo ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/
Uji file konfigurasi baru Anda untuk kesalahan sintaksis:

sudo nginx -t
Jika ada kesalahan yang dilaporkan, kembali dan periksa ulang berkas Anda sebelum melanjutkan.

Jika Anda sudah siap, muat ulang Nginx untuk membuat perubahan yang diperlukan:

sudo systemctl reload nginx
Ini mengakhiri instalasi dan konfigurasi tumpukan LEMP Anda. Namun, sebaiknya pastikan bahwa semua komponen dapat berkomunikasi satu sama lain.

Langkah 4 – Membuat File PHP untuk Menguji Konfigurasi
Tumpukan LEMP Anda sekarang sudah sepenuhnya siap. Anda dapat mengujinya untuk memvalidasi bahwa Nginx dapat menyerahkan .phpberkas dengan benar ke pemroses PHP.

Untuk melakukan ini, gunakan editor teks pilihan Anda untuk membuat file PHP uji yang disebut info.phpdi root dokumen Anda:

sudo nano /var/www/html/info.php
Masukkan baris berikut ke dalam berkas baru. Ini adalah kode PHP yang valid yang akan mengembalikan informasi tentang server Anda:

/var/www/html/info.php
<?php
phpinfo();
Setelah selesai, simpan dan tutup berkas.

Sekarang, Anda dapat mengunjungi halaman ini di peramban web Anda dengan mengunjungi nama domain atau alamat IP publik server Anda diikuti dengan /info.php:

http://your_server_domain_or_IP/info.php
Peramban Anda akan memuat halaman web seperti berikut yang telah dihasilkan oleh PHP dengan informasi tentang server Anda:

Info halaman PHP

Jika halaman Anda seperti yang dijelaskan, Anda telah berhasil menyiapkan pemrosesan PHP dengan Nginx.

Setelah memverifikasi bahwa Nginx menyajikan halaman dengan benar, sebaiknya hapus file yang Anda buat karena file tersebut dapat memberikan petunjuk kepada pengguna yang tidak berwenang tentang konfigurasi Anda yang dapat membantu mereka mencoba membobolnya. Anda selalu dapat membuat ulang file ini jika Anda membutuhkannya nanti.

Untuk saat ini, hapus file:

sudo rm /var/www/html/info.php
Dengan itu, Anda memiliki tumpukan LEMP yang terkonfigurasi sepenuhnya dan berfungsi pada server Ubuntu 18.04 Anda.
