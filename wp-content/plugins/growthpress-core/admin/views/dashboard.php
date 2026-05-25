<div class="wrap growthpress-dashboard">
    <div class="dashboard-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h1><?php echo esc_html(get_option('growthpress_brand_name', 'GrowthPress')); ?> OS</h1>
        <div class="status-badge" style="background:#10B981; color:white; padding:5px 12px; border-radius:20px; font-size:11px; font-weight:bold;">AI BRAIN ACTIVE</div>
    </div>

    <div class="dashboard-grid" style="display:grid; grid-template-columns: 2fr 1fr; gap:25px;">
        <div class="main-col">
            <!-- Launch Roadmap -->
            <div class="glass-card" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color:white; border:none;">
                <h3 style="color:white; margin-bottom:10px;">🚀 Launch Readiness</h3>
                <div style="background:rgba(255,255,255,0.1); height:8px; border-radius:10px; margin:15px 0;">
                    <div style="background:#10B981; height:100%; width:75%; border-radius:10px;"></div>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:11px;">
                    <span>System Status: Nearly Ready to Scale</span>
                    <span>75% Complete</span>
                </div>
            </div>

            <!-- Help & docs -->
            <div class="glass-card" style="border-left: 4px solid #2563EB;">
                <h3>📚 Knowledge Base & System Map</h3>
                <p>Confused about the flow? View the <strong>Lead-to-Cash</strong> visual map and full documentation.</p>
                <a href="<?php echo GROWTHPRESS_CORE_URL . 'docs/system-map.md'; ?>" target="_blank" class="button">View Visual System Map</a>
                <a href="<?php echo admin_url('admin.php?page=growthpress-settings'); ?>" class="button">Configure OS</a>
            </div>

            <!-- Niche Setup -->
            <div class="glass-card">
                <h3>Industry Initializer</h3>
                <p>Build your niche structure. Current Niche: <strong><?php echo ucfirst(get_option('growthpress_niche')); ?></strong></p>
                <select id="gp-niche-select">
                    <option value="dental">Dental</option>
                    <option value="solar">Solar</option>
                    <option value="law">Law</option>
                    <option value="medical">Medical</option>
                </select>
                <button class="button button-primary" onclick="setupNiche()">Re-Initialize System</button>
            </div>
        </div>

        <div class="side-col">
            <div class="glass-card">
                <h3>Live Activity Feed</h3>
                <ul style="font-size:12px; max-height: 300px; overflow-y:auto;">
                    <?php foreach(GrowthPress_Activity::get_logs() as $log) echo "<li>{$log['msg']}</li>"; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
