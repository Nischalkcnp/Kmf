#!/usr/bin/env bash
# -----------------------------------------------------------------------------
# KMF Website - Automated Git Pull & Database Migration Script
# -----------------------------------------------------------------------------
set -euo pipefail

# Print title
echo "====================================================================="
echo "  KMF Website Deployer: $(date)"
echo "====================================================================="

# Step 1: Pull latest updates from Git
echo -e "\n[1/2] Pulling latest updates from GitHub..."
git pull origin mid

# Step 2: Run the master database migrations
echo -e "\n[2/2] Running database updates..."
if [ -f database/run_migrations.php ]; then
    php database/run_migrations.php
else
    echo "ERROR: database/run_migrations.php not found! Skipping database updates."
    exit 1
fi

echo -e "\n====================================================================="
echo "  Deployment completed successfully!"
echo "====================================================================="
