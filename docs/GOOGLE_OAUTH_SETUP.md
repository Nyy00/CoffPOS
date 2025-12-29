# Google OAuth Setup for Production (Railway)

## Overview
This guide will help you configure Google OAuth login for your CoffPOS application in production on Railway.

## Prerequisites
- Railway account with deployed CoffPOS application
- Google Cloud Console account
- Your production domain URL: `https://coffpos.up.railway.app`

## Step 1: Create Google OAuth Application

### 1.1 Access Google Cloud Console
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Sign in with your Google account
3. Create a new project or select existing project

### 1.2 Enable Google+ API
1. Go to **APIs & Services** → **Library**
2. Search for "Google+ API" or "Google Identity Services API"
3. Click **Enable**

### 1.3 Configure OAuth Consent Screen
1. Go to **APIs & Services** → **OAuth consent screen**
2. Choose **External** (unless you have Google Workspace)
3. Fill in required information:
   - **App name**: CoffPOS
   - **User support email**: your-email@domain.com
   - **Developer contact information**: your-email@domain.com
4. Add your domain to **Authorized domains**:
   - `up.railway.app`
5. Save and continue

### 1.4 Create OAuth Credentials
1. Go to **APIs & Services** → **Credentials**
2. Click **Create Credentials** → **OAuth 2.0 Client IDs**
3. Choose **Web application**
4. Configure:
   - **Name**: CoffPOS Production
   - **Authorized JavaScript origins**:
     ```
     https://coffpos.up.railway.app
     ```
   - **Authorized redirect URIs**:
     ```
     https://coffpos.up.railway.app/auth/google/callback
     ```
5. Click **Create**
6. **IMPORTANT**: Copy the Client ID and Client Secret

## Step 2: Configure Railway Environment Variables

### 2.1 Add Google OAuth Variables
1. Open your Railway project dashboard
2. Go to **Variables** tab
3. Add these environment variables:

```env
GOOGLE_CLIENT_ID=your-google-client-id-here
GOOGLE_CLIENT_SECRET=your-google-client-secret-here
GOOGLE_REDIRECT_URL=https://coffpos.up.railway.app/auth/google/callback
```

**Replace**:
- `your-google-client-id-here` with your actual Client ID from Google Console
- `your-google-client-secret-here` with your actual Client Secret from Google Console

### 2.2 Verify Other Required Variables
Make sure these are also set in Railway:
```env
APP_URL=https://coffpos.up.railway.app
APP_ENV=production
APP_DEBUG=false
```

## Step 3: Deploy and Test

### 3.1 Redeploy Application
After adding the environment variables, Railway will automatically redeploy your application.

### 3.2 Test Google Login
1. Visit your production site: `https://coffpos.up.railway.app/login`
2. Click "Sign in with Google" button
3. Complete Google OAuth flow
4. Verify you're redirected back and logged in

## Step 4: Troubleshooting

### Common Issues:

#### 1. "redirect_uri_mismatch" Error
**Cause**: Redirect URI in Google Console doesn't match the one used by your app.

**Solution**: 
1. Check that your redirect URI in Google Console exactly matches:
   ```
   https://coffpos.up.railway.app/auth/google/callback
   ```
2. Verify Railway environment variable `GOOGLE_REDIRECT_URL` is set correctly
3. Ensure no typos or extra spaces in the URL

#### 2. "This app isn't verified" Warning
**Solution**: This is normal for new apps. Users can click "Advanced" → "Go to CoffPOS (unsafe)" to continue.

To remove this warning:
1. Go to Google Cloud Console → OAuth consent screen
2. Click "Publish App"
3. Submit for verification (optional, takes time)

#### 3. "Access blocked" Error
**Solution**: 
1. Check OAuth consent screen configuration
2. Ensure `up.railway.app` is added to authorized domains
3. Verify app is published (not in testing mode)

#### 4. Environment Variables Not Loading
**Solution**:
1. Check Railway Variables tab
2. Ensure no extra spaces in variable values
3. Redeploy after adding variables

### Debug Endpoint
Add this route to check configuration (remove after testing):

```php
// In routes/web.php (temporary)
Route::get('/debug/google-config', function () {
    return response()->json([
        'client_id' => config('services.google.client_id') ? 'Set (' . substr(config('services.google.client_id'), 0, 10) . '...)' : 'Not Set',
        'client_secret' => config('services.google.client_secret') ? 'Set' : 'Not Set',
        'redirect_url' => config('services.google.redirect'),
        'app_url' => config('app.url'),
        'current_domain' => request()->getHost(),
    ]);
});
```

Access: `https://coffpos.up.railway.app/debug/google-config`

## Step 5: Security Best Practices

### 5.1 Environment Variables Security
- Never commit OAuth credentials to your repository
- Use Railway's environment variables only
- Regularly rotate your OAuth credentials

### 5.2 User Data Handling
Your current implementation:
- Creates new users with 'cashier' role by default
- Updates existing users' Google ID and avatar
- Generates random password for Google users

### 5.3 Production Considerations
- Monitor OAuth usage in Google Cloud Console
- Set up proper error logging
- Consider implementing rate limiting for auth endpoints

## Step 6: Testing Checklist

- [ ] Google OAuth credentials created in Google Cloud Console
- [ ] Authorized JavaScript origins set to `https://coffpos.up.railway.app`
- [ ] Authorized redirect URIs set to `https://coffpos.up.railway.app/auth/google/callback`
- [ ] Railway environment variables set with actual credentials
- [ ] Application redeployed successfully
- [ ] Login button appears on login page
- [ ] Google OAuth flow works without redirect_uri_mismatch error
- [ ] User is created/updated correctly
- [ ] User is redirected to appropriate dashboard based on role
- [ ] Error handling works (try canceling OAuth)

## Current Railway Domain
Your current Railway domain is: `https://coffpos.up.railway.app`

Make sure all configurations use this exact domain.

## Support

If you encounter issues:
1. Check Railway deployment logs
2. Verify Google Cloud Console configuration matches exactly
3. Test with debug endpoint
4. Check Laravel logs for detailed errors
5. Ensure environment variables are set correctly in Railway dashboard