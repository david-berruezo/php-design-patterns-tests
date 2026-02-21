<?php
/**
 * Bootstrap file for PHPUnit tests.
 * Loads all pattern source files with output buffering to suppress demo output.
 */

define('BASE_PATH', dirname(__DIR__));

/**
 * Helper function to safely require a file that may contain executable code.
 * Uses output buffering to suppress any echo/print statements.
 */
function safeRequire(string $path): void
{
    if (file_exists($path)) {
        ob_start();
        require_once $path;
        ob_end_clean();
    }
}

// ---- Standalone class-based patterns (clean includes) ----

// DataMapper
require_once BASE_PATH . '/DataMapper/StorageAdapter.php';
require_once BASE_PATH . '/DataMapper/User.php';
require_once BASE_PATH . '/DataMapper/UserMapper.php';

// DependencyInjection
require_once BASE_PATH . '/DependencyInjection/DatabaseConfiguration.php';
require_once BASE_PATH . '/DependencyInjection/DatabaseConnection.php';

// EAV
require_once BASE_PATH . '/EAV/Attribute.php';
require_once BASE_PATH . '/EAV/Value.php';
require_once BASE_PATH . '/EAV/Entity.php';

// FluentInterface
require_once BASE_PATH . '/FluentInterface/Sql.php';

// NullObject
require_once BASE_PATH . '/NullObject/Logger.php';
require_once BASE_PATH . '/NullObject/NullLogger.php';
require_once BASE_PATH . '/NullObject/PrintLogger.php';
require_once BASE_PATH . '/NullObject/Service.php';

// Pool
require_once BASE_PATH . '/Pool/StringReverseWorker.php';
require_once BASE_PATH . '/Pool/WorkerPool.php';

// Registry
require_once BASE_PATH . '/Registry/Service.php';
require_once BASE_PATH . '/Registry/Registry.php';

// Repository
require_once BASE_PATH . '/Repository/Domain/PostStatus.php';
require_once BASE_PATH . '/Repository/Domain/PostId.php';
require_once BASE_PATH . '/Repository/Domain/Post.php';
require_once BASE_PATH . '/Repository/Persistence.php';
require_once BASE_PATH . '/Repository/InMemoryPersistence.php';
require_once BASE_PATH . '/Repository/PostRepository.php';

// ServiceLocator
require_once BASE_PATH . '/ServiceLocator/Service.php';
require_once BASE_PATH . '/ServiceLocator/LogService.php';
require_once BASE_PATH . '/ServiceLocator/ServiceLocator.php';

// SimpleFactory
require_once BASE_PATH . '/SimpleFactory/Bicycle.php';
require_once BASE_PATH . '/SimpleFactory/SimpleFactory.php';

// Specification
require_once BASE_PATH . '/Specification/Specification.php';
require_once BASE_PATH . '/Specification/Item.php';
require_once BASE_PATH . '/Specification/PriceSpecification.php';
require_once BASE_PATH . '/Specification/AndSpecification.php';
require_once BASE_PATH . '/Specification/OrSpecification.php';
require_once BASE_PATH . '/Specification/NotSpecification.php';

// StaticFactory
require_once BASE_PATH . '/StaticFactory/Formatter.php';
require_once BASE_PATH . '/StaticFactory/FormatNumber.php';
require_once BASE_PATH . '/StaticFactory/FormatString.php';
require_once BASE_PATH . '/StaticFactory/StaticFactory.php';

// ---- Index.php-based patterns (safe require with output buffering) ----

safeRequire(BASE_PATH . '/AbstractFactory/Conceptual/index.php');
safeRequire(BASE_PATH . '/Adapter/Conceptual/index.php');
safeRequire(BASE_PATH . '/Bridge/Conceptual/index.php');
safeRequire(BASE_PATH . '/Bridge/RealWorld/index.php');
safeRequire(BASE_PATH . '/Builder/Conceptual/index.php');
safeRequire(BASE_PATH . '/Builder/RealWorld/index.php');
safeRequire(BASE_PATH . '/Command/Conceptual/index.php');
safeRequire(BASE_PATH . '/ChainOfResponsibility/Conceptual/index.php');
safeRequire(BASE_PATH . '/Composite/Conceptual/index.php');
safeRequire(BASE_PATH . '/Composite/RealWorld/index.php');
safeRequire(BASE_PATH . '/Decorator/Conceptual/index.php');
safeRequire(BASE_PATH . '/Decorator/RealWorld/index.php');
safeRequire(BASE_PATH . '/Facade/Conceptual/index.php');
safeRequire(BASE_PATH . '/FactoryMethod/Conceptual/index.php');
safeRequire(BASE_PATH . '/Flyweight/Conceptual/index.php');
safeRequire(BASE_PATH . '/Interpreter/RealWorld/index.php');
safeRequire(BASE_PATH . '/Iterator/Conceptual/index.php');
safeRequire(BASE_PATH . '/Mediator/Conceptual/index.php');
safeRequire(BASE_PATH . '/Memento/Conceptual/index.php');
safeRequire(BASE_PATH . '/Observer/Conceptual/index.php');
safeRequire(BASE_PATH . '/Proxy/Conceptual/index.php');
safeRequire(BASE_PATH . '/Singleton/Conceptual/index.php');
safeRequire(BASE_PATH . '/State/Conceptual/index.php');
safeRequire(BASE_PATH . '/Strategy/Conceptual/index.php');
safeRequire(BASE_PATH . '/TemplateMethod/Conceptual/index.php');
safeRequire(BASE_PATH . '/Visitor/Conceptual/index.php');
