run:
	php bin/console --no-interaction doctrine:migrations:migrate 
	symfony server:start 

# TODO: database automatic start
