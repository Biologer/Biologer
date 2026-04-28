SELECT 'CREATE DATABASE biologer_testing'
WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = 'biologer_testing')\gexec
