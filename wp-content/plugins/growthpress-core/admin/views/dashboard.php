<div class="wrap growthpress-dashboard">
    <div class="dashboard-header gp-reveal">
        <div style="display:flex; align-items:center; gap:25px;">
            <?php $dash_logo = get_option('growthpress_dashboard_logo'); if($dash_logo): ?>
                <img src="<?php echo esc_url($dash_logo); ?>" style="max-height:50px;">
            <?php else: ?>
                <h1 style="font-size:2.5rem; font-weight:950; letter-spacing:-0.08em; margin:0; line-height:1;"><?php echo esc_html(get_option('growthpress_brand_name', 'GrowthPress')); ?> <span style="font-weight:300; opacity:0.3;">OS</span></h1>
            <?php endif; ?>
            <div style="height:35px; width:1px; background:rgba(0,0,0,0.08);"></div>
            <select id="gp-niche-switcher" onchange="switchNiche(this.value)" style="background:rgba(255,255,255,0.5); border:1px solid rgba(0,0,0,0.1); padding:8px 15px; border-radius:12px; font-size:10px; font-weight:950; letter-spacing:2px; text-transform:uppercase; cursor:pointer;">
                <?php
                $active_niche = get_option('growthpress_niche', 'business');
                $niches = array('dental', 'law', 'contractor', 'roofing', 'solar', 'accounting', 'medical', 'real-estate', 'coaches', 'consultants');
                foreach($niches as $n): ?>
                    <option value="<?php echo $n; ?>" <?php selected($n, $active_niche); ?>><?php echo strtoupper($n); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="display:flex; gap:15px; align-items:center;">
            <div style="position:relative;" id="gp-search-container">
                <input type="text" id="gp-strategic-search" placeholder="Strategic Search..." style="background:rgba(255,255,255,0.9); border:1px solid rgba(0,0,0,0.1); padding:10px 20px; border-radius:20px; font-size:11px; width:220px;">
                <span class="dashicons dashicons-search" style="position:absolute; right:15px; top:10px; opacity:0.3;"></span>
                <div id="gp-search-results" style="display:none; position:absolute; top:50px; left:0; width:100%; background:white; border-radius:15px; box-shadow:0 20px 40px rgba(0,0,0,0.1); z-index:1000; overflow:hidden;"></div>
            </div>
            <div class="dark-mode-toggle" onclick="toggleDarkMode()" title="Toggle Strategic Dark Mode" style="width:40px; height:40px;"><span class="dashicons dashicons-visibility"></span></div>
            <button class="gp-btn" style="padding:10px 20px; font-size:11px; border-radius:12px; background:var(--secondary); color:white !important;" onclick="exportLeads()">EXPORT INTEL</button>
            <div class="ai-status" style="background:#10B981; color:white; padding:10px 20px; border-radius:30px; font-size:11px; font-weight:950; letter-spacing:1px; box-shadow:0 15px 30px rgba(16,185,129,0.25);">CORE ACTIVE</div>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="main-col">
            <!-- Strategic Performance Engine -->
            <div class="glass-card gp-reveal" style="margin-bottom:30px; padding:30px;">
                <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:30px;">
                    <div>
                        <h3 style="margin:0; font-size:22px; font-weight:950; letter-spacing:-0.04em;">Intelligence Performance Hub</h3>
                        <p style="font-size:15px; opacity:0.6; margin-top:8px;">Real-time trajectory modeling across all high-ticket conversion vectors.</p>
                    </div>
                    <div style="text-align:right;">
                        <span style="font-size:11px; font-weight:950; opacity:0.4; letter-spacing:2px;">ENGINE LATENCY</span><br>
                        <span style="color:#10B981; font-weight:900; font-size:14px;">142ms (OPTIMAL)</span>
                    </div>
                </div>
                <div class="stats-grid">
                    <div class="stat gp-reveal">
                        <span style="font-size:10px; font-weight:950; color:#64748B; text-transform:uppercase; letter-spacing:2px;">Captured Inquiries</span>
                        <b><?php echo $lead_count_30d; ?></b>
                        <div style="position:absolute; bottom:0; left:0; height:4px; width:100%; background:var(--primary);"></div>
                    </div>
                    <div class="stat gp-reveal" style="animation-delay: 0.1s;">
                        <span style="font-size:10px; font-weight:950; color:#64748B; text-transform:uppercase; letter-spacing:2px;">Strategy Sessions</span>
                        <b style="color:#10B981;"><?php echo $booking_count; ?></b>
                        <div style="position:absolute; bottom:0; left:0; height:4px; width:100%; background:#10B981;"></div>
                    </div>
                    <div class="stat gp-reveal" style="animation-delay: 0.2s;">
                        <span style="font-size:10px; font-weight:950; color:#64748B; text-transform:uppercase; letter-spacing:2px;">Pipeline Equity</span>
                        <?php $pipe_val = GrowthPress_Proposals::get_instance()->get_pipeline_value(); ?>
                        <b style="color:var(--primary);">$<?php echo number_format($pipe_val); ?></b>
                        <div style="position:absolute; bottom:0; left:0; height:4px; width:100%; background:var(--primary);"></div>
                    </div>
                </div>
                <div style="display:grid; grid-template-columns: 1.5fr 1fr; gap:30px;">
                    <div style="background:rgba(255,255,255,0.4); padding:40px; border-radius:40px; border: 1px solid rgba(255,255,255,0.8); box-shadow:inset 0 10px 30px rgba(0,0,0,0.02);">
                        <div style="font-size:11px; font-weight:950; opacity:0.4; letter-spacing:1px; margin-bottom:30px; display:flex; align-items:center; gap:10px;">
                            <div style="width:8px; height:8px; background:var(--primary); border-radius:50%;"></div> CONVERSION TRAJECTORY
                        </div>
                        <canvas id="gp-main-chart" height="150"></canvas>
                    </div>
                    <div style="background:rgba(255,255,255,0.4); padding:40px; border-radius:40px; border: 1px solid rgba(255,255,255,0.8); box-shadow:inset 0 10px 30px rgba(0,0,0,0.02);">
                        <div style="font-size:11px; font-weight:950; opacity:0.4; letter-spacing:1px; margin-bottom:30px; display:flex; align-items:center; gap:10px;">
                            <div style="width:8px; height:8px; background:var(--accent); border-radius:50%;"></div> MARKETING VELOCITY
                        </div>
                        <canvas id="gp-velocity-chart" height="230"></canvas>
                    </div>
                </div>
            </div>

            <!-- CRM Board -->
            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:60px; margin-bottom:40px;">
                <h3 style="display:flex; align-items:center; gap:15px; font-size:28px; letter-spacing:-0.05em; margin:0;">
                    <span class="dashicons dashicons-networking" style="color: var(--primary); font-size:32px; width:32px; height:32px;"></span> Neural Sales Command
                </h3>
                <div style="font-size:12px; font-weight:900; opacity:0.4; letter-spacing:1px;">SORT BY: STRATEGIC PRIORITY</div>
            </div>

            <div id="gp-kanban-board" class="kanban-board-container">
                <?php foreach ( $stages as $slug => $label ) : ?>
                    <div class="kanban-col" data-stage="<?php echo $slug; ?>">
                        <h4 style="margin-top:0; font-weight:950; color:var(--secondary); display:flex; justify-content:space-between; align-items:center; text-transform: uppercase; letter-spacing:2px; font-size:11px; padding:0 10px; opacity:0.6;">
                            <?php echo $label; ?>
                            <span style="font-size:11px; background:#FFF; border:1px solid #E2E8F0; padding:4px 14px; border-radius:30px; color:var(--secondary);">
                                <?php
                                $count_in_stage = count(array_filter($leads, function($l) use ($slug) {
                                    $s = wp_get_object_terms($l->ID, 'gp_lead_stage', array('fields' => 'slugs'));
                                    return (empty($s) && $slug === 'new') || in_array($slug, $s);
                                }));
                                echo $count_in_stage;
                                ?>
                            </span>
                        </h4>
                        <div class="kanban-cards" style="min-height:600px; margin-top:30px;">
                            <?php foreach ( $leads as $lead ) :
                                $stage = wp_get_object_terms( $lead->ID, 'gp_lead_stage', array('fields' => 'slugs') );
                                if ( (empty($stage) && $slug === 'new') || in_array($slug, $stage) ) :
                                    $prob = get_post_meta($lead->ID, '_gp_ai_probability', true) ?: 50;
                                    $staff_id = get_post_meta($lead->ID, '_assigned_staff', true);
                                    $staff = $staff_id ? get_userdata($staff_id) : null;
                                    ?>
                                    <div class="kanban-card glass-card <?php echo $prob > 85 ? 'neural-pulse' : ''; ?> gp-reveal" data-id="<?php echo $lead->ID; ?>" style="border-left: 8px solid <?php echo $prob > 80 ? '#10B981' : 'var(--primary)'; ?>; padding:25px;">
                                        <?php if($prob > 88): ?>
                                            <div style="position: absolute; top: 0; right: 0; background: linear-gradient(135deg, #EF4444, #B91C1C); color: white; font-size: 8px; font-weight: 950; padding: 4px 20px; transform: rotate(45deg) translate(15px, -15px); text-transform: uppercase; letter-spacing:1px; box-shadow:0 5px 15px rgba(239,68,68,0.3);">HOT</div>
                                        <?php endif; ?>

                                        <strong style="display:block; margin-bottom:12px; font-size:16px; font-weight:950; letter-spacing:-0.04em; color:var(--secondary);"><?php echo esc_html($lead->post_title); ?></strong>

                                        <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                                            <div>
                                                <?php $tag = wp_get_object_terms($lead->ID, 'gp_lead_tag', array('fields' => 'names')); if($tag): ?>
                                                    <div style="font-size:10px; background:var(--primary-glow); color:var(--primary); display:inline-block; padding:6px 15px; border-radius:30px; margin-bottom:20px; font-weight:900; text-transform: uppercase; letter-spacing:1px; border:1px solid rgba(37,99,235,0.08);"><?php echo esc_html($tag[0]); ?></div>
                                                <?php endif; ?>
                                                <div style="font-size:12px; font-weight:950; color:#10B981; letter-spacing:0.5px;">PROBABILITY: <?php echo $prob; ?>%</div>
                                            </div>

                                            <div class="staff-avatar" title="<?php echo $staff ? esc_attr($staff->display_name) : 'UNASSIGNED'; ?>" style="width: 40px; height: 40px; border-radius: 50%; background: #FFF; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 950; border: 3px solid #F8FAFC; box-shadow: 0 10px 20px rgba(0,0,0,0.08); color:var(--secondary);">
                                                <?php echo $staff ? substr($staff->display_name, 0, 1) : '?'; ?>
                                            </div>
                                        </div>

                                        <div style="margin-top:20px; display: flex; gap:8px;">
                                            <a href="<?php echo get_edit_post_link($lead->ID); ?>" class="gp-btn" style="flex:1; padding:8px; font-size:10px; border-radius:10px; background:var(--secondary); text-align:center; color:white !important;">INTEL BRIEF</a>
                                            <button class="gp-btn" style="padding:8px; border-radius:10px; background:transparent; border:1px solid #E2E8F0; color:var(--secondary) !important; width:40px;"><span class="dashicons dashicons-email"></span></button>
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
            <!-- Neural Activity Feed -->
            <div class="glass-card gp-reveal" style="margin-bottom:30px;">
                <h3 style="font-size:14px; text-transform: uppercase; letter-spacing: 2px; opacity: 0.4; margin-bottom:25px; font-weight:950;">Neural Activity</h3>
                <div style="display:grid; gap:20px;">
                    <?php
                    $logs = GrowthPress_Activity::get_logs(6);
                    if($logs): foreach($logs as $log): ?>
                        <div style="display:flex; gap:15px; position:relative;">
                            <div style="width:6px; height:6px; background:var(--primary); border-radius:50%; margin-top:6px; box-shadow:0 0 10px var(--primary-glow);"></div>
                            <div>
                                <div style="font-size:12px; font-weight:700; line-height:1.4; color:var(--secondary);"><?php echo esc_html($log['msg']); ?></div>
                                <div style="font-size:9px; opacity:0.4; font-weight:800; margin-top:4px;"><?php echo strtoupper($log['time']); ?></div>
                            </div>
                        </div>
                    <?php endforeach; else: echo "<p style='opacity:0.4; font-size:11px;'>Neural core warming up...</p>"; endif; ?>
                </div>
            </div>

            <!-- AI Strategic Insight -->
            <div class="glass-card gp-reveal" style="border-top: 8px solid #10B981; margin-bottom:30px;">
                <h3 style="font-size:14px; text-transform: uppercase; letter-spacing: 2px; opacity: 0.4; margin-bottom: 20px; font-weight:950;">Strategic Vector</h3>
                <?php
                $niche = get_option('growthpress_niche', 'business');
                $insights = array(
                    'solar'       => "High electricity load profiles in your sector are driving an 18% increase in 'S-Tier' system inquiries.",
                    'law'         => "Leads from high-wealth ZIP codes are peaking. Calibrate AI merit engine for 'Corporate' litigation.",
                    'medical'     => "Post-holiday intake volume is rising. Ensure AI Health Assistant is optimized for routing.",
                );
                ?>
                <p style="font-size:15px; line-height:1.6; font-weight: 600; color:var(--secondary); margin-bottom:25px;"><?php echo $insights[$niche] ?? "Market authority is peaking. Implement tiered 'Elite' membership model to capture high-intent interest."; ?></p>
                <div style="background:linear-gradient(135deg, var(--primary), var(--primary-alt)); padding:20px; border-radius:20px; box-shadow:0 15px 30px var(--primary-glow);">
                    <div style="font-size:9px; font-weight:950; color:white; opacity:0.7; letter-spacing:2px; margin-bottom:8px;">STRATEGIC COMMAND</div>
                    <div style="color:white; font-weight:950; font-size:13px; line-height:1.3;">EXECUTE OMNI-CHANNEL RETARGETING</div>
                </div>
            </div>

            <!-- Autonomous Agent Feed -->
            <div class="glass-card gp-reveal" style="margin-bottom:30px;">
                <h3 style="font-size:14px; text-transform: uppercase; letter-spacing: 2px; opacity: 0.4; margin-bottom:25px; font-weight:950;">Agent Task Queue</h3>
                <div style="display:grid; gap:15px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; font-weight:700;">
                        <span>AI Triage: Lead #422</span>
                        <span style="color:#10B981;">ACTIVE</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; font-weight:700;">
                        <span>SEO Content: "Solar ROI"</span>
                        <span style="opacity:0.4;">QUEUED</span>
                    </div>
                </div>
            </div>

            <!-- Staff Efficiency Hub -->
            <div class="glass-card gp-reveal" style="margin-bottom:30px;">
                <h3 style="font-size:14px; text-transform: uppercase; letter-spacing: 2px; opacity: 0.4; margin-bottom:25px; font-weight:950;">Staff Efficiency</h3>
                <div style="display:grid; gap:15px;">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-size:12px; font-weight:700;">Avg. Response</span>
                        <span style="font-size:12px; font-weight:950; color:#10B981;">4.2 min</span>
                    </div>
                    <div style="height:5px; background:#F1F5F9; border-radius:10px; overflow:hidden;">
                        <div style="width:85%; height:100%; background:var(--primary);"></div>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:5px;">
                        <span style="font-size:12px; font-weight:700;">Closing Rate</span>
                        <span style="font-size:12px; font-weight:950; color:#10B981;">28.4%</span>
                    </div>
                    <div style="height:5px; background:#F1F5F9; border-radius:10px; overflow:hidden;">
                        <div style="width:62%; height:100%; background:#10B981;"></div>
                    </div>
                </div>
            </div>

            <!-- Revenue Forecast Engine -->
            <div class="forecast-widget">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div>
                        <div style="font-size:11px; font-weight:950; opacity:0.4; letter-spacing:2px; margin-bottom:5px;">REVENUE FORECAST</div>
                        <div style="font-size:32px; font-weight:950;">$<?php echo number_format($pipe_val * 0.65); ?></div>
                    </div>
                    <div style="background:rgba(16,185,129,0.2); color:#10B981; padding:6px 12px; border-radius:20px; font-size:10px; font-weight:950;">+12.4%</div>
                </div>
                <div style="margin-top:25px; font-size:12px; opacity:0.6; line-height:1.6;">
                    AI models predict a 65% weighted conversion probability for current high-intent pipeline items.
                </div>
                <div style="margin-top:20px; height:6px; background:rgba(255,255,255,0.05); border-radius:10px; overflow:hidden;">
                    <div style="width:65%; height:100%; background:#10B981; box-shadow:0 0 15px rgba(16,185,129,0.5);"></div>
                </div>
            </div>

            <!-- Conversion Command -->
            <div class="glass-card" style="background: var(--secondary); color: white; border: none; border-radius:40px; padding:45px; margin-top:40px;">
                <h3 style="color: white; font-size: 16px; letter-spacing:1px; font-weight:950;">Funnel Command</h3>
                <div style="height:280px; display:flex; align-items:flex-end; gap:25px; padding: 40px 0;">
                    <div style="flex:1; height:100%; background:var(--primary); border-radius:15px; display:flex; align-items:center; justify-content:center; color:white; font-size:11px; font-weight:950; writing-mode:vertical-rl; box-shadow:0 0 25px var(--primary-glow);">INTAKE</div>
                    <div style="flex:1; height:85%; background:#10B981; border-radius:15px; display:flex; align-items:center; justify-content:center; color:white; font-size:11px; font-weight:950; writing-mode:vertical-rl;">NEURAL TRIAGE</div>
                    <div style="flex:1; height:52%; background:#F59E0B; border-radius:15px; display:flex; align-items:center; justify-content:center; color:white; font-size:11px; font-weight:950; writing-mode:vertical-rl;">STRATEGY</div>
                    <div style="flex:1; height:24%; background:#EF4444; border-radius:15px; display:flex; align-items:center; justify-content:center; color:white; font-size:11px; font-weight:950; writing-mode:vertical-rl;">EQUITY</div>
                </div>
                <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 25px; display: flex; justify-content: space-between; font-size: 13px; font-weight: 900;">
                    <span style="opacity:0.5;">CONV. VELOCITY</span>
                    <span style="color:var(--accent);">10.8 DAYS</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function switchNiche(niche) {
    if(confirm('Switching ecosystem to ' + niche.toUpperCase() + '? This will recalibrate Neural Hub prompts.')) {
        jQuery.post(ajaxurl, {
            action: 'gp_setup_niche',
            niche: niche,
            gp_nonce: gp_admin.nonce
        }, function() { location.reload(); });
    }
}

function toggleDarkMode() {
    document.querySelector('.growthpress-dashboard').classList.toggle('gp-dark-mode');
}

function exportLeads() {
    window.location.href = ajaxurl + '?action=gp_export_leads&gp_nonce=' + gp_admin.nonce;
}

document.addEventListener('DOMContentLoaded', function() {
    jQuery('#gp-strategic-search').on('keyup', function() {
        const q = jQuery(this).val();
        if(q.length < 3) { jQuery('#gp-search-results').hide(); return; }
        jQuery.post(ajaxurl, { action: 'gp_strategic_search', query: q, gp_nonce: gp_admin.nonce }, function(res) {
            if(res.success) jQuery('#gp-search-results').show().html(res.data.html);
        });
    });
    var ctxMain = document.getElementById('gp-main-chart').getContext('2d');
    new Chart(ctxMain, {
        type: 'line',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5'],
            datasets: [{
                data: [22, 48, 36, 75, 68],
                borderColor: '#2563EB',
                borderWidth: 8,
                tension: 0.5,
                pointRadius: 0,
                fill: true,
                backgroundColor: 'rgba(37, 99, 235, 0.04)'
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { display: false }, x: { grid: { display: false }, ticks: { font: { weight: '900', size: 10, family: 'Inter' } } } }
        }
    });

    var ctxVel = document.getElementById('gp-velocity-chart').getContext('2d');
    new Chart(ctxVel, {
        type: 'bar',
        data: {
            labels: ['M', 'T', 'W', 'T', 'F'],
            datasets: [{
                label: 'Ad Spend',
                data: [120, 190, 150, 250, 210],
                backgroundColor: '#E2E8F0',
                borderRadius: 10
            }, {
                label: 'Pipeline Value',
                data: [400, 650, 590, 900, 820],
                backgroundColor: '#2563EB',
                borderRadius: 10
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { display: false }, x: { grid: { display: false }, stacked: true } }
        }
    });
});
</script>
