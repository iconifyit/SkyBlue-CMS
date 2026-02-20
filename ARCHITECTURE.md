# SkyBlue CMS Architecture Document

## Core Design Patterns

### 1. **MVC Architecture**
- **Model**: Transfer Objects (Beans) - e.g., Page, User, Configuration
- **DAO Layer**: Data Access Objects handle persistence
  - **Base DAO**: Extends PDO, provides base CRUD operations
  - **XmlDAO**: For XML file-based storage
  - **SqliteDAO**: For SQLite database storage (extends DAO, uses PDO)
- **View**: Template-based rendering with token replacement
- **Controller**: Handles business logic and coordinates Model/View

### 2. **Helper Classes Pattern**
Helper classes provide **utility/factory methods** for specific managers. They serve THREE purposes:
1. **Lazy loading** of DAO, Controller, and Bean classes
2. **Factory pattern** for creating DAO instances (using static variable caching)
3. **Convenience methods** for common operations

**Critical Pattern**: Helper methods use `static $variable` for caching DAO instances:
```php
function getDao($refresh=false) {
    static $Dao;  // Cached across calls
    if (! is_object($Dao) || $refresh) {
        if (! class_exists('SomeDAO')) {
            SomeHelper::initialize();
        }
        $Dao = new SomeDAO();
    }
    return $Dao;
}
```

**IMPORTANT**: Helpers are called STATICALLY throughout the codebase (`Helper::method()`), but methods may NOT be declared `static`. This is the PHP 8 incompatibility!

### 3. **Data Access Layer**

#### Base DAO (extends PDO)
- Abstract layer over PDO
- Child classes: SqliteDAO, XmlDAO
- Methods: index(), getItem(), insert(), update(), delete()

#### SqliteDAO Pattern
- Constructor requires DB_HOST, DB_USER, DB_PASS constants
- These are defined in Config::load()
- Uses PDO with SQLite DSN: `"sqlite:" . DB_HOST`

####  XmlDAO Pattern
- Stores data in XML files under `/custom/data/xml/`
- Uses Core->xmlHandler for XML parsing
- No database constants needed

### 4. **Initialization Flow**

```
index.php
  → define constants
  → require base.php
    → loads all core classes
    → loads MVC.php
      → loads Controller, View, DAO base classes
      → loads SqliteDAO from /custom/daos/
  → new Core()
    → LoadConstants() - defines SB_MANAGERS_DIR, etc.
    → LoadHelperClasses()
      → Loads PluginHelper
      → Loads PageHelper
  → Config::load()
    → ConfigurationHelper::getConfiguration()
      → ConfigurationHelper::getDao()
        → ConfigurationHelper::initialize()
          → loads ConfigurationDAO (extends SqliteDAO)
          → loads Configuration bean
          → loads ConfigurationController (extends Controller)
  → $Core->GetActiveSkin()
    → SkinHelper::getActiveSkin()
      → SkinHelper::getDao()
        → new SkinDAO() (extends SqliteDAO - needs DB constants)
```

### 5. **Singleton Pattern**
Used for shared instances: Session, LanguageHelper, RequestObject, Authenticate, Authorize

```php
$Session = Singleton::getInstance('Session');
```

### 6. **Event System** (Publisher/Observer)
- Core extends Publisher
- Event::register(), Event::trigger()
- Events: beforeInitPage, afterShowPage, etc.

## PHP 8 Compatibility Issues

### The Core Problem
**Helper classes have methods called statically but NOT declared static.**

In PHP 5.x, this worked:
```php
class Helper {
    function getDao() { /*...*/ }  // NOT static
}
Helper::getDao();  // Called statically - works in PHP 5
```

In PHP 8.2, this generates **"Non-static method cannot be called statically"** fatal error OR causes infinite loops/hangs.

### Why Helpers Use Static Calls
1. **Convenience**: `ConfigurationHelper::getConfiguration()` is simpler than `$helper = new ConfigurationHelper(); $helper->getConfiguration();`
2. **Caching**: Using `static $Dao` inside methods provides cross-call caching
3. **Factory Pattern**: Helpers act as factories, not stateful objects

### Current Confirmed Issues
From the codebase analysis:
- ConfigurationHelper - methods called statically but not declared static
- SkinHelper - methods called statically but not declared static
- CheckoutsHelper - methods called statically but not declared static
- FileSystem - methods called statically but not declared static
- PageHelper - methods called statically but not declared static (partially fixed)
- Event, Timer, Loader - already fixed

### The Solution Strategy
Make Helper methods static where they're called statically:
1. Identify which methods are called with `::`
2. Add `static` keyword to method declarations
3. Test that `static $var` caching still works (it does)

## Current Blocker

**ConfigurationController loading hangs the system.**

From debug logs, execution stops when requiring ConfigurationController.php. The file loads fine in isolation but hangs in the full framework context. This suggests:

1. **Circular dependency**: Something ConfigurationController needs tries to load ConfigurationController again
2. **Missing dependency**: Controller.php loads CheckoutsHelper which may have issues
3. **PHP 8 incompatibility**: Some code in the loading chain causes infinite recursion

**Next steps**: Need to trace EXACTLY what happens when ConfigurationController.php is required in the actual runtime environment, not test scripts.

## Directory Structure

```
/src
  /skyblue          - Core framework
    /includes       - Core classes (Core, Config, Event, etc.)
      /mvc          - MVC base classes (Controller, View, DAO, Loader)
      /auth         - Authentication/Authorization
      /utils        - Utility classes
    /managers       - Business logic modules
      /configuration
      /page
      /skin
      /users
      /menus
      (etc.)
    /config         - Configuration files and constants
    /plugins        - System plugins

  /custom           - User/site-specific overrides
    /daos           - Custom DAO implementations
    /plugins        - User plugins
    /data           - Data storage
      /xml          - XML data files
      /data.sqlite  - SQLite database

  /webroot          - Public web files
    index.php       - Frontend entry point
```

## Key Files

- `src/webroot/index.php` - Frontend entry point
- `src/skyblue/base.php` - Loads all core framework files
- `src/skyblue/includes/Core.php` - Core framework class
- `src/skyblue/includes/Config.php` - Configuration loader
- `src/skyblue/includes/mvc/MVC.php` - MVC framework loader
- `src/skyblue/includes/mvc/Controller.php` - Base controller
- `src/skyblue/includes/mvc/DAO.php` - Base DAO (extends PDO)
- `src/custom/daos/SqliteDAO.php` - SQLite DAO implementation
