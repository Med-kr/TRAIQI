# Pilot Readiness Checklist

## Product

- Seed at least one demo school with admin, teachers, parents, and students.
- Prepare sample imports for students, parents, and teachers.
- Validate teacher grading, parent review request, and notification flows.

## Technical

- Confirm production `.env` values.
- Enable HTTPS and secure cookies.
- Configure daily backups and restore test.
- Enable queue worker monitoring.
- Confirm email and password reset flow.
- Confirm `https` links are generated in production emails.
- Review `failed_jobs` and application logs every pilot day.

## Operations

- Create deployment procedure.
- Create rollback procedure.
- Assign support owner during pilot.
- Keep an audit log review routine for school admins.
- Keep one tested database backup before each release.
- Keep one previous stable release ready for rollback.
