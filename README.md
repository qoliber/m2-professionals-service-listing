# Qoliber Professional Service Listing for Magento 2

This module provides professional service listing functionality for Magento 2, allowing customers to create and manage their professional profiles, which can be reviewed and approved by administrators.

## Features

- ⚠️ **ONLY HYVA** Compatible! ⚠️
- ⚠️ **OpenSearch** required ⚠️
- Customer professional profile management
- Social media links integration
- Certificates and services listing
- Admin approval workflow
- Profile status management (Pending, Approved, Rejected)
- Ajax-based admin actions
- Email notifications for status changes
- Automated profile checks via cron (every hour)
- Order status-based feature activation

## How It Works

### Profile Feature Management

The module uses a sophisticated system to manage profile features based on subscriptions and orders:

1. **Order Requirements**:
   - Features are only activated when the associated order has a **COMPLETE** status
   - Pending or processing orders will not activate profile features

2. **Automated Checks**:
   - A cron job runs every hour to:
     - Verify subscription status
     - Check feature expiration dates
     - Update profile visibility in OpenSearch
     - Deactivate expired features automatically

3. **Feature Activation**:
   - Features are tied to specific products (e.g., `psl_profile_geolocation`)
   - Each feature is independently managed
   - Features are only active with valid subscriptions and complete orders

## Installation

### Via Composer (Recommended)

1. Add the repository to your Magento 2 `composer.json`:

```bash
composer require qoliber/m2-professionals-service-listing
```

2. Enable the module:

```bash
bin/magento module:enable Qoliber_Psl
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy -f
bin/magento cache:flush
```

## Configuration

1. Go to Stores > Configuration > Qoliber > Professional Service Listing
2. Configure the following sections:
   - Social Media Platforms
   - Available Certificates
   - Services Offered by Agencty / Freelancer
   - Email Notifications
   - Cron Settings (if needed)

## Usage

### Customer Side

1. Customers can create and manage their professional profiles from their account dashboard
2. They can add:
   - Company information
   - Profile picture/logo
   - Social media links
   - Certificates
   - Services offered
   - Description and contact details
   - Set Their GEO Coordinates

### Admin Side

1. Administrators can review profiles from Customer > Professional Service Profiles
2. For each profile, admins can:
   - View all submitted information
   - Approve profiles
   - Reject profiles with a reason
   - View profile status history

## 🔍 REST API: Profile Search

The module exposes a search API for frontend or integrations.

**Endpoint:**

Curl Example
```bash
curl -X POST "https://your-magento-site.com/rest/V1/psl/search" \
     -H "Content-Type: application/json" \
     -d '{
           "filters": {
             "country": "Poland",
             "city": "poznan",
             "certificates": [
               "Adobe Commerce Front-End Developer Expert",
               "Adobe Commerce Developer Professional"
             ],
             "services": [
               "Ecommerce Mobile App Development"
             ],
             "latitude": 52.4064,
             "longitude": 16.9252,
             "distance": "50km"
           }
         }'   
```

Content-Type: `application/json`

```json
{
    "filters": {
        "country": "Poland",
        "city": "poznan",
        "certificates": [
            "Adobe Commerce Front-End Developer Expert",
            "Adobe Commerce Developer Professional"
        ],
        "services": [
            "Ecommerce Mobile App Development"
        ],
        "latitude": 52.4064,
        "longitude": 16.9252,
        "distance": "50km"
    }
}

```

## GEO Search

Fields:

* `latitude`
* `longitude`
* `distance`

needs to be **YOUR** location


## Support

For support and questions, please contact:
- Email: extensions@qoliber.com
- Website: https://qoliber.com

## License

This module is licensed under the MIT License - see the LICENSE file for details.

## Version

Current version: 1.0.0

## Authors

- Jakub Winkler (jwinkler@qoliber.com)
