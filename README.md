## Installation

Require this package with composer. It is recommended to only require the package for development.

```shell
composer require toanld/laravel-module-inertiajs-vue
```

### Syntax

```php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Toanld\DebugToSql\DebugToSQL;

class Test extends Model
{
    use DebugToSQL;
}
```

