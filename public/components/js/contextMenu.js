$jq(document).on('click', (e) => {
    const $customContextMenu = $jq('.custom-contextMenu');
    if ($customContextMenu) {
        if (!$jq.contains(e.target, $customContextMenu)) {
            hideAllCustomContextElement();
        }
    }
})

function hideAllCustomContextElement() {
    const $customContextMenu = $jq('.custom-contextMenu');
    // $jq('.custom-contextMenu[data-*=""]').attr('data-custom-context-menu', 'data context');
    $jq.each($customContextMenu, (i, v) => {
        const itmC = $jq(v)[0];
        
        $jq.each(itmC.attributes, (idx, valAttr) => {
            if (valAttr.name.startsWith('data-')) {
                itmC.removeAttribute(valAttr.name);
            }
        })
    });
    $customContextMenu.css({
        opacity: '0',
        visibility: 'hidden',
    });
}