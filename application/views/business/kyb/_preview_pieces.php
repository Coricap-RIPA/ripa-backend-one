<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$url_legal = isset($url_legal) ? (string) $url_legal : '';
$url_compl = isset($url_compl) ? (string) $url_compl : '';
$kind_legal = isset($kind_legal) ? (string) $kind_legal : 'none';
$kind_compl = isset($kind_compl) ? (string) $kind_compl : 'none';
if ($url_legal === '' && $url_compl === '') {
    return;
}
?>
<div class="card ripa-kyb-preview-card white">
    <div class="card-content">
        <h2 style="font-size:1.05rem;color:#270345;font-weight:700;margin-top:0;">Pièces jointes</h2>
        <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:10px;">
            <?php if ($url_legal !== '') { ?>
                <button type="button" class="btn btn-small purple ripa-kyb-portal-tab" data-target="legal">Pièce légale</button>
            <?php } ?>
            <?php if ($url_compl !== '') { ?>
                <button type="button" class="btn btn-small grey lighten-1 ripa-kyb-portal-tab" data-target="complement">Complément</button>
            <?php } ?>
        </div>
        <div style="background:#f5f5f5;border-radius:8px;min-height:320px;overflow:hidden;border:1px solid #e0e0e0;">
            <iframe id="ripa-kyb-portal-iframe" title="Prévisualisation" style="width:100%;height:55vh;border:0;display:none;"></iframe>
            <img id="ripa-kyb-portal-img" alt="Prévisualisation" style="max-width:100%;height:auto;display:none;vertical-align:top;" />
        </div>
    </div>
</div>
<script>
(function () {
    var uL = <?php echo json_encode($url_legal); ?>;
    var uC = <?php echo json_encode($url_compl); ?>;
    var kL = <?php echo json_encode($kind_legal); ?>;
    var kC = <?php echo json_encode($kind_compl); ?>;
    var iframe = document.getElementById('ripa-kyb-portal-iframe');
    var img = document.getElementById('ripa-kyb-portal-img');
    if (!iframe || !img) return;
    function show(which) {
        var url = which === 'complement' ? uC : uL;
        var kind = which === 'complement' ? kC : kL;
        if (!url) return;
        iframe.style.display = 'none';
        img.style.display = 'none';
        iframe.removeAttribute('src');
        img.removeAttribute('src');
        if (kind === 'pdf') {
            iframe.style.display = 'block';
            iframe.src = url;
        } else if (kind === 'image') {
            img.style.display = 'block';
            img.src = url;
        } else {
            iframe.style.display = 'block';
            iframe.src = url;
        }
    }
    document.querySelectorAll('.ripa-kyb-portal-tab').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.ripa-kyb-portal-tab').forEach(function (b) {
                b.classList.remove('purple', 'white-text');
                b.classList.add('grey', 'lighten-1');
            });
            btn.classList.remove('grey', 'lighten-1');
            btn.classList.add('purple', 'white-text');
            show(btn.getAttribute('data-target'));
        });
    });
    var first = document.querySelector('.ripa-kyb-portal-tab[data-target="legal"]') || document.querySelector('.ripa-kyb-portal-tab');
    if (first) first.click();
})();
</script>
