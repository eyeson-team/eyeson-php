
IMG=eyeson-php

all: build test

run:
	docker run -it --rm -v `pwd`:/live:Z $(IMG) bash

test:
	docker run -it --rm $(IMG) ./vendor/bin/phpunit

build:
	docker build -t $(IMG) .

clean:
	docker rmi $(IMG) && rm -f composer-setup.php

.PHONY: all run test build clean
