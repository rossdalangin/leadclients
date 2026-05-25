# GrowthPress Business OS: Configuration & Deployment Guide

GrowthPress is an enterprise-grade AI-powered Business Operating System for WordPress. Follow these instructions to configure the system for maximum growth and conversion.

## 🛠️ Step 1: Core System Deployment
1. **Environment**: Ensure you are running WordPress 6.0+ with PHP 7.4+.
2. **Installation**:
   - Upload and activate the `growthpress` theme.
   - Upload and activate the `growthpress-core` plugin.
3. **Identity & Branding**:
   - Navigate to `Appearance > Customize`.
   - **Global Branding**: Set your Primary Brand Color (affects buttons, AI chat, and highlights).
   - **Logo**: Upload your high-resolution business logo.
   - **Hero Content**: Define your primary headline and subheadline for the homepage.

## 🤖 Step 2: AI & Automation Configuration
Navigate to `GrowthPress > Settings` to connect your intelligence engines.

### A. OpenAI Intelligence (Required)
- **API Key**: Input your secret key from [platform.openai.com](https://platform.openai.com).
- **Functionality**: This powers Lead Sentiment analysis, the AI Content Studio, and the Floating Assistant.
- **Lead Scoring**: Set your "Hot Lead Threshold" (Standard: 80). Leads scoring above this will trigger priority notifications.

### B. Twilio & SMS (Optional)
- **SID / Token**: Input your Twilio credentials.
- **Functionality**: Enables automated SMS appointment reminders and missed-call follow-ups.

### C. WhatsApp Business (Optional)
- **API Key**: Input your Meta/WhatsApp developer key.
- **Functionality**: High-ticket client communication and proposal notifications.

## 🚀 Step 3: Niche Initialization (One-Click)
1. Navigate to the **GrowthPress Dashboard** in your WP Admin.
2. In the **OS Launch Wizard**, select your specific industry (e.g., Law Firm, Solar Company).
3. Click **"Initialize OS"**.
4. **What Happens**: The system will automatically generate:
   - Optimized **Home, Services, FAQ, Pricing,** and **Contact** pages.
   - Industry-specific **Calculators, Triage Forms,** and **Shortcodes**.
   - Realistic **Sample Data** (Leads, Cases, Projects) to populate your CRM immediately.

## 📊 Step 4: CRM & Team Onboarding
1. **User Setup**: Add your team members as WordPress users (Roles: Editor or Author).
2. **Permissions**: Go to `GrowthPress > Settings` and define which roles have access to the Kanban board.
3. **Lead Routing**: Ensure your Multi-Location settings (if applicable) have correct ZIP codes to route leads to the nearest branch.

## 🔧 Step 5: Advanced Shortcode Deployment
You can use the following shortcodes to add system features to any page:
- `[gp_lead_form]`: The primary high-converting intake form.
- `[gp_quiz_lead_form]`: Multi-step qualification quiz.
- `[gp_booking_form]`: Staff-aware appointment scheduling.
- `[gp_ai_faq]`: Conversational AI knowledge base.
- `[gp_client_portal]`: Secure area for client document management.

---

## 🔄 Sync & Regeneration
If you update your global settings (e.g., Change of Address, New Primary Color, or Updated Hero Headlines), you can instantly sync your core pages:
1. Go to **GrowthPress > Settings** and click **"Regenerate Core Assets"**.
2. OR open the **Customizer** and navigate to the **"OS Maintenance & Sync"** section. Here you can selectively choose which pages to refresh.
3. **Behavior**: This will update existing pages (Home, Services, etc.) with the new data while preserving your Custom Post Types and user-created content. The system uses robust title-based collision detection to ensure no duplicate pages are created.

---

*For technical support or deep customization, refer to the documentation in the `/docs` folder.*
