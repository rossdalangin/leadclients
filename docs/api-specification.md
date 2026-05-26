# GrowthPress REST API Specification

The GrowthPress OS provides a secure REST API for external integrations (e.g., Zapier, Make, Twilio).

## Authentication
All requests must include a Bearer Token in the authorization header.
- **Header**: `Authorization: Bearer YOUR_API_TOKEN`
- **Setup**: Configure your token in `GrowthPress > Settings`.

## Endpoints

### 1. Create Lead
- **URL**: `/wp-json/growthpress/v1/leads`
- **Method**: `POST`
- **Parameters**:
  - `name` (string, required): Full name of the prospect.
  - `email` (string): Prospect's email address.
  - `msg` (string): Details of the inquiry.
  - `zip` (string): Used for multi-location routing.
- **Behavior**: Automatically triggers AI sentiment analysis and lead scoring upon ingestion.

### 2. Missed Call Webhook (Twilio)
- **URL**: `/wp-json/growthpress/v1/missed-call`
- **Method**: `POST`
- **Parameters**:
  - `From` (string, required): The phone number that called.
- **Behavior**: Logs the missed call in the Activity Feed and generates an AI-personalized SMS response for re-engagement.

### 3. Track Funnel Event
- **URL**: `/wp-json/growthpress/v1/track-funnel`
- **Method**: `POST`
- **Parameters**:
  - `funnel_id` (int): ID of the `gp_funnel` CPT.
  - `variation` (string): 'A' or 'B'.
- **Behavior**: Increments the performance counter for the specified variation, used in the ROI Report.

## Response Codes
- `201 Created`: Object successfully created.
- `200 OK`: Request handled successfully.
- `401 Unauthorized`: Missing or invalid Bearer Token.
- `400 Bad Request`: Missing required parameters.
