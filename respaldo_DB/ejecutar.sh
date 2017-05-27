#!/bin/bash
set anio=%date:~6,4%
set mes=%date:~3,2%
set dia=%date:~0,2%

export PGPASSWORD=271188pmbs
export PGUSER=postgres

/usr/bin/pg_dump --host localhost --port 5432 --format plain --inserts --verbose --file "/var/html/ssat/respaldo_DB/ssat.sql" "ssat"

unset PGUSER
unset PGPASSWORD

