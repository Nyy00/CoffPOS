# Railway Migration Troubleshooting

## Common Migration Errors

### 1. PostgreSQL Type Error
```
ERROR: type "transactions_status_check" does not exist
```

**Penyebab**: Migration mencoba menggunakan `ALTER TYPE` pada constraint yang bukan custom type.

**Solusi**: 
- Migration baru `fix_transactions_status_constraint_for_production.php` menangani ini
- Menggunakan `railway-migrate-safe.sh` script dengan retry mechanism

### 2. Check Constraint Violation
```
ERROR: new row for relation "transactions" violates check constraint
```

**Penyebab**: Status 'voided' belum ditambahkan ke constraint.

**Solusi**: Jalankan migration terbaru yang menambah 'voided' ke constraint.

### 3. Migration Timeout
```
Migration timeout or connection lost
```

**Penyebab**: Database connection unstable atau migration terlalu lama.

**Solusi**: Script `railway-migrate-safe.sh` memiliki retry mechanism.

## Safe Migration Process

### 1. Pre-Migration Checks
```bash
# Check database connection
php artisan tinker --execute="DB::connection()->getPdo();"

# Check current schema
php artisan migrate:status
```

### 2. Migration Execution
```bash
# Use safe migration script
bash scripts/railway-migrate-safe.sh
```

### 3. Post-Migration Verification
```bash
# Test void status
php artisan tinker --execute="
\$t = new \App\Models\Transaction();
\$t->status = 'voided';
echo 'Void status OK';
"
```

## Manual Recovery Steps

### If Migration Fails Completely

1. **Check Migration Status**
```bash
php artisan migrate:status
```

2. **Rollback Last Migration**
```bash
php artisan migrate:rollback --step=1 --force
```

3. **Fix Database Manually**
```sql
-- Connect to PostgreSQL
-- Drop existing constraint
ALTER TABLE transactions DROP CONSTRAINT IF EXISTS transactions_status_check;

-- Add new constraint
ALTER TABLE transactions ADD CONSTRAINT transactions_status_check 
CHECK (status IN ('completed', 'cancelled', 'voided'));

-- Add void columns if missing
ALTER TABLE transactions ADD COLUMN IF NOT EXISTS void_reason VARCHAR(255);
ALTER TABLE transactions ADD COLUMN IF NOT EXISTS voided_by BIGINT;
ALTER TABLE transactions ADD COLUMN IF NOT EXISTS voided_at TIMESTAMP;
```

4. **Mark Migration as Run**
```bash
php artisan migrate --force
```

## Railway Deployment Best Practices

### 1. Build Command Order
```json
{
  "buildCommand": "npm install && npm run build && bash scripts/railway-migrate-safe.sh && bash scripts/post-deploy.sh"
}
```

### 2. Environment Variables
Ensure these are set in Railway:
- `DB_CONNECTION=pgsql`
- `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`

### 3. Migration Safety
- Always use `--force` flag in production
- Implement retry mechanism for unstable connections
- Test constraints after migration
- Have rollback plan ready

## Monitoring & Logs

### Check Railway Logs
```bash
# In Railway dashboard, check build logs for:
- Migration attempt messages
- Database connection status
- Constraint test results
```

### Verify Deployment
```bash
# After deployment, test void functionality:
curl -X POST https://your-app.railway.app/admin/transactions/{id}/void \
  -H "Content-Type: application/json" \
  -d '{"reason": "Test void"}'
```

## Emergency Contacts

If migration fails and app is down:
1. Check Railway dashboard logs
2. Use Railway CLI to rollback: `railway rollback`
3. Apply manual database fixes via Railway PostgreSQL console
4. Redeploy after fixes

## Prevention

- Test migrations locally first
- Use staging environment
- Keep database backups
- Monitor Railway build logs
- Have rollback procedures ready