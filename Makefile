
IMG=eyeson-php

all: build run

run:
	docker run -it --rm -v `pwd`:/live:Z $(IMG) bash

test: build
	docker run -it --rm $(IMG) ./vendor/bin/phpunit

build:
	docker build -t $(IMG) .

clean:
	docker rmi $(IMG) && rm -f composer-setup.php

.PHONY: build clean
