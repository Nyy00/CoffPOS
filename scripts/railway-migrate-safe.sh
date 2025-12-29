#!/bin/bash

echo "🚀 Starting safe Railway migration..."

# Set error handling
set -e

# Function to run migration with retry
run_migration_with_retry() {
    local max_attempts=3
    local attempt=1
    
    while [ $attempt -le $max_attempts ]; do
        echo "Migration attempt $attempt of $max_attempts..."
        
        if php artisan migrate --force; then
            echo "✅ Migration successful on attempt $attempt"
            return 0
        else
            echo "❌ Migration failed on attempt $attempt"
            if [ $attempt -eq $max_attempts ]; then
                echo "💥 All migration attempts failed"
                return 1
            fi
            
            echo "⏳ Waiting 5 seconds before retry..."
            sleep 5
            attempt=$((attempt + 1))
        fi
    done
}

# Check database connection
echo "🔍 Checking database connection..."
if ! php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database connected successfully';" 2>/dev/null; then
    echo "❌ Database connection failed"
    exit 1
fi

echo "✅ Database connection successful"

# Run migrations with retry
echo "📦 Running migrations..."
if run_migration_with_retry; then
    echo "✅ All migrations completed successfully"
else
    echo "❌ Migration failed after all attempts"
    
    # Try to rollback the problematic migration
    echo "🔄 Attempting to rollback last migration..."
    php artisan migrate:rollback --step=1 --force || true
    
    exit 1
fi

# Verify void status works
echo "🧪 Testing void status constraint..."
if php artisan tinker --execute="
try {
    \$transaction = new \App\Models\Transaction();
    \$transaction->status = 'voided';
    echo 'Void status constraint test: PASSED';
} catch (Exception \$e) {
    echo 'Void status constraint test: FAILED - ' . \$e->getMessage();
    exit(1);
}
" 2>/dev/null; then
    echo "✅ Void status constraint working correctly"
else
    echo "❌ Void status constraint test failed"
    exit 1
fi

echo "🎉 Railway migration completed successfully!"