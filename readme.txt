=== Bangladesh Doctor Finder ===
Contributors: shawonshajjad
Tags: doctor, directory, bangladesh, location, ajax, reviews
Requires at least: 6.0
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A responsive WordPress doctor directory and location finder for Bangladesh.

== Description ==

Bangladesh Doctor Finder provides a doctor custom post type, searchable doctor directory, hierarchical location filters, AJAX-powered results and suggestions, doctor profile pages, reviews, recommendation voting, CSV import, duplicate detection, and a focused Data Entry workflow.

The public repository intentionally ships with only a representative sample of Bangladesh division/district/upazila data. It does not contain the production 64-district / 495-upazila dataset.

== Features ==

* Doctor custom post type and manual location taxonomies
* Division, district and upazila/area filters
* AJAX doctor search and smart location suggestions
* Responsive frontend via `[doctor_finder]`
* Doctor profile pages and metadata fields
* Reviews with basic IP-based rate limiting
* Recommend / not-recommend voting with nonce validation
* CSV doctor import
* Duplicate detection during editing and from an admin review screen
* Data Entry role/workflow with restricted admin access
* Representative Bangladesh location sample for public demonstration

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/` or install the ZIP through WordPress.
2. Activate **Bangladesh Doctor Finder**.
3. Add `[doctor_finder]` to a page.
4. Add doctor records from **Doctors** in the WordPress admin.

== Changelog ==

= 1.0.0 =
* First public GitHub release.
* Removed production site branding and footer code.
* Repackaged the existing plugin without redesigning its feature set.
* Replaced the production-scale Bangladesh location dataset with a representative sample.
