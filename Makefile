
CMD=podman
IMG=eyeson-php

all: build test

run:
	$(CMD) run -it --rm -v `pwd`:/live:Z $(IMG) bash

test:
	$(CMD) run -it --rm $(IMG) ./vendor/bin/phpunit

build:
	$(CMD) build -t $(IMG) .

clean:
	$(CMD) rmi $(IMG) && rm -f composer-setup.php

.PHONY: all run test build clean
