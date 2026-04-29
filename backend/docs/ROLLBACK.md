# Rollback Procedure

## Use This When

- deployment breaks login or dashboard access
- migrations introduce blocking errors
- queue workers start failing repeatedly
- email or notification flows stop working after release

## Immediate Actions

1. Enable maintenance mode.
2. Stop queue workers gracefully.
3. Switch traffic back to the previous release.

## Database Decision

- If no migration or destructive write happened, restore code only.
- If schema changed or data got corrupted, restore the latest verified database backup.

## Recovery Commands

```bash
php artisan down
php artisan queue:restart
php artisan up
```

## Verification After Rollback

- admin can log in
- teacher dashboard loads
- parent review request flow works
- notifications page loads
- password reset email can still be triggered

## Release Discipline

- Keep one backup per release.
- Keep one previous build artifact or code snapshot.
- Write the release timestamp and operator name in deployment notes.
