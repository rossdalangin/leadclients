<div class="wrap growthpress-dashboard">
    <div class="dashboard-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h1><?php echo esc_html(get_option('growthpress_brand_name', 'GrowthPress')); ?> OS</h1>
        <div class="status-badge" style="background:#10B981; color:white; padding:5px 12px; border-radius:20px; font-size:11px; font-weight:bold;">AI ACTIVE</div>
    </div>

    <div class="dashboard-grid" style="display:grid; grid-template-columns: 2fr 1fr; gap:25px;">
        <div class="main-col">
            <!-- Launch Roadmap -->
            <div class="glass-card" style="background: linear-gradient(135deg, #2563EB 0%, #1e40af 100%); color:white; border:none;">
                <h3 style="color:white;">🚀 Launch Status</h3>
                <div style="background:rgba(255,255,255,0.2); height:8px; border-radius:10px; margin:15px 0;">
                    <div style="background:#10B981; height:100%; width:75%; border-radius:10px;"></div>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:11px;">
                    <span>Step 3 of 4: Market Initialization</span>
                    <span>75% Complete</span>
                </div>
            </div>

            <!-- Niche Pro Tips -->
            <div class="glass-card" style="border-left: 4px solid #F59E0B;">
                <h3>💡 Growth Tip for <?php echo ucfirst(get_option('growthpress_niche')); ?></h3>
                <p style="font-style:italic;">
                    <?php
                    $niche = get_option('growthpress_niche');
                    $tips = array(
                        'dental' => 'Use the Smile Gallery to showcase Invisalign results—this niche converts 40% higher with visual proof.',
                        'solar' => 'Leads interested in "financing" are high-intent. Trigger the AI ROI Consultant immediately.',
                        'law' => 'Focus on "Urgency Detection." Leads mentioning "deadline" should be moved to litigation stage instantly.'
                    );
                    echo $tips[$niche] ?? 'Keep your AI Content Studio updated with local keywords to dominate your regional search results.';
                    ?>
                </p>
            </div>

            <!-- Kanban -->
            <div id="gp-kanban-board">
                <!-- Kanban logic... -->
            </div>
        </div>

        <div class="side-col">
            <div class="glass-card">
                <h3>System Activity</h3>
                <ul style="font-size:12px;">
                    <?php foreach(GrowthPress_Activity::get_logs() as $log) echo "<li>{$log['msg']}</li>"; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
