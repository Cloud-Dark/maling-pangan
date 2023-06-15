# Run the script php

sebelum buat database harus di aktifkan extension pgsql di `php.ini`.

```
extension=pdo_pgsql
extension=pgsql
```

Buat database **badanpangan** di postgres database

lalu run script `create-tabel.php` dan script crawling web badan pangan

```
php create-table.php "01-01-2023"
```

---

jika sudah punya tabel yang diperlukan maka cukup jalani script `fe-badanpangan.php`

```
php fe-badanpangan.php "01-01-2023"
```

tanggal tersebut yaitu mulai crawling dari tanggal **01-01-2023** hingga hari ini

---

buat cronjob di server untuk script `api-badanpangan.php` setiap jam 3 siang

```
php api-badanpangan.php
```
