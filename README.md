# slim3-doctrine2-migrations-oauth2-modules app

vendor/bin/doctrine orm:convert-mapping -f --namespace="Entities\\" --from-database annotation ./storage/
vendor/bin/doctrine orm:generate-entities --generate-annotations=true --generate-methods=true --no-backup ./storage/
vendor/bin/doctrine orm:generate-proxies