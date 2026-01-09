# Lost and Found System

## 📱 Client (Android)
Client aplikasi Android disimpan dalam bentuk ZIP.

Cara menjalankan:
1. Extract `Client/LostAndFoundApp.zip`
2. Buka folder hasil extract menggunakan **Android Studio**
3. Tunggu Gradle sync selesai
4. Jalankan aplikasi

## 🌐 Rest API (PHP)
Folder `RestAPI` berisi backend PHP.

Cara menjalankan:
1. Pindahkan folder `RestAPI` ke `htdocs` atau `www`  (XAMPP/Laragon)
2. Import database:
   - `RestAPI/database/db_lost_found.sql`
3. Jalankan di browser:
   http://localhost/lost-found
4. Aplikasi akan otomatis diarahkan ke halaman login.

