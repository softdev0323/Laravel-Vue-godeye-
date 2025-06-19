# Laravel + Vue Monolith (Docker + Azure Deployment)

This project is a Laravel + Vue monolithic application designed to run in a Docker container for both local development and production deployments (e.g., Azure App Service via Azure Container Registry).

---

## 📦 Stack Overview

-   **Backend**: Laravel (PHP 8.2)
-   **Frontend**: Vue 3 with Vite
-   **Database**: SQLite
-   **Web Server**: Nginx + Supervisor (single container)

---

## 🐳 Local Development (Docker)

### 1. Set up the local environment

```bash
cp .env.local .env
```

### 2. Build the Docker image

```bash
sudo docker build -t godeyeacr.azurecr.io/laravel-app:latest .
```

### 3. Run the Docker container

```bash
sudo docker run -p 80:80 godeyeacr.azurecr.io/laravel-app:latest
```

### 4. Access the app

Go to: [http://localhost](http://localhost) or [http://your-server-ip](http://your-server-ip)

---

## ☁️ Deploy to Azure Web App (Docker + ACR)

### 🧾 1. Prepare production environment

```bash
cp .env.prod .env
```

### 🔧 2. Build and push to Azure Container Registry (ACR)

```bash
sudo docker build -t godeyeacr.azurecr.io/laravel-app:latest .
sudo docker push godeyeacr.azurecr.io/laravel-app:latest
```

Replace `godeyeacr` and `laravel-app` with your actual registry and image names.

---

## 🧭 3. Azure Web App Configuration (Web App + Container)

1. **Go to Azure Portal**
2. **Create a new Web App**

    - **Publish**: Docker Container
    - **Operating System**: Linux
    - **Plan**: Basic or higher (for custom container support)

3. **Docker Settings**

    - **Image Source**: Azure Container Registry
    - **Registry**: Select your ACR
    - **Image and Tag**:

        - Image: `laravel-app`
        - Tag: `latest`

4. **App Settings (Optional but recommended)**

    - Go to `Configuration` → `Application settings`
    - Add: `APP_ENV=production`, `APP_DEBUG=false`, etc.

5. **Access**

    - After deployment, browse to the Azure Web App domain (e.g. `https://<your-app>.azurewebsites.net`)

---

## 🔐 Fix for HTTPS Mixed Content

In `AppServiceProvider.php`:

```php
use Illuminate\Support\Facades\URL;

public function boot()
{
    if (env('APP_ENV') === 'production') {
        URL::forceScheme('https');
    }
}
```

This forces all `asset()`, `url()`, and `route()` calls to use `https://`.

---

## 📂 Environment Files

-   `.env.local` — used for local Docker runs
-   `.env.prod` — used for production (Azure)

Manually copy the correct one before build:

```bash
cp .env.local .env      # For local dev
cp .env.prod .env       # For Azure deploy
```

---

## 📋 Useful Laravel Commands

These are already run during Docker image build, but here’s the reference:

```bash
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```

---

## 💡 Workflow Summary

| Step       | Local                     | Azure Deployment                     |
| ---------- | ------------------------- | ------------------------------------ |
| 1. Set ENV | `.env.local` → `.env`     | `.env.prod` → `.env`                 |
| 2. Build   | `docker build ...`        | `docker build ...`                   |
| 3. Run     | `docker run -p 80:80 ...` | Push to ACR, Azure auto pulls it     |
| 4. Access  | `http://localhost`        | `https://your-app.azurewebsites.net` |

---

## 🛠 Dockerfile Overview

Your image includes PHP, Node, Composer, Laravel artisan steps, Nginx, and Supervisor, all ready to run a single-container production-ready Laravel app.

---

## 🧼 Troubleshooting

-   **Assets not loading in Azure**? Check `.env.prod` has correct `APP_URL` (must be HTTPS)
-   **Mixed Content**? Clear browser + CDN cache after HTTPS enforcement
-   **404s for assets?** Ensure Vite assets are built and published into `public/build/`

---

## 🏁 Done!

You can now run, test, build, and deploy your Laravel + Vue app across local and Azure environments with Docker.
