<div class="wrap growthpress-dashboard">
    <div class="dashboard-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
        <h1><?php echo esc_html(get_option('growthpress_brand_name', 'GrowthPress')); ?> OS</h1>
        <div style="display:flex; gap:10px; align-items:center;">
            <button class="button" onclick="exportLeads()">Export CSV</button>
            <div class="ai-status" style="background:#10B981; color:white; padding:5px 12px; border-radius:20px; font-size:11px; font-weight:bold;">AI ACTIVE</div>
        </div>
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

    <div class="system-diagnostics glass-card" style="margin-bottom: 25px; padding: 20px; display:flex; gap:30px; align-items:center;">
        <div style="flex:1;">
            <h3 style="font-size:14px; margin-bottom:10px;">System Diagnostics</h3>
            <div style="display:flex; gap:20px; font-size:11px;">
                <span><strong>API:</strong> <?php echo get_option('growthpress_openai_api_key') ? '✅ Connected' : '❌ Offline'; ?></span>
                <span><strong>OS:</strong> <?php echo get_option('growthpress_niche') ? '✅ Ready' : '❌ Needs Setup'; ?></span>
                <span><strong>PHP:</strong> <?php echo version_compare(PHP_VERSION, '7.4', '>=') ? '✅ OK' : '❌ Update Needed'; ?></span>
            </div>
        </div>
        <div style="text-align:right;">
            <a href="<?php echo admin_url('admin.php?page=growthpress-settings#tab-docs'); ?>" class="button button-small">View Knowledge Base</a>
        </div>
    </div>

    <div class="dashboard-grid" style="display:grid; grid-template-columns: 2fr 1fr; gap:25px;">
        <div class="main-col">
            <!-- Growth Overview -->
            <div class="glass-card">
                <h3>Executive Summary</h3>
                <div class="stats-grid" style="display:flex; gap:20px; margin-bottom:20px;">
                    <div class="stat" style="flex:1;">
                        <span style="font-size:12px; color:#666;">Leads (Total)</span>
                        <div style="font-size:24px; font-weight:bold; color:#2563EB;"><?php echo $lead_count_30d; ?></div>
                    </div>
                    <div class="stat" style="flex:1;">
                        <span style="font-size:12px; color:#666;">Bookings</span>
                        <div style="font-size:24px; font-weight:bold; color:#10B981;"><?php echo $booking_count; ?></div>
                    </div>
                    <div class="stat" style="flex:1;">
                        <span style="font-size:12px; color:#666;">Conv. Rate</span>
                        <div style="font-size:24px; font-weight:bold; color:#F59E0B;"><?php echo $conv_rate; ?>%</div>
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
                                    <div class="kanban-card glass-card" data-id="<?php echo $lead->ID; ?>" style="background:white; margin-bottom:12px; padding:15px; cursor:grab; position:relative; border-left: 4px solid <?php echo $prob > 75 ? '#10B981' : '#2563EB'; ?>;">
                                        <strong style="display:block; margin-bottom:8px;"><?php echo esc_html($lead->post_title); ?></strong>

                                        <?php
                                        $tag = wp_get_object_terms($lead->ID, 'gp_lead_tag', array('fields' => 'names'));
                                        if($tag): ?>
                                            <div style="font-size:9px; background:#eef2ff; color:#4338ca; display:inline-block; padding:2px 6px; border-radius:4px; margin-bottom:8px; font-weight:bold;">
                                                <?php echo esc_html($tag[0]); ?>
                                            </div>
                                        <?php endif; ?>

                                        <div class="gp-probability" style="font-size:10px; color:#10B981; font-weight:700;">
                                            AI Prob: <?php echo $prob; ?>%
                                        </div>

                                        <div class="gp-next-step" style="font-size:10px; background:#f8fafc; padding:6px; border-radius:6px; margin-top:8px; border: 1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center;">
                                            <span>💡 <?php echo $prob > 80 ? 'Draft Proposal' : 'Schedule Discovery'; ?></span>
                                            <a href="<?php echo get_edit_post_link($lead->ID); ?>#gp_lead_insights" style="text-decoration:none;">Brief &rarr;</a>
                                        </div>

                                        <div style="margin-top:10px; display:flex; gap:8px; opacity:0.5;">
                                            <span class="dashicons dashicons-admin-comments" style="font-size:14px;"></span>
                                            <span class="dashicons dashicons-yes-alt" style="font-size:14px;"></span>
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
            <div class="glass-card" style="border-left: 6px solid #2563EB;">
                <h3>🚀 Launch Readiness</h3>
                <div class="roadmap-steps" style="font-size: 13px;">
                    <?php
                    $checks = array(
                        'OpenAI API'  => array('status' => get_option('growthpress_openai_api_key'), 'link' => admin_url('admin.php?page=growthpress-settings')),
                        'Niche OS'    => array('status' => get_option('growthpress_niche'), 'link' => admin_url('admin.php?page=growthpress-dashboard')),
                        'Custom Logo' => array('status' => get_theme_mod('custom_logo'), 'link' => admin_url('customize.php')),
                        'Identity'    => array('status' => true, 'link' => admin_url('customize.php')),
                        'Comms (SMS)' => array('status' => get_option('growthpress_twilio_sid'), 'link' => admin_url('admin.php?page=growthpress-settings'))
                    );
                    foreach($checks as $label => $data): ?>
                        <div class="step" style="margin-bottom:12px; display:flex; justify-content:space-between; align-items:center; opacity: <?php echo $data['status'] ? '1' : '0.4'; ?>;">
                            <div style="display:flex; align-items:center; gap:10px;">
                                <span style="font-size:16px;"><?php echo $data['status'] ? '✅' : '⚪'; ?></span>
                                <strong><?php echo $label; ?></strong>
                            </div>
                            <?php if(!$data['status']): ?><a href="<?php echo $data['link']; ?>" style="font-size:10px; color:#2563EB; text-decoration:none;">Setup</a><?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    <?php if(get_option('growthpress_niche')): ?>
                        <hr style="border:0; border-top:1px solid #eee; margin:15px 0;">
                        <a href="<?php echo home_url(); ?>" target="_blank" class="button button-primary button-small" style="width:100%; text-align:center; display:block;">Preview Live Site</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Pro Tips -->
            <div class="glass-card" style="background: linear-gradient(135deg, #1e293b, #0f172a); color:white;">
                <h3 style="color:white; font-size:14px; text-transform:uppercase; letter-spacing:1px; opacity:0.7;">Industry Pro Tip</h3>
                <?php
                $niche = get_option('growthpress_niche', 'business');
                $tips = array(
                    'dental'    => "Use the 'Smile Gallery' post type to showcase transformation. High-ticket dental leads buy based on visual outcomes.",
                    'law'       => "Urgency is key. Ensure your 'Secure Legal Intake' shortcode is above the fold on your Contact page.",
                    'solar'     => "The ROI Estimator is your best lead magnet. Leads who see savings convert 4x faster.",
                    'medical'   => "The symptom checker creates trust. Use it to route patients to the correct booking calendar instantly."
                );
                $tip = $tips[$niche] ?? "Use the AI Content Studio weekly to target long-tail keywords in your local area.";
                ?>
                <p style="color:rgba(255,255,255,0.9); font-size:14px; line-height:1.6; margin-top:10px;"><?php echo $tip; ?></p>
            </div>

            <!-- Growth Opportunities -->
            <div class="glass-card" style="border-left: 6px solid #7c3aed;">
                <h3 style="color:#7c3aed;">📈 Growth Opportunities</h3>
                <?php
                $waiting = get_posts(array('post_type' => 'gp_appointment', 'meta_key' => '_is_waiting_list', 'meta_value' => '1'));
                $reactivation = get_posts(array('post_type' => 'gp_lead', 'meta_key' => '_reactivation_flagged', 'meta_value' => '1', 'posts_per_page' => 3));
                ?>
                <div style="font-size:12px;">
                    <div style="margin-bottom:10px;"><strong>Waiting List:</strong> <?php echo count($waiting); ?> prospects waiting for slots.</div>
                    <?php if($reactivation): ?>
                        <div style="margin-top:15px;"><strong>Top Reactivation Targets:</strong></div>
                        <?php foreach($reactivation as $rl): ?>
                            <div style="background:#f5f3ff; padding:8px; border-radius:6px; margin-top:5px; border:1px solid #ddd6fe;">
                                <a href="<?php echo get_edit_post_link($rl->ID); ?>" style="text-decoration:none;">⚡ <?php echo esc_html($rl->post_title); ?></a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Team performance -->
            <div class="glass-card">
                <h3>Team Efficiency</h3>
                <?php
                $users = get_users( array( 'role__in' => array('administrator', 'editor', 'author'), 'number' => 5 ) );
                if($users): foreach($users as $u):
                    $assigned_leads = get_posts( array( 'post_type' => 'gp_lead', 'meta_key' => '_assigned_staff', 'meta_value' => $u->ID, 'posts_per_page' => -1 ) );
                    $closed_leads = get_posts( array( 'post_type' => 'gp_lead', 'meta_key' => '_assigned_staff', 'meta_value' => $u->ID, 'tax_query' => array( array( 'taxonomy' => 'gp_lead_stage', 'field' => 'slug', 'terms' => 'closed' ) ), 'posts_per_page' => -1 ) );
                    $count_all = count($assigned_leads);
                    $count_closed = count($closed_leads);
                    $perc = $count_all > 0 ? round(($count_closed / $count_all) * 100) : 0;
                    ?>
                    <div class="team-stat" style="margin-bottom:15px;">
                        <div style="display:flex; justify-content:space-between; font-size:11px; margin-bottom:4px;">
                            <span><?php echo esc_html($u->display_name); ?></span>
                            <span><?php echo $count_closed; ?>/<?php echo $count_all; ?> Closed</span>
                        </div>
                        <div style="height:6px; background:#f1f5f9; border-radius:3px; overflow:hidden;">
                            <div style="width:<?php echo $perc; ?>%; height:100%; background:<?php echo $perc > 70 ? '#10B981' : '#2563EB'; ?>;"></div>
                        </div>
                    </div>
                <?php endforeach; else: echo "No staff active yet."; endif; ?>
            </div>

            <!-- Activity Feed -->
            <div class="glass-card" style="max-height:300px; overflow-y:auto; padding:25px;">
                <h3 style="font-size:14px; position:sticky; top:0; background:white; padding-bottom:10px; margin-bottom:15px;">System Activity</h3>
                <div style="font-size:11px;">
                    <?php
                    $logs = GrowthPress_Activity::get_logs();
                    if($logs): foreach($logs as $log): ?>
                        <div style="margin-bottom:12px; border-left:2px solid #e2e8f0; padding-left:10px;">
                            <div style="opacity:0.6; font-size:9px;"><?php echo $log['time']; ?></div>
                            <div style="color:#475569;"><?php echo esc_html($log['msg']); ?></div>
                        </div>
                    <?php endforeach; else: echo "Waiting for activity..."; endif; ?>
                </div>
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
function exportLeads() {
    window.location.href = ajaxurl + "?action=gp_export_leads&gp_nonce=" + gp_admin.nonce;
}
</script>
