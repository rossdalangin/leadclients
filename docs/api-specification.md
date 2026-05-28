# GrowthPress Elite: API & Integration Specification

The GrowthPress OS is built with a REST-first architecture, allowing seamless integration with Zapier, Make.com, and custom enterprise middleware.

## 🔒 Authentication
All requests must include the `growthpress_api_token` in the Bearer header.
`Authorization: Bearer YOUR_TOKEN`

## 📡 Endpoints

### 1. Lead Injection (`POST /wp-json/gp/v1/leads`)
Inject leads from external sources (e.g., Facebook Lead Ads).
**Payload**: `{"name": "...", "email": "...", "message": "...", "niche": "..."}`

### 2. Appointment Webhook (`POST /wp-json/gp/v1/missed-call`)
Twilio webhook endpoint for automated missed call follow-up.
**Logic**: Triggers the AI to generate a niche-aware SMS response.

### 3. Strategy Fetch (`GET /wp-json/gp/v1/strategy/{lead_id}`)
Retrieve AI-generated sales insights and probability scores for a specific lead.

### 4. Portal Sync (`GET /wp-json/gp/v1/portal/{user_id}`)
Fetch active proposals and case statuses for external client dashboards.

---

## 🛠️ Internal Hooks for Developers
*   `gp_lead_captured`: Triggered after successful intake.
*   `gp_proposal_accepted`: Triggered after one-click acceptance in the portal.
*   `gp_niche_lead_analysis`: Fires after the AI completes sentiment and intent scoring.
