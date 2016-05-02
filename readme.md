# Dartisan [![Build Status](https://travis-ci.org/schnittstabil/dartisan.svg?branch=master)](https://travis-ci.org/schnittstabil/dartisan) [![Coverage Status](https://coveralls.io/repos/schnittstabil/dartisan/badge.svg?branch=master&service=github)](https://coveralls.io/github/schnittstabil/dartisan?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/schnittstabil/dartisan/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/schnittstabil/dartisan/?branch=master) [![Code Climate](https://codeclimate.com/github/schnittstabil/dartisan/badges/gpa.svg)](https://codeclimate.com/github/schnittstabil/dartisan)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/01f6b84e-39a2-4a0c-9efe-198ade330f4b/big.png)](https://insight.sensiolabs.com/projects/01f6b84e-39a2-4a0c-9efe-198ade330f4b)

> Create and run Illuminate\Database migration scripts

## Install

```
$ composer global require schnittstabil/dartisan
```

## Usage

```bash
$ dartisan --help

usage: dartisan <command> [<options>] [<args>]

COMMANDS
  migrate            Run the database migrations.
  migrate:install    Create the migration repository.
  make:migration     Create a new migration file.
  migrate:reset      Rollback all database migrations.
  migrate:rollback   Rollback the last database migration.
  migrate:status     Show the status of each migration.
```

```bash
$ dartisan migrate --help
usage: dartisan migrate [<options>]

Run the database migrations.

OPTIONS
  --connection-charset     The character set to use, defaults to DB_CHARSET and
                           "utf8"
  --connection-collation   MySQL only: The collation to use, defaults to
                           DB_COLLATION and "utf8_unicode_ci"
  --connection-database    The databse to use, defaults to DB_DATABASE and
                           "forge"
  --connection-driver      The database driver to use, defaults to DB_DRIVER and
                           "mysql"
  --connection-host        The host to use, defaults to DB_HOST and "localhost"
  --connection-password    The password to use, defaults to DB_PASSWORD and ""
  --connection-prefix      The table prefix to use, defaults to DB_PREFIX and ""
  --connection-schema      PostgreSQL only: The schema to use, defaults to
                           DB_SCHEMA and "public"
  --connection-strict      MySQL only: Force strict mode, detaults to DB_STRICT
                           and false
  --connection-username    The username to use, defaults to DB_USERNAME and
                           "forge"
  --help, -?               Display this help.
  --migration-path         The migration path to use, defaults to
                           DB_MIGRATION_PATH and "database/migrations"
  --migration-table        The migration table to use, defaults to
                           DB_MIGRATION_TABLE and "migrations"
  --path                   The path of migrations files to be executed.
  --pretend                Dump the SQL queries that would be run.
  --step                   Force the migrations to be run so they can be rolled
                           back individually.
```

## License

MIT © [Michael Mayer](http://schnittstabil.de)
