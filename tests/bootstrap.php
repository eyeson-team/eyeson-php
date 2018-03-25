<?php

/**
 * Use vcr cassettes to record test fixtures for requests via curl.
 **/
\VCR\VCR::configure()->enableLibraryHooks(array('curl'));
