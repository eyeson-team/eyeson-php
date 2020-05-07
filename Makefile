
# CMD=podman
# https://github.com/containers/libpod/issues/5903
CMD=podman --cgroup-manager=systemd
IMG=eyeson-php

all: build test

run:
	$(CMD) run -it --rm -v `pwd`:/live:Z $(IMG) bash

test:
	@$(CMD) run -it --rm $(IMG) ./vendor/bin/phpunit

build:
	$(CMD) build -t $(IMG) .

clean:
	$(CMD) rmi $(IMG) && rm -f composer-setup.php

.PHONY: all run test build clean
