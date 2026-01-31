# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.3] - 2024

### Added
- Professional Service Listing (PSL) module for Hyva theme with professional profile management
- OpenSearch integration for advanced profile search with geo-location capabilities
- Customer professional profile CRUD operations with admin approval workflow
- Profile status management supporting Pending, Approved, and Rejected states
- REST API endpoint `POST /rest/V1/psl/search` for profile searching with filters:
  - Country and city filtering
  - Certificates multi-select filtering
  - Services offered filtering
  - Geo-location search with latitude, longitude, and distance radius (e.g., "50km")
- OpenSearch index automatic creation with geo_point mapping for location-based queries
- Profile data synchronization to OpenSearch via console command `qoliber:psl:sync-profiles`
- Cron job `OpenSearchProfileSync` running hourly to:
  - Verify subscription status
  - Check feature expiration dates
  - Update profile visibility in OpenSearch
  - Deactivate expired features automatically
- Feature activation system tied to order completion status (only COMPLETE orders activate features)
- Product-based feature flags (e.g., `psl_profile_geolocation` SKU)
- Customer account dashboard integration with profile management section
- Admin panel section under Customer > Professional Service Profiles
- Social media links integration supporting configurable platforms
- Certificates listing with count tracking in nested OpenSearch structure
- Services listing with keyword-based filtering
- Profile picture/logo upload functionality
- Company information management
- Google Maps integration helper for coordinate mapping
- Email notifications for profile status changes
- AJAX-based admin actions for approve/reject operations
- Profile repository implementing full CRUD with `ProfileRepositoryInterface`
- Search results interface with pagination support
- Extension attributes for customer entity to attach PSL profiles

### Technical Details
- OpenSearch index schema with mappings:
  - `customer_id` (integer)
  - `status` (boolean for approved/rejected)
  - `company_name`, `city` (text with full-text search)
  - `country`, `services`, `active_features` (keyword for exact matching)
  - `logo` (keyword for URL storage)
  - `location` (geo_point for distance queries)
  - `certificates` (nested with name and count)
  - `social_media_links` (nested with name and URL)
  - `features_expiry` (object for feature expiration tracking)
- Bulk indexing using OpenSearch `bulk()` API for performance
- Profile data formatter service `OpenSearchProfileDataFormatter` for index document preparation
- Client resolver using `\Magento\AdvancedSearch\Model\Client\ClientResolver`
- Error handling with `\Psr\Log\LoggerInterface` for sync failures
- Profile search service `PslSearchService` implementing `PslSearchInterface`
- OpenSearch query builder model implementing `OpenSearchQueryInterface`
- Customer extension attributes joined via `JoinProcessorInterface`
- Collection processor for search criteria filtering and pagination
- Profile repository with `getByCustomerId()` method for customer-profile linking
- Admin menu item under Customer section
- ACL resources for admin permission control
- Frontend routes for customer profile management
- Admin routes for profile review and actions
- System configuration in Stores > Configuration > Qoliber > Professional Service Listing
- Configurable social media platforms, certificates, and services options
- Cron configuration using custom cron group
- Requires `qoliber/core` module dependency
- Hyva theme compatibility requirement (not Luma compatible)
- PHP 8.1+ with constructor property promotion and readonly properties
