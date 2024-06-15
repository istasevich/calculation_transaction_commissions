# Transactions Commission

Given a task to refactor ugly code: [task description](https://github.com/istasevich/calculation_transaction_commissions/blob/main/Task.md)

The idea is to calculate commissions from ready-made transactions from a json file as flexibly and structuredly as possible


!NOTE: If it seems like an overhead for a given task, in reality the code can be simplified, but this is to show a possible use case in certain cases

## Installation

Use the package composer

```bash
composer install
```

Change your [exchangeratesapi](https://exchangeratesapi.io/) API key in .env

```bash
RATE_API_ACCESS_KEY="YOUR_API_KEY"
```

## Usage

```php
# Calculate all input data placed in input.json
php index.php

#run unit tests
 ./vendor/bin/phpunit 
```

Main class for calculation: ```bash src/Services/CalculateTransactionsService ```

## Providers folder
For each external BIN search service, a BinListProvider is implemented, which must be implemented from the BinProviderInterface interface

If you want to change the test result of the service provider, you can replace it in the src/Config/DIConfig :

```php
binProvider' => create(BinListProvider::class)->constructor(DI\get('binClient'))
#TO
'binProvider' => create(LocalBinTestProvider::class)

```

because the current BIN service will allow you to make only 5 requests per hour

In the same way, you can change/add providers to obtain a list of Exchange Rates via RateProviderInterface


## License

[MIT](https://choosealicense.com/licenses/mit/)