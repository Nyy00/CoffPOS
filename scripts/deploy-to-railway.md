# Deploy CoffPOS to Railway with Google OAuth

## Quick Deployment Checklist

### 1. Railway Environment Variables Setup

Copy these variables to your Railway project (Variables tab):

```bash
# Application Configuration
APP_NAME="CoffPOS"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-railway-domain.up.railway.app

# Google OAuth (REQUIRED for Google Login)
GOOGLE_CLIENT_ID=your-google-client-id-here
GOOGLE_CLIENT_SECRET=your-google-client-secret-here
GOOGLE_REDIRECT_URL=https://your-railway-domain.up.railway.app/auth/google/callback

# Database (Railway auto-injects these)
DB_CONNECTION=pgsql
DB_HOST=${PGHOST}
DB_PORT=${PGPORT}
DB_DATABASE=${PGDATABASE}
DB_USERNAME=${PGUSER}
DB_PASSWORD=${PGPASSWORD}

# Session & Cache
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# Midtrans (Production)
MIDTRANS_SERVER_KEY=your-production-server-key
MIDTRANS_CLIENT_KEY=your-production-client-key
MIDTRANS_IS_PRODUCTION=true
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true

# Security
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict
```

### 2. Google Cloud Console Setup

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create/select project
3. Enable Google+ API
4. Configure OAuth consent screen
5. Create OAuth 2.0 credentials
6. Add authorized redirect URIs:
   - `https://your-railway-domain.up.railway.app/auth/google/callback`

### 3. Deploy Commands

```bash
# 1. Commit your changes
git add .
git commit -m "Add Google OAuth configuration"

# 2. Push to Railway (if connected to GitHub)
git push origin main

# 3. Or deploy directly to Railway
railway up
```

### 4. Post-Deployment Testing

1. Visit: `https://your-railway-domain.up.railway.app/login`
2. Click "Sign in with Google"
3. Complete OAuth flow
4. Verify login works

### 5. Debug Endpoint (Remove after testing)

Visit: `https://your-railway-domain.up.railway.app/debug/google-oauth`

Should show:
```json
{
  "google_client_id": "Set (1234567890...)",
  "google_client_secret": "Set",
  "google_redirect_url": "https://your-domain.com/auth/google/callback",
  "app_url": "https://your-domain.com",
  "app_env": "production",
  "socialite_installed": true
}
```

## Troubleshooting

### Common Issues:

1. **"redirect_uri_mismatch"**
   - Check Google Console redirect URIs match exactly
   - Ensure HTTPS is used in production

2. **"This app isn't verified"**
   - Normal for new apps
   - Users can click "Advanced" → "Go to CoffPOS"
   - Submit app for verification in Google Console

3. **Environment variables not loading**
   - Check Railway Variables tab
   - Redeploy after adding variables
   - No extra spaces in variable values

4. **Database connection issues**
   - Railway auto-injects PostgreSQL variables
   - Ensure DB_CONNECTION=pgsql

### Support

- Check Railway deployment logs
- Use debug endpoint to verify configuration
- Review Google Cloud Console settings
- Check Laravel logs for detailed errors

## Security Notes

- Never commit OAuth credentials to repository
- Use Railway environment variables only
- Regularly rotate OAuth credentials
- Monitor OAuth usage in Google Console