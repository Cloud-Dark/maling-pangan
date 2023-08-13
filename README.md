# Run the script php

Buat database **badanpangan** di postgres database

lalu run script php create tabel dan script crawling web badan pangan

```
php create-table.php "01-01-2023"
```

---

jika sudah punya tabel yang diperlukan maka cukup jalani script crawling dari frontend saja

```
php fe-badanpangan.php "01-01-2023"
```

tanggal tersebut yaitu mulai crawling dari tanggal **01-01-2023** hingga hari ini

---

buat cronjob di server untuk script api setiap jam 3 siang

```
php api-badanpangan.php
```
