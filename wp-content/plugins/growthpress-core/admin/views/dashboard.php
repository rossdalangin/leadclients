<div class="wrap growthpress-dashboard">
    <h1>GrowthPress Business OS</h1>

    <div class="dashboard-grid" style="display:grid; grid-template-columns: 2fr 1fr; gap:20px; margin-top:20px;">
        <div class="main-col">
            <!-- Onboarding Checklist -->
            <div class="glass-card roadmap-widget" style="margin-bottom:20px; border-left: 4px solid #10B981;">
                <h3>🚀 Your Success Roadmap</h3>
                <ul class="checklist" style="list-style:none; padding:0;">
                    <li>✅ Core Plugin Activated</li>
                    <li><?php echo get_option('growthpress_openai_api_key') ? '✅' : '⭕'; ?> OpenAI API Key Configured</li>
                    <li><?php echo get_option('growthpress_niche') ? '✅' : '⭕'; ?> Business Niche Selected</li>
                    <li>⭕ Niche Setup Wizard Launched</li>
                    <li>⭕ First Lead Captured</li>
                </ul>
            </div>

            <!-- Niche Setup -->
            <div class="glass-card setup-wizard" style="margin-bottom:20px;">
                <h3>System Initializer</h3>
                <p>Launch the setup wizard to generate core pages and demo content for your niche.</p>
                <select id="gp-niche-select">
                    <option value="dental">Dental</option>
                    <option value="solar">Solar</option>
                    <option value="law">Law</option>
                </select>
                <button class="button button-primary" onclick="setupNiche()">Initialize OS</button>
            </div>

            <!-- Kanban -->
            <div id="gp-kanban-board" style="display:flex; gap:10px; overflow-x:auto;">
                <!-- Kanban logic... -->
            </div>
        </div>

        <div class="side-col">
            <div class="glass-card">
                <h3>Activity Feed</h3>
                <ul style="font-size:0.8rem;">
                    <?php foreach(GrowthPress_Activity::get_logs() as $log) echo "<li>{$log['msg']}</li>"; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
