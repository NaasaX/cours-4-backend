#!/bin/bash

# Script pour exécuter tous les tests du projet
# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo "=========================================="
echo "Running all project tests"
echo "=========================================="
echo ""

# Counter for failed tests
FAILED=0

# Run PHPCS (Code Style)
echo -e "${YELLOW}Running PHPCS (Code Style)...${NC}"
if docker compose run --rm php composer run phpcs; then
    echo -e "${GREEN}✓ PHPCS passed${NC}"
else
    echo -e "${RED}✗ PHPCS failed${NC}"
    FAILED=$((FAILED + 1))
fi
echo ""

# Run PHPStan (Static Analysis)
echo -e "${YELLOW}Running PHPStan (Static Analysis)...${NC}"
if docker compose run --rm php composer run phpstan; then
    echo -e "${GREEN}✓ PHPStan passed${NC}"
else
    echo -e "${RED}✗ PHPStan failed${NC}"
    FAILED=$((FAILED + 1))
fi
echo ""

# Run PHPUnit (Unit Tests)
echo -e "${YELLOW}Running PHPUnit (Unit Tests)...${NC}"
if docker compose run --rm php php bin/console --env=test doctrine:database:drop --force --if-exists 2>/dev/null; then
    echo "Test database dropped"
fi
if docker compose run --rm php php bin/console --env=test doctrine:database:create 2>/dev/null; then
    echo "Test database created"
fi
if docker compose run --rm php php bin/console --env=test doctrine:migrations:migrate --no-interaction 2>/dev/null; then
    echo "Migrations executed"
fi

if docker compose run --rm php php bin/phpunit; then
    echo -e "${GREEN}✓ PHPUnit passed${NC}"
else
    echo -e "${RED}✗ PHPUnit failed${NC}"
    FAILED=$((FAILED + 1))
fi
echo ""

# Summary
echo "=========================================="
if [ $FAILED -eq 0 ]; then
    echo -e "${GREEN}All tests passed!${NC}"
    exit 0
else
    echo -e "${RED}$FAILED test(s) failed${NC}"
    exit 1
fi
