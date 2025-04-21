
<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Centralized Vending Machine Control System

The designed system functions as a centralized control platform for vending machines, responsible for handling all payment operations, order placement, and order reception in an integrated and centralized manner.
In addition to carrying out the essential tasks involved in processing and receiving orders, the system monitors the performance of the machines to minimize order processing time. It also evaluates the delivery status of the machines to determine whether they should be accessible or inaccessible to customers.

To implement this system:
- The `Retry Pattern` is used for handling failed requests,
- The `State Pattern` is applied to manage the different states across various parts of the system (including payment, orders, and vending machines).

Moreover, to optimize overall system performance, a `Circuit Breaker` has been designed and implemented. 

This mechanism prevents repeated failures by temporarily disabling faulty components and redirecting operations to alternative services until the issue is resolved.

## Technologies & Tools
- **Programming Language:** PHP (Laravel framework)
- **Database:** 
  - **MySQL** (for data storage and management)
  - **Redis** (for request queuing)
- **Containerization**: Docker


## Run Project
In the root directory of the project, you only need to run the following command to start the project on your system:

```bash
./docker.sh bash
```

## Tests
Also for running **Feature** and ./docker.sh bash tests, you can run `php artisan test` command

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
