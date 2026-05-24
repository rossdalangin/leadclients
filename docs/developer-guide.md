# GrowthPress Developer Guide

GrowthPress is designed with a modular, extensible architecture. Developers can easily add new niches or customize automation flows.

## Core Architecture
- **Central Intelligence**: `GrowthPress_AI` handles all OpenAI API communication.
- **Data Structures**: Uses standard WordPress Custom Post Types (CPTs) for Leads, Appointments, Tasks, and Proposals.
- **Modular Niches**: Located in `wp-content/plugins/growthpress-core/modules/`. Each niche is a self-contained class.

## Key Hooks for Extension
- `gp_lead_captured`: Triggered when a new lead is saved. Use this for third-party CRM syncing.
- `gp_appointment_created`: Triggered on new bookings.
- `gp_proposal_accepted`: Triggered when a client accepts a quote in the portal.

## Shortcode Reference
- `[gp_lead_form]`: Secure lead capture.
- `[gp_quiz_lead_form]`: Conversational intake.
- `[gp_booking_form]`: Multi-staff scheduling.
- `[gp_ai_faq]`: Chat-bubble enabled assistant.

## REST API Authentication
All endpoints require a Bearer Token.
`Authorization: Bearer YOUR_TOKEN`
Example Endpoint: `POST /wp-json/growthpress/v1/leads`
