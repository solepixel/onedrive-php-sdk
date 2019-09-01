#!/bin/sh

# Remove V1 API.
git grep --files-with-matches --quiet deprecated src && echo "[ ] Remove V1 API (found references of 'deprecated' in source)"
git grep --files-with-matches --quiet deprecated test && echo "[ ] Remove V1 API (found references of 'deprecated' in tests)"

# Upgrade to PHPUnit 8.
git grep --files-with-matches --quiet PHPUnit_Framework_TestCase test && echo '[ ] Upgrade to PHPUnit 8 (found references of PHPUnit_Framework_TestCase)'
git grep --files-with-matches --quiet assertInternalType test && echo '[ ] Upgrade to PHPUnit 8 (found references assertInternalType)'
git grep 'function setUpBeforeClass' | grep --invert --quiet void && echo '[ ] Upgrade to PHPUnit 8 (found references assertInternalType)'

# Add declare(strict_types = 1) to all PHP files.
[ $(find src -type f -name '*.php' -exec grep --files-without-match 'declare(strict_types=1);' {} \; | wc --lines) -ne 0 ] && echo '[ ] Add declare(strict_types = 1) to all PHP files (found files without it)'
