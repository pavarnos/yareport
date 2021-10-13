## Yet Another Report Writer

A report writer where you

* define the columns you want
* get data from somewhere (eg a database query)
* render the data in different formats eg json, csv, html, or extract email addresses

This is based on a library i first wrote in the late 1990s. I have used it in many projects since then, and kept it up
to date as it was used across projects:

* PHP >= 8.0 with use of modern PHP language features
* phpstan --level=max
* high unit test coverage

## Usage

Create a report

```php
$report = new Report();
$report->addColumn((new Column('name', 'Name', 'Personal name'))->setSortOrder(['name']));
$report->addColumn((new EmailColumn('email', 'Email'))->setSortOrder(['email']));
$report->addColumn((new MoneyColumn('rate', 'Hourly Rate'))->setCurrencySymbol('$')->setSortOrder(['rate', 'name']));
$report->addColumn(new IntegerColumn('weight', 'Weight (kg)', 'before eating'));
$report->addColumn((new BooleanColumn('is_active', 'Is Active'))->setNoHtml('Nope')->setYesHtml('Yep'));
$report->addColumn((new CalculatedColumn('menu', ''))->setRenderHtml(fn() => '*menu*'));
```

Then render it in multiple formats

Comma Separated Values

```php
$csvString = (new CSVRender())->render($report, $data);
return new Response(200, ['Content-Type' => 'text/csv'], $csvString);
```

JSON

```php
$jsonArray = (new JsonRender())->render($report, $data);
return new Response(200, ['Content-Type' => 'text/json'], json_encode($jsonArray));
```

## Per-User customisation

Columns can be required, visible or hidden. The `ReportSerializer` allows you to define a standard report with default
columns that everyone sees. If you create a way to customise the report (add extra optional columns, change the order of
columns, change titles etc), the `ReportSerializer` allows you to save this somewhere per user and then re-load it so
the user sees their version of the report based on the standard template. The serializer is robust so if you add or
delete columns from the code and the per-user configuration refers to old columns, they will be silently ignored

## Installation

```
composer require lss/yareport
```

See also `lss/yadbal`, a database abstraction layer which plays nice with this. You need a bit of extra glue code (see
the examples folder)
