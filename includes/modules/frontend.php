<?php
// ৫. CSS (Hobuho Design v12.0)
add_action('wp_head', function(){
    ?>
    <style>
        .dr-master-wrap { font-family: 'Segoe UI', sans-serif; max-width: 1000px; margin: 0 auto; color: #333; }
        .search-area { background: #f0f7f6; padding: 25px; border-radius: 12px; margin-bottom: 30px; border: 1px solid #d1e8e4; }
        .q-row input { width: 100%; padding: 15px; border-radius: 8px; border: 1px solid #ccc; font-size: 16px; margin-bottom: 15px; box-sizing: border-box; outline: none; }
        .f-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px; }
        .f-row select, #find-btn { padding: 12px; border-radius: 8px; border: 1px solid #ccc; font-weight: bold; cursor: pointer; }
        #find-btn { background: #418681; color: #fff; border: none; }
        .dr-card { display: flex; gap: 20px; background: #fff; border: 1px solid #d1e8e4; border-radius: 12px; padding: 20px; margin-bottom: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .dr-avatar img { width: 110px; height: 110px; border-radius: 50%; object-fit: cover; }
        .dr-avatar .dr-avatar-icon { font-size: 50px; width: 110px; height: 110px; display: flex; align-items: center; justify-content: center; background: #f0f7f6; border-radius: 50%; }
        .dr-info { flex: 1; }
        .dr-info h2 { margin: 0 0 5px; color: #418681; font-size: 22px; }
        .meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; background: #f9f9f9; padding: 12px; border-radius: 8px; font-size: 14px; margin-top: 10px; }
        .vote-panel { min-width: 180px; text-align: center; border-left: 1px solid #eee; padding-left: 15px; display: flex; flex-direction: column; justify-content: center; }
        .v-score { font-size: 32px; font-weight: bold; color: #418681; }
        .vbac-badge { background: #418681; color: #fff; padding: 2px 8px; border-radius: 5px; font-size: 11px; margin-left: 6px; vertical-align: middle; }
        .exp-badge { background: #e8f4f3; color: #418681; border: 1px solid #b2d8d4; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; display: inline-block; margin-top: 5px; }
        .nearby-notice { background: #fff8e1; padding: 12px 15px; border-radius: 8px; border-left: 4px solid #f0ad4e; margin-bottom: 15px; font-size: 14px; }
        @media(max-width:700px){
            .dr-card { flex-direction: column; text-align: left; gap: 12px; }
            .dr-avatar { margin: 0; }
            .dr-avatar img, .dr-avatar .dr-avatar-icon { width: 80px; height: 80px; font-size: 36px; }
            .meta-grid { grid-template-columns: 1fr !important; }
            .meta-grid div[style*="grid-column"] { grid-column: span 1 !important; }
            .vote-panel { border-left: none; border-top: 1px solid #eee; padding-top: 15px; padding-left: 0; align-items: flex-start; text-align: left; }
            .vote-act-btn { width: 100% !important; }
        }
    </style>
    <?php
});

// ৬. Shortcode [doctor_finder]
add_shortcode('doctor_finder', function(){
    if (!empty($_GET['doctor_id'])) {
        $pid = intval($_GET['doctor_id']);
        if (get_post_type($pid) === 'doctor' && get_post_status($pid) === 'publish') return dr_render_profile_page($pid);
    }
    $locs = get_bd_hardcoded_database_full();
    ob_start(); ?>
    <div class="dr-master-wrap">
        <div class="search-area">
            <div class="q-row" style="position:relative;"><input type="text" id="final-q" placeholder="ডাক্তারের নাম, এলাকা, হাসপাতাল..." autocomplete="off"><div id="dr-suggest-box" style="display:none; position:absolute; top:100%; left:0; right:0; z-index:9999; background:#fff; border:1px solid #d1e8e4; border-radius:0 0 10px 10px; box-shadow:0 8px 20px rgba(65,134,129,0.12); overflow:hidden;"></div></div>
            <div class="f-row">
                <select id="ui-div"><option value="">বিভাগ</option><?php foreach(array_keys($locs) as $d) echo "<option value='$d'>$d</option>"; ?></select>
                <select id="ui-dist"><option value="">জেলা</option></select>
                <select id="ui-upz"><option value="">উপজেলা</option></select>
                <button id="find-btn">সার্চ করুন</button>
            </div>
        </div>
        <div id="results-area-v37"></div>
    </div>
    <script>
    (function(){
        const db = <?php echo json_encode($locs); ?>;
        const fDiv = document.getElementById("ui-div"), fDist = document.getElementById("ui-dist"), fUpz = document.getElementById("ui-upz");
        fDiv.onchange = function(){ let h = '<option value="">জেলা</option>'; if(db[this.value]) Object.keys(db[this.value]).forEach(d => h += `<option value="${d}">${d}</option>`); fDist.innerHTML = h; fUpz.innerHTML = '<option value="">উপজেলা</option>'; };
        fDist.onchange = function(){ let h = '<option value="">উপজেলা</option>'; if(db[fDiv.value] && db[fDiv.value][this.value]) db[fDiv.value][this.value].forEach(u => h += `<option value="${u}">${u}</option>`); fUpz.innerHTML = h; };
        function doSearch(q='', div='', dist='', upz=''){
            const fd = new FormData(); fd.append("action", "master_ajax_v37"); fd.append("q", q); fd.append("div", div); fd.append("dist", dist); fd.append("upz", upz); fd.append("base_url", window.location.href);
            document.getElementById("results-area-v37").innerHTML = '<p style="text-align:center; color:#888;">লোড হচ্ছে...</p>';
            fetch("<?php echo admin_url('admin-ajax.php'); ?>", { method: "POST", body: fd }).then(r => r.text()).then(h => document.getElementById("results-area-v37").innerHTML = h);
        }
        window.onload = () => doSearch();
        document.getElementById("find-btn").onclick = () => doSearch(document.getElementById("final-q").value, fDiv.value, fDist.value, fUpz.value);
        const qInput = document.getElementById("final-q"), sugBox = document.getElementById("dr-suggest-box");
        qInput.addEventListener("keypress", function(e){ if(e.key === "Enter") { sugBox.style.display = "none"; doSearch(this.value, fDiv.value, fDist.value, fUpz.value); } });
        let sugTimer = null;
        qInput.addEventListener("input", function(){
            const val = this.value.trim(); clearTimeout(sugTimer); if(val.length < 3){ sugBox.style.display = "none"; sugBox.innerHTML = ""; return; }
            sugTimer = setTimeout(function(){ const fd = new FormData(); fd.append("action", "dr_location_suggest_v37"); fd.append("q", val); fetch("<?php echo admin_url('admin-ajax.php'); ?>", { method:"POST", body:fd }).then(r => r.json()).then(items => { if(!items || !items.length){ sugBox.style.display = "none"; return; } sugBox.innerHTML = items.map(it => `<div class="dr-sug-item" data-value="${it.value}" data-div="${it.div||''}" data-dist="${it.dist||''}" data-upz="${it.upz||''}" style="padding:10px 12px; cursor:pointer; border-bottom:1px solid #f0f7f6; font-size:14px;"><strong>${it.value}</strong> <small style="color:#888;">${it.type}</small></div>`).join(''); sugBox.style.display = "block"; }); }, 220);
        });
        document.addEventListener("click", function(e){
            if(e.target.classList.contains("dr-sug-item")){ qInput.value = e.target.dataset.value; sugBox.style.display = "none"; doSearch(qInput.value, e.target.dataset.div || fDiv.value, e.target.dataset.dist || fDist.value, e.target.dataset.upz || fUpz.value); return; }
            if(!e.target.classList.contains("vote-act-btn")) return;
            var b = e.target, fd = new FormData(); fd.append("action", "master_vote_v37"); fd.append("id", b.dataset.id); fd.append("type", b.dataset.type); fd.append("nonce", "<?php echo wp_create_nonce('master_vote_nonce'); ?>");
            fetch("<?php echo admin_url('admin-ajax.php'); ?>", { method: "POST", body: fd, credentials: "same-origin" }).then(r => r.json()).then(res => { if(res && res.success) { const panel = b.closest(".vote-panel"); panel.querySelector(".v-score").innerText = res.score; panel.querySelectorAll(".vote-act-btn").forEach(btn => btn.classList.remove("active-vote")); b.classList.add("active-vote"); } });
        });
    })();
    </script>
    <?php return ob_get_clean();
});
