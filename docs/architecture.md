# GrowthPress System Architecture

## Overview
GrowthPress is built as a modular WordPress ecosystem where the theme handles the UI/UX (Aesthetics) and the Core Plugin handles the Business Intelligence (Logic).

## Component Map
- `growthpress-core.php`: Entry point, module loader.
- `/includes`: Core engines (AI, CRM, Booking).
- `/modules`: Niche-specific logic (Dental, Law, etc.).
- `/admin`: Management dashboard and setup wizard.
- `/assets`: CSS/JS for both backend and frontend.

## API Architecture
- Integrated with OpenAI Chat Completions (GPT-4) for lead analysis.
- REST API endpoints for external CRM syncing (Webhook support).
