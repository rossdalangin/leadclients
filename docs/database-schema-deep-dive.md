# GrowthPress Database Schema & Metadata Mapping

For enterprise-level data integration, this document provides the granular mapping of all Custom Post Types, Taxonomies, and Meta Keys.

## 1. Custom Post Types (CPTs)
- `gp_lead`: The core prospect record.
- `gp_task`: Internal operational tasks, often AI-generated.
- `gp_appointment`: Scheduling records for multi-staff booking.
- `gp_proposal`: Strategic service quotes and contracts.
- `gp_transaction`: Financial records from the Payments engine.
- `gp_kb`: Knowledge Base/Authority articles.
- `gp_project`: Case studies and portfolio items.
- `gp_service`: Core business service lines.

## 2. Taxonomies
- `gp_lead_stage`: pipeline status (new, qualified, booked, closed).
- `gp_lead_tag`: segmentation (residential, commercial, enterprise).

## 3. Metadata Reference (Prefix: `_gp_`)
| Meta Key | Context | Description |
| :--- | :--- | :--- |
| `_lead_score` | Lead | AI-calculated quality (0-100). |
| `_ai_probability` | Lead | Predicted close percentage. |
| `_ai_sentiment_json` | Lead | Granular AI sentiment analysis data. |
| `_behavior_log` | Lead | JSON array of user page views. |
| `_proposal_value` | Proposal | Estimated dollar value of the deal. |
| `_appointment_date` | Appointment | Scheduled date/time string. |
| `_telemedicine_link` | Appointment | Auto-generated meeting URL. |
| `_is_waiting_list` | Appointment | Boolean flag for priority queuing. |
| `_reschedule_requested`| Appointment | Boolean flag for client portal requests. |

## 4. Operational Options
- `growthpress_niche`: The active industry persona.
- `growthpress_api_token`: Secure Bearer token for REST API.
- `gp_activity_logs`: Serialized array of system events.
