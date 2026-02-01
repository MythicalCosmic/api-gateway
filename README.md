# [api-gateway]

> Enterprise-grade Laravel application with Clean Architecture and SOLID principles

![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?logo=laravel&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?logo=docker&logoColor=white)
![Architecture](https://img.shields.io/badge/Architecture-Clean-brightgreen)
![SOLID](https://img.shields.io/badge/Principles-SOLID-blue)

A high-performance Laravel application built with enterprise patterns. Features a complete clean architecture implementation with DTOs, Repositories, Services, Presenters, and Transformers. Includes automated Telegram logging, queue workers, and zero-downtime deployment.

---

## ✨ Features

### 🏗️ Architecture
- **Clean Architecture** — Full separation of concerns
- **SOLID Principles** — Single responsibility throughout
- **Repository Pattern** — Abstracted data layer
- **Service Layer** — Business logic isolation
- **DTO Pattern** — Type-safe data transfer
- **Presenter Pattern** — View logic separation
- **Transformer Pattern** — API response formatting

### ⚡ Performance
- **Blazing Fast** — Optimized queries and caching
- **Queue System** — Background job processing
- **Zero Downtime** — Never shuts down

### 🔧 DevOps
- **Docker** — Containerized deployment
- **Telegram Logging** — Automated error notifications
- **PHPUnit** — Comprehensive test suite
- **Laravel Pint** — Code style enforcement

---

## 🏗️ Architecture Overview

```
┌────────────────────────────────────────────────────────────────────┐
│                      CLEAN ARCHITECTURE                            │
├────────────────────────────────────────────────────────────────────┤
│                                                                    │
│   ┌────────────────────────────────────────────────────────────┐   │
│   │                    PRESENTATION LAYER                      │   │
│   │  ┌──────────┐  ┌────────────┐  ┌────────────────────────┐ │   │
│   │  │   Http   │  │ Presenters │  │     Transformers       │ │   │
│   │  │Controllers│  │  (Views)   │  │   (API Responses)      │ │   │
│   │  └────┬─────┘  └─────┬──────┘  └───────────┬────────────┘ │   │
│   └───────┼──────────────┼─────────────────────┼──────────────┘   │
│           │              │                     │                   │
│   ┌───────▼──────────────▼─────────────────────▼──────────────┐   │
│   │                    APPLICATION LAYER                       │   │
│   │  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌───────────┐  │   │
│   │  │ Services │  │   DTOs   │  │   Jobs   │  │ Exceptions│  │   │
│   │  │ (Logic)  │  │  (Data)  │  │ (Queues) │  │ (Errors)  │  │   │
│   │  └────┬─────┘  └──────────┘  └──────────┘  └───────────┘  │   │
│   └───────┼───────────────────────────────────────────────────┘   │
│           │                                                        │
│   ┌───────▼───────────────────────────────────────────────────┐   │
│   │                     DOMAIN LAYER                           │   │
│   │  ┌──────────────┐  ┌──────────────┐  ┌─────────────────┐  │   │
│   │  │ Repositories │  │   Models     │  │   Serializers   │  │   │
│   │  │ (Interfaces) │  │  (Entities)  │  │ (Data Format)   │  │   │
│   │  └──────┬───────┘  └──────────────┘  └─────────────────┘  │   │
│   └─────────┼─────────────────────────────────────────────────┘   │
│             │                                                      │
│   ┌─────────▼─────────────────────────────────────────────────┐   │
│   │                  INFRASTRUCTURE LAYER                      │   │
│   │  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌───────────┐  │   │
│   │  │ Database │  │ Telegram │  │ Providers│  │  Storage  │  │   │
│   │  │          │  │ Logging  │  │          │  │           │  │   │
│   │  └──────────┘  └──────────┘  └──────────┘  └───────────┘  │   │
│   └───────────────────────────────────────────────────────────┘   │
│                                                                    │
└────────────────────────────────────────────────────────────────────┘
```

---

## 📁 Project Structure

```
app/
│
├── DTO/                        # Data Transfer Objects
│   ├── UserDTO.php            # User data structure
│   ├── OrderDTO.php           # Order data structure
│   └── ...
│
├── Exceptions/                 # Custom Exceptions
│   ├── Handler.php            # Exception handler
│   ├── ValidationException.php
│   └── BusinessException.php
│
├── Http/                       # HTTP Layer
│   ├── Controllers/           # Request handlers
│   ├── Middleware/            # HTTP middleware
│   ├── Requests/              # Form requests
│   └── Resources/             # API resources
│
├── Jobs/                       # Queue Jobs
│   ├── ProcessOrder.php       # Background tasks
│   ├── SendNotification.php
│   └── ...
│
├── Logging/                    # Logging System
│   └── Telegram/              # Telegram notifications
│       ├── TelegramLogger.php # Log to Telegram
│       └── TelegramHandler.php
│
├── Models/                     # Eloquent Models
│   ├── User.php
│   ├── Order.php
│   └── ...
│
├── Presenters/                 # View Presenters
│   ├── UserPresenter.php      # User view logic
│   └── ...
│
├── Providers/                  # Service Providers
│   ├── AppServiceProvider.php
│   ├── RepositoryServiceProvider.php
│   └── ...
│
├── Repositories/               # Repository Layer
│   ├── Interfaces/            # Repository contracts
│   │   ├── UserRepositoryInterface.php
│   │   └── ...
│   ├── UserRepository.php     # Implementations
│   └── ...
│
├── Serializers/                # Data Serializers
│   ├── UserSerializer.php
│   └── ...
│
├── Services/                   # Business Logic
│   ├── UserService.php        # User operations
│   ├── OrderService.php       # Order operations
│   └── ...
│
├── Telegram/                   # Telegram Integration
│   ├── TelegramService.php
│   └── Commands/
│
└── Transformers/               # API Transformers
    ├── UserTransformer.php    # Transform for API
    └── ...

bootstrap/                      # Laravel bootstrap
config/                         # Configuration files
database/                       # Migrations & seeds
public/                         # Public assets
routes/                         # Route definitions
storage/                        # File storage
tests/                          # Test suite
```

---

## 🛠️ Tech Stack

| Component | Technology |
|-----------|------------|
| **Language** | PHP 8.2+ |
| **Framework** | Laravel 11 |
| **Database** | MySQL / PostgreSQL |
| **Queue** | Redis / Database |
| **Container** | Docker |
| **Testing** | PHPUnit |
| **Code Style** | Laravel Pint |
| **Logging** | Telegram |

---

## 📦 Installation

### Prerequisites

- PHP 8.2+
- Composer
- Docker & Docker Compose (optional)
- MySQL / PostgreSQL

### Standard Setup

```bash
# Clone repository
git clone https://github.com/MythicalCosmic/[repo-name].git
cd [repo-name]

# Install dependencies
composer install

# Configure environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Start server
php artisan serve
```

### Docker Setup

```bash
# Build and start containers
docker-compose up -d

# Run migrations
docker-compose exec app php artisan migrate

# Application available at http://localhost:8000
```

---

## ⚙️ Configuration

### Environment Variables

```env
# Application
APP_NAME="My App"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://example.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=myapp
DB_USERNAME=root
DB_PASSWORD=secret

# Queue
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1

# Telegram Logging
TELEGRAM_BOT_TOKEN=your-bot-token
TELEGRAM_CHAT_ID=your-chat-id
TELEGRAM_LOG_LEVEL=error

# Cache
CACHE_DRIVER=redis
SESSION_DRIVER=redis
```

---

## 🏛️ Architecture Patterns

### DTO (Data Transfer Object)

```php
namespace App\DTO;

readonly class UserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $phone = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            email: $request->input('email'),
            phone: $request->input('phone'),
        );
    }
}
```

### Repository Pattern

```php
// Interface
namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function find(int $id): ?User;
    public function create(UserDTO $dto): User;
    public function update(int $id, UserDTO $dto): User;
    public function delete(int $id): bool;
}

// Implementation
namespace App\Repositories;

class UserRepository implements UserRepositoryInterface
{
    public function find(int $id): ?User
    {
        return User::find($id);
    }

    public function create(UserDTO $dto): User
    {
        return User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'phone' => $dto->phone,
        ]);
    }
}
```

### Service Layer

```php
namespace App\Services;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function createUser(UserDTO $dto): User
    {
        // Business logic here
        $user = $this->userRepository->create($dto);
        
        // Dispatch job
        ProcessNewUser::dispatch($user);
        
        return $user;
    }
}
```

### Transformer Pattern

```php
namespace App\Transformers;

class UserTransformer
{
    public function transform(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at->toISOString(),
        ];
    }

    public function collection(Collection $users): array
    {
        return $users->map(fn($user) => $this->transform($user))->toArray();
    }
}
```

---

## 📱 Telegram Logging

Errors are automatically sent to Telegram for instant notification.

### Configuration

```php
// config/logging.php
'channels' => [
    'telegram' => [
        'driver' => 'custom',
        'via' => App\Logging\Telegram\TelegramLogger::class,
        'level' => 'error',
    ],
],
```

### Usage

```php
// Errors automatically logged to Telegram
Log::channel('telegram')->error('Something went wrong!', [
    'user_id' => $userId,
    'action' => 'payment_failed',
]);
```

### What Gets Logged

- ❌ Exceptions and errors
- ⚠️ Critical business events
- 🔄 Queue failures
- 🔐 Security alerts

---

## 📋 Queue & Jobs

### Running Queue Worker

```bash
# Single worker
php artisan queue:work

# With supervisor (production)
php artisan queue:work --sleep=3 --tries=3 --max-time=3600
```

### Creating Jobs

```php
namespace App\Jobs;

class ProcessOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private Order $order,
    ) {}

    public function handle(): void
    {
        // Process order in background
    }
}

// Dispatch
ProcessOrder::dispatch($order);
```

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# With coverage
php artisan test --coverage

# Specific test
php artisan test --filter=UserServiceTest
```

### Test Structure

```
tests/
├── Feature/
│   ├── Http/
│   │   └── UserControllerTest.php
│   └── Services/
│       └── UserServiceTest.php
└── Unit/
    ├── DTO/
    │   └── UserDTOTest.php
    └── Repositories/
        └── UserRepositoryTest.php
```

---

## 🐳 Docker

### docker-compose.yml

```yaml
version: '3.8'

services:
  app:
    build: .
    ports:
      - "8000:8000"
    volumes:
      - .:/var/www/html
    depends_on:
      - mysql
      - redis

  mysql:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: myapp
      MYSQL_ROOT_PASSWORD: secret
    volumes:
      - mysql_data:/var/lib/mysql

  redis:
    image: redis:alpine

  queue:
    build: .
    command: php artisan queue:work
    depends_on:
      - mysql
      - redis

volumes:
  mysql_data:
```

---

## 🚀 Deployment

### Zero-Downtime Deployment

```bash
# Pull latest changes
git pull origin main

# Install dependencies
composer install --no-dev --optimize-autoloader

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Restart queue workers
php artisan queue:restart
```

---

## 📊 SOLID Principles Applied

| Principle | Implementation |
|-----------|----------------|
| **S** — Single Responsibility | Each class has one job (Services, Repositories, DTOs) |
| **O** — Open/Closed | Extensions via interfaces, not modifications |
| **L** — Liskov Substitution | Repository interfaces with swappable implementations |
| **I** — Interface Segregation | Small, focused interfaces |
| **D** — Dependency Inversion | Constructor injection, interface dependencies |

---

## 📄 License

MIT License - see [LICENSE](LICENSE) for details.

---

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/NewFeature`)
3. Commit changes (`git commit -m 'Add NewFeature'`)
4. Push to branch (`git push origin feature/NewFeature`)
5. Open a Pull Request

---

**Enterprise architecture. Production ready. Built to scale.**
