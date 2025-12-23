# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

RWK (Rundenwettkampf) Web Input - A mobile-responsive web application for recording shooting competition results in the TLSB (Tiroler Landessportverband für das Schützenwesen) "ALL IN" system. The application allows team captains and district sports directors to input results for round competitions via mobile browsers.

**Production URL**: https://rwk-tlsb.net/webinput/login.php

## User Roles & Permissions

- **Mannschaftsführer (Team Captain)**: Can only enter results during the round time period for their guild and opponent teams
- **Bezirkssportleiter (District Sports Director)**: Can enter results outside the round time period for all guilds in their district

## Architecture

### Technology Stack
- **Backend**: PHP with MySQL database (PDO)
- **Frontend**: Bootstrap 4, jQuery, HTML
- **Deployment**: GitHub Actions with LFTP sync to production server

### Database Connection
Database credentials are in `models/Database.php`:
- Database: `tlsb`
- Connection uses PDO with singleton pattern
- All model classes implement `DatabaseService` interface

### Session Management
- PHP sessions track user authentication and context (season, guild)
- User credentials stored in `$_SESSION['user']` (user ID only)
- Season stored in `$_SESSION['saison']`
- Guild/club code stored in `$_SESSION['verein']`

### Application Flow
Navigation follows a hierarchical breadcrumb structure:
1. **disciplines.php** - Select discipline (Winter/Summer competitions)
2. **rounds.php** - Select competition round
3. **vereins.php** - Select guild/club (Bezirkssportleiter only)
4. **teams.php** - Select team
5. **round_input.php** - Enter results for team matches
   - Alternative: **round_input_ezw.php** - Enter individual shooter results

### Data Models (in `models/` directory)

All models implement `DatabaseService` interface with methods:
- `create()`, `update()`, `delete($id)`, `get($id)`, `getAll()`

Key models:
- **User**: Authentication, permissions (right: 0=team captain, 1=district sports director)
- **Discipline**: Competition disciplines, has season (W/S), weapon type, team size
- **Round**: Competition rounds with start/end dates for input window
- **Verein**: Guilds/clubs
- **Team**: Teams belonging to guilds
- **Shooter**: Individual shooters with pass numbers
- **TeamResults**: Match results with home/guest teams, individual results, points
  - Separate tables for winter (`tlsb_team_result`) and summer (`tlsb_team_result_s`)
  - Handles up to 5 shooters per team
- **SingleResult**: Individual shooter results (for ezw mode)

### HTML Rendering (Html.php)

Shared rendering functions:
- `renderHeader($title)` - HTML head with Bootstrap, jQuery, Google Analytics
- `headLine($title)` - Page title with info modal
- `userLine($user)` - Display logged-in user info
- `crumbBar($index, $rights, $disciplineId, $roundId)` - Breadcrumb navigation
- `seasonSelector($discipline)` - Season dropdown with AJAX reload
- `infoTableStart/Row/End()` - Info table helpers
- `backButton($href)` - Back navigation button
- `renderLogoutSection()` - Logout form
- `utf8_convert($string)` - Character encoding utility
- `formatDateString($dateString)` - Date formatter (dd.mm.yyyy)
- `getRoundRange($round, $user)` - Shows round dates with permission warnings

### Input Validation & Logic (round_input.php)

- Result input fields are disabled unless a shooter is selected (enforced via JavaScript)
- Team totals are calculated dynamically on the client side
- Input is only enabled if:
  - User has Bezirkssportleiter rights (right=1), OR
  - Current date is within round start/stop dates
- Points calculation: Winner gets 2 points, loser gets 0, draw gets 1 point each
- Supports decimal results (0.1 step) if discipline has `ZiroOne` flag set

### JavaScript Patterns

Embedded in PHP files using inline `<script>` tags with jQuery:
- Dynamic total calculation on result input change
- Shooter selection enables/disables corresponding result input field
- Season selector triggers AJAX call to `setSaisonToSession.php` with page reload
- Tab switching for Winter/Summer disciplines

## Common Development Tasks

### Deployment
GitHub Actions automatically deploys to production on push to `main` branch:
```bash
# Changes are deployed via .github/workflows/php.yml
git push origin main
```

### Testing Locally
No automated test suite. Manual testing required:
1. Set up local MySQL database with schema matching production
2. Update database credentials in `models/Database.php`
3. Run PHP development server: `php -S localhost:8000`
4. Test with mobile device viewport in browser

### Debugging
- PHP errors are displayed directly (production mode not hardened)
- Check `$_SESSION` variables for state issues
- Verify user `right` level for permission issues
- Check round start/stop dates for input window issues

### Important Notes on Result Entry Fix
Recent fix (PR #2): Result input fields are now properly disabled when no shooter is selected. The JavaScript `update{prefix}Row()` function manages this behavior:
- When shooter is selected: enable result input
- When no shooter: disable input and clear value
- Initial state set on page load for all rows
- Only active when form is not globally disabled (i.e., within input window or as Bezirkssportleiter)

See `round_input.php:252-276` for the implementation of shooter selection logic.

### Character Encoding
The `utf8_convert()` function in `Html.php` currently returns strings as-is. If encoding issues arise with German umlauts, uncomment the `mb_convert_encoding()` call.
