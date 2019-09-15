# Laravel Persistable Constants

Take the following example of a class:

```php
class Status
{
    const INACTIVE = 1;
    const DRAFT = 2;
    const PUBLISHED = 3;
}
```

You might use it in the context of a query, such as:

```php
// Get inactive posts
Post::where('status_id', Status::INACTIVE)->get();
```

This is all fine, however you may want to persist those `status` constants into a database table so that you can build a better picture from the DB alone (without having to look into the codebase to see what those magic numbers represent).

## Usage
This package aims to solve the above problem with a very simple solution. Just attach the trait to the status class and declare the table name:

```php
use JoeyRush\PersistableConstants\PersistsConstants;

class Status
{
    use PersistsConstants;
    const INACTIVE = 1;
    const DRAFT = 2;
    const PUBLISHED = 3;

    public $constantsTable = 'statuses';
}
```

And now at any point you can call `persistConstants()` and the `statuses` table will be sync'd with the constants:

```php
Status::persistConstants();
```

| id        | name          | created_at       | updated_at       |
| --------- |:-------------:| --------------- :| --------------- :|
| 1         | inactive      | 2019-01-01 00:00 | 2019-01-01 00:00 |
| 2         | draft         | 2019-01-01 00:00 | 2019-01-01 00:00 |
| 3         | published     | 2019-01-01 00:00 | 2019-01-01 00:00 |

The package assumes the following columns `id`, `name`, `created_at` and `updated_at`, however you are free to change how the constants are stored by overriding the following method:

```php
public function formatConstantsForDB(array $constants): array
{
    return collect($constants)->map(function ($id, $key) {
        return [
            'id' => $id,
            'name' => strtolower($key),
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s')
        ];
    })->toArray();
}
```

