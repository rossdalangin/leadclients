<div class="wrap growthpress-dashboard">
    <div class="dashboard-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
        <h1><?php echo esc_html(get_option('growthpress_brand_name', 'GrowthPress')); ?> OS</h1>
        <div class="ai-status" style="background:#10B981; color:white; padding:5px 12px; border-radius:20px; font-size:11px; font-weight:bold;">AI ACTIVE</div>
    </div>

    <?php if ( ! get_option('growthpress_niche') ) : ?>
    <div class="setup-wizard glass-card" style="margin-bottom: 25px; border: 2px solid #2563EB;">
        <h3>🚀 OS Launch Wizard</h3>
        <p>Your Business Operating System is almost ready. Select your industry to generate optimized pages, AI prompts, and demo data.</p>
        <div style="display:flex; gap:15px; align-items:center;">
            <select id="gp-niche-select" style="padding:10px; border-radius:8px; border:1px solid #ddd; flex:1;">
                <option value="dental">Dental Clinic</option>
                <option value="law">Law Firm</option>
                <option value="contractor">Contracting Company</option>
                <option value="roofing">Roofing Company</option>
                <option value="solar">Solar Company</option>
                <option value="accounting">Accounting Firm</option>
                <option value="medical">Medical Clinic</option>
                <option value="real-estate">Real Estate Team</option>
                <option value="coaches">Business Coach</option>
                <option value="consultants">Consultancy</option>
            </select>
            <button class="button button-primary button-hero" onclick="setupNiche()">Initialize OS</button>
        </div>
    </div>
    <?php endif; ?>

    <div class="dashboard-grid" style="display:grid; grid-template-columns: 2fr 1fr; gap:25px;">
        <div class="main-col">
            <!-- Growth Overview -->
            <div class="glass-card">
                <h3>Executive Summary</h3>
                <div class="stats-grid" style="display:flex; gap:20px; margin-bottom:20px;">
                    <div class="stat" style="flex:1;">
                        <span style="font-size:12px; color:#666;">Leads (30d)</span>
                        <div style="font-size:24px; font-weight:bold; color:#2563EB;">128</div>
                    </div>
                    <div class="stat" style="flex:1;">
                        <span style="font-size:12px; color:#666;">Bookings</span>
                        <div style="font-size:24px; font-weight:bold; color:#10B981;">32</div>
                    </div>
                    <div class="stat" style="flex:1;">
                        <span style="font-size:12px; color:#666;">Conv. Rate</span>
                        <div style="font-size:24px; font-weight:bold; color:#F59E0B;">25%</div>
                    </div>
                </div>
                <canvas id="gp-main-chart" height="100"></canvas>
            </div>

            <!-- Kanban -->
            <div id="gp-kanban-board" style="display:flex; gap:15px; overflow-x:auto; margin-top:30px;">
                <?php foreach ( $stages as $slug => $label ) : ?>
                    <div class="kanban-col" data-stage="<?php echo $slug; ?>" style="min-width:220px; background:#f8fafc; padding:15px; border-radius:12px; border:1px solid #e2e8f0;">
                        <h4 style="margin-top:0; color:#1e293b;"><?php echo $label; ?></h4>
                        <div class="kanban-cards" style="min-height:200px;">
                            <?php foreach ( $leads as $lead ) :
                                $stage = wp_get_object_terms( $lead->ID, 'gp_lead_stage', array('fields' => 'slugs') );
                                if ( (empty($stage) && $slug === 'new') || in_array($slug, $stage) ) :
                                    $prob = get_post_meta($lead->ID, '_gp_ai_probability', true) ?: 50; ?>
                                    <div class="kanban-card glass-card" data-id="<?php echo $lead->ID; ?>" style="background:white; margin-bottom:12px; padding:12px; cursor:grab; position:relative; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                                        <strong style="display:block; margin-bottom:5px;"><?php echo esc_html($lead->post_title); ?></strong>
                                        <div class="gp-probability" style="font-size:10px; color:#10B981; font-weight:600;">
                                            AI Confidence: <?php echo $prob; ?>%
                                        </div>
                                        <div class="gp-next-step" style="font-size:9px; background:#f1f5f9; padding:4px; border-radius:4px; margin-top:5px;">
                                            AI Suggests: <?php echo $prob > 80 ? 'Send Proposal' : 'Qualifying Call'; ?>
                                        </div>
                                    </div>
                                <?php endif;
                            endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="side-col">
            <!-- Lead Sources -->
            <div class="glass-card">
                <h3>Lead Sources</h3>
                <canvas id="gp-source-chart" height="200"></canvas>
            </div>

            <!-- Success Roadmap -->
            <div class="glass-card" style="border-left: 4px solid #F59E0B;">
                <h3>Success Roadmap</h3>
                <div class="roadmap-steps" style="font-size: 13px;">
                    <div class="step <?php echo get_option('growthpress_openai_api_key') ? 'done' : ''; ?>" style="margin-bottom:10px; opacity: <?php echo get_option('growthpress_openai_api_key') ? '1' : '0.5'; ?>;">
                        ✅ Connect OpenAI API
                    </div>
                    <div class="step <?php echo get_option('growthpress_niche') ? 'done' : ''; ?>" style="margin-bottom:10px; opacity: <?php echo get_option('growthpress_niche') ? '1' : '0.5'; ?>;">
                        🚀 Initialize Niche Setup <?php if(get_option('growthpress_niche')): ?>— <a href="<?php echo home_url(); ?>" target="_blank" style="color:#2563EB;">View Site</a><?php endif; ?>
                    </div>
                    <div class="step" style="margin-bottom:10px; opacity: 0.5;">
                        📞 Setup Twilio SMS
                    </div>
                    <div class="step" style="margin-bottom:10px; opacity: 0.5;">
                        📧 Launch 1st AI Campaign
                    </div>
                </div>
            </div>

            <!-- Team performance -->
            <div class="glass-card">
                <h3>Team Performance</h3>
                <ul style="list-style:none; padding:0; font-size:13px;">
                    <li style="display:flex; justify-content:space-between; margin-bottom:10px;">
                        <span>John Doe</span>
                        <span style="font-weight:bold;">12 Closed</span>
                    </li>
                    <li style="display:flex; justify-content:space-between; margin-bottom:10px;">
                        <span>Jane Smith</span>
                        <span style="font-weight:bold;">8 Closed</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctxMain = document.getElementById('gp-main-chart').getContext('2d');
    new Chart(ctxMain, {
        type: 'line',
        data: { labels: ['W1', 'W2', 'W3', 'W4'], datasets: [{ label: 'Growth', data: [10, 25, 15, 45], borderColor: '#2563EB', fill: true, backgroundColor: 'rgba(37, 99, 235, 0.1)' }] }
    });

    var ctxSource = document.getElementById('gp-source-chart').getContext('2d');
    new Chart(ctxSource, {
        type: 'doughnut',
        data: { labels: ['Google', 'Facebook', 'Referral'], datasets: [{ data: [60, 30, 10], backgroundColor: ['#2563EB', '#10B981', '#F59E0B'] }] }
    });
});
</script>
