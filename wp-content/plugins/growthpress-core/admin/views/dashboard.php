<div class="wrap growthpress-dashboard">
    <div class="dashboard-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
        <h1><?php echo esc_html(get_option('growthpress_brand_name', 'GrowthPress')); ?> OS</h1>
        <div class="ai-status" style="background:#10B981; color:white; padding:5px 12px; border-radius:20px; font-size:11px; font-weight:bold;">AI ACTIVE</div>
    </div>

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
                <!-- Kanban logic... -->
            </div>
        </div>

        <div class="side-col">
            <!-- Lead Sources -->
            <div class="glass-card">
                <h3>Lead Sources</h3>
                <canvas id="gp-source-chart" height="200"></canvas>
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
