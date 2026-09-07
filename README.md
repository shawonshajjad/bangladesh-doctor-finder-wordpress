# Bangladesh Doctor Finder

A WordPress doctor-directory plugin focused on Bangladesh, packaged as a public portfolio/open-source release from the existing production plugin.

## Features

- Doctor custom post type and manual location taxonomies
- Division → district → upazila/area filtering
- AJAX search and location suggestions
- Responsive `[doctor_finder]` frontend
- Doctor profile pages with structured metadata
- Reviews and recommendation voting
- CSV import workflow
- Duplicate-doctor detection
- Dedicated Data Entry role/workflow

## Public dataset note

This repository intentionally includes **only representative sample location data** (selected divisions, districts, and areas). The full production Bangladesh dataset covering all districts/upazilas is not distributed here. The search/filter architecture remains intact and can be extended with a complete dataset by a site owner.

## Installation

1. Download or clone this repository.
2. Place it in `wp-content/plugins/bangladesh-doctor-finder/`.
3. Activate **Bangladesh Doctor Finder** in WordPress.
4. Add `[doctor_finder]` to any page where the finder should appear.
5. Manage records from **Doctors** in the WordPress admin.

## Main metadata

Doctor records support fields used by the existing implementation, including designation, workplace, clinic, phone, address, map link, division, district, upazila, Facebook link, experience, and other doctor-specific flags.

## Requirements

- WordPress 6.0+
- PHP 7.4+
- `mbstring` recommended for Bangla-aware search translation
- WPForms is optional; the Data Entry workflow includes compatibility capabilities when WPForms is present.

## Security notes

The existing AJAX voting/review/duplicate-check flows use WordPress nonces and input sanitization. Public review submission also includes basic IP-based rate limiting. Deployments should still follow normal WordPress hardening, backup, privacy, and moderation practices.

## Author

**Sajjadur Rahaman Shawon**  
GitHub: https://github.com/shawonshajjad

## License

GPL-2.0-or-later
