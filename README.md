# Bangladesh Doctor Finder

A full-featured WordPress doctor-directory and discovery plugin built for Bangladesh. It combines structured doctor profiles, hierarchical location filtering, AJAX search, reviews and recommendations with administrative workflows for importing, validating and maintaining directory data.

## Highlights

- **Doctor Custom Post Type** with structured profile metadata
- **Division → District → Upazila/Area** location model
- Responsive `[doctor_finder]` directory interface
- AJAX search and location suggestions
- Dedicated doctor profile presentation
- Public reviews and recommendation voting
- CSV-based doctor import workflow
- Duplicate-doctor detection
- Dedicated **Data Entry** role/workflow
- Bangladesh/Bangla-aware search behavior
- WordPress-native taxonomies, metadata, nonces and AJAX endpoints

## Directory Model

Doctor records can contain information such as:

- Designation and professional details
- Workplace / hospital
- Clinic or chamber information
- Phone
- Address
- Map link
- Division
- District
- Upazila / area
- Facebook link
- Experience
- Additional doctor-specific status/metadata fields

Location terms are organized as manual WordPress taxonomies so the frontend can provide progressively narrower geographic filtering.

## Search Flow

```text
Visitor
  │
  ├─ Search / choose location
  │
  ▼
Division → District → Upazila/Area
  │
  ▼
WordPress AJAX request
  │
  ▼
Doctor query + matching logic
  │
  ▼
Responsive results
  │
  ▼
Doctor profile → reviews / recommendation
```

## Data Management Workflow

The plugin includes tooling beyond the public directory:

- **CSV import** for bulk doctor records
- **Duplicate detection** to reduce repeated entries
- A dedicated **Data Entry** role for controlled content-entry work
- Metadata and taxonomy fields used by the doctor profile/search system

This separation allows directory maintenance to be delegated without granting unrestricted administrator access.

## Reviews & Recommendations

Doctor profiles support public feedback and recommendation voting. The public interaction flows use WordPress nonces/input handling, and review submission includes basic IP-based rate limiting in the current implementation.

## Public Dataset Note

This public repository intentionally contains **representative sample location data only**. The complete production dataset covering all 64 districts and the full upazila set is not distributed here.

The directory architecture and cascading location workflow remain intact, so a site owner can extend the taxonomy dataset independently.

## Project Structure

```text
bangladesh-doctor-finder-wordpress/
├── bangladesh-doctor-finder.php
├── index.php
├── README.md
├── readme.txt
├── .gitignore
└── includes/
    ├── index.php
    ├── doctor-finder.php
    └── modules/
        ├── core.php
        ├── data-entry.php
        ├── duplicates.php
        ├── frontend.php
        ├── profiles-reviews.php
        ├── search.php
        └── voting.php
```

## Module Responsibilities

| Module | Responsibility |
| --- | --- |
| `core.php` | Core doctor content model, metadata and location foundations |
| `data-entry.php` | Data Entry role/workflow and import-related administration |
| `duplicates.php` | Duplicate detection and record validation workflow |
| `frontend.php` | Public finder UI and frontend rendering |
| `profiles-reviews.php` | Doctor profile and review functionality |
| `search.php` | AJAX search, filtering and location matching |
| `voting.php` | Doctor recommendation/voting flow |

## Installation

1. Download or clone the repository.
2. Place it in `wp-content/plugins/bangladesh-doctor-finder/`.
3. Activate **Bangladesh Doctor Finder** in WordPress.
4. Add `[doctor_finder]` to the page where the directory should appear.
5. Manage doctor records and location terms from the WordPress dashboard.

## Requirements

- WordPress 6.0+
- PHP 7.4+
- `mbstring` recommended for Bangla-aware search translation
- WPForms is optional; the Data Entry workflow includes compatibility capabilities when WPForms is present

## Security Notes

The current AJAX voting, review and duplicate-check flows use WordPress nonces and input sanitization. Public review submission also includes basic IP-based rate limiting. Production deployments should still apply standard WordPress security, privacy, backup and moderation practices.

## Portfolio Note

This project demonstrates a larger WordPress application architecture: custom content modeling, hierarchical location data, AJAX discovery, public user interaction, bulk data operations, duplicate handling and delegated editorial/data-entry permissions are packaged into one directory system.

## Author

**Sajjadur Rahaman Shawon**  
GitHub: https://github.com/shawonshajjad

## License

GPL-2.0-or-later
