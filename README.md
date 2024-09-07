# Haber API

Bu proje, Laravel kullanarak basit bir haber API'si oluşturmaktadır. API, haber ekleme, güncelleme, silme, görüntüleme ve arama işlevlerini destekler. Ayrıca, Bearer token doğrulaması ve IP adresi tabanlı istek sınırlandırması gibi güvenlik özelliklerine sahiptir.

## Özellikler

- Haberleri listeleme
- Yeni haber ekleme
- Belirli bir haberi getirme
- Haber güncelleme
- Haber silme
- Haber arama
- Bearer token doğrulaması
- IP adresi tabanlı istek sınırlandırması

## Gereksinimler

- PHP 8.0 veya üzeri
- Laravel 11.x
- MySQL veya MariaDB
- Composer

## Kurulum

Projeyi yerel bilgisayarınıza kurmak için aşağıdaki adımları izleyin:

1. **Projeyi Klonlayın**

   ```bash
   git clone https://github.com/kaanboyacii/news-api.git
    ```
2. Proje Dizini İçine Girin

   ```bash
   cd news-api
    ```
3. Bağımlılıkları Yükleyin

   ```bash
   composer install
    ```
4. Çevresel Değişkenleri Kopyalayın (Proje dizininde .env dosyasını oluşturun ve .env.example dosyasının bir kopyasını yapın:)

   ```bash
   cp .env.example .env
   ```
5. Çevresel Değişkenleri Düzenleyin (.env dosyasını açın ve veritabanı ayarlarınızı ve diğer gerekli ayarları yapılandırın.)
   
6. Veritabanı şemalarını oluşturun

   ```bash
   php artisan migrate
    ```
7. Otomatik verileri veritabanında oluşturun

   ```bash
   php artisan db:seed
    ```
8. Uygulama Anahtarını Oluşturun

   ```bash
   php artisan key:generate
    ```
9. Geliştirme Sunucusunu Başlatın

   ```bash
   php artisan serve
    ```

API Kullanımı
Haberleri Listeleme

Yöntem: GET
URL: /news
Yeni Haber Ekleme

Yöntem: POST
URL: /news
Gerekli Parametreler:

    title (string): Başlık
    content (string): İçerik
    image (file, optional): Görsel (webp formatında)

Belirli Bir Haberi Getirme

Yöntem: GET
URL: /news/{id}
URL Parametresi:

    id (integer): Haber ID'si

Haber Güncelleme

Yöntem: PUT
URL: /news/{id}
URL Parametresi:

    id (integer): Haber ID'si
    Gerekli Parametreler:
    title (string): Başlık
    content (string): İçerik
    image (file, optional): Görsel (webp formatında)

Haber Silme

Yöntem: DELETE
URL: /news/{id}
URL Parametresi:

    id (integer): Haber ID'si

Haber Arama

Yöntem: GET
URL: /search
Sorgu Parametreleri:

    title (string, optional): Başlık arama terimi
    content (string, optional): İçerik arama terimi

Güvenlik

    Bearer token ile kimlik doğrulama yapılmaktadır.
    IP adresi bazlı istek sınırlandırması ve engelleme yapılmaktadır.
