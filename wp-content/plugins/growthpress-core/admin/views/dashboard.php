<div class="wrap growthpress-dashboard">
    <div class="dashboard-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
        <h1><?php echo esc_html(get_option('growthpress_brand_name', 'GrowthPress')); ?> OS</h1>
        <div class="os-version" style="font-size:12px; color:#666;">Version <?php echo GROWTHPRESS_CORE_VERSION; ?></div>
    </div>

    <div class="dashboard-grid" style="display:grid; grid-template-columns: 2fr 1fr; gap:20px;">
        <div class="main-col">
            <!-- New Progress Roadmap -->
            <div class="glass-card roadmap-v2" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: white; border: none;">
                <h3 style="color:white; margin-bottom:20px;">🚀 Launch Readiness</h3>
                <div class="progress-container" style="background:rgba(255,255,255,0.1); height:10px; border-radius:10px; margin-bottom:20px;">
                    <div class="progress-bar" style="background:#10B981; height:100%; width:60%; border-radius:10px;"></div>
                </div>
                <div class="onboarding-steps" style="display:grid; grid-template-columns: repeat(3, 1fr); gap:10px; font-size:12px;">
                    <div class="step done">✅ API Key</div>
                    <div class="step done">✅ Niche Selection</div>
                    <div class="step">⭕ Launch Wizard</div>
                </div>
            </div>

            <!-- Niche Setup -->
            <div class="glass-card">
                <h3>System Initializer</h3>
                <p class="description">Select your industry and click 'Initialize' to generate custom pages, calculators, and demo leads.</p>
                <div style="display:flex; gap:10px;">
                    <select id="gp-niche-select" style="flex:1;">
                        <option value="dental">Dental Clinic</option>
                        <option value="solar">Solar Energy</option>
                        <option value="law">Law Firm</option>
                        <option value="contractor">Home Contractor</option>
                    </select>
                    <button class="button button-primary" onclick="setupNiche()">Initialize OS</button>
                </div>
            </div>

            <!-- Kanban -->
            <div id="gp-kanban-board" style="display:flex; gap:10px; overflow-x:auto;">
                <!-- Kanban items... -->
            </div>
        </div>

        <div class="side-col">
            <div class="glass-card">
                <h3>System Activity</h3>
                <ul class="activity-log" style="font-size:12px; max-height:400px; overflow-y:auto;">
                    <?php foreach(GrowthPress_Activity::get_logs() as $log) echo "<li><strong>{$log['time']}:</strong> {$log['msg']}</li>"; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
