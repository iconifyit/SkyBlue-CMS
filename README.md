# SkyBlue CMS

A lightweight PHP content management system, updated for PHP 8.2+.

## Quick Start

1. **Clone the repository:**
   ```bash
   git clone git@github.com:iconifyit/SkyBlue-CMS.git
   cd SkyBlue-CMS
   ```

2. **Start the application:**
   ```bash
   docker compose up
   ```

3. **Access the site:**
   - Frontend: http://localhost:8090
   - Admin Panel: http://localhost:8090/admin.php
   - phpMyAdmin: http://localhost:8091

## Default Admin Credentials

```
Username: admin
Password: admin
```

**Important:** Change these credentials immediately after first login.

## Architecture

SkyBlue CMS uses:
- **MVC Architecture** with Transfer Objects, DAOs, and Controllers
- **SQLite** for data persistence (no external database required)
- **Template-based rendering** with customizable skins
- **Plugin system** for extensibility

See [ARCHITECTURE.md](ARCHITECTURE.md) for detailed technical documentation.

## Directory Structure

```
src/
├── skyblue/        # Core framework
│   ├── includes/   # Core classes (MVC, auth, utils)
│   ├── managers/   # Business modules (page, users, menus, etc.)
│   └── plugins/    # System plugins
├── custom/         # Site-specific customizations
│   ├── daos/       # Custom data access objects
│   ├── plugins/    # User plugins
│   └── data/       # SQLite database and data files
└── webroot/        # Public web root
    ├── skins/      # Theme templates
    ├── resources/  # Static assets (JS, CSS)
    └── media/      # User uploads
```

## Docker Services

| Service    | Port | Description                    |
|------------|------|--------------------------------|
| web        | 8090 | PHP 8.2 + Apache               |
| db         | 3306 | MySQL 8.0 (internal)           |
| redis      | 6380 | Redis 7 cache                  |
| phpmyadmin | 8091 | Database management UI         |

## Configuration

### Site URL

The site URL is **auto-detected** from the incoming request. No configuration needed.

If you need to override the URL (e.g., behind a reverse proxy), create `src/custom/config/site.php`:

```php
<?php
return [
    'site_url' => 'https://example.com/',
];
```

See `src/custom/config/site.php.example` for reference.

## Customization

### Skins/Themes
Place custom skins in `src/webroot/skins/`. Each skin folder should contain:
- `skin.default.php` - Default page template
- `skin.home.php` - Homepage template
- `css/` - Stylesheets
- `images/` - Theme images

### Plugins
Add plugins to `src/custom/plugins/`. Plugins extend functionality via the event system.

## License

GNU General Public License v2 or later. See [COPYING.txt](src/webroot/COPYING.txt) for details.

## Author

Scott Edwin Lewis
