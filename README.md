# Qoliber Professional Service Listing for Magento 2

This module provides professional service listing functionality for Magento 2, allowing customers to create and manage their professional profiles, which can be reviewed and approved by administrators.

## Features

- Customer professional profile management
- Social media links integration
- Certificates and services listing
- Admin approval workflow
- Profile status management (Pending, Approved, Rejected)
- Ajax-based admin actions
- Email notifications for status changes

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

### Manual Installation

1. Create the following directory in your Magento installation:
   `app/code/Qoliber/Psl`

2. Download the module and copy all files into the directory

3. Enable the module:

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
