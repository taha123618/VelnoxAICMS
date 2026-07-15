# Ward Security Report

**Project:** fusigabs/zioracms  
**Laravel:** ^12.0  
**PHP:** ^8.2  
**Duration:** 2.146s  
**Scanners:** env-scanner, config-scanner, dependency-scanner, rules-scanner  

## Summary

| Total | 10 |
|-------|---|
| 🟠 High | 2 |
| 🟡 Medium | 7 |
| 🟢 Low | 1 |

## Findings

### 🟠 High (2)

#### ENV-002 — APP_DEBUG is enabled

- **File:** `.env:4`
- **Category:** Configuration
- **Scanner:** env-scanner

APP_DEBUG is set to true. In production, this exposes detailed error messages including stack traces, database queries, and environment variables to end users.

```
APP_DEBUG=true
```

**Remediation:**

Set APP_DEBUG=false in your production .env file. Use Laravel's logging system for error tracking instead.

**References:**
- https://owasp.org/Top10/A05_2021-Security_Misconfiguration/

---

#### INJECT-001 — DB::raw() with variable interpolation

- **File:** `Modules/Dashboard/app/Actions/GetVisitsGroupedByDimensionAction.php:12`
- **Category:** Injection
- **Scanner:** rules-scanner

DB::raw() is called with a PHP variable, which may lead to SQL injection if the variable contains unsanitized user input. Laravel's query builder automatically escapes parameters — use bindings instead of raw SQL.


```
->select(DB::raw("count(*) as total, {$dimension}"))
```

**Remediation:**

Use parameter bindings:
  DB::raw('COUNT(*) as count')           // safe — no variables
  DB::select('SELECT * FROM users WHERE id = ?', [$id])  // safe — bound
Avoid:
  DB::raw("WHERE name = '$name'")        // vulnerable


**References:**
- https://owasp.org/Top10/A03_2021-Injection/
- https://cwe.mitre.org/data/definitions/89.html

---

### 🟡 Medium (7)

#### ENV-005 — APP_ENV is set to 'local'

- **File:** `.env:2`
- **Category:** Configuration
- **Scanner:** env-scanner

The application environment suggests a non-production configuration. If this is a production server, this may cause debug features to be enabled and performance optimizations to be skipped.

```
APP_ENV=local
```

**Remediation:**

Set APP_ENV=production on production servers.

---

#### CRYPTO-002 — sha1() used for security purposes

- **File:** `Modules/Auth/tests/Feature/Auth/EmailVerificationTest.php:27`
- **Category:** Cryptography
- **Scanner:** rules-scanner

SHA-1 is considered weak — practical collision attacks exist. While less broken than MD5, it should not be used for security-sensitive operations like password hashing or token generation.


```
['id' => $user->id, 'hash' => sha1($user->email)]
```

**Remediation:**

Use SHA-256 or stronger:
  hash('sha256', $data)
For passwords, always use Hash::make().


**References:**
- https://cwe.mitre.org/data/definitions/328.html

---

#### CRYPTO-002 — sha1() used for security purposes

- **File:** `Modules/Auth/tests/Feature/Auth/EmailVerificationTest.php:43`
- **Category:** Cryptography
- **Scanner:** rules-scanner

SHA-1 is considered weak — practical collision attacks exist. While less broken than MD5, it should not be used for security-sensitive operations like password hashing or token generation.


```
['id' => $user->id, 'hash' => sha1('wrong-email')]
```

**Remediation:**

Use SHA-256 or stronger:
  hash('sha256', $data)
For passwords, always use Hash::make().


**References:**
- https://cwe.mitre.org/data/definitions/328.html

---

#### DEBUG-005 — Debug bar or Clockwork left enabled

- **File:** `config/data.php:108`
- **Category:** Debug
- **Scanner:** rules-scanner

Debug toolbar packages (Laravel Debugbar, Clockwork) are useful in development but expose query logs, route information, session data, and request details in production.


```
'enabled' => true,
```

**Remediation:**

Ensure debug tools are environment-gated:
  'enabled' => env('DEBUGBAR_ENABLED', false),
And never set DEBUGBAR_ENABLED=true in production .env.


---

#### DEBUG-005 — Debug bar or Clockwork left enabled

- **File:** `config/data.php:116`
- **Category:** Debug
- **Scanner:** rules-scanner

Debug toolbar packages (Laravel Debugbar, Clockwork) are useful in development but expose query logs, route information, session data, and request details in production.


```
'enabled' => true,
```

**Remediation:**

Ensure debug tools are environment-gated:
  'enabled' => env('DEBUGBAR_ENABLED', false),
And never set DEBUGBAR_ENABLED=true in production .env.


---

#### DEBUG-005 — Debug bar or Clockwork left enabled

- **File:** `config/location.php:79`
- **Category:** Debug
- **Scanner:** rules-scanner

Debug toolbar packages (Laravel Debugbar, Clockwork) are useful in development but expose query logs, route information, session data, and request details in production.


```
'enabled' => env('LOCATION_TESTING', true),
```

**Remediation:**

Ensure debug tools are environment-gated:
  'enabled' => env('DEBUGBAR_ENABLED', false),
And never set DEBUGBAR_ENABLED=true in production .env.


---

#### DEBUG-005 — Debug bar or Clockwork left enabled

- **File:** `config/modules.php:29`
- **Category:** Debug
- **Scanner:** rules-scanner

Debug toolbar packages (Laravel Debugbar, Clockwork) are useful in development but expose query logs, route information, session data, and request details in production.


```
'enabled' => true,
```

**Remediation:**

Ensure debug tools are environment-gated:
  'enabled' => env('DEBUGBAR_ENABLED', false),
And never set DEBUGBAR_ENABLED=true in production .env.


---

### 🟢 Low (1)

#### AUTH-001 — Route without middleware

- **File:** `routes/web.php:9`
- **Category:** Authentication
- **Scanner:** rules-scanner

A route is defined without any middleware. Depending on the route, this may expose endpoints without authentication or rate limiting. Sensitive routes should always have auth or throttle middleware.


```
Route::get('/', [PageController::class, 'index'])->name('home');
```

**Remediation:**

Apply middleware to routes:
  Route::get('/dashboard', [DashboardController::class, 'index'])
      ->middleware(['auth', 'verified']);
Or group routes:
  Route::middleware(['auth'])->group(function () { ... });


**References:**
- https://cwe.mitre.org/data/definitions/306.html

---

*Generated by [Ward](https://github.com/Eljakani/ward) v0.4.0*
