# GrowthPress System Architecture

## Overview
GrowthPress is built as a modular WordPress ecosystem where the theme handles the UI/UX (Aesthetics) and the Core Plugin handles the Business Intelligence (Logic).

## Component Map
- `growthpress-core.php`: Entry point, module loader.
- `/includes`: Core engines (AI, CRM, Booking, Reputation, Portal).
- `/modules`: Niche-specific logic (Dental, Law, etc.).
- `/admin`: Management dashboard, settings, and AI studio.
- `/assets`: CSS/JS for both backend and frontend.

## Key Modules
- **CRM & Kanban**: Lead management with drag-and-drop pipeline.
- **Booking Engine**: Appointment scheduling with automated reminders.
- **Reputation**: Review management and AI-suggested replies.
- **Client Portal**: Secure area for clients to manage appointments/docs.
- **AI Content Studio**: In-dashboard marketing asset generation.

## API Architecture
- Integrated with OpenAI Chat Completions (GPT-4) for lead analysis.
- REST API endpoints for external CRM syncing (Bearer Token Auth).
